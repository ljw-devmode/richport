<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting; // 서식 추가
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\CompanySalesMaster;
use App\Models\CompanySalesTarget;
use Carbon\Carbon;

class companySalesExport implements FromArray, WithHeadings, WithStyles, WithColumnFormatting
{
    protected $filters;
	protected $summaryRows = [];

    public function __construct(array $filters)
    {
        $this->filters = array_merge([
        'tgt_year'		=> null, 
		'tgt_month' 	=> null, 
		'group_idx' 	=> null, 
        'comp_idx' 		=> null, 
		'comp_name' 	=> null, 
		'day' 			=> null, 
        'type' 			=> null, 
        'salestype'		=> null, 
		'supplier' 		=> null, 
		'buyer' 		=> null, 
        'sales_title' 	=> null, 
		'receive_date' 	=> null, 
        'receive_status'=> null, 
		'receive_bank' 	=> null, 
		'receive_remain'=> null
   		], $filters);
    }

	public function columnFormats(): array
    {
        return [
            'K' => '#,##0', // 자사 발전대금
            'L' => '#,##0', // 관리 운영비
            'M' => '#,##0', // 리스 전력대금
            'N' => '#,##0', // IPP 발전대금
            'O' => '#,##0', // 수량
            'P' => '#,##0', // 단가
            'Q' => '#,##0', // 매출액
            'R' => '#,##0', // 공급가
            'S' => '#,##0', // 부가세
            'T' => '#,##0', // 합계
            'X' => '#,##0', // 수금 금액
            'Y' => '#,##0', // 수금 차액
        ];
	}

