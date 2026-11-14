<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class OutCompany extends Authenticatable
{
    protected $table = 'out_company';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'out_name',         // 아웃소싱 업체명
        'id',               // 아이디
    	'password',         // 비밀번호
        'ceo',              // 대표자명
        'phone',            // 전화번호
        'email',            // 이메일
        'address',          // 주소
        'business_number',  // 사업자 번호
        'use_yn',           // 사용여부
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function outuser()
    {
        return $this->hasMany(OutUser::class, 'out_idx', 'idx');
    }

    public function companyout()
    {
    	return $this->hasMany(CompanyOut::class, 'comp_idx','idx');
    }

    public function companies()
    {
        // 아웃소싱 업체가 담당하는 발전소들
        return $this->belongsToMany(Company::class, 'company_out', 'out_idx', 'comp_idx');
    }
    
}