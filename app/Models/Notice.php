<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table = 'notice';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'subject',
    		'subject_en',
    		'subject_cn',
    		'gubun',
    		'contents',
    		'contents_en',
    		'contents_cn',
    		'writed_at',
    		'created_by',
    		'created_at',
    		'updated_by',
    		'updated_at'
    ];
    
    public function files()
    {
    	return $this->hasMany(NoticeFiles::class, 'idx', 'notice_idx');
    }
    
    public function getGubunKrAttribute()
    {
    	return [
    			1 => '언론보도',
    			2 => 'MOU',
    			3 => '봉사',
    			4 => '인증',
    	][$this->gubun] ?? '-';
    }
    
    public function firstFile()
    {
    	return $this->hasOne(NoticeFiles::class, 'notice_idx', 'idx')
    	->orderBy('orderby', 'asc');
    }

}