{{--
	업체 발주 목록 (RIPS > 발주관리, 관리자 > RIPS 업체 상세 > 발주 현황 공통)
	$data : RipsOrder::listFor paginator, $categoryMap : 업체 카테고리 경로
	$detailRid : 값이 있으면 [상세] 버튼 표시 (RIPS 업체 아이디), 관리자는 null
--}}
@php
	$detailRid = $detailRid ?? null;
	$cols      = $detailRid ? 10 : 9;
@endphp
<div class="rp-table-wrap">
	<table class="table table-bordered">
		<thead class="bg-slate-50 text-center">
			<tr>
				<th class="rp-nowrap">발주일</th>
				<th class="rp-nowrap">카테고리</th>
				<th class="rp-nowrap">업체명</th>
				<th class="rp-nowrap">상품명</th>
				<th class="rp-nowrap">수량</th>
				<th class="rp-nowrap">금액</th>
				<th class="rp-nowrap">발주총액</th>
				<th class="rp-nowrap">발주상태</th>
				<th class="rp-nowrap">예상입고일</th>
				@if ($detailRid)<th class="rp-nowrap">발주관리</th>@endif
			</tr>
		</thead>
		<tbody class="text-center font-medium">
			@forelse ($data as $order)
				@php $rows = max(1, $order->items->count()); @endphp
				@foreach ($order->items->isEmpty() ? [null] : $order->items as $i => $item)
					<tr>
						@if ($i === 0)
							<td rowspan="{{ $rows }}" class="rp-nowrap">
								{{ $order->order_date->format('Y-m-d') }}
								<div class="rp-muted text-xs">{{ $order->order_no }}</div>
							</td>
						@endif
						<td class="text-left">{{ $item ? ($categoryMap[$item->category_idx] ?? '-') : '-' }}</td>
						<td>{{ $item->company_name ?? '-' }}</td>
						<td class="text-left">{{ $item->product_name ?? '-' }}</td>
						<td class="rp-num">{{ $item ? number_format($item->qty) : '-' }}</td>
						<td class="rp-num">{{ $item ? number_format($item->amount) : '-' }}</td>
						@if ($i === 0)
							<td rowspan="{{ $rows }}" class="rp-num">{{ number_format($order->total_amount) }}</td>
							<td rowspan="{{ $rows }}"><span class="rp-badge {{ $order->status }}">{{ $order->status_text }}</span></td>
							<td rowspan="{{ $rows }}" class="rp-nowrap">{{ $order->expected_date?->format('Y-m-d') ?? '-' }}</td>
							@if ($detailRid)
								<td rowspan="{{ $rows }}">
									<a href="{{ route('rips.order.detail', ['rid' => $detailRid, 'idx' => $order->idx]) }}" class="btn btn-sm btn-outline-secondary rp-nowrap">상세</a>
								</td>
							@endif
						@endif
					</tr>
				@endforeach
			@empty
				<tr><td colspan="{{ $cols }}" class="rp-muted">발주 내역이 없습니다.</td></tr>
			@endforelse
		</tbody>
	</table>
</div>
@include('rips.common._pagination', ['data' => $data])