    public function array(): array
    {
		 $filters = $this->filters;

    	// 등록된 매출 데이터 추출 - CompanySalesTarget과 join해서 group_idx, comp_idx 가져오기
		// 먼저 CompanySalesTarget을 그룹화하는 서브쿼리
		$targetsSubquery = CompanySalesTarget::selectRaw('
			sales_idx,
			GROUP_CONCAT(DISTINCT company_sales_target.group_idx) as group_idx_list,
			GROUP_CONCAT(DISTINCT company_sales_target.comp_idx) as comp_idx_list,
			GROUP_CONCAT(DISTINCT cg.group_name ORDER BY cg.group_name SEPARATOR ", ") as group_name_list,
			GROUP_CONCAT(DISTINCT c.comp_name ORDER BY c.comp_name SEPARATOR ", ") as comp_name_list
		')->leftJoin('company_group as cg', 'company_sales_target.group_idx', '=', 'cg.idx')
		->leftJoin('company as c', 'company_sales_target.comp_idx', '=', 'c.idx')
		->groupBy('sales_idx');

		// group_idx 또는 comp_idx 필터가 있으면 결과 제한
		if (!empty($filters['group_idx']) || !empty($filters['comp_idx'])) {
			$targetFilter = CompanySalesTarget::query();
			
			if (!empty($filters['group_idx'])) {
				$targetFilter->where('group_idx', $filters['group_idx']);
			}
			
			if (!empty($filters['comp_idx'])) {
				$targetFilter->where('comp_idx', $filters['comp_idx']);
			}
			
			$filteredSalesIdx = $targetFilter->pluck('sales_idx')->unique();
			
			$compaysalesmaster = CompanySalesMaster::whereIn('company_sales_master.idx', $filteredSalesIdx)
				->leftJoinSub($targetsSubquery, 'targets', function($join) {
					$join->on('company_sales_master.idx', '=', 'targets.sales_idx');
				})
				->leftJoin('bank_account as ba', 'company_sales_master.receive_bank', '=', 'ba.idx')
				->select(
					'company_sales_master.*',
					'targets.group_idx_list',
					'targets.comp_idx_list',
					'targets.group_name_list',
					'targets.comp_name_list',
					'ba.bank_name',
					'ba.account_number'
				);
		} else {
			$compaysalesmaster = CompanySalesMaster::leftJoinSub($targetsSubquery, 'targets', function($join) {
				$join->on('company_sales_master.idx', '=', 'targets.sales_idx');
			})
			->leftJoin('bank_account as ba', 'company_sales_master.receive_bank', '=', 'ba.idx')
			->select(
				'company_sales_master.*',
				'targets.group_idx_list',
				'targets.comp_idx_list',
				'targets.group_name_list',
				'targets.comp_name_list',
				'ba.bank_name',
				'ba.account_number'
			);
		}

		// 검색 조건 처리
    	if (!empty($filters['day'])) {
    		$compaysalesmaster->whereDate('company_sales_master.day', $filters['day']);
    	}

    	if (!empty($filters['tgt_year'])) {
    		$compaysalesmaster->where('company_sales_master.tgt_year', $filters['tgt_year']);
    	}

    	if (!empty($filters['tgt_month'])) {
    		$compaysalesmaster->where('company_sales_master.tgt_month', $filters['tgt_month']);
    	}

    	if (!empty($filters['type'])) {
    		$compaysalesmaster->where('company_sales_master.type', $filters['type']);
    	}		
   	
    	if (!empty($filters['salestype'])) {
    		$compaysalesmaster->where('company_sales_master.salestype', $filters['salestype']);
    	}		
   	
    	if (!empty($filters['buyer'])) {
    		$compaysalesmaster->where('company_sales_master.buyer', $filters['buyer']);
    	}
    	
    	if (!empty($filters['receive_date'])) {
    		$compaysalesmaster->whereDate('company_sales_master.receive_date', $filters['receive_date']);
    	}
    	
    	if (!empty($filters['receive_status'])) {
    		$compaysalesmaster->where('company_sales_master.receive_status', $filters['receive_status']);
    	}
    	
    	if (!empty($filters['receive_bank'])) {
    		$compaysalesmaster->where('company_sales_master.receive_bank', $filters['receive_bank']);
    	}
    	
    	if (!empty($filters['receive_remain'])) {
    		if ($filters['receive_remain'] == '1') {
    			// 차액있음: sales * 1.1 > receive_amount
    			$compaysalesmaster->whereRaw('CAST(company_sales_master.sales AS DECIMAL(15,2)) * 1.1 > CAST(COALESCE(company_sales_master.receive_amount, 0) AS DECIMAL(15,2))');
    		} else if ($filters['receive_remain'] == '0') {
    			// 차액없음: sales * 1.1 <= receive_amount
    			$compaysalesmaster->whereRaw('CAST(company_sales_master.sales AS DECIMAL(15,2)) * 1.1 <= CAST(COALESCE(company_sales_master.receive_amount, 0) AS DECIMAL(15,2))');
    		}
    	}

		// 목~수 기준으로 주차를 나누기 위해서 날짜순 정렬 적용
		$data = $compaysalesmaster->orderBy('company_sales_master.day', 'asc')->distinct()->get();
        
		// 데이터가 없을때 오류가 없도록 배열 생성
		if ($data->isEmpty()) {
            return [];
        }

		// 최종 정리된 데이터
        $finalRows = [];

        // 주간 타입별 합계 저장소
        $types = ['자사 발전대금', '관리 운영비', '리스 전력대금', 'IPP 발전대금'];

		// 주간 합계
        $weeklyTypeSums = array_fill_keys($types, 0);	// 타입별 합계
        $weeklySales = 0;	// 매출 합계
		$weeklySupply = 0;	// 공급가 합계
		$weeklyVat = 0;		// 부가세 합계
		$weeklyTotal = 0;	// 공급가 + 부가세 합계
		$weeklyReceive = 0;	// 수금액 합계
		$weeklyRemain = 0;	// 미수금액 합계

    	foreach ($data as $index => $item) {
            // 개별 데이터의 타입별 금액 컬럼 세팅
            $typeValues = [];
            foreach ($types as $type) {
                $typeValues[] = ($item->salestype === $type) ? (float)$item->sales : 0;
            }

            // 1. 일반 데이터 행 추가
            $finalRows[] = array_merge([
                $item->tgt_year, $item->tgt_month, $item->group_name_list, $item->comp_name_list,
                $item->day, $item->supplier, $item->buyer, $item->title, $item->memo, $item->type
            ], $typeValues, [
                $item->quantity, $item->unitprice,
                $item->sales, $item->supplyprice, $item->vat, (float)$item->sales + (float)$item->vat,
                ($item->receive_status == 1 ? '수금 완료' : '수금 미완료'),
                $item->receive_date, $item->receive_bank ? ($item->bank_name . ' ' . $item->account_number) : '',
                $item->receive_amount, $item->receive_remain, $item->receive_memo, $item->created_at
            ]);

            // 주간 합계 누계
            if (isset($weeklyTypeSums[$item->salestype])) $weeklyTypeSums[$item->salestype] += (float)$item->sales;
            $weeklySales += (float)$item->sales;
            $weeklySupply += (float)$item->supplyprice;
            $weeklyVat += (float)$item->vat;
            $weeklyTotal += ((float)$item->sales + (float)$item->vat);
            $weeklyReceive += (float)$item->receive_amount;
            $weeklyRemain += (float)$item->receive_remain;


			$currentDate = Carbon::parse($item->day);
			$nextItem = $data[$index + 1] ?? null;
			$isWednesday = ($currentDate->dayOfWeek === Carbon::WEDNESDAY);
			$isLastRow = !$nextItem;
			
			$shouldShowSummary = false;

			if ($isLastRow) {
				// 마지막 데이터면 무조건 합계 출력
				$shouldShowSummary = true;
			} else {
				$nextDate = Carbon::parse($nextItem->day);
				
				// 오늘이 수요일인데 다음 데이터가 목요일 이후라면 (주차 종료)
				if ($isWednesday && $nextDate->gt($currentDate)) {
					$shouldShowSummary = true;
				} 
				// 오늘이 수요일은 아니지만, 다음 데이터가 "다음번 목요일"을 넘어갔다면
				else {
					// 해당 날짜의 '정산 종료 수요일'을 구함
					$thisWednesday = $currentDate->copy();
					if ($currentDate->dayOfWeek > Carbon::WEDNESDAY) {
						$thisWednesday->next(Carbon::WEDNESDAY);
					} else if ($currentDate->dayOfWeek < Carbon::WEDNESDAY) {
						// 이미 해당 주 수요일이 안 지났다면 그대로 둠
						$thisWednesday->startOfDay()->setUnit('day', $currentDate->day + (Carbon::WEDNESDAY - $currentDate->dayOfWeek));
					}

					// 다음 데이터가 이번 주 수요일보다 뒤에 있다면 합계 출력
					if ($nextDate->gt($thisWednesday)) {
						$shouldShowSummary = true;
					}
				}
			}

			if ($shouldShowSummary) {
				// 2. 주간 합계 행 추가 (기존과 동일)
				$finalRows[] = array_merge([
					'주간합계', '', '', '', '', '', '', '', '', ''
				], array_values($weeklyTypeSums), [ 
					'', '', $weeklySales, $weeklySupply, $weeklyVat, $weeklyTotal,
					'', '', '', $weeklyReceive, $weeklyRemain, '', ''
				]);

				$this->summaryRows[] = count($finalRows) + 1;

				// 합계 초기화 (기존과 동일)
				$weeklyTypeSums = array_fill_keys($types, 0);
				$weeklySales = 0; $weeklySupply = 0; $weeklyVat = 0; $weeklyTotal = 0; $weeklyReceive = 0; $weeklyRemain = 0;
			}
        }

		return $finalRows;
    }

	public function styles(Worksheet $sheet)
	{
		// 1. 전체 데이터 범위 계산 (A1부터 마지막 셀까지)
		$lastRow = $sheet->getHighestRow();
		$fullRange = "A1:AA{$lastRow}";

		// 2. 전체 표 테두리 설정 (모든 셀에 가느다란 테두리)
		$sheet->getStyle($fullRange)->applyFromArray([
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => ['rgb' => '000000'],
				],
			],
		]);

