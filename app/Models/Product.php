<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasLocalizedFields;

    protected $table = 'product';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'partner_idx',			// 파트너
			'forms_idx',			// 카테고리
			'congbo',
			'new',
			'testbed',
			'congbo_number',		// 등록번호
    		'product_name',			// 업체명
    		'product_name_en',
    		'product_name_vn',
			'barcode',				// 바코드 (등록 시 필수)
			'order_price',			// 발주금액 (기본, RIPS 발주 단가)
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
    		'product_recommend',	// 상품 화면 노출
			'contents',				// 소개글
			'contents_en',
			'contents_vn',
    		'created_by',
    		'created_at',
    		'updated_by',
    		'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];	
	
    public function partners()
    {
    	return $this->belongsTo(Partner::class, 'partner_idx','idx');
    }

    public function forms()
    {
    	return $this->belongsTo(FormTypes::class, 'forms_idx','idx');
    }	
    
	public function files()
    {
    	return $this->hasMany(ProductFiles::class, 'product_idx', 'idx');
    }

	// 테스트베드 설문조사
	public function surveys()
    {
    	return $this->hasMany(CheckList::class, 'product_idx', 'idx');
    }

	// 테스트베드 설문조사 응답
	public function surveyResponses()
    {
    	return $this->hasManyThrough(CheckExecution::class, CheckList::class, 'product_idx', 'list_idx', 'idx', 'idx');
    }

}