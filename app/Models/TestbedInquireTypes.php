<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestbedInquireTypes extends Model
{
    protected $table = 'testbed_inquire_types';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'subject',
        'subject_en',
        'subject_vn',
    ];
    
    public function inquire()
    {
    	return $this->hasMany(Testbedinquire::class, 'types_idx', 'idx');
    }
}