<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasLocalizedFields;

    protected $table = 'history';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'subject',
    	'subject_en',
    	'subject_vn',				
    	'year',
    	'month',
    	'created_by',
    	'created_at',
    	'updated_by',
    	'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];	

}