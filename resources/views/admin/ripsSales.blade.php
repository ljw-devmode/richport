@php
	$header_skip = false;
	$depth1_num = '7';
	$depth2_num = '5';
	$ym = $month->format('Y-m');
@endphp
@include('admin.header._header')
<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">판매 관리</h3>
	<p class="mt-1 text-slate-500">RIPS 업체가 등록한 판매 데이터를 리치포트 상품 기준으로 월별 합계합니다. (업체 상품이 리치포트 상품에 매핑된 경우만 집계)</p>

	@include('admin.ripsSalesCommon', ['tab' => 'monthly'])

	<div class="intro-y border border-slate-200 p-6 rounded-lg bg-white rs-tab-panel">
		<form method="GET" action="/admin/ripsSales">
			<div class="flex items-end gap-3 flex-wrap">
				<div>
					<p class="mb-1">년/월</p>
					<input type="month" name="ym" class="form-control w-40" value="{{ $ym }}">
				</div>
				<div>
					<p class="mb-1">업체</p>
					<select name="partner_idx" class="form-select w-56">
						<option value="">전체</option>
						@foreach ($partners as $item)
						<option value="{{ $item->idx }}" {{ request('partner_idx') == $item->idx ? 'selected' : '' }}>{{ $item->partner_name }}{{ $item->store_name ? ' · '.$item->store_name : '' }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">카테고리</p>
					<select name="forms_idx" class="form-select w-64">
						<option value="">전체</option>
						@foreach ($formPaths as $idx => $path)
						<option value="{{ $idx }}" {{ request('forms_idx') == $idx ? 'selected' : '' }}>{{ $path }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">상품</p>
					<input type="text" name="product_name" class="form-control w-48" placeholder="상품명" value="{{ request('product_name') }}">
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
				<a href="/admin/ripsSales" class="shrink-0 btn btn-outline-secondary">초기화</a>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between gap-3 border-b border-slate-200 py-3 px-6 flex-wrap">
			<div class="text-base text-primary">총 {{ number_format($data->total()) }}개 상품</div>
			<div class="rs-sum">
				<span>{{ $month->format('Y년 n월') }} 총 판매수량 <b>{{ number_format($summary->qty) }}</b></span>
				<span>총 판매금액 <b>{{ number_format($summary->amount) }}</b></span>
			</div>
		</div>

		<div class="p-6 overflow-x-auto">
			<table class="table table-sm table-bordered table-hover rs-table">
				<thead class="bg-slate-50 text-center">
					<tr>
						<th>번호</th>
						<th>년</th>
						<th>월</th>
						<th>카테고리</th>
						<th>상품명</th>
						<th>판매 업체수</th>
						<th>총 판매수량</th>
						<th>총 판매금액</th>
						<th>발주필요</th>
						<th>상세</th>
					</tr>
				</thead>
				<tbody class="text-center">
					@forelse ($data as $index => $item)
					<tr>
						<td>{{ $data->total() - ($data->currentPage() - 1) * $data->perPage() - $index }}</td>
						<td>{{ $month->year }}</td>
						<td>{{ $month->month }}</td>
						<td class="text-left wrap">{{ $formPaths[$item->forms_idx] ?? '-' }}</td>
						<td class="text-left wrap">{{ $item->product_name }}</td>
						<td class="rs-num">{{ number_format($item->partner_count) }}</td>
						<td class="rs-num">{{ number_format($item->qty) }}</td>
						<td class="rs-num">{{ number_format($item->amount) }}</td>
						<td>
							@if (!empty($alertCounts[$item->idx]))
								<span class="rs-badge order">발주필요 {{ $alertCounts[$item->idx] }}곳</span>
							@else
								<span class="rs-muted">-</span>
							@endif
						</td>
						<td>
							<a href="/admin/ripsSales/monthly/{{ $item->idx }}?{{ http_build_query(array_filter(['ym' => $ym, 'partner_idx' => request('partner_idx')])) }}" class="btn btn-sm btn-outline-secondary">자세히</a>
						</td>
					</tr>
					@empty
					<tr><td colspan="10" class="rs-muted">{{ $month->format('Y년 n월') }} 판매 데이터가 없습니다.</td></tr>
					@endforelse
				</tbody>
			</table>
			<p class="mt-2 text-xs text-slate-500">· 발주필요 : 해당 상품을 판매하는 업체 중 재고 소진 · 즉시 발주 · 소진 예상일 {{ \App\Services\RipsForecast::ALERT_DAYS }}일 이내인 업체 수 (오늘 기준 계산)</p>
			@include('rips.common._pagination', ['data' => $data])
		</div>
	</div>
</div>
@include('admin.footer._footer')
