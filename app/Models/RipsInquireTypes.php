<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RipsInquireTypes extends Model
{
    protected $table = 'rips_inquire_types';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'subject',
        'subject_en',
        'subject_vn',
    ];
    
    public function inquire()
    {
    	return $this->hasMany(Ripsinquire::class, 'types_idx', 'idx');
    }
}