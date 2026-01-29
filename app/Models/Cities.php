<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $table = 'cities';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'regions_idx',
    	'name_kr',
        'name_en',
        'name_cn'
    ];
    
    public function region()
    {
    	return $this->belongsTo(Regions::class, 'regions_idx', 'idx');
    }
    
    public function portfolio()
    {
    	return $this->hasMany(Portfolio::class, 'cities_idx', 'idx');
    }
}