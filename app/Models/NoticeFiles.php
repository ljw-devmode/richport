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
    	'ori_name',
    	'up_name',
    	'file_ext',
    	'file_size',
    	'orderby',
    	'created_by',
    	'created_at',
    	'updated_by',
    	'updated_at',
    ];
    
    protected $appends = ['url'];
    
    public function getUrlAttribute()
    {
    	return $this->up_name? Storage::disk('public')->url($this->up_name): null;
    }
}