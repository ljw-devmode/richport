<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * [테스트] lottemart RIPS 업체 : 리치포트 신상품 / 테스트베드 신청 / 설문조사 결과
 * 운영 DB 에서 실행하지 마세요.
 *
 * 실행 : php artisan db:seed --class=TestLottemartTestbedSeeder
 * 삭제 : TEST_CLEAN=1 php artisan db:seed --class=TestLottemartTestbedSeeder   (PowerShell: $env:TEST_CLEAN=1; ...)
 *
 * - 모든 데이터는 product.created_by / check_list.created_by = 'TEST-TB' 로 구분, 다시 실행하면 지우고 새로 만듦
 * - 상품명 앞에 [TEST-TB] 가 붙음
 *
 * 테스트 시나리오 (lottemart 기준)
 *   불닭 까르보     : 진행완료 + 설문 2개 (응답 40 / 12, 비활성 1개)     → 설문조사 결과 목록·설문 선택 확인
 *   비비고 김치왕교자 : 진행완료 + 설문 1개 (응답 25)
 *   정관장 홍삼정    : 진행완료 + 설문 1개 (응답 0)          → '아직 응답이 없습니다'
 *   CJ 햇반         : 진행완료 + 설문 없음                  → '등록된 설문 없음'
 *   꼬북칩          : 진행중 + 설문 1개 (응답 15)           → 업체에는 보이면 안 됨 (관리자에서 진행완료로 바꾸면 보임)
 *   바나나맛우유     : 답변완료
 *   메디힐 마스크팩   : 답변준비중
 *   진라면 순한맛     : 접수
 *   신라면 블랙      : 신청 없음                            → 테스트베드 신청 테스트용
 *   + 불닭 까르보 홈페이지 신청 1건 (관리자 신청구분 '홈페이지' 확인용)
 *   + lottemart 업체 상품 4개(불닭·교자·홍삼정·꼬북칩)를 리치포트 상품에 매핑하고 일별 판매 데이터 생성
 *     (햇반은 매핑하지 않음 → 설문조사 결과의 '판매 데이터 없음' 확인용)
 */
class TestLottemartTestbedSeeder extends Seeder
{
	const MARK     = 'TEST-TB';
	const CATEGORY = '[TEST-TB] 테스트베드';		// lottemart 업체 카테고리

	// 상태별 테스트 답변 내용
	const RESPONSES = [
		2 => '신청 내용을 검토 중입니다. 매장 일정 확인 후 연락드리겠습니다.',
		3 => '테스트베드 진행이 가능합니다. 11월 셋째 주부터 시식 매대를 운영합니다.',
		4 => '테스트베드 진행중입니다. 시식 매대 운영 및 설문조사를 진행하고 있습니다.',
		5 => '테스트베드가 완료되었습니다. 설문조사 결과를 확인해주세요.',
	];

