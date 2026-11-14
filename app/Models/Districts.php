<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Model;

class Districts extends Model
{
    use HasLocalizedFields;

    // 한국어 컬럼명이 name_kr
    protected $localizedBase = ['name' => 'name_kr'];

    protected $table = 'districts';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'cities_idx',
    	'name_kr',
        'name_en',
        'name_vn'
    ];
    
    public function cities()
    {
    	return $this->belongsTo(Cities::class, 'cities_idx', 'idx');
    }
}