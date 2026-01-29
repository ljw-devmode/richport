<?php

namespace App\Exports;

use App\Models\Inquire;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InquireExport implements FromCollection, WithHeadings
{
	protected array $filters;
	
	public function __construct(array $filters)
	{
		$this->filters = $filters;
	}
	
	public function collection()
	{
		$query   = Inquire::with('types');
		$filters = $this->filters;
		
		if (!empty($filters['types_idx'])) {
			$query->where('types_idx', $filters['types_idx']);
		}
		
		if (!empty($filters['status'])) {
			$query->where('status', $filters['status']);
		}
		
		if (!empty($filters['inquire_hp'])) {
			$query->where('inquire_hp', 'like', '%' . $filters['inquire_hp'] . '%');
		}
		
		if (!empty($filters['inquire_company'])) {
			$query->where('inquire_company', 'like', '%' . $filters['inquire_company'] . '%');
		}
		
		if (!empty($filters['inquire_address'])) {
			$query->where('inquire_address', 'like', '%' . $filters['inquire_address'] . '%');
		}
		
		$rows = $query->orderByDesc('idx')->get();
		
		return $rows->map(function ($item) {
			return [
					'순번'       => $item->idx,
					'등록일시'   => $item->inquire_date,
					'문의구분'   => optional($item->types)->subject,
					'회사명/성명' => $item->inquire_company,
					'연락처'     => $item->inquire_hp,
					'주소'       => $item->inquire_address,
					'문의내용'   => $item->inquire_contents,
					'확인일시'   => $item->response_at,
					'처리상태'   => $item->status_kr,
					'처리완료일' => $item->complete_at,
					'처리내용'   => $item->response_contents,
			];
		});
	}
	
	public function headings(): array
	{
		return [
				'순번',
				'등록일시',
				'문의구분',
				'회사명/성명',
				'연락처',
				'주소',
				'문의내용',
				'확인일시',
				'처리상태',
				'처리완료일',
				'처리내용',
		];
	}
}