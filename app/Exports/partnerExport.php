<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Partner;

class partnerExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
    	$query   = Partner::with('types','forms','cities','districts','head')->partnersOnly();
    	$filters = $this->filters;

    	if (!empty($filters['country'])) {
    		$query->where('partner.country', $filters['country']);
    	}

    	if (!empty($filters['branch_type'])) {
    		$query->where('partner.branch_type', $filters['branch_type']);
    	}

    	if (!empty($filters['types_idx'])) {
    		$query->where('partner.types_idx', $filters['types_idx']);
    	}

    	if (!empty($filters['forms_idx'])) {
    		$query->where('partner.forms_idx', $filters['forms_idx']);
    	}

    	if (($filters['home_recommend'] ?? '') !== '') {
    		$query->where('partner.home_recommend', $filters['home_recommend']);
    	}

    	if (($filters['partner_recommend'] ?? '') !== '') {
    		$query->where('partner.partner_recommend', $filters['partner_recommend']);
    	}

    	if (!empty($filters['cities_idx'])) {
    		$query->where('partner.cities_idx', $filters['cities_idx']);
    	}

    	if (!empty($filters['districts_idx'])) {
    		$query->where('partner.districts_idx', $filters['districts_idx']);
    	}

    	if (!empty($filters['partner_name'])) {
    		$query->where('partner.partner_name', 'like', '%' . $filters['partner_name'] . '%');
    	}

    	$rows = $query->orderByDesc('idx')->get();

    	return $rows->map(function ($item) {

    		$toYN = function ($v) {
    			return ((string)$v === '1') ? 'Y' : 'N';
    		};

    		return [
    				'순번'      		=> $item->idx,
    				'국가'      		=> $item->country,
    				'본점/지점'		=> $item->branch_type_text,
    				'소속 본점'		=> $item->branch_type === 'branch' ? optional($item->head)->partner_name : '',
    				'구분'   			=> optional($item->types)->subject,
    				'카테고리'   		=> optional($item->forms)->subject,
    				'도시'   			=> optional($item->cities)->name_kr,
    				'구역'      		=> optional($item->districts)->name_kr,
    				'업체명' 			=> $item->partner_name,
    				'업체명(en)' 		=> $item->partner_name_en,
    				'업체명(vn)' 		=> $item->partner_name_vn,
    				'주요 유통채널' 	=> $item->channel,
    				'유통기간' 		=> $item->logistic,
    				'홈 노출'   		=> $toYN($item->home_recommend),
    				'파트너 노출'   	=> $toYN($item->partner_recommend),
    				'등록일'			=> $item->created_at
    		];
    	});
    }

    public function headings(): array
    {
    	return [
    			'순번',
    			'국가',
    			'본점/지점',
    			'소속 본점',
    			'구분',
    			'카테고리',
    			'도시',
    			'구역',
    			'업체명',
    			'업체명(en)',
    			'업체명(vn)',
    			'주요 유통채널',
    			'유통기간',
    			'홈 노출',
    			'파트너 노출',
    			'등록일'
    	];
    }
}
