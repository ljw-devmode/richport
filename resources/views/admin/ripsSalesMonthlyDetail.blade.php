@php
	$header_skip = false;
	$depth1_num = '7';
	$depth2_num = '5';
	$ym = $month->format('Y-m');
@endphp
@include('admin.header._header')
<div class="mt-6 px-5">
	<div class="flex items-center justify-between gap-3">
		<div>
			<h3 class="intro-y text-xl font-bold">{{ $product->product_name }}</h3>
			<p class="mt-1 text-slate-500">{{ $month->format('Y년 n월') }} 일자 · 업체별 판매 · {{ $formPaths[$product->forms_idx] ?? '-' }}</p>
		</div>
		<a href="/admin/ripsSales?{{ http_build_query(array_filter(['ym' => $ym, 'partner_idx' => request('partner_idx')])) }}" class="btn btn-outline-secondary shrink-0">목록으로</a>
	</div>

	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/ripsSales/monthly/{{ $product->idx }}">
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
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	@include('admin.ripsSalesCommon', ['tab' => null])

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between gap-3 border-b border-slate-200 py-3 px-6 flex-wrap">
			<div class="text-base text-primary">총 {{ number_format($data->total()) }}건</div>
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
						<th>년</th>
						<th>월</th>
						<th>일</th>
						<th>업체명</th>
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
					@php $day = \Carbon\Carbon::parse($row->sales_date); @endphp
					<tr>
						<td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
						<td>{{ $day->year }}</td>
						<td>{{ $day->month }}</td>
						<td>{{ $day->day }}</td>
						<td class="text-left">{{ $row->partner_name }}@if ($row->store_name) <span class="rs-muted">· {{ $row->store_name }}</span>@endif</td>
						<td class="rs-num">{{ number_format($row->qty) }}</td>
						<td class="rs-num">{{ number_format($row->amount) }}</td>
						<td class="rs-num">{{ $row->stock_qty !== null ? number_format($row->stock_qty) : '' }}</td>
						<td>{{ $row->promotion_yn }}</td>
						<td>@if ($row->promotion_text)<span class="rs-badge promo">{{ $row->promotion_text }}</span>@endif</td>
						<td>{{ $row->display_text }}</td>
						<td>@include('admin.ripsSalesAlert', ['row' => $row])</td>
					</tr>
					@empty
					<tr><td colspan="12" class="rs-muted">판매 데이터가 없습니다.</td></tr>
					@endforelse
				</tbody>
			</table>
			<p class="mt-2 text-xs text-slate-500">· 잔여수량 : 그날 마지막으로 입력된 잔여수량 · 발주필요 : 업체 상품의 현재 재고 기준 (소진 · 즉시 발주 · 소진 예상일 {{ \App\Services\RipsForecast::ALERT_DAYS }}일 이내)</p>
			@include('rips.common._pagination', ['data' => $data])
		</div>
	</div>
</div>
@include('admin.footer._footer')
