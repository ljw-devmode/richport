<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerFiles extends Model
{
    protected $table = 'partner_files';
    protected $primaryKey = 'idx';
    public $timestamps = false;
    
    protected $fillable = [
        'partner_idx',
    	'ori_name',
    	'up_name',
    	'file_ext',
    	'file_size',
    	'orderby',
    	'created_by',
    	'created_at',
    	'updated_by',
    	'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];	

	public function partner()
    {
    	return $this->belongsTo(Partner::class, 'partner_idx', 'idx');
    }
}