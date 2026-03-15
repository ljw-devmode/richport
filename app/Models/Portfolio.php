<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $table = 'portfolio';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'types_idx',
    		'form_idx',
    		'regions_idx',
    		'cities_idx',
    		'year',
    		'station_name',
    		'station_name_en',
    		'station_name_cn',
    		'volume',
    		'price',
    		'price_en',
    		'price_cn',
    		'completion_date',
    		'url',
    		'blog',
    		'pc_ori_name',
    		'pc_up_name',
    		'pc_file_ext',
    		'pc_file_size',
    		'fo_ori_name',
    		'fo_up_name',
    		'fo_file_ext',
    		'fo_file_size',
    		'mo_ori_name',
    		'mo_up_name',
    		'mo_file_ext',
    		'mo_file_size',
    		'youtube_iframe',
    		'youtube_subject',
    		'youtube_subject_en',
    		'youtube_subject_cn',
    		'youtube_contents',
    		'youtube_contents_en',
    		'youtube_contents_cn',
    		'blog_contents',
    		'blog_contents_en',
    		'blog_contents_cn',
    		'home_recommend',
    		'introduce_recommend',
    		'portfolio_recommend',
    		'created_by',
    		'created_at',
    		'updated_by',
    		'updated_at'
    ];
    
    public function types()
    {
    	return $this->belongsTo(Types::class, 'types_idx','idx');
    }
    
    public function formTypes()
    {
    	return $this->belongsTo(FormTypes::class, 'form_idx','idx');
    }
    
    public function regions()
    {
    	return $this->belongsTo(Regions::class, 'regions_idx', 'idx');
    }
    
    public function cities()
    {
    	return $this->belongsTo(Cities::class, 'cities_idx', 'idx');
    }
    
    public function files()
    {
    	return $this->hasMany(PortfolioFiles::class, 'portfolio_idx', 'idx');
    }

}