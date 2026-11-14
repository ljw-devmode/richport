<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Foundation\Auth\User as Authenticatable; // RIPS 로그인(guard: rips)

class Partner extends Authenticatable
{
    use HasLocalizedFields;

    protected $table = 'partner';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    // 본점/지점 구분 (본점은 구분값일 뿐 지점 정보 조회 권한 없음)
    const BRANCH_TYPES = [
    		'head'   => '본점',
    		'branch' => '지점',
    ];

    protected $fillable = [
    		'types_idx',			// 대형,소형마트, 편의점, 로컬
			'forms_idx',
			'country',
			'branch_type',			// 본점/지점 (head, branch)
			'head_idx',				// 소속 본점 (지점일 때)
    		'cities_idx',			// 시
    		'districts_idx',		// 구.군
    		'partner_name',			// 업체명
    		'partner_name_en',
    		'partner_name_vn',
			'store_name',			// 매장명
			'channel',				// 주요유통채널
			'channel_en',			
			'channel_vn',			
			'logistic',				// 평균배송일
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
    		'partner_recommend',	// 포트폴리오 화면 노출
			'rips_use',				// RIPS 사용여부
			'rips_id',				// RIPS 아이디
			'rips_pw',				// RIPS 비밀번호
			'rips_only',			// RIPS 업체 등록으로 만든 업체 (파트너 목록 · 선택에서 제외)
			'rips_partner_idx',		// 연결된 RIPS 업체 (사업관리 > 파트너 > RIPS 업체 설정)
			'rips_type_idx',		// RIPS 업체구분 (rips_inquire_types, 신청 시 정보)
			'photo_ori_name',		// 업체 사진 (RIPS > 내 정보 수정)
			'photo_up_name',
			'company_address',		// 회사 주소
			'company_tel',			// 회사 대표전화
			'ceo_name',				// 대표자명
			'customs_code',			// 통관고유번호 (최초 입력 후 업체 수정 불가)
			'biz_no',				// 사업자번호 (최초 입력 후 업체 수정 불가)
			'manager_name',			// 담당자명 (신청 시 정보)
			'manager_hp',			// 담당자 연락처 (신청 시 정보)
			'manager_email',		// 담당자 이메일
			'manager_task',			// 담당업무
			'contents',				// 소개글
			'contents_en',
			'contents_vn',
    		'created_by',
    		'created_at',
    		'updated_by',
    		'updated_at'
    ];

    protected $hidden = [
    		'rips_pw',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // 사업관리 > 파트너 에 표시되는 업체 (RIPS 업체 등록으로 만든 RIPS 전용 업체 제외)
    public function scopePartnersOnly($query)
    {
    	return $query->where('partner.rips_only', 0);
    }

    // RIPS 업체 (RIPS 계정이 있는 업체)
    public function scopeRipsAccounts($query)
    {
    	return $query->whereNotNull('rips_id')->where('rips_id', '!=', '');
    }

    // 이 파트너에 연결된 RIPS 업체
    public function ripsPartner()
    {
    	return $this->belongsTo(Partner::class, 'rips_partner_idx', 'idx');
    }

    // 이 RIPS 업체를 연결한 파트너
    public function mappedPartners()
    {
    	return $this->hasMany(Partner::class, 'rips_partner_idx', 'idx');
    }

    // RIPS 업체구분 (한국 제조업체 / 베트남 유통업체 등)
    public function ripsType()
    {
    	return $this->belongsTo(RipsInquireTypes::class, 'rips_type_idx', 'idx');
    }

    public function getAuthPassword()
    {
    	return $this->rips_pw;
    }

    public function getAuthPasswordName()
    {
    	return 'rips_pw';
    }
	
    // 소속 본점 (지점일 때)
    public function head()
    {
    	return $this->belongsTo(Partner::class, 'head_idx', 'idx');
    }

    public function getBranchTypeTextAttribute()
    {
    	return self::BRANCH_TYPES[$this->branch_type] ?? '본점';
    }

    // 유통기간 코드(5,10,15..) → "평균 10~15일" (관리자 선택지와 동일, 현재 언어로)
    public function getLogisticTextAttribute()
    {
    	$days = (int) $this->logistic;
    	if ($days <= 0) return '';

    	$range = ($days <= 5 ? 1 : $days - 5).'~'.$days;

    	return match (app()->getLocale()) {
    		'en'    => 'Avg. '.$range.' days',
    		'vn'    => 'Trung bình '.$range.' ngày',
    		default => '평균 '.$range.'일',
    	};
    }

    public function types()
    {
    	return $this->belongsTo(Types::class, 'types_idx','idx');
    }
    
    public function forms()
    {
    	return $this->belongsTo(FormTypes::class, 'forms_idx','idx');
    }
    
    public function cities()
    {
    	return $this->belongsTo(Cities::class, 'cities_idx', 'idx');
    }
    
    public function districts()
    {
    	return $this->belongsTo(Districts::class, 'districts_idx', 'idx');
    }

	public function products()
    {
    	return $this->hasMany(Product::class, 'partner_idx', 'idx');
    }

	public function files()
    {
    	return $this->hasMany(PartnerFiles::class, 'partner_idx', 'idx');
    }

	// RIPS 업체 카테고리 / 상품
	public function ripsCategories()
    {
    	return $this->hasMany(RipsCategory::class, 'partner_idx', 'idx');
    }

	public function ripsProducts()
    {
    	return $this->hasMany(RipsProduct::class, 'partner_idx', 'idx');
    }

}