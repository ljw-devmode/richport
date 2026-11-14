<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutType extends Model
{
    protected $table = 'out_type';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'subject',          // 업무명
    ];
}