	public function run(): void
	{
		$this->clean();
		if (env('TEST_CLEAN')) {
			$this->command?->info('TEST-TB 데이터를 삭제했습니다.');
			return;
		}

		$lotte = DB::table('partner')->where('rips_id', 'lottemart')->first();
		if (!$lotte) {
			$this->command?->error('rips_id = lottemart 업체가 없습니다.');
			return;
		}

		mt_srand(20261106);	// 매번 같은 결과

		DB::transaction(function () use ($lotte) {
			$now     = Carbon::now();
			$forms   = DB::table('form_types')->where('depth', 1)->pluck('idx', 'subject');
			$images  = DB::table('product')->whereNotNull('pc_up_name')->where('pc_up_name', '!=', '')
				->where('created_by', '!=', self::MARK)->limit(10)->get(['partner_idx', 'pc_up_name', 'pc_ori_name', 'fo_up_name', 'mo_up_name']);
			$supplier = $images->first()->partner_idx ?? DB::table('partner')->where('idx', '!=', $lotte->idx)->min('idx');

			$company = trim($lotte->partner_name.' '.($lotte->store_name ?? ''));

			// [키, 카테고리, 상품명, 영문, 소개글, 테스트베드 상태(null:신청없음), 설문 [[이름, 응답수, 사용여부]]]
			$products = [
				['buldak',  '면·오일·양념',          '삼양 까르보 불닭볶음면 130g',   'Samyang Carbo Buldak 130g',     "크림 소스로 매운맛을 부드럽게 잡은 까르보 불닭볶음면입니다.\n베트남 MZ 세대 반응 테스트를 위한 신상품입니다.", 5, [['까르보 불닭 재구매 의향 설문', 12, 'N'], ['까르보 불닭 1차 소비자 설문', 40, 'Y']]],
				['mandu',   '반찬·가공 식품',        '비비고 김치왕교자 1.05kg',      'Bibigo Kimchi Mandu 1.05kg',    "국내산 김치를 넣어 시원하고 칼칼한 맛의 왕교자입니다.", 5, [['비비고 김치왕교자 시식 설문', 25, 'Y']]],
				['ginseng', '건강식품·헬스케어',     '정관장 홍삼정 에브리타임 10ml×30포', 'KGC Everytime Red Ginseng',  "언제 어디서나 간편하게 섭취하는 스틱형 홍삼정입니다.", 5, [['홍삼정 에브리타임 체험 설문', 0, 'Y']]],
				['rice',    '과일·견과류·쌀',        'CJ 햇반 210g×3',               'CJ Hetbahn Cooked Rice 210g×3', "전자레인지 2분이면 완성되는 즉석밥입니다.", 5, []],
				['kkobuk',  '군것질·아이스크림',     '오리온 꼬북칩 초코츄러스 160g', 'Orion Turtle Chips Choco Churros', "네 겹 바삭한 식감에 초코 츄러스 맛을 더한 과자입니다.", 4, [['꼬북칩 초코츄러스 시식 설문', 15, 'Y']]],
				['banana',  '음료·우유',             '빙그레 바나나맛우유 240ml',     'Binggrae Banana Milk 240ml',    "한국 대표 가공우유, 바나나맛우유입니다.", 3, []],
				['mask',    '뷰티·개인 위생 도구',   '메디힐 티트리 마스크팩 10매',   'Mediheal Teatree Mask 10ea',    "진정 효과의 티트리 에센스 마스크팩입니다.", 2, []],
				['jin',     '면·오일·양념',          '오뚜기 진라면 순한맛 120g×5',   'Ottogi Jin Ramen Mild 120g×5',  "부드럽고 깔끔한 국물의 진라면 순한맛입니다.", 1, []],
				['black',   '면·오일·양념',          '농심 신라면 블랙 134g×4',       'Nongshim Shin Ramyun Black',    "사골 육수로 깊은 맛을 낸 프리미엄 신라면입니다.", null, []],
			];

			$created = [];	// 키 => [상품 idx, 상품명, 테스트베드 신청일]

			foreach ($products as $i => [$key, $formName, $name, $nameEn, $contents, $status, $surveys]) {
				$image     = $images->count() ? $images[$i % $images->count()] : null;
				$createdAt = $now->copy()->subDays(40 - $i);

				$productIdx = DB::table('product')->insertGetId([
					'partner_idx'       => $image->partner_idx ?? $supplier,
					'forms_idx'         => $forms[$formName] ?? $forms->first(),
					'congbo'            => 0,
					'new'               => 1,
					'testbed'           => 1,
					'product_name'      => '[TEST-TB] '.$name,
					'product_name_en'   => '[TEST-TB] '.$nameEn,
					'product_name_vn'   => '[TEST-TB] '.$nameEn,
					'order_price'       => null,
					'pc_ori_name'       => $image->pc_ori_name ?? null,
					'pc_up_name'        => $image->pc_up_name ?? null,
					'fo_up_name'        => $image->fo_up_name ?? '',
					'fo_file_size'      => 0,
					'mo_up_name'        => $image->mo_up_name ?? null,
					'contents'          => $contents,
					'home_recommend'    => 0,
					'product_recommend' => 0,
					'created_at'        => $createdAt,
					'created_by'        => self::MARK,
				]);

				// 테스트베드 신청 (lottemart)
				if ($status !== null) {
					$applied = $now->copy()->subDays(30 - $i)->setTime(10 + $i % 6, 15);
					$created[$key] = [$productIdx, $name, $applied->copy(), $status === 5 ? $applied->copy()->addDays(18) : null];
					DB::table('testbed_inquire')->insert([
						'status'            => $status,
						'types_idx'         => $productIdx,
						'partner_idx'       => $lotte->idx,
						'inquire_date'      => $applied,
						'inquire_company'   => $company,
						'inquire_hp'        => '028-3776-1234',
						'inquire_address'   => '469 Nguyễn Hữu Thọ, Tân Hưng, Quận 7, TP. Hồ Chí Minh',
						'inquire_contents'  => "[TEST-TB] {$name} 테스트베드 진행을 희망합니다.\n시식 매대 2주 운영 가능합니다.",
						'response_contents' => $status >= 2 ? "[TEST-TB] ".self::RESPONSES[$status] : null,
						'response_by'       => $status >= 2 ? '관리자' : null,
						'response_at'       => $status >= 2 ? $applied->copy()->addDay() : null,
						'complete_at'       => in_array($status, [3, 5]) ? $applied->copy()->addDays($status === 5 ? 18 : 3) : null,
					]);
				}

				// 설문조사
				foreach ($surveys as [$surveyName, $responseCount, $useYn]) {
					$this->createSurvey($productIdx, '[TEST-TB] '.$surveyName, $responseCount, $useYn, $now->copy()->subDays(14 - $i));
				}

				// 홈페이지 신청 1건 (신청구분 비교용)
				if ($key === 'buldak') {
					DB::table('testbed_inquire')->insert([
						'status'           => 1,
						'types_idx'        => $productIdx,
						'partner_idx'      => null,
						'inquire_date'     => $now->copy()->subDays(5),
						'inquire_company'  => '[TEST-TB] 홈페이지 신청 마트',
						'inquire_hp'       => '090-111-2222',
						'inquire_address'  => 'Quận 1, TP. Hồ Chí Minh',
						'inquire_contents' => '[TEST-TB] 홈페이지에서 신청한 테스트베드입니다.',
					]);
				}
			}
			$this->createSales($lotte, $created, $now);
		});

		$this->command?->info('lottemart 테스트 데이터를 만들었습니다. (상품 9, 테스트베드 9, 설문 5, 업체 상품·판매 데이터 4개)');
	}

