<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyYearKwh extends Model
{
    protected $table = 'company_yearkwh';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'comp_idx',
    		'period',
    		'kwh',
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