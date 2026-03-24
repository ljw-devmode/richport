<?php
// app/Services/KmaAsosService.php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\CompanyKwh;
use App\Models\CompanyReportMonthly;
use App\Models\CompanySales;
use App\Models\CompanyYearKwh;
use App\Models\CompanyYearKwhHours;
use App\Models\KmaObv;
use App\Models\KmaSumgsr;


class ReportService
{
    public function generateCompanyMonthly($company)
    {
        $start = Carbon::parse($company->start_date)->startOfMonth();
        $end   = Carbon::now()->startOfMonth();
        
        // 단위 변환 참고: ASOS 일사량(합계일사)은 보통 MJ/m²로 제공되는 경우가 많습니다.
        // 필요하면 kWh/m²로 변환: 1 MJ/m² = 0.277784755950055 kWh/m²
        $mjToKwh = 0.277784755950055;

        while ($start <= $end) {

            $year  = $start->year;
            $month = $start->month;

            // period 계산
            $period = $this->getPeriod($company->start_date, $year, $month);

            // 날짜수
            $days = $start->daysInMonth;

            // =========================
            // 1 발전량
            // =========================
            $act_kwh = CompanyKwh::where('comp_idx', $company->idx)
                ->where('year', $year)
                ->where('month', $month)
                ->value('act_kwh') ?? 0;

            // =========================
            // 2 목표 시간
            // =========================
            $tgt_hours = CompanyYearKwhHours::where('comp_idx', $company->idx)
                ->where('year', $year)
                ->where('month', $month)
                ->value('tgt_hours') ?? 0;

            // =========================
            // 3 목표 발전량 (발전시간 * 용량 * 해당월 일수)
            // =========================
            $tgt_kwh = $tgt_hours * $company->volume * $days;

            // =========================
            // 4 년차별 년간 목표 발전량
            // =========================
            $tgt_yearkwh = CompanyYearKwh::where('comp_idx', $company->idx)
                ->where('period', $period)
                ->value('year_kwh') ?? 0;          

            // =========================
            // 5 실제 시간 (실제 발전량 / 용량 / 해당월 일수)
            // =========================
            $act_hours = $company->volume > 0
                ? $act_kwh / $company->volume / $days
                : 0;

            // =========================
            // 6 일사량
            // =========================
            $cur_month_kma = $this->getMonthlyKma($company->kma_idx, $year, $month);

            $prev = $start->copy()->subMonth();
            $pre_month_kma = $this->getMonthlyKma($company->kma_idx, $prev->year, $prev->month);

            $pre_year_kma = $this->getMonthlyKma($company->kma_idx, $year - 1, $month);

            // =========================
            // 7 효율
            // =========================
            $total_rate = ($cur_month_kma > 0 && $company->volume > 0 && $act_kwh > 0)
                ? $act_kwh / ($cur_month_kma * $company->volume * $mjToKwh)
                : 0;

            $act_rate = ($cur_month_kma > 0 && $company->volume > 0 && $act_kwh > 0)
                     ? $total_rate / 0.83
                     : 0;

            // =========================
            // 8 매출
            // =========================
            $sales = CompanySales::where('comp_idx', $company->idx)
                ->where('tgt_year', $year)
                ->where('tgt_month', $month)
                ->get();

            $total_sales = $sales->sum('sales');
            $smp_sales   = $sales->where('type', 'SMP')->sum('sales');
            $rec_sales   = $sales->where('type', 'REC')->sum('sales');
            $man_sales   = $sales->where('type', '운영비')->sum('sales');

            // =========================
            // 9 저장 (핵심)
            // =========================
            CompanyReportMonthly::updateOrCreate(
                [
                    'comp_idx' => $company->idx,
                    'tgt_year' => $year,
                    'tgt_month'=> $month,
                ],
                [
                    'group_idx' => $company->group_idx,
                    'period' => $period,

                    'tgt_days'=> $days,
                    'tgt_kwh' => $tgt_kwh,
                    'act_kwh' => $act_kwh,
                    'tgt_yearkwh' => $tgt_yearkwh,

                    'tgt_hours' => $tgt_hours,
                    'act_hours' => $act_hours,

                    'total_rate' => $total_rate,
                    'act_rate' => $act_rate,

                    'pre_year_kma' => $pre_year_kma,
                    'pre_month_kma' => $pre_month_kma,
                    'cur_month_kma' => $cur_month_kma,

                    'total_sales' => $total_sales,
                    'smp_sales' => $smp_sales,
                    'rec_sales' => $rec_sales,
                    'man_sales' => $man_sales,

                    'updated_at' => now(),
                ]
            );

            $start->addMonth();
        }
    }

    private function getPeriod($startDate, $year, $month)
    {
        $start = Carbon::parse($startDate)->startOfMonth();
        $target = Carbon::create($year, $month, 1);

        $diffMonths = $start->diffInMonths($target);

        return floor($diffMonths / 12) + 1;
    }

    private function getMonthlyKma($stn_idx, $year, $month)
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth()->format('Y-m-d');
        $start = $start->format('Y-m-d');

        $rows = KmaSumgsr::where('stn_idx', $stn_idx)
							->whereBetween('date', [$start, $end])
							->orderByDesc('date')
							->get();

        // 합계 (null 제외)
        $sum = $rows->sum('sumgsr');

        return $sum;
    }

}