	// 설문 1개 + 응답 N건
	private function createSurvey(int $productIdx, string $name, int $responseCount, string $useYn, Carbon $createdAt): void
	{
		$listIdx = DB::table('check_list')->insertGetId([
			'check_name'   => $name,
			'product_idx'  => $productIdx,
			'survey_token' => Str::random(32),
			'multi_sign'   => 'N',
			'use_yn'       => $useYn,
			'created_at'   => $createdAt,
			'created_by'   => self::MARK,
		]);

		// [섹션명, layout_type, [[d2, 문항, 유형, 비고, [보기...], 응답 가중치]]]
		$scale = ['매우 만족', '만족', '보통', '불만족', '매우 불만족'];
		$sections = [
			['제품 평가', 1, [
				[null, '전반적으로 제품에 얼마나 만족하셨나요?', 'radio', 'N', $scale, [35, 40, 15, 7, 3]],
				[null, '맛(사용감)은 어떠셨나요?', 'radio', 'N', $scale, [30, 38, 20, 9, 3]],
				[null, '가격은 어떻게 생각하시나요?', 'radio', 'Y', ['비싸다', '적당하다', '저렴하다'], [45, 45, 10]],
				[null, '구매하고 싶은 이유를 모두 선택해주세요.', 'checkbox', 'N', ['맛·품질', '가격', '한국 브랜드', '포장·디자인', '지인 추천'], [70, 30, 60, 35, 15]],
				[null, '다시 구매하실 의향이 있나요?', 'radio', 'N', ['있다', '없다', '잘 모르겠다'], [65, 10, 25]],
				[null, '제품에 대한 개선 의견을 자유롭게 적어주세요.', 'input', 'N', [], [55]],
			]],
			['매장 경험', 2, [
				['진열', '제품을 매장에서 쉽게 찾으셨나요?', 'radio', 'N', ['쉬웠다', '보통', '어려웠다'], [50, 35, 15]],
				['시식', '시식 행사 진행은 어떠셨나요?', 'radio', 'N', $scale, [40, 35, 18, 5, 2]],
			]],
		];
		$opinions = [
			'조금 덜 매웠으면 좋겠어요.', '양이 조금 더 많았으면 합니다.', '포장에 베트남어 설명이 있으면 좋겠습니다.',
			'가격이 조금 비싼 편이에요.', '맛있어서 가족들도 좋아했어요.', '용량이 다양하게 나왔으면 합니다.',
			'할인 행사를 자주 했으면 좋겠습니다.', '편의점에서도 팔았으면 좋겠어요.', '아이들이 먹기엔 조금 자극적이에요.',
		];
		$remarks = ['비슷한 현지 제품보다 2배 정도 비싸요.', '프로모션 가격이면 사겠습니다.', '품질 대비 괜찮습니다.'];

		$items = [];
		foreach ($sections as $s => [$sectionName, $layout, $questions]) {
			$sectionIdx = DB::table('check_section')->insertGetId(['list_idx' => $listIdx, 'section_name' => $sectionName, 'sort_order' => $s + 1, 'layout_type' => $layout]);

			foreach ($questions as $q => [$d2, $question, $type, $hasText, $options, $weights]) {
				$itemIdx = DB::table('check_item')->insertGetId([
					'section_idx' => $sectionIdx, 'd2_name' => $d2, 'd3_name' => null,
					'item_name' => $question, 'input_type' => $type, 'sort_order' => $q + 1, 'has_text' => $hasText,
				]);
				$optionIds = [];
				foreach ($options as $o => $option) {
					$optionIds[] = DB::table('check_option')->insertGetId(['item_idx' => $itemIdx, 'option_name' => $option, 'sort_order' => $o + 1]);
				}
				$items[] = compact('sectionIdx', 'itemIdx', 'type', 'hasText', 'optionIds', 'weights');
			}
		}

		// 응답자 분포
		$ages      = ['1-10' => 2, '11-20' => 12, '21-30' => 35, '31-40' => 28, '41-50' => 14, '51-59' => 6, '60+' => 3];
		$districts = DB::table('districts')->get(['idx', 'cities_idx']);
		$hcm       = $districts->where('cities_idx', 1)->values();

		for ($n = 0; $n < $responseCount; $n++) {
			// 호치민 70%, 그 외 30%
			$district = (mt_rand(1, 100) <= 70 && $hcm->count()) ? $hcm[mt_rand(0, $hcm->count() - 1)] : $districts[mt_rand(0, $districts->count() - 1)];

			$execIdx = DB::table('check_execution')->insertGetId([
				'list_idx'      => $listIdx,
				'age_group'     => $this->pick($ages),
				'gender'        => mt_rand(1, 100) <= 62 ? 'F' : 'M',
				'cities_idx'    => $district->cities_idx,
				'districts_idx' => $district->idx,
				'use_yn'        => 'Y',
				'created_at'    => $createdAt->copy()->addDays(1 + intdiv($n, 4))->setTime(mt_rand(9, 20), mt_rand(0, 59)),
				'created_by'    => 'survey',
			]);

			$rows = [];
			foreach ($items as $item) {
				$base = ['exec_idx' => $execIdx, 'list_idx' => $listIdx, 'section_idx' => $item['sectionIdx'], 'item_idx' => $item['itemIdx'], 'input_type' => $item['type']];

				if ($item['type'] === 'input') {
					if (mt_rand(1, 100) <= $item['weights'][0]) $rows[] = $base + ['option_idx' => null, 'result_value' => $opinions[mt_rand(0, count($opinions) - 1)]];
					continue;
				}

				if ($item['type'] === 'checkbox') {
					$chosen = [];
					foreach ($item['optionIds'] as $o => $optionIdx) {
						if (mt_rand(1, 100) <= $item['weights'][$o]) $chosen[] = $optionIdx;
					}
					if (!$chosen) $chosen[] = $item['optionIds'][0];
				} else {
					$chosen = [$item['optionIds'][$this->pick(array_combine(array_keys($item['optionIds']), $item['weights']))]];
				}

				foreach ($chosen as $c => $optionIdx) {
					$remark = ($c === 0 && $item['hasText'] === 'Y' && mt_rand(1, 100) <= 30) ? $remarks[mt_rand(0, count($remarks) - 1)] : null;
					$rows[] = $base + ['option_idx' => $optionIdx, 'result_value' => $remark];
				}
			}
			DB::table('check_result')->insert($rows);
		}
	}

