<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyYearKwhHours extends Model
{
    protected $table = 'company_yearkwhhours';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'comp_idx',		// 발전소 (comopany.idx)
    		'year',			// 발전 년도 (yyyy)
    		'month',		// 발전 월 (m)
    		'tgt_hours',	// 월별 목표 발전 시간
    		'created_by',
    		'created_at',
    		'updated_by',
    		'updated_at'
    ];
    
    public function company()
    {
    	return $this->belongsTo(Company::class, 'comp_idx','idx');
    }	
}