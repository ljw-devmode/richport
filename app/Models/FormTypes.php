<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormTypes extends Model
{
    protected $table = 'form_types';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'subject',
        'subject_en',        
        'subject_cn',
    ];
    
    public function portfolio()
    {
    	return $this->hasMany(Portfolio::class, 'form_idx', 'idx');
    }
}