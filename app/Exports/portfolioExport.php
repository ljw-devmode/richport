<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Portfolio;

class portfolioExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
    	$query   = Portfolio::with('types','cities','districts');
    	$filters = $this->filters;

    	if (!empty($filters['types_idx'])) {
    		$query->where('portfolio.types_idx', $filters['types_idx']);
    	}

    	if (($filters['home_recommend'] ?? '') !== '') {
    		$query->where('portfolio.home_recommend', $filters['home_recommend']);
    	}

    	if (($filters['portfolio_recommend'] ?? '') !== '') {
    		$query->where('portfolio.portfolio_recommend', $filters['portfolio_recommend']);
    	}

    	if (!empty($filters['cities_idx'])) {
    		$query->where('portfolio.cities_idx', $filters['cities_idx']);
    	}

    	if (!empty($filters['districts_idx'])) {
    		$query->where('portfolio.districts_idx', $filters['districts_idx']);
    	}

    	if (!empty($filters['station_name'])) {
    		$query->where('portfolio.station_name', 'like', '%' . $filters['station_name'] . '%');
    	}

    	$rows = $query->orderByDesc('idx')->get();

    	return $rows->map(function ($item) {

    		$toYN = function ($v) {
    			return ((string)$v === '1') ? 'Y' : 'N';
    		};

    		return [
    				'순번'      	=> $item->idx,
    				'구분'   		=> optional($item->types)->subject,
    				'도시'   		=> optional($item->cities)->name_kr,
    				'구역'      	=> optional($item->districts)->name_kr,
    				'업체명' 		=> $item->station_name,
    				'업체명(en)' 	=> $item->station_name_en,
    				'업체명(vn)' 	=> $item->station_name_vn,
    				'홈 노출'   	=> $toYN($item->home_recommend),
    				'도입사례 노출' => $toYN($item->portfolio_recommend),
    				'등록일'		=> $item->created_at
    		];
    	});
    }

    public function headings(): array
    {
    	return [
    			'순번',
    			'구분',
    			'도시',
    			'구역',
    			'업체명',
    			'업체명(en)',
    			'업체명(vn)',
    			'홈 노출',
    			'도입사례 노출',
    			'등록일'
    	];
    }
}
