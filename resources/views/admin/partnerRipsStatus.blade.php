{{--
	RIPS 업체 상세 > 상품 현황 / 판매 현황 / 발주 현황 (RIPS 업체 화면과 같은 목록, 조회만)
	$tab : stock | sales | orders
--}}
@php
	$tabUrl = route('admin.ripsPartner.'.$tab, ['idx' => $partner->idx]);
@endphp
<link rel="stylesheet" href="{{ asset('rips_assets/css/rips.css') }}?v={{ filemtime(public_path('rips_assets/css/rips.css')) }}" />

<div class="intro-y border border-slate-200 p-5 rounded-b-lg rounded-tr-lg bg-white">
	<form method="GET" action="{{ $tabUrl }}" class="rp-filter">
		@if ($tab === 'stock')
			<input type="hidden" name="status" value="{{ request('status') }}">
			<div class="rp-field">
				<p>카테고리</p>
				<select name="category_idx" class="form-select w-64">
					<option value="">전체</option>
					@foreach ($categoryMap as $cIdx => $path)
						<option value="{{ $cIdx }}" {{ request('category_idx') == $cIdx ? 'selected' : '' }}>{{ $path }}</option>
					@endforeach
				</select>
			</div>
			<div class="rp-field">
				<p>검색어</p>
				<input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="상품명 또는 업체명">
			</div>
		@elseif ($tab === 'sales')
			<div class="rp-field">
				<p>판매일자</p>
				<div class="flex items-center gap-2">
					<input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
					<span>~</span>
					<input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
				</div>
			</div>
			<div class="rp-field">
				<p>카테고리</p>
				<select name="category_idx" class="form-select w-56">
					<option value="">전체</option>
					@foreach ($categoryMap as $cIdx => $path)
						<option value="{{ $cIdx }}" {{ request('category_idx') == $cIdx ? 'selected' : '' }}>{{ $path }}</option>
					@endforeach
				</select>
			</div>
			<div class="rp-field">
				<p>구분</p>
				<select name="input_type" class="form-select">
					<option value="">전체</option>
					@foreach (\App\Models\RipsSales::INPUT_TYPES as $key => $label)
						<option value="{{ $key }}" {{ request('input_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
					@endforeach
				</select>
			</div>
			<div class="rp-field">
				<p>등록경로</p>
				<select name="source" class="form-select">
					<option value="">전체</option>
					@foreach (\App\Models\RipsSales::SOURCES as $key => $label)
						<option value="{{ $key }}" {{ request('source') === $key ? 'selected' : '' }}>{{ $label }}</option>
					@endforeach
				</select>
			</div>
			<div class="rp-field">
				<p>상품명</p>
				<input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="상품명">
			</div>
		@else
			<div class="rp-field">
				<p>발주일</p>
				<div class="flex items-center gap-2">
					<input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
					<span>~</span>
					<input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
				</div>
			</div>
			<div class="rp-field">
				<p>발주상태</p>
				<select name="status" class="form-select">
					<option value="">전체</option>
					@foreach (\App\Models\RipsOrder::STATUSES as $key => $label)
						<option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
					@endforeach
				</select>
			</div>
		@endif
		<div class="rp-actions">
			<button type="submit" class="btn btn-primary w-20">검색</button>
			<a href="{{ $tabUrl }}" class="btn btn-outline-secondary w-20">초기화</a>
		</div>
	</form>
</div>

<div class="intro-y border border-slate-200 mt-5 p-5 rounded-lg bg-white">
	@if ($tab === 'stock')
		<div class="rp-box-title">
			<span>상품 목록 <span class="rp-muted text-sm font-normal">총 {{ number_format($data->total()) }}개</span></span>
			<span class="rp-muted text-xs font-normal">잔여소진 예상일 · 추천 발주일 · 추천수량은 AI 예측값이며, AI 값이 없는 상품은 최근 판매 데이터 기준 단순 계산값입니다. (AI 값은 날짜 옆 <span class="rp-ai-tag">AI</span> 표시)</span>
		</div>
		@include('rips.common._status_tabs', ['counts' => $counts, 'baseUrl' => $tabUrl])
		@include('rips.common._product_table', ['data' => $data, 'categoryMap' => $categoryMap, 'orderRid' => null, 'rowClick' => false])
	@elseif ($tab === 'sales')
		<div class="rp-box-title">
			<span>판매 목록 <span class="rp-muted text-sm font-normal">총 {{ number_format($summary->cnt) }}건</span></span>
			<span class="text-sm font-normal">총 판매수량 <b>{{ number_format($summary->qty) }}</b> · 총 판매금액 <b>{{ number_format($summary->amount) }}</b></span>
		</div>
		@include('rips.common._sales_table', ['data' => $data, 'categoryMap' => $categoryMap, 'clickable' => false])
	@else
		<div class="rp-box-title">
			<span>발주 목록 <span class="rp-muted text-sm font-normal">총 {{ number_format($data->total()) }}건</span></span>
		</div>
		@include('rips.common._order_table', ['data' => $data, 'categoryMap' => $categoryMap, 'detailRid' => null])
	@endif
</div>
