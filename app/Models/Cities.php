<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    use HasLocalizedFields;

    // 한국어 컬럼명이 name_kr
    protected $localizedBase = ['name' => 'name_kr'];

    protected $table = 'cities';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'name_kr',
        'name_en',
        'name_vn'
    ];
    
    public function districts()
    {
    	return $this->hasMany(Districts::class, 'cities_idx', 'idx');
    }
    
    public function partner()
    {
    	return $this->hasMany(Partner::class, 'cities_idx', 'idx');
    }

    public function portfolio()
    {
    	return $this->hasMany(Portfolio::class, 'cities_idx', 'idx');
    }
}