	/**
	 * lottemart 업체 상품 (리치포트 상품 매핑) + 일별 판매 데이터
	 * 테스트베드 신청 3일 후부터 오늘까지, 테스트베드 완료 후에는 판매량 증가
	 * 햇반은 일부러 매핑하지 않음 → '판매 데이터 없음' 확인용
	 */
	private function createSales($lotte, array $created, Carbon $now): void
	{
		$categoryIdx = DB::table('rips_category')->insertGetId([
			'partner_idx' => $lotte->idx, 'parent_idx' => 0, 'depth' => 1,
			'name' => self::CATEGORY, 'orderby' => 99, 'created_at' => $now,
		]);

		// [키, 업체 상품명, 제조사, 일평균 판매량, 단가, 완료 후 증가율, 시작 재고]
		$items = [
			['buldak',  '까르보불닭볶음면 130g',     '삼양식품', 12, 32000,  1.45, 900],
			['mandu',   '비비고 김치왕교자 1.05kg',  'CJ제일제당', 5, 145000, 1.25, 300],
			['ginseng', '홍삼정 에브리타임 30포',    '정관장',     2, 520000, 1.10, 120],
			['kkobuk',  '꼬북칩 초코츄러스 160g',    '오리온',     8, 38000,  1.00, 500],
		];

		foreach ($items as [$key, $storeName, $maker, $base, $price, $boost, $stock]) {
			if (!isset($created[$key])) continue;
			[$productIdx, , $applied, $completeAt] = $created[$key];

			$ripsProductIdx = DB::table('rips_product')->insertGetId([
				'partner_idx'  => $lotte->idx,
				'category_idx' => $categoryIdx,
				'product_idx'  => $productIdx,			// 리치포트 상품 매핑
				'company_name' => $maker,
				'product_name' => '[TEST-TB] '.$storeName,
				'unit_price'   => (int) round($price * 0.65, -2),
				'price_type'   => 'default',
				'stock_qty'    => $stock,
				'lead_days'    => 14,
				'created_at'   => $applied,
				'created_by'   => self::MARK,
			]);

			$rows = [];
			for ($date = $applied->copy()->startOfDay()->addDays(3); $date->lte($now); $date->addDay()) {
				$weekend = $date->isWeekend() ? 1.4 : 1.0;
				$factor  = ($completeAt && $date->gte($completeAt->copy()->startOfDay())) ? $boost : 1.0;
				$qty     = max(0, (int) round($base * $weekend * $factor * mt_rand(60, 140) / 100));
				if ($qty === 0) continue;

				$promotion = mt_rand(1, 100) <= 15 ? 'discount' : 'N';
				$rate      = $promotion === 'discount' ? 10 : null;
				$amount    = (int) round($qty * $price * ($rate ? 0.9 : 1));
				$stock     = max(0, $stock - $qty);
				if ($stock < $base * 5) $stock += $base * 30;	// 입고

				$rows[] = [
					'partner_idx'   => $lotte->idx,
					'product_idx'   => $ripsProductIdx,
					'category_idx'  => $categoryIdx,
					'input_type'    => 'daily',
					'source'        => 'manual',
					'sales_date'    => $date->toDateString(),
					'qty'           => $qty,
					'total_amount'  => $amount,
					'unit_price'    => (int) round($amount / $qty),
					'stock_qty'     => $stock,
					'promotion'     => $promotion,
					'discount_rate' => $rate,
					'display_place' => $promotion === 'discount' ? 'event' : 'basic',
					'created_at'    => $date->copy()->setTime(21, 0),
					'created_by'    => self::MARK,
				];
			}
			DB::table('rips_sales')->insert($rows);
			DB::table('rips_product')->where('idx', $ripsProductIdx)->update(['stock_qty' => $stock]);
		}
	}

