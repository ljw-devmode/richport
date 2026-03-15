<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyManage extends Model
{
    protected $table = 'company_manage';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'comp_idx',
    		'day',
    		'type',
    		'manage',
    		'del_yn',
    		'created_at',
			'created_by',
    		'updated_at',
			'updated_by'
    ];
    
    public function company()
    {
    	return $this->belongsTo(Company::class, 'comp_idx','idx');
    }	
}