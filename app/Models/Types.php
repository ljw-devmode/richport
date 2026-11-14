<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    use HasLocalizedFields;

    protected $table = 'types';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'subject',
    	'subject_en',
    	'subject_vn',
    ];
    
    public function portfolios()
    {
    	return $this->hasMany(Portfolio::class, 'types_idx', 'idx');
    }
}