	// 가중치 랜덤 [값 => 가중치]
	private function pick(array $weights)
	{
		$r = mt_rand(1, array_sum($weights));
		foreach ($weights as $value => $weight) {
			if (($r -= $weight) <= 0) return $value;
		}
		return array_key_first($weights);
	}

	// TEST-TB 데이터 삭제
	private function clean(): void
	{
		DB::transaction(function () {
			$productIds = DB::table('product')->where('created_by', self::MARK)->pluck('idx');
			$listIds    = DB::table('check_list')->where('created_by', self::MARK)->orWhereIn('product_idx', $productIds)->pluck('idx');
			$sectionIds = DB::table('check_section')->whereIn('list_idx', $listIds)->pluck('idx');
			$itemIds    = DB::table('check_item')->whereIn('section_idx', $sectionIds)->pluck('idx');

			DB::table('check_result')->whereIn('list_idx', $listIds)->delete();
			DB::table('check_execution')->whereIn('list_idx', $listIds)->delete();
			DB::table('check_option')->whereIn('item_idx', $itemIds)->delete();
			DB::table('check_item')->whereIn('idx', $itemIds)->delete();
			DB::table('check_section')->whereIn('idx', $sectionIds)->delete();
			DB::table('check_list')->whereIn('idx', $listIds)->delete();
			DB::table('testbed_inquire')->whereIn('types_idx', $productIds)->delete();
			DB::table('product')->whereIn('idx', $productIds)->delete();

			// 업체 상품 / 판매 데이터 / 카테고리
			$ripsProductIds = DB::table('rips_product')->where('created_by', self::MARK)->pluck('idx');
			DB::table('rips_sales')->whereIn('product_idx', $ripsProductIds)->delete();
			DB::table('rips_product')->whereIn('idx', $ripsProductIds)->delete();
			DB::table('rips_category')->where('name', self::CATEGORY)->delete();
		});
	}
}
