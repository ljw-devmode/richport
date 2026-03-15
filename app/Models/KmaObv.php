<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KmaObv extends Model
{
    protected $table = 'kma_obv';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'stn_name',
    ];
    
    public function company()
    {
    	return $this->hasMany(Company::class, 'idx', 'kma_idx');
    }
}