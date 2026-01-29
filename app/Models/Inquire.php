<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquire extends Model
{
    protected $table = 'inquire';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'status',
    		'types_idx',
    		'inquire_date',
    		'inquire_company',
    		'inquire_hp',
    		'inquire_address',
    		'inquire_contents',
    		'response_contents',
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
    	return $this->belongsTo(InquireTypes::class, 'types_idx','idx');
    }
}