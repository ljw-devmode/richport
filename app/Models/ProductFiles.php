<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFiles extends Model
{
    protected $table = 'product_files';
    protected $primaryKey = 'idx';
    public $timestamps = false;
    
    protected $fillable = [
        'product_idx',
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

	public function product()
    {
    	return $this->belongsTo(Product::class, 'product_idx', 'idx');
    }
}