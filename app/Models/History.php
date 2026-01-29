<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'history';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'subject',
    	'subject_en',
    	'subject_cn',				
    	'year',
    	'month',
    	'created_by',
    	'created_at',
    	'updated_by',
    	'updated_at'
    ];
}