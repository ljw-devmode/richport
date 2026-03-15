<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthDays extends Model
{
    protected $table = 'monthdays';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'year',
    		'month',
    		'days'
    ];
}