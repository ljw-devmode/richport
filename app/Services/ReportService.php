<?php
// app/Services/KmaAsosService.php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\CompanyKwh;
use App\Models\CompanyManage;
use App\Models\CompanyRec;
use App\Models\CompanyReportMonthly;
use App\Models\CompanySales;
use App\Models\CompanySmp;
use App\Models\CompanyYearKwh;
use App\Models\CompanyYearKwhHours;
use App\Models\KmaObv;
use App\Models\KmaSumgsr;


class ReportService
{
    public function generateCompanyMonthly($company)
    {
        $start = Carbon::parse($company->start_date)->startOfMonth();
        $end   = Carbon::now()->subMonth()->startOfMonth();
        $mjToKwh = 0.277784755950055;

        while ($start <= $end) {

            $year  = $start->year;
            $month = $start->month;

            // period 계산
            $period = $this->getPeriod($company->start_date, $year, $month);

            // 날짜수
            $days = $start->daysInMonth;

            // =========================
            // 발전량
            // =========================
            $act_kwh = CompanyKwh::where('comp_idx', $company->idx)
                ->where('year', $year)
                ->where('month', $month)
                ->value('act_kwh') ?? 0;

            // =========================
            // 목표 시간
            // =========================
            $tgt_hours = CompanyYearKwhHours::where('comp_idx', $company->idx)
                ->where('year', $year)
                ->where('month', $month)
                ->value('tgt_hours') ?? 0;

            // =========================
            // 목표 발전량 (발전시간 * 용량 * 해당월 일수)
            // =========================
            $tgt_kwh = $tgt_hours * $company->volume * $days;

            // =========================
            // 년차별 년간 목표 발전량
            // =========================
            $tgt_yearkwh = CompanyYearKwh::where('comp_idx', $company->idx)
                ->where('period', $period)
                ->value('year_kwh') ?? 0;          

            // =========================
            // 실제 시간 (실제 발전량 / 용량 / 해당월 일수)
            // =========================
            $act_hours = $company->volume > 0
                ? $act_kwh / $company->volume / $days
                : 0;

            // =========================
            // SMP 매출
            // =========================
            $smp = CompanySmp::where('comp_idx', $company->idx)
                ->where('year', $year)
                ->where('month', $month)
                ->value('smp') ?? 0;

            $smp_amount = ($smp > 0 && $act_kwh > 0) ? $smp * $act_kwh : 0;

            // =========================
            // REC 매출
            // =========================
            $rec = CompanyRec::where('comp_idx', $company->idx)
                ->where('year', $year)
                ->where('month', $month)
                ->value('rec') ?? 0;

            $rec_amount = ($rec > 0 && $act_kwh > 0) ? $rec * $act_kwh : 0;
            
            // =========================
            // 해당 월까지 SMP, REC 누적 매출
            // =========================
            $summary = DB::table('company_kwh as kwh')
                ->leftJoin('company_smp as smp', function ($join) {
                    $join->on('kwh.comp_idx', '=', 'smp.comp_idx')
                        ->on('kwh.year', '=', 'smp.year')
                        ->on('kwh.month', '=', 'smp.month');
                })
                ->leftJoin('company_rec as rec', function ($join) {
                    $join->on('kwh.comp_idx', '=', 'rec.comp_idx')
                        ->on('kwh.year', '=', 'rec.year')
                        ->on('kwh.month', '=', 'rec.month');
                })
                // 해당 년도 1월부터 선택 월까지
                ->where(function ($query) use ($year, $month) {
                    $query->where('kwh.year', $year)
                        ->where('kwh.month', '<=', $month);
                })
                // 대상 발전소
                ->where('kwh.comp_idx', $company->idx)

                // SMP, REC 누적 합계
                ->select(
                    DB::raw('SUM(kwh.act_kwh * IFNULL(smp.smp, 0)) as total_smp_amount'),
                    DB::raw('SUM(kwh.act_kwh * IFNULL(rec.rec, 0)) as total_rec_amount')
                )
                ->first();

            // 각각의 변수에 할당 (결과가 없으면 0)
            $total_smp_amount = $summary->total_smp_amount ?? 0;
            $total_rec_amount = $summary->total_rec_amount ?? 0;

            // =========================
            // 일사량
            // =========================
            $cur_month_kma = $this->getMonthlyKma($company->kma_idx, $year, $month);

            $prev = $start->copy()->subMonth();
            $pre_month_kma = $this->getMonthlyKma($company->kma_idx, $prev->year, $prev->month);

            $pre_year_kma = $this->getMonthlyKma($company->kma_idx, $year - 1, $month);

            // =========================
            // 효율
            // =========================
            $total_rate = ($cur_month_kma > 0 && $company->volume > 0 && $act_kwh > 0)
                ? $act_kwh / ($cur_month_kma * $company->volume * $mjToKwh)
                : 0;

            $act_rate = ($cur_month_kma > 0 && $company->volume > 0 && $act_kwh > 0)
                     ? $total_rate / 0.83
                     : 0;

            // =========================
            // 매출
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
            // 저장
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

                    'smp_amount' => $smp_amount,
                    'rec_amount' => $rec_amount,
                    'total_smp_amount' => $total_smp_amount,
                    'total_rec_amount' => $total_rec_amount,

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