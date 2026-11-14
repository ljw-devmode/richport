@php
	$header_skip = false;
	$depth1_num = '7';
	$depth2_num = '5';
@endphp
@include('admin.header._header')
<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">판매 관리</h3>
	<p class="mt-1 text-slate-500">선택한 하루 동안 RIPS 업체가 등록한 판매 데이터를 업체 상품별로 조회합니다.</p>

	@include('admin.ripsSalesCommon', ['tab' => 'daily'])

	<div class="intro-y border border-slate-200 p-6 rounded-lg bg-white rs-tab-panel">
		<form method="GET" action="/admin/ripsSales/daily" id="daily-form">
			<div class="flex items-end gap-3 flex-wrap">
				<div>
					<p class="mb-1">판매일자</p>
					<input type="date" name="date" class="form-control w-40" value="{{ $date->toDateString() }}" required>
				</div>
				<div>
					<p class="mb-1">업체</p>
					<select name="partner_idx" class="form-select w-56" onchange="this.form.category_idx.value=''; this.form.submit();">
						<option value="">전체</option>
						@foreach ($partners as $item)
						<option value="{{ $item->idx }}" {{ request('partner_idx') == $item->idx ? 'selected' : '' }}>{{ $item->partner_name }}{{ $item->store_name ? ' · '.$item->store_name : '' }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">카테고리</p>
					<select name="category_idx" class="form-select w-64" {{ $categoryMap ? '' : 'disabled' }} title="{{ $categoryMap ? '' : '업체를 선택하면 해당 업체의 카테고리를 고를 수 있습니다.' }}">
						<option value="">{{ $categoryMap ? '전체' : '업체 선택 후 선택' }}</option>
						@foreach ($categoryMap as $idx => $path)
						<option value="{{ $idx }}" {{ request('category_idx') == $idx ? 'selected' : '' }}>{{ $path }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">상품명</p>
					<input type="text" name="product_name" class="form-control w-48" placeholder="상품명" value="{{ request('product_name') }}">
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
				<a href="/admin/ripsSales/daily" class="shrink-0 btn btn-outline-secondary">초기화</a>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between gap-3 border-b border-slate-200 py-3 px-6 flex-wrap">
			<div class="text-base text-primary">{{ $date->format('Y-m-d') }} · 총 {{ number_format($data->total()) }}건</div>
			<div class="rs-sum">
				<span>판매 업체 <b>{{ number_format($summary->partners) }}</b>곳</span>
				<span>총 판매수량 <b>{{ number_format($summary->qty) }}</b></span>
				<span>총 판매금액 <b>{{ number_format($summary->amount) }}</b></span>
			</div>
		</div>

		<div class="p-6 overflow-x-auto">
			<table class="table table-sm table-bordered table-hover rs-table">
				<thead class="bg-slate-50 text-center">
					<tr>
						<th>번호</th>
						<th>업체명</th>
						<th>카테고리</th>
						<th>상품명</th>
						<th>총 판매수량</th>
						<th>총 판매금액</th>
						<th>잔여수량</th>
						<th>프로모션</th>
						<th>프로모션 내용</th>
						<th>판매매대</th>
						<th>발주필요</th>
					</tr>
				</thead>
				<tbody class="text-center">
					@forelse ($data as $index => $row)
					<tr>
						<td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
						<td class="text-left">{{ $row->partner_name }}@if ($row->store_name) <span class="rs-muted">· {{ $row->store_name }}</span>@endif</td>
						<td class="text-left wrap">{{ $categoryPaths[$row->partner_idx][$row->category_idx] ?? '-' }}</td>
						<td class="text-left wrap">{{ $row->product_name }}@if ($row->company_name) <div class="rs-muted text-xs">{{ $row->company_name }}</div>@endif</td>
						<td class="rs-num">{{ number_format($row->qty) }}</td>
						<td class="rs-num">{{ number_format($row->amount) }}</td>
						<td class="rs-num">{{ $row->stock_qty !== null ? number_format($row->stock_qty) : '' }}</td>
						<td>{{ $row->promotion_yn }}</td>
						<td>@if ($row->promotion_text)<span class="rs-badge promo">{{ $row->promotion_text }}</span>@endif</td>
						<td>{{ $row->display_text }}</td>
						<td>@include('admin.ripsSalesAlert', ['row' => $row])</td>
					</tr>
					@empty
					<tr><td colspan="11" class="rs-muted">{{ $date->format('Y-m-d') }} 판매 데이터가 없습니다.</td></tr>
					@endforelse
				</tbody>
			</table>
			<p class="mt-2 text-xs text-slate-500">· 잔여수량 : 그날 마지막으로 입력된 잔여수량 · 발주필요 : 업체 상품의 현재 재고 기준 (소진 · 즉시 발주 · 소진 예상일 {{ \App\Services\RipsForecast::ALERT_DAYS }}일 이내)</p>
			@include('rips.common._pagination', ['data' => $data])
		</div>
	</div>
</div>
@include('admin.footer._footer')
