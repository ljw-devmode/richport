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
    	$query   = Portfolio::with('types','formTypes','regions','cities');
    	$filters = $this->filters;
    	
    	if (!empty($filters['types_idx'])) {
    		$query->where('portfolio.types_idx', $filters['types_idx']);
    	}
    	
    	if (!empty($filters['is_url'])) {
    		$query->where('portfolio.url', '!=', '');
    	}
    	
    	if (!empty($filters['is_blog'])) {
    		$query->where('portfolio.blog', '!=', '');
    	}
    	
    	if (!empty($filters['home_recommend'])) {
    		$query->where('portfolio.home_recommend', $filters['home_recommend']);
    	}
    	
    	if (!empty($filters['introduce_recommend'])) {
    		$query->where('portfolio.introduce_recommend', $filters['introduce_recommend']);
    	}
    	
    	if (!empty($filters['portfolio_recommend'])) {
    		$query->where('portfolio.portfolio_recommend', $filters['portfolio_recommend']);
    	}
    	
    	if (!empty($filters['regions_idx'])) {
    		$query->where('portfolio.regions_idx', $filters['regions_idx']);
    	}
    	
    	if (!empty($filters['cities_idx'])) {
    		$query->where('portfolio.cities_idx', $filters['cities_idx']);
    	}
    	
    	if (!empty($filters['completion_date'])) {
    		$query->whereDate('portfolio.completion_date', $filters['completion_date']);
    	}
    	
    	if (!empty($filters['station_name'])) {
    		$query->where('portfolio.station_name', 'like', '%' . $filters['station_name'] . '%');
    	}
    	
    	$rows = $query->orderByDesc('idx')->get();
    	
    	return $rows->map(function ($item) {
    		
    		$toYN = function ($v) {
    			return ((string)$v === '1') ? 'Y' : 'N';
    		};
    		
    		$item->home_recommend_text       = $toYN($item->home_recommend);
    		$item->introduce_recommend_text  = $toYN($item->introduce_recommend);
    		$item->portfolio_recommend_text  = $toYN($item->portfolio_recommend);
    		
    		return [
    				'순번'      	=> $item->idx,
    				'년도'   		=> $item->year,
    				'구분'   		=> optional($item->types)->subject,
    				'형태' 			=> optional($item->formTypes)->subject,
    				'준공일'    	=> $item->completion_date,
    				'지역'      	=> optional($item->regions)->name_kr,
    				'도시'   		=> optional($item->cities)->name_kr,
    				'용량(kW)'  	=> number_format($item->volume),
    				'금액'   		=> $item->price,
    				'발전소명' 		=> $item->station_name,
    				'영상URL'   	=> $item->url,
    				'블로그URL' 	=> $item->blog,
    				'유튜브 iframe' => $item->youtube_iframe,
    				'영상제목'   	=> $item->youtube_subject,
    				'영상내용'   	=> $item->youtube_contents,
    				'솔택이 후기 내용'   		=> $item->blog_contents,
    				'홈 추천포트폴리오'   		=> $item->home_recommend_text,
    				'사업소개 추천포트폴리오'   => $item->introduce_recommend_text,
    				'포트폴리오 추천포트폴리오' => $item->portfolio_recommend_text,
    				'등록일'		=> $item->created_at 
    		];
    	});
    }
    
    public function headings(): array
    {
    	return [
    			'순번',
    			'년도',
    			'구분',
    			'형태',
    			'준공일',
    			'지역',
    			'도시',
    			'용량(kW)',
    			'금액',
    			'발전소명',
    			'영상URL',
    			'블로그URL',
    			'유튜브 iframe',
    			'영상제목',
    			'영상내용',
    			'솔택이 후기 내용',
    			'홈 추천포트폴리오',
    			'사업소개 추천포트폴리오',
    			'포트폴리오 추천포트폴리오',
    			'등록일'
    	];
    }
}