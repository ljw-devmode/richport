@use('App\Models\RipsOrder')
@php
	$menu = 'order';
@endphp
@include('rips.header._header')
<div class="rp-page">
	<div class="rp-page-head">
		<div>
			<h3>발주관리</h3>
			<p class="rp-desc">발주 내역과 진행 상태를 확인합니다. 발주접수 상태에서는 수정·취소할 수 있습니다.</p>
		</div>
		<div class="rp-actions">
			<a href="{{ route('rips.order.create', ['rid' => $partner->rips_id]) }}" class="btn btn-dark">발주하기</a>
		</div>
	</div>

	<div class="rp-box">
		<form method="GET" action="{{ route('rips.order', ['rid' => $partner->rips_id]) }}" class="rp-filter">
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
					@foreach (RipsOrder::STATUSES as $key => $label)
						<option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
					@endforeach
				</select>
			</div>
			<div class="rp-actions">
				<button type="submit" class="btn btn-primary w-20">검색</button>
				<a href="{{ route('rips.order', ['rid' => $partner->rips_id]) }}" class="btn btn-outline-secondary w-20">초기화</a>
			</div>
		</form>
	</div>

	<div class="rp-box">
		<div class="rp-box-title">
			<span>발주 목록 <span class="rp-muted text-sm font-normal">총 {{ number_format($data->total()) }}건</span></span>
		</div>
		@include('rips.common._order_table', ['data' => $data, 'categoryMap' => $categoryMap, 'detailRid' => $partner->rips_id])
	</div>
</div>
@include('rips.footer._footer')
