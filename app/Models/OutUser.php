<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutUser extends Model
{
    protected $table = 'out_user';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'out_idx',          // 아웃소싱업체 (outcompany.idx)
    	'user_name',        // 사용자 성명
        'user_phone',       // 사용자 전화번호
        'work_type',        // 업무 내용
        'reg_type',         // 등록 방식 (R 직접 등록, C 체크리스트 입력)
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

    public function getPassword()
    {
    	return $this->password;
    }

	public function outcompany()
    {
    	return $this->belongsTo(Outcompany::class, 'out_idx','idx');
    }  
}