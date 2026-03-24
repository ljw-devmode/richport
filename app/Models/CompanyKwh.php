<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyKwh extends Model
{
    protected $table = 'company_kwh';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'comp_idx',		// 발전소 (comopany.idx)
    		'year',			// 발전 년도 (yyyy)
    		'month',		// 발전 월 (m)
    		'act_kwh',		// 발전량
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