<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    protected $table = 'regions';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'name_kr',
        'name_en',
        'name_cn'
    ];
    
    public function cities()
    {
    	return $this->hasMany(Cities::class, 'regions_idx', 'idx');
    }
    
    public function portfolio()
    {
    	return $this->hasMany(Portfolio::class, 'regions_idx', 'idx');
    }
}