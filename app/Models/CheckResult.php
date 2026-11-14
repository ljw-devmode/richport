<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckResult extends Model
{
    protected $table = 'check_result';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'exec_idx',         // 체크리스트 (check_execution.idx)
    	'list_idx',         // 체크리스트 (check_list.idx)
        'section_idx',      // 섹션 (check_section.idx)
        'item_idx',         // 아이템 (check_item.idx)
        'input_type',       // 입력방식 (radio, checkbox, input)
        'option_idx',       // 옵션 (check_option.idx)
        'result_value',     // 결과 값 (체크된 옵션의 idx, 입력값 등)
    ];

    public function checkexecution() { 
        return $this->belongsTo(CheckExecution::class, 'exec_idx', 'idx'); 
    }

    public function checklist() { 
        return $this->belongsTo(CheckList::class, 'list_idx', 'idx'); 
    }

    public function checksection() { 
        return $this->belongsTo(CheckSection::class, 'section_idx', 'idx'); 
    }    

    public function checkitem() { 
        return $this->belongsTo(CheckItem::class, 'item_idx', 'idx'); 
    }

    public function checkoption() { 
        return $this->belongsTo(CheckOption::class, 'option_idx', 'idx'); 
    }
}