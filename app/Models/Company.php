<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'comp_name',    // 발전소명
    		'regions_idx',  // 발전소 위치 시도 (regions.idx)
    		'cities_idx',   // 발전소 위치 시군 (cities.idx)
    		'volume',       // 발전 용량
    		'tgt_kwh',      // 최초년도 목표 발전량
    		'tgt_rate',     // 목표 발전 효율
    		'start_date',   // 발전 시작일 (1차년도 시작 년월일 yyyy-mm-dd)
    		'kma_idx',      // 기상청 일사량 조회 관측소 코드 (omaobv.idx)
    		'group_idx',    // SPC 그룹 (companygroup.idx)
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

	public function companyyearkwhhours()
    {
    	return $this->hasMany(CompanyYearKwhHours::class, 'comp_idx','idx');
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