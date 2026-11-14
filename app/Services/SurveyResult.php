<?php

namespace App\Services;

use App\Models\Cities;
use App\Models\CheckExecution;
use App\Models\CheckList;
use App\Models\CheckResult;
use App\Models\Districts;
use App\Models\Product;

/**
 * 테스트베드 설문조사 결과 집계 (관리자 > 설문조사 결과, RIPS > 리치포트 상품 > 설문조사 결과 공통)
 */
class SurveyResult
{
	/**
	 * 상품의 설문별 요약
	 * @return array [surveys, survey(선택한 설문), total, demographics, questions]
	 */
	public static function summary(Product $product, $listIdx = null): array
	{
		$surveys = CheckList::where('product_idx', $product->idx)
			->withCount(['checkexecution' => fn ($q) => $q->where('use_yn', 'Y')])
			->orderByDesc('idx')->get();

		// 선택한 설문, 없으면 응답이 있는 최근 설문
		$survey = $surveys->firstWhere('idx', (int) $listIdx)
			?? $surveys->firstWhere('checkexecution_count', '>', 0)
			?? $surveys->first();

		$questions = $demographics = [];
		$total = 0;

		if ($survey) {
			$survey->load('checksection.checkitem.checkoption');
			$total        = (int) $survey->checkexecution_count;
			$demographics = self::demographics($survey->idx);
			$questions    = self::questions($survey);
		}

		return compact('surveys', 'survey', 'total', 'demographics', 'questions');
	}

	// 응답자 정보 분포 (연령대는 전체 구간, 나머지는 응답 많은 순)
	public static function demographics($listIdx): array
	{
		$responses = fn () => CheckExecution::where('list_idx', $listIdx)->where('use_yn', 'Y');
		$counts    = fn ($column) => $responses()->whereNotNull($column)->selectRaw("{$column} k, COUNT(*) c")->groupBy($column)->pluck('c', 'k');
		$toRows    = fn ($labels, $counts) => collect($labels)->map(fn ($label, $key) => ['label' => $label, 'count' => (int) ($counts[$key] ?? 0)])->values()->all();
		$sorted    = fn ($rows) => collect($rows)->sortByDesc('count')->values()->all();

		$cityCounts     = $counts('cities_idx');
		$districtCounts = $counts('districts_idx');
		$cityNames      = Cities::whereIn('idx', $cityCounts->keys())->pluck('name_kr', 'idx');
		$districts      = Districts::with('cities:idx,name_kr')->whereIn('idx', $districtCounts->keys())->get()
			->mapWithKeys(fn ($d) => [$d->idx => trim(($d->cities->name_kr ?? '') . ' ' . $d->name_kr)]);

		return [
			'age'      => $toRows(CheckExecution::AGE_GROUPS, $counts('age_group')),
			'gender'   => $toRows(CheckExecution::GENDERS, $counts('gender')),
			'city'     => $sorted($toRows($cityNames, $cityCounts)),
			'district' => $sorted($toRows($districts, $districtCounts)),
		];
	}

	// 문항별 집계 (선택 건수, 주관식·비고 답변)
	public static function questions($survey): array
	{
		$results = fn () => CheckResult::where('list_idx', $survey->idx)
			->whereIn('exec_idx', CheckExecution::where('list_idx', $survey->idx)->where('use_yn', 'Y')->select('idx'));

		$answered = $results()->selectRaw('item_idx, COUNT(DISTINCT exec_idx) c')->groupBy('item_idx')->pluck('c', 'item_idx');
		$counts   = $results()->whereNotNull('option_idx')->selectRaw('option_idx, COUNT(*) c')->groupBy('option_idx')->pluck('c', 'option_idx');
		$texts    = $results()->whereNotNull('result_value')->orderByDesc('idx')->get(['item_idx', 'result_value'])->groupBy('item_idx');

		return $survey->checksection->map(fn ($section) => [
			'name'  => $section->section_name,
			'items' => $section->checkitem->map(fn ($item) => [
				'idx'      => $item->idx,
				'path'     => collect([$section->layout_type >= 2 ? $item->d2_name : null, $section->layout_type == 3 ? $item->d3_name : null])->filter()->implode(' > '),
				'name'     => $item->item_name,
				'type'     => $item->input_type,
				'has_text' => $item->has_text === 'Y',
				'answered' => (int) ($answered[$item->idx] ?? 0),
				'options'  => $item->checkoption->map(fn ($option) => ['idx' => $option->idx, 'label' => $option->option_name, 'count' => (int) ($counts[$option->idx] ?? 0)])->values()->all(),
				'texts'    => ($texts[$item->idx] ?? collect())->pluck('result_value')->all(),
			])->values()->all(),
		])->values()->all();
	}

	/**
	 * 개별 설문 (no : 응답 순번, 1 부터)
	 * @return array JSON 응답 데이터
	 */
	public static function response($listIdx, $no): array
	{
		$responses = CheckExecution::where('list_idx', $listIdx)->where('use_yn', 'Y');
		$total     = (clone $responses)->count();
		if ($total === 0) return ['status' => 'error', 'message' => '응답이 없습니다.'];

		$no = max(1, min($total, (int) $no));
		$response = (clone $responses)->with(['checkresult', 'cities:idx,name_kr', 'districts:idx,name_kr'])->orderBy('idx')->skip($no - 1)->first();

		$answers = $response->checkresult->groupBy('item_idx')->map(fn ($rows) => [
			'options' => $rows->whereNotNull('option_idx')->map(fn ($row) => (int) $row->option_idx)->values(),
			'text'    => $rows->whereNotNull('result_value')->first()?->result_value,
		]);

		return [
			'status'   => 'success',
			'no'       => $no,
			'total'    => $total,
			'response' => [
				'created_at' => $response->created_at?->format('Y-m-d H:i'),
				'age'        => CheckExecution::AGE_GROUPS[$response->age_group] ?? '-',
				'gender'     => CheckExecution::GENDERS[$response->gender] ?? '-',
				'city'       => $response->cities->name_kr ?? '-',
				'district'   => $response->districts->name_kr ?? '-',
				'answers'    => $answers,
			],
		];
	}
}
