<?php

namespace App\Support;

/**
 * 상품 바코드 입력값 검사 (관리자 > 제품, RIPS > 상품 공통)
 * - 영문 · 숫자 · 하이픈 4~50자 (EAN-13, UPC 등), 앞뒤 공백 제거
 * - 같은 범위(관리자 제품 전체 / 같은 업체 상품) 안에서 중복 불가
 */
class Barcode
{
	const PATTERN = '/^[0-9A-Za-z\-]{4,50}$/';

	/**
	 * @param \Illuminate\Database\Eloquent\Builder $scope 중복을 검사할 범위 (barcode 컬럼 보유)
	 * @return array [정리된 값|null, 오류메시지|null]
	 */
	public static function check($value, bool $required, $scope, $exceptIdx = null): array
	{
		$value = trim((string) $value);

		if ($value === '') return [null, $required ? '바코드를 입력해주세요.' : null];
		if (!preg_match(self::PATTERN, $value)) return [null, '바코드는 영문, 숫자, 하이픈(-) 4~50자로 입력해주세요.'];

		$duplicate = $scope->where('barcode', $value)->when($exceptIdx, fn ($q) => $q->where('idx', '!=', $exceptIdx))->exists();
		if ($duplicate) return [null, '이미 등록된 바코드입니다. ('.$value.')'];

		return [$value, null];
	}
}
