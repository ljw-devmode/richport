@php
	$menu = 'survey';
@endphp
@include('rips.header._header')
<div class="rp-page">
	<div class="rp-page-head">
		<div>
			<h3>{{ $product->product_name }}</h3>
			<p class="rp-desc">테스트베드 설문조사 결과 · {{ $product->partners->partner_name ?? '-' }}</p>
		</div>
		<div class="rp-actions">
			<a href="{{ route('rips.survey', ['rid' => $partner->rips_id]) }}" class="btn btn-outline-secondary">목록으로</a>
		</div>
	</div>

	@php
		$maxWeek = max(1, collect($sales['weeks'])->max('qty'));
		$stat    = fn ($row) => $row ? (int) $row->qty : 0;
	@endphp
	<div class="rp-box">
		<div class="rp-box-title">
			<span>매장 판매 현황 <span class="rp-muted text-xs font-normal">{{ $partner->partner_name }} 판매 데이터 기준</span></span>
			@if ($sales['mapped']->isNotEmpty())
				<span class="rp-muted text-xs font-normal">연결된 업체 상품 : {{ $sales['mapped']->pluck('product_name')->implode(', ') }}</span>
			@endif
		</div>

		@if ($sales['mapped']->isEmpty())
			<div class="rp-ai-empty">
				<i data-lucide="link-2-off" class="w-6 h-6"></i>
				<p>이 상품과 연결된 업체 상품이 없어 판매 수량을 집계할 수 없습니다.<br>리치포트 담당자에게 상품 매핑을 요청해주세요.</p>
			</div>
		@else
			<div class="rp-stats" style="margin-top:0;">
				<div class="rp-stat">
					<p class="label">누적 판매수량</p>
					<p class="value">{{ number_format($stat($sales['total'])) }}<small>개</small></p>
					<p class="rp-help">{{ $sales['total']->first_date ? $sales['total']->first_date.' ~ '.$sales['total']->last_date : '판매 데이터 없음' }}</p>
				</div>
				<div class="rp-stat">
					<p class="label">누적 판매금액</p>
					<p class="value">{{ number_format($sales['total']->amount ?? 0) }}</p>
					<p class="rp-help">판매일수 {{ number_format($sales['total']->days ?? 0) }}일</p>
				</div>
				<div class="rp-stat">
					<p class="label">테스트베드 완료 후 판매수량</p>
					<p class="value">{{ $sales['after'] ? number_format($stat($sales['after'])) : '-' }}<small>{{ $sales['after'] ? '개' : '' }}</small></p>
					<p class="rp-help">{{ $sales['completeAt'] ? '완료일 '.$sales['completeAt']->format('Y-m-d').' 이후' : '완료일 없음' }}@if ($sales['after'] && $sales['after']->days > 0) · 일평균 {{ number_format($sales['after']->qty / $sales['after']->days, 1) }}개@endif</p>
				</div>
				<div class="rp-stat">
					<p class="label">최근 30일 판매수량</p>
					<p class="value">{{ number_format($stat($sales['recent'])) }}<small>개</small></p>
					<p class="rp-help">잔여수량 {{ number_format($sales['mapped']->sum('stock_qty')) }}개</p>
				</div>
			</div>

			<p class="mt-5 mb-1 text-sm font-semibold">최근 12주 주별 판매수량 <span class="rp-muted font-normal text-xs">· 진한 막대 : 테스트베드 완료 주</span></p>
			<div class="rp-bars">
				@foreach ($sales['weeks'] as $week)
					<div class="bar" title="{{ $week['from'] }} ~ {{ $week['to'] }} : {{ number_format($week['qty']) }}개{{ $week['testbed'] ? ' (테스트베드 완료)' : '' }}">
						<span style="height: {{ round($week['qty'] / $maxWeek * 100) }}%; {{ $week['testbed'] ? 'background:#1e3a5f;' : '' }}"></span>
						<em>{{ \Carbon\Carbon::parse($week['from'])->format('m/d') }}</em>
					</div>
				@endforeach
			</div>
		@endif
	</div>

	<div class="mt-5">
		@include('survey._result', [
			'surveyUrl'   => route('rips.survey.detail', ['rid' => $partner->rips_id, 'idx' => $product->idx]),
			'responseUrl' => $survey ? route('rips.survey.response', ['rid' => $partner->rips_id, 'idx' => $product->idx, 'listIdx' => $survey->idx]) : '',
		])
	</div>
</div>
@include('rips.footer._footer')
