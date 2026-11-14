<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Product;
use App\Models\FormTypes;

class productExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
    	$query   = Product::with('partners','forms');
    	$filters = $this->filters;

    	if (!empty($filters['partner_idx'])) {
    		$query->where('product.partner_idx', $filters['partner_idx']);
    	}

    	// 카테고리 : 선택한 단계 + 하위 카테고리 전체
    	if (!empty($filters['forms_idx'])) {
    		$query->whereIn('product.forms_idx', FormTypes::descendantIds($filters['forms_idx']));
    	}

    	if (($filters['home_recommend'] ?? '') !== '') {
    		$query->where('product.home_recommend', $filters['home_recommend']);
    	}

    	if (($filters['product_recommend'] ?? '') !== '') {
    		$query->where('product.product_recommend', $filters['product_recommend']);
    	}

    	if (!empty($filters['product_name'])) {
    		$query->where('product.product_name', 'like', '%' . $filters['product_name'] . '%');
    	}

    	$rows      = $query->orderByDesc('idx')->get();
    	$formPaths = FormTypes::pathMap();

    	return $rows->map(function ($item) use ($formPaths) {

    		$toYN = function ($v) {
    			return ((string)$v === '1') ? 'Y' : 'N';
    		};

    		return [
    				'순번'      	=> $item->idx,
    				'파트너'   		=> optional($item->partners)->partner_name,
    				'카테고리'   	=> $formPaths[$item->forms_idx] ?? '',
    				'제품명' 		=> $item->product_name,
    				'제품명(en)' 	=> $item->product_name_en,
    				'제품명(vn)' 	=> $item->product_name_vn,
    				'바코드' 		=> $item->barcode,
    				'기본 발주금액'	=> $item->order_price,
    				'CongBo'   		=> $toYN($item->congbo),
    				'등록번호'   	=> $item->congbo_number,
    				'신상품'   		=> $toYN($item->new),
    				'테스트베드'   	=> $toYN($item->testbed),
    				'홈 노출'   	=> $toYN($item->home_recommend),
    				'제품 노출'   	=> $toYN($item->product_recommend),
    				'등록일'		=> $item->created_at
    		];
    	});
    }

    public function headings(): array
    {
    	return [
    			'순번',
    			'파트너',
    			'카테고리',
    			'제품명',
    			'제품명(en)',
    			'제품명(vn)',
    			'바코드',
    			'기본 발주금액',
    			'CongBo',
    			'등록번호',
    			'신상품',
    			'테스트베드',
    			'홈 노출',
    			'제품 노출',
    			'등록일'
    	];
    }
}
