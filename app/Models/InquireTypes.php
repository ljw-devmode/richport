<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InquireTypes extends Model
{
    protected $table = 'inquire_types';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'subject',
        'subject_en',
        'subject_vn',
    ];
    
    public function inquire()
    {
    	return $this->hasMany(Inquire::class, 'types_idx', 'idx');
    }
}