<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioFiles extends Model
{
    protected $table = 'portfolio_files';
    protected $primaryKey = 'idx';
    public $timestamps = false;
    
    protected $fillable = [
        'portfolio_idx',
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

	public function portfolio()
    {
    	return $this->belongsTo(Portfolio::class, 'portfolio_idx', 'idx');
    }
}