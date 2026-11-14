<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\History;

class historyExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
    	$query   = History::query();
    	$filters = $this->filters;
    	
    	if (!empty($filters['year'])) {
    		$query->where('year', $filters['year']);
    	}
    	
    	if (!empty($filters['subject'])) {
    		$query->where('subject', 'like', '%' . $filters['subject'] . '%');
    	}
    	
    	$rows = $query->orderByDesc('created_at')->get();
    	
    	return $rows->map(function ($item) {
    		return [
    				'순번'		=> $item->idx,
    				'년도'   	=> $item->year,
    				'월'   		=> $item->month,
    				'제목(ko)' 		=> $item->subject,
					'제목(en)' 		=> $item->subject_en,
					'제목(vn)' 		=> $item->subject_vn,
    				'등록일'	=> $item->created_at
    		];
    	});
    }
    
    public function headings(): array
    {
    	return [
    			'순번',
    			'년도',
    			'월',
    			'제목(ko)',
				'제목(en)',
				'제목(vn)',
    			'등록일'
    	];
    }
}