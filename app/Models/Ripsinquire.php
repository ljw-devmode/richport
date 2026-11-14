<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ripsinquire extends Model
{
    protected $table = 'rips_inquire';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'status',
    		'types_idx',			// 타입 (types.php)
    		'inquire_date',
    		'inquire_company',
    		'inquire_hp',
    		'inquire_address',
    		'inquire_contents',		// 문의 사항
    		'response_contents',	// 답변 내용
    		'response_by',
    		'response_at',
    		'complete_at'
    ];
    
    protected $appends = ['status_kr'];
    
    public function getStatusKrAttribute()
    {
    	return match ((int) $this->status) {
    		1 => '접수',
    		2 => '답변준비중',
    		3 => '답변완료',
    		default => '알수없음',
    	};
    }
    
    public function types()
    {
    	return $this->belongsTo(RipsInquireTypes::class, 'types_idx','idx');
    }
}