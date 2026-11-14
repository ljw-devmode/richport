@php
	use App\Models\RipsOrder;
	$header_skip = false;
	$depth1_num = '7';
	$depth2_num = '3';
	$fmtRange = function ($start, $end) {
		if (!$start && !$end) return '<span class="text-slate-300">-</span>';
		return ($start ? $start->format('m-d') : '?') . ' ~ ' . ($end ? $end->format('m-d') : '<span class="text-primary">진행중</span>');
	};
	$statusQuery = fn ($status) => '/admin/ripsOrder?' . http_build_query(array_merge(request()->except(['status', 'page']), $status ? ['status' => $status] : []));
@endphp
@include('admin.header._header')
<style>
	.ro-badge {display:inline-block; padding:2px 9px; border-radius:999px; font-size:12px; font-weight:600; white-space:nowrap; background:#f1f5f9; color:#475569; }
	.ro-badge.ordered   {background:#e0f2fe; color:#0369a1; }
	.ro-badge.preparing {background:#fef3c7; color:#b45309; }
	.ro-badge.shipping  {background:#ede9fe; color:#6d28d9; }
	.ro-badge.customs   {background:#fce7f3; color:#be185d; }
	.ro-badge.received  {background:#dcfce7; color:#15803d; }
	.ro-badge.canceled  {background:#f1f5f9; color:#94a3b8; text-decoration:line-through; }
	.ro-tabs {display:flex; gap:6px; flex-wrap:wrap; }
	.ro-tabs a {padding:5px 12px; border:1px solid #e2e8f0; border-radius:999px; font-size:13px; color:#475569; background:#fff; }
	.ro-tabs a.active {border-color:rgb(var(--color-primary)); color:rgb(var(--color-primary)); font-weight:600; }
	.ro-tabs a b {margin-left:4px; }
	.ro-table td, .ro-table th {white-space:nowrap; }
	.ro-table td.wrap {white-space:normal; min-width:160px; }
	.ro-planned {color:#94a3b8; }
</style>
<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">발주 관리</h3>

	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/ripsOrder">
			<input type="hidden" name="status" value="{{ request('status') }}">
			<div class="flex items-end gap-3 flex-wrap">
				<div>
					<p class="mb-1">발주 업체</p>
					<select name="partner_idx" class="form-select w-48">
						<option value="">전체</option>
						@foreach ($ripsPartners as $item)
						<option value="{{ $item->idx }}" {{ request('partner_idx') == $item->idx ? 'selected' : '' }}>{{ $item->partner_name }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">발주일</p>
					<div class="flex items-center gap-1">
						<input type="date" name="order_from" class="form-control w-36" value="{{ request('order_from') }}">
						<span>~</span>
						<input type="date" name="order_to" class="form-control w-36" value="{{ request('order_to') }}">
					</div>
				</div>
				<div>
					<p class="mb-1">입고일</p>
					<div class="flex items-center gap-1">
						<input type="date" name="recv_from" class="form-control w-36" value="{{ request('recv_from') }}">
						<span>~</span>
						<input type="date" name="recv_to" class="form-control w-36" value="{{ request('recv_to') }}">
					</div>
				</div>
				<div>
					<p class="mb-1">리치포트 발주 업체</p>
					<select name="supplier_idx" class="form-select w-44">
						<option value="">전체</option>
						@foreach ($suppliers as $item)
						<option value="{{ $item->idx }}" {{ request('supplier_idx') == $item->idx ? 'selected' : '' }}>{{ $item->partner_name }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">발주번호 / 상품명</p>
					<input type="text" name="keyword" class="form-control w-48" placeholder="검색어" value="{{ request('keyword') }}">
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
				<a href="/admin/ripsOrder" class="shrink-0 btn btn-outline-secondary">초기화</a>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between gap-3 border-b border-slate-200 py-3 px-6 flex-wrap">
			<div class="ro-tabs">
				<a href="{{ $statusQuery(null) }}" class="{{ request('status') ? '' : 'active' }}">전체<b>{{ $statusCounts->sum() }}</b></a>
				@foreach (RipsOrder::STATUSES as $key => $label)
				<a href="{{ $statusQuery($key) }}" class="{{ request('status') === $key ? 'active' : '' }}">{{ $label }}<b>{{ $statusCounts[$key] ?? 0 }}</b></a>
				@endforeach
			</div>
			<div class="text-base text-primary">총 {{ $data->total() }}건</div>
		</div>

		<div class="p-6 overflow-x-auto">
			<table class="table table-sm table-bordered table-hover ro-table">
				<thead class="bg-slate-50 text-center">
					<tr>
						<th rowspan="2">번호</th>
						<th rowspan="2">발주번호</th>
						<th rowspan="2">발주일</th>
						<th rowspan="2">발주 업체</th>
						<th rowspan="2">상품</th>
						<th rowspan="2">수량</th>
						<th rowspan="2">발주금액<div class="text-xs font-normal text-slate-500">업체 → 리치포트</div></th>
						<th rowspan="2">실제 발주금액<div class="text-xs font-normal text-slate-500">리치포트 → 공급업체</div></th>
						<th rowspan="2">리치포트 발주 업체</th>
						<th rowspan="2">상태</th>
						<th colspan="3">일정</th>
					</tr>
					<tr>
						<th>물류</th>
						<th>통관</th>
						<th>입고일</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $index => $order)
					<tr onclick="location.href='{{ route('admin.ripsOrder.detail', ['idx' => $order->idx]) }}'" style="cursor:pointer;">
						<td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
						<td>{{ $order->order_no }}</td>
						<td>{{ $order->order_date?->format('Y-m-d') }}</td>
						<td class="text-left">{{ $order->partner->partner_name ?? '-' }}@if($order->partner?->store_name)<div class="text-xs text-slate-500">{{ $order->partner->store_name }}</div>@endif</td>
						<td class="text-left wrap">{{ $order->product_summary }}</td>
						<td class="text-right">{{ number_format($order->total_qty) }}</td>
						<td class="text-right">{{ number_format($order->total_amount) }}</td>
						<td class="text-right">
							@if ($order->purchase_total !== null)
								{{ number_format($order->purchase_total) }}
								@if ($order->purchase_entered < $order->items->count())<div class="text-xs text-danger">{{ $order->purchase_entered }}/{{ $order->items->count() }} 입력</div>@endif
							@else
								<span class="text-slate-300">미입력</span>
							@endif
						</td>
						<td class="text-left wrap">
							@forelse ($order->suppliers as $s)
								<div class="{{ $s['planned'] ? 'ro-planned' : '' }}" title="{{ $s['planned'] ? '발주 정보 입력 전 (매핑 상품 기준 예정)' : '' }}">{{ $s['name'] }}{{ $s['planned'] ? ' (예정)' : '' }}</div>
							@empty
								<span class="text-slate-300">-</span>
							@endforelse
						</td>
						<td><span class="ro-badge {{ $order->status }}">{{ $order->status_text }}</span></td>
						<td>{!! $fmtRange($order->logistics_start, $order->logistics_end) !!}</td>
						<td>{!! $fmtRange($order->customs_start, $order->customs_end) !!}</td>
						<td>{!! $order->received_date ? $order->received_date->format('Y-m-d') : '<span class="text-slate-300">-</span>' !!}</td>
					</tr>
					@empty
					<tr>
						<td colspan="13" class="text-center">조회된 발주가 없습니다.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
			@php
				$currentPage = $data->currentPage();
				$lastPage = $data->lastPage();
				$blockSize = 10;
				$start = ((int) ceil($currentPage / $blockSize) - 1) * $blockSize + 1;
				$end = min($start + $blockSize - 1, $lastPage);
			@endphp
			<nav class="w-full mt-5">
				<ul class="pagination justify-center">
					<li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
						<a class="page-link" href="{{ $data->url(1) }}"> <i data-lucide="chevrons-left" class="w-4 h-4"></i></a>
					</li>
					<li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
						<a class="page-link" href="{{ $data->previousPageUrl() }}"> <i data-lucide="chevron-left" class="w-4 h-4"></i></a>
					</li>
					@for ($i = $start; $i <= $end; $i++)
					<li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
						<a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
					</li>
					@endfor
					<li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
						<a class="page-link" href="{{ $data->nextPageUrl() }}"> <i data-lucide="chevron-right" class="w-4 h-4"></i></a>
					</li>
					<li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
						<a class="page-link" href="{{ $data->url($lastPage) }}"> <i data-lucide="chevrons-right" class="w-4 h-4"></i></a>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</div>
@include('admin.footer._footer')
