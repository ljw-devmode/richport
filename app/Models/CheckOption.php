<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckOption extends Model
{
    protected $table = 'check_option';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'item_idx',     // 아이템 (check_item.idx)
    	'option_name',  // 옵션 이름
        'sort_order',   // 옵션 순서
    ];

    // item 하위 option
    public function checkitem() {
        return $this->belongsTo(CheckItem::class, 'item_idx', 'idx');
    }
}