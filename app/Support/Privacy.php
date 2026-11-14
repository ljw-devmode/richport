<?php

namespace App\Support;

/**
 * 개인정보 부분 마스킹 (관리자 목록 화면용, 상세 화면에서는 원래 값 표시)
 *   이름     : 홍길동            → 홍**
 *   전화번호 : 01012345678      → 010********   (010-1234-5678 → 010-****-****, 구분자는 유지)
 *   이메일   : abcdef@naver.co.kr → abc****@***.co.kr
 */
class Privacy
{
	public static function name($value): string
	{
		$value = trim((string) $value);
		if ($value === '') return '';

		$chars = mb_str_split($value);
		return count($chars) === 1 ? '*' : $chars[0].str_repeat('*', count($chars) - 1);
	}

	// 앞의 숫자 3자리만 보이고 나머지 숫자는 * (하이픈 · 공백 등 구분자는 그대로)
	public static function phone($value): string
	{
		$value = trim((string) $value);
		if ($value === '') return '';

		$seen = 0;
		return preg_replace_callback('/\d/', function ($m) use (&$seen) {
			return ++$seen <= 3 ? $m[0] : '*';
		}, $value);
	}

	// 아이디 앞 3자리 + ****, 도메인 첫 부분은 *** (나머지 .co.kr 등은 그대로)
	public static function email($value): string
	{
		$value = trim((string) $value);
		if ($value === '') return '';

		$at = mb_strrpos($value, '@');
		if ($at === false) return self::name($value);

		$local  = mb_substr($value, 0, $at);
		$domain = mb_substr($value, $at + 1);
		$labels = explode('.', $domain);
		$labels[0] = '***';

		return mb_substr($local, 0, min(3, max(1, mb_strlen($local) - 1))).'****@'.implode('.', $labels);
	}
}
