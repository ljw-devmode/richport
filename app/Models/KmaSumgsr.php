<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KmaSumgsr extends Model
{
    protected $table = 'kma_sumgsr';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'stn_idx',      // 관측소 (kma_obv.idx)
            'date',         // 일자
            'sumgsr',       // 일사량
            'created_at',
    ];
    
    public function kma_obv()
    {
    	return $this->belongsTo(KmaObv::class, 'stn_idx', 'idx');
    }
}