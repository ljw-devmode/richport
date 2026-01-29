<?php

namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Notice;

class noticeExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
    	$query = Notice::query();
    	$filters = $this->filters;
        // 검색 조건 처리
        
        if (!empty($filters['gubun'])) {
        	$query->where('gubun', $filters['gubun']);
        }
        
        if (!empty($filters['subject'])) {
        	$query->where('subject', 'like', '%' . $filters['subject'] . '%');
        }
        
        $rows = $query->orderByDesc('idx')->get();

        return $rows->map(function ($item) {
        	return [
        			'순번'		=> $item->idx,
        			'구분'   	=> $item->gubun_kr,
        			'제목(ko)'   	=> $item->subject,
        			'제목(en)'   	=> $item->subject_en,
        			'제목(cn)'   	=> $item->subject_cn,
        			'등록일' 	=> $item->writed_at,
        			'설명글(ko)'	=> $item->contents,
                    '설명글(en)'	=> $item->contents_en,
                    '설명글(cn)'	=> $item->contents_cn
        	];
        });
    }

    public function headings(): array
    {
        return [
            '순번',
            '구분',
            '제목(ko)',
            '제목(en)',
            '제목(cn)',
            '등록일',
            '설명글(ko)',
            '설명글(en)',
            '설명글(cn)'
        ];
    }
}