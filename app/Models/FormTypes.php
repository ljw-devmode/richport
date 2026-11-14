<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Model;

class FormTypes extends Model
{
    use HasLocalizedFields;

    protected $table = 'form_types';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    // 카테고리 최대 단계
    const MAX_DEPTH = 4;

    protected $fillable = [
    	'parent_idx',		// 상위 카테고리 (0: 최상위)
    	'depth',			// 단계 (1~4)
    	'subject',
        'subject_en',
        'subject_vn',
        'orderby',			// 정렬순서 (작은 값이 위)
    ];

    // 1단계 카테고리만 (파트너 등 기존 화면용), 정렬순서대로
    public function scopeTop($query)
    {
    	return $query->where('depth', 1)->orderBy('orderby')->orderBy('idx');
    }

    // 1~4단계 전체 (단계 → 정렬순서), 화면의 1~4차 연동 셀렉트용
    public static function tree()
    {
    	return static::orderBy('depth')->orderBy('orderby')->orderBy('idx')->get(['idx', 'parent_idx', 'depth', 'subject']);
    }

    // 자신 + 모든 하위 카테고리 idx (상위 카테고리로 검색하면 하위에 등록된 제품도 포함)
    public static function descendantIds($idx, $tree = null)
    {
    	$children = ($tree ?? static::get(['idx', 'parent_idx']))->groupBy('parent_idx');
    	$ids      = [(int) $idx];
    	for ($i = 0; $i < count($ids); $i++) {
    		foreach ($children->get($ids[$i], []) as $child) $ids[] = (int) $child->idx;
    	}
    	return $ids;
    }

    // idx => '1차 > 2차 > 3차 > 4차' 경로
    public static function pathMap($tree = null)
    {
    	$tree  = ($tree ?? static::tree())->keyBy('idx');
    	$paths = [];
    	foreach ($tree as $idx => $item) {
    		$names = [];
    		for ($f = $item, $guard = 0; $f && $guard < static::MAX_DEPTH; $f = $tree->get($f->parent_idx), $guard++) {
    			array_unshift($names, $f->subject);
    		}
    		$paths[$idx] = implode(' > ', $names);
    	}
    	return $paths;
    }

    public function parent()
    {
    	return $this->belongsTo(FormTypes::class, 'parent_idx', 'idx');
    }

    public function children()
    {
    	return $this->hasMany(FormTypes::class, 'parent_idx', 'idx')->orderBy('orderby')->orderBy('idx');
    }

    public function partners()
    {
    	return $this->hasMany(Partner::class, 'forms_idx', 'idx');
    }

    public function products()
    {
    	return $this->hasMany(Product::class, 'forms_idx', 'idx');
    }

    // RIPS 업체 카테고리 매핑
    public function ripsCategories()
    {
    	return $this->hasMany(RipsCategory::class, 'forms_idx', 'idx');
    }
}
