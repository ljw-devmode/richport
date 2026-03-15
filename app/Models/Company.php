<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'comp_name',
    		'regions_idx',
    		'cities_idx',
    		'volume',
    		'tgt_kwh',
    		'tgt_rate',
    		'start_date',
    		'kma_idx',
    		'group_idx',
    		'created_by',
    		'created_at',
    		'updated_by',
    		'updated_at'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function companygroup()
    {
    	return $this->belongsTo(CompanyGroup::class, 'group_idx','idx');
    }
    
	public function companykwh()
    {
    	return $this->hasMany(CompanyKwh::class, 'comp_idx','idx');
    }

	public function companyyearkwh()
    {
    	return $this->hasMany(CompanyYearKwh::class, 'comp_idx','idx');
    }    

	public function companysales()
    {
    	return $this->hasMany(CompanySales::class, 'comp_idx','idx');
    }    

	public function companymanage()
    {
    	return $this->hasMany(CompanyManage::class, 'comp_idx','idx');
    }

    public function kmaobv()
    {
    	return $this->belongsTo(KmaObv::class, 'kma_idx', 'idx');
    }

    public function regions()
    {
    	return $this->belongsTo(Regions::class, 'regions_idx', 'idx');
    }
    
    public function cities()
    {
    	return $this->belongsTo(Cities::class, 'cities_idx', 'idx');
    }
}