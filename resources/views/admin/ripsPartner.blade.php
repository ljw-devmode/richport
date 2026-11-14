@php
	$header_skip = false;
	$depth1_num = '7';
	$depth2_num = '1';
	$rate = fn ($done, $total) => $total ? round($done / $total * 100) : 0;
	// 발주관리 표시 상태 (발주 / 준비 / 물류 / 통관 / 입고)
	$orderSteps = ['ordered' => '발주', 'preparing' => '준비', 'shipping' => '물류', 'customs' => '통관', 'received' => '입고'];
@endphp
<style>
	.rp-counts {display:inline-flex; gap:4px; white-space:nowrap; }
	.rp-counts a, .rp-counts span {display:inline-block; min-width:52px; padding:2px 6px; border-radius:6px; font-size:12px; background:#f8fafc; color:#94a3b8; }
	.rp-counts .on.soldout, .rp-counts .on.urgent {background:#fee2e2; color:#dc2626; font-weight:600; }
	.rp-counts .on.soon {background:#dbeafe; color:#2563eb; font-weight:600; }
	.rp-counts .on.order {background:#e0f2fe; color:#0369a1; font-weight:600; }
	.rp-counts .on.received {background:#dcfce7; color:#15803d; font-weight:600; }
</style>
@include('admin.header._header')
<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">RIPS 업체</h3>

	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/ripsPartner">
			<div class="flex items-end gap-3 flex-wrap">
				<div>
					<p class="mb-1">RIPS 사용</p>
					<select name="rips_use" class="form-select w-36">
						<option value="">전체</option>
						<option value="1" {{ request('rips_use') === '1' ? 'selected' : '' }}>사용</option>
						<option value="0" {{ request('rips_use') === '0' ? 'selected' : '' }}>미사용</option>
					</select>
				</div>
				<div class="w-2/5">
					<p class="mb-1">업체명 / RIPS 아이디</p>
					<input type="text" name="keyword" class="form-control" placeholder="검색어를 입력해주세요" value="{{ request('keyword') }}">
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">총 {{ $data->total() }}개</div>
			<div class="flex items-center gap-3">
				<p class="text-xs text-slate-500">업체 정보 · 계정은 업체 상세 &gt; 업체 정보 에서 수정합니다.</p>
				@if (auth()->user()->hasPerm('rips.create'))
					<a href="/admin/ripsPartner/create" class="btn btn-primary btn-sm">RIPS 업체 등록</a>
				@endif
			</div>
		</div>

		<div class="p-6">
			<table class="table table-sm table-bordered table-hover">
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="whitespace-nowrap">번호</th>
						<th class="whitespace-nowrap">업체명</th>
						<th class="whitespace-nowrap">구분</th>
						<th class="whitespace-nowrap">RIPS 아이디</th>
						<th class="whitespace-nowrap">사용</th>
						<th class="whitespace-nowrap">카테고리 매핑</th>
						<th class="whitespace-nowrap">상품 매핑</th>
						<th class="whitespace-nowrap">상품 관리 <span class="text-xs font-normal text-slate-500">(재고소진 / 즉시발주 / 발주필요)</span></th>
						<th class="whitespace-nowrap">발주관리 <span class="text-xs font-normal text-slate-500">(발주 / 준비 / 물류 / 통관 / 입고)</span></th>
						<th class="whitespace-nowrap">관리</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $index => $item)
					<tr>
						<td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
						<td class="text-left">
							{{ $item->partner_name }}
							@if($item->store_name)<span class="text-xs text-slate-500">· {{ $item->store_name }}</span>@endif
							<div class="text-xs mt-1 {{ isset($mappedPartners[$item->idx]) ? 'text-primary' : 'text-slate-400' }}">
								파트너 연결 : {{ isset($mappedPartners[$item->idx]) ? ($mappedPartners[$item->idx]->idx === $item->idx ? '자체 파트너' : $mappedPartners[$item->idx]->partner_name) : '없음' }}
							</div>
						</td>
						<td class="whitespace-nowrap">
							{{ $item->branch_type_text }}
							@if($item->branch_type === 'branch')<div class="text-xs text-slate-500">본점 {{ $item->head->partner_name ?? '-' }}</div>@endif
						</td>
						<td>{{ $item->rips_id }}</td>
						<td>{!! $item->rips_use ? '<span class="text-success">사용</span>' : '<span class="text-slate-400">미사용</span>' !!}</td>
						<td class="whitespace-nowrap">
							{{ $item->rips_categories_mapped_count }} / {{ $item->rips_categories_count }}
							<span class="text-xs {{ $item->rips_categories_count && $item->rips_categories_mapped_count < $item->rips_categories_count ? 'text-danger' : 'text-slate-500' }}">({{ $rate($item->rips_categories_mapped_count, $item->rips_categories_count) }}%)</span>
						</td>
						<td class="whitespace-nowrap">
							{{ $item->rips_products_mapped_count }} / {{ $item->rips_products_count }}
							<span class="text-xs {{ $item->rips_products_count && $item->rips_products_mapped_count < $item->rips_products_count ? 'text-danger' : 'text-slate-500' }}">({{ $rate($item->rips_products_mapped_count, $item->rips_products_count) }}%)</span>
						</td>
						<td>
							<div class="rp-counts">
								@foreach (\App\Services\RipsForecast::ALERT_TYPES as $type => $label)
									@php $cnt = $stockCounts[$item->idx][$type] ?? 0; @endphp
									<a href="{{ route('admin.ripsPartner.stock', ['idx' => $item->idx, 'status' => $type]) }}" class="{{ $cnt ? 'on '.$type : '' }}" title="{{ $label }}">{{ $label }} {{ $cnt }}</a>
								@endforeach
							</div>
						</td>
						<td>
							<div class="rp-counts">
								@foreach ($orderSteps as $status => $label)
									@php $cnt = $orderCounts[$item->idx][$status] ?? 0; @endphp
									<a href="{{ route('admin.ripsPartner.orders', ['idx' => $item->idx, 'status' => $status]) }}" class="{{ $cnt ? 'on '.($status === 'received' ? 'received' : 'order') : '' }}" title="{{ \App\Models\RipsOrder::STATUSES[$status] }}">{{ $label }} {{ $cnt }}</a>
								@endforeach
							</div>
						</td>
						<td class="whitespace-nowrap">
							<a href="{{ route('admin.ripsPartner.stock', ['idx' => $item->idx]) }}" class="btn btn-sm btn-primary">업체 상세</a>
							<a href="{{ route('admin.ripsPartner.category', ['idx' => $item->idx]) }}" class="btn btn-sm btn-outline-secondary ml-1">카테고리 매핑</a>
							<a href="{{ route('admin.ripsPartner.products', ['idx' => $item->idx]) }}" class="btn btn-sm btn-outline-primary ml-1">상품 매핑 · 발주금액</a>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="10" class="text-center">RIPS 계정이 등록된 업체가 없습니다.</td>
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
