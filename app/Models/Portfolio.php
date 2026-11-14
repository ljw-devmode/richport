<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasLocalizedFields;

    protected $table = 'portfolio';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'types_idx',			// 대형,소형마트, 편의점, 로컬
    		'cities_idx',			// 시
    		'districts_idx',		// 구.군
    		'station_name',			// 업체명
    		'station_name_en',
    		'station_name_vn',
			'contents',
			'contents_en',
			'contents_vn',
    		'pc_ori_name',			// PC 용 썸네일
    		'pc_up_name',
    		'pc_file_ext',
    		'pc_file_size',
    		'fo_ori_name',			// Fold 용 썸네일
    		'fo_up_name',
    		'fo_file_ext',
    		'fo_file_size',
    		'mo_ori_name',			// Mobile 용 썸네일
    		'mo_up_name',
    		'mo_file_ext',
    		'mo_file_size',
    		'home_recommend',		// 홈 화면 노출
    		'portfolio_recommend',	// 포트폴리오 화면 노출
    		'created_by',
    		'created_at',
    		'updated_by',
    		'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];	
	
    public function types()
    {
    	return $this->belongsTo(Types::class, 'types_idx','idx');
    }
    
    public function cities()
    {
    	return $this->belongsTo(Cities::class, 'cities_idx', 'idx');
    }
    
    public function districts()
    {
    	return $this->belongsTo(Districts::class, 'districts_idx', 'idx');
    }

	public function files()
    {
    	return $this->hasMany(PortfolioFiles::class, 'portfolio_idx', 'idx');
    }

}