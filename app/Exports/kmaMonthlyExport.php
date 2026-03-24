<?php

namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class kmaMonthlyExport implements FromArray, WithStyles, WithColumnWidths
{
    protected $info;
    protected $rows;

	// 단위 변환 참고: ASOS 일사량(합계일사)은 보통 MJ/m²로 제공되는 경우가 많습니다.
    // 필요하면 kWh/m²로 변환: 1 MJ/m² = 0.277784755950055 kWh/m²
    protected $mjToKwh = 0.277784755950055;

    public function __construct($info, $rows)
    {
        $this->info = $info; // [name, year, month]
        $this->rows = $rows;
    }

    public function array(): array
    {
        // 1. 상단 정보
        $data = [
            ['관측소', $this->info['name'], '대상년', $this->info['year'], '대상월', $this->info['month']],
            [], // 빈 줄
            ['번호', '날짜', '전년도 일사량', '전년도 발전량', '전월 일사량', '전월 발전량', '당월 일사량', '당월 발전량']
        ];

        $sumPyMj = 0; $sumPyKwh = 0; $sumPmMj = 0; $sumPmKwh = 0; $sumTgMj = 0; $sumTgKwh = 0;

        // 2. 본문 데이터 가공 및 합계 계산
        foreach ($this->rows as $index => $r) {
            $pyKwh = $r['prev_year'] ? $r['prev_year'] * $this->mjToKwh : 0;
            $pmKwh = $r['prev_month'] ? $r['prev_month'] * $this->mjToKwh : 0;
            $tgKwh = $r['target'] ? $r['target'] * $this->mjToKwh : 0;

            $data[] = [
                $index + 1,
                $r['day'] . '일',
                $r['prev_year'], $pyKwh,
                $r['prev_month'], $pmKwh,
                $r['target'], $tgKwh
            ];

            // 합계 누적
            $sumPyMj += $r['prev_year']; $sumPyKwh += $pyKwh;
            $sumPmMj += $r['prev_month']; $sumPmKwh += $pmKwh;
            $sumTgMj += $r['target']; $sumTgKwh += $tgKwh;
        }

        // 3. 마지막 줄 합계 추가
        $data[] = [
            '합계', '',
            $sumPyMj, $sumPyKwh,
            $sumPmMj, $sumPmKwh,
            $sumTgMj, $sumTgKwh
        ];

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]], // 상단 정보 강조
            2    => ['font' => ['bold' => true]], // 헤더 강조
            $sheet->getHighestRow() => ['font' => ['bold' => true]], // 합계 줄 강조
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 15, 'C' => 15, 'D' => 15, 'E' => 15, 'F' => 15, 'G' => 15, 'H' => 15,
        ];
    }
}