		// 3. 헤더 스타일 (1행: 옅은 회색 배경 + 굵게 + 가운데 정렬)
		$sheet->getStyle('A1:AA1')->applyFromArray([
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => ['rgb' => 'E9E9E9'], // 옅은 회색
			],
		]);

		// 4. 주간 합계 행 스타일 (노란색 배경 - 기존 로직 유지)
		foreach ($this->summaryRows as $rowNum) {
			$sheet->getStyle("A{$rowNum}:AA{$rowNum}")->applyFromArray([
				'font' => ['bold' => true],
				'fill' => [
					'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
					'startColor' => ['rgb' => 'FFFF00'], // 노란색
				],
			]);
		}

		// 5. 열 너비 자동 조절 (A-Z 까지만 가능)
		foreach (range('A', 'Z') as $col) {
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}

		// AA, AB 컬럼 수동 추가
		$sheet->getColumnDimension('AA')->setAutoSize(true);
	}
    
    public function headings(): array
    {
    	return [
			'대상 년도',
			'대상 월'	,
			'대상 SPC',
			'대상 발전소',
			'계산서 발행일',
			'매출 귀속',
			'공급 받는자',
			'계산서 품목명',
			'계산서 비고',
			'SMP/REC',
			'자사 발전대금',
			'관리 운영비', 
			'리스 전력대금', 
			'IPP 발전대금',
			'수량',
			'단가',
			'매출액',
			'공급가',
			'부가세',
			'합계',
			'수금 상태',
			'수금 일자',
			'수금 계좌',
			'수금 금액',
			'수금 차액',
			'수금 차액 사유',
			'등록일'
    	];
    }
}

