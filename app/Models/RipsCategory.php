<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RipsCategory extends Model
{
    protected $table = 'rips_category';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    const MAX_DEPTH = 4;

    protected $fillable = [
    		'partner_idx',		// 파트너
    		'parent_idx',		// 상위 카테고리 (0: 최상위)
    		'depth',			// 깊이 (1~4)
    		'name',				// 카테고리명
			'forms_idx',		// 리치포트 카테고리 매핑 (form_types.idx)
    		'orderby',			// 정렬순서
    		'created_at',
    		'updated_at'
    ];

    // 매핑된 리치포트 카테고리
    public function forms()
    {
    	return $this->belongsTo(FormTypes::class, 'forms_idx', 'idx');
    }

    public function children()
    {
    	return $this->hasMany(RipsCategory::class, 'parent_idx', 'idx');
    }

    public function products()
    {
    	return $this->hasMany(RipsProduct::class, 'category_idx', 'idx');
    }

    /**
     * 파트너의 전체 카테고리를 [idx => '1차 > 2차 > ...'] 경로 문자열로 반환
     */
    public static function pathMap($partnerIdx)
    {
    	$rows = self::where('partner_idx', $partnerIdx)->orderBy('depth')->orderBy('orderby')->orderBy('idx')->get()->keyBy('idx');

    	$map = [];
    	foreach ($rows as $row) {
    		$names = [$row->name];
    		$parent = $row->parent_idx;
    		while ($parent && isset($rows[$parent])) {
    			array_unshift($names, $rows[$parent]->name);
    			$parent = $rows[$parent]->parent_idx;
    		}
    		$map[$row->idx] = implode(' > ', $names);
    	}

    	asort($map, SORT_NATURAL);

    	return $map;
    }

    /**
     * 카테고리 자신과 모든 하위 카테고리 idx 목록
     */
    public static function descendantIds($partnerIdx, $categoryIdx)
    {
    	$rows = self::where('partner_idx', $partnerIdx)->get(['idx', 'parent_idx'])->groupBy('parent_idx');

    	$ids = [(int) $categoryIdx];
    	$queue = [(int) $categoryIdx];
    	while ($queue) {
    		$current = array_shift($queue);
    		foreach ($rows[$current] ?? [] as $child) {
    			$ids[] = $child->idx;
    			$queue[] = $child->idx;
    		}
    	}

    	return $ids;
    }
}
