<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NoticeFiles extends Model
{
    protected $table = 'notice_files';
    protected $primaryKey = 'idx';
    public $timestamps = false;
    
    protected $fillable = [
        'notice_idx',
    	'ori_name',     // 원본 파일명 (다운로드 시 원본 파일명으로 복원 후 다운로드 필요)
    	'up_name',      // 중복 방지를 위해 변경된 파일명
    	'file_ext',
    	'file_size',
    	'orderby',
    	'created_by',
    	'created_at',
    	'updated_by',
    	'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];	
    
    protected $appends = ['url'];
    
    public function getUrlAttribute()
    {
    	return $this->up_name? Storage::disk('public')->url($this->up_name): null;
    }
}