<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySales extends Model
{
    protected $table = 'company_sales';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'comp_idx',
			'day',
    		'type',
    		'quantity',
			'unitprice',
    		'sales',
    		'supplyprice',
    		'vat',
    		'created_by',
    		'created_at',
    		'updated_by',
    		'updated_at'
    ];
    
    public function company()
    {
    	return $this->belongsTo(Company::class, 'comp_idx','idx');
    }	
}