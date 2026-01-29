<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    protected $table = 'types';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'subject',
    	'subject_en',
    	'subject_cn',
    ];
    
    public function portfolios()
    {
    	return $this->hasMany(Portfolio::class, 'types_idx', 'idx');
    }
}