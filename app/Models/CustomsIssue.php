<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomsIssue extends Model
{
    protected $table = 'customs_issue';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'title',			// 제목(ko)
    		'title_en',
    		'title_vn',
    		'contents',			// 내용(ko)
    		'contents_en',
    		'contents_vn',
    		'ori_name',			// 첨부파일
    		'up_name',
    		'file_ext',
    		'file_size',
    		'created_at',
    		'created_by',
    		'updated_at',
    		'updated_by'
    ];

    protected $hidden = [
    		'up_name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
