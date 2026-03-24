<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyGroup extends Model
{
    protected $table = 'company_group';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'group_name',   // SPC명
    		'created_by',
    		'created_at',
    		'updated_by',
    		'updated_at'
    ];
    
    public function company()
    {
    	return $this->hasMany(Company::class, 'group_idx', 'idx');
    }

    public function companyreport()
    {
    	return $this->hasMany(CompanyReport::class, 'group_idx', 'idx');
    }    
}