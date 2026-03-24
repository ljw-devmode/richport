<?php
// app/Services/KmaAsosService.php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\KmaObv;
use App\Models\KmaSumgsr;


class KmaAsosService
{
    private string $baseUrl = 'http://apis.data.go.kr/1360000/AsosDalyInfoService/getWthrDataList'; // 요청주소 :contentReference[oaicite:2]{index=2}

    public function fetchDailyAsos(string $startDt, string $endDt, int $stnId): array
    {
        $cacheKey = "asos:daily:{$stnId}:{$startDt}:{$endDt}";
		Cache::forget($cacheKey);

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($startDt, $endDt, $stnId) {
            $key = env('KMA_ASOS_SERVICE_KEY');

            $resp = Http::timeout(20)->retry(2, 300)->get($this->baseUrl, [
                'serviceKey' => $key,
                'pageNo' => 1,
                'numOfRows' => 500,
                'dataType' => 'JSON',
                'dataCd' => 'ASOS',
                'dateCd' => 'DAY',
                'startDt' => $startDt,
                'endDt' => $endDt,
                'stnIds' => $stnId,
            ]);

            if (!$resp->ok()) {
                throw new \RuntimeException("KMA API HTTP error: {$resp->status()}");
            }

            $json = $resp->json();
            return $json['response']['body']['items']['item'] ?? [];
        });
    }

    /**
     * 월 단위로 [day(1..N) => sumGsr] 형태로 반환
     * sumGsr = 합계 일사 (daily) :contentReference[oaicite:3]{index=3}
     */
    public function getMonthlySumGsrByRange(int $start, int $end, int $stnId): array
    {
        $items = $this->fetchDailyAsos($start, $end, $stnId);

        $byDay = [];
        foreach ($items as $row) {
            // tm 예: 2010-01-01 :contentReference[oaicite:4]{index=4}
            $tm = $row['tm'] ?? null;
            if (!$tm) continue;

            $day = (int)substr($tm, 8, 2);

            // 합계 일사: sumGsr :contentReference[oaicite:5]{index=5}
            $val = $row['sumGsr'] ?? null;

            // 결측/빈값 처리
            if ($val === null || $val === '' || $val === '-99' || $val === '-99.0') {
                $byDay[$day] = null;
            } else {
                // 문자열 숫자 방어
                $byDay[$day] = (float)str_replace(',', '', (string)$val);
            }
        }

        return $byDay;
    }
}