@php
	$header_skip = false;
	$depth1_num = '4';
	$depth2_num = '2';
@endphp
@include('admin.header._header')

	<div class="mt-6 px-5">
		<h3 class="intro-y text-xl font-bold">테스트베드 설문조사 결과</h3>

		<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
			<form method="GET" action="/admin/checkResultList">
				<div class="flex items-end gap-3">
					<div>
						<p class="mb-1">상품명</p>
						<input type="text" name="product_name" class="form-control w-72" placeholder="상품명을 입력해주세요" value="{{ request('product_name') }}">
					</div>
					<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
				</div>
			</form>
		</div>

		<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
			<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
				<div class="text-base text-primary">총 {{ $data->total() }}개</div>
			</div>

			<div class="p-6">
				<table class="table table-bordered table-hover">
					<thead class="bg-slate-50 text-center">
						<tr>
							<th class="whitespace-nowrap">상품명</th>
							<th class="whitespace-nowrap">파트너</th>
							<th class="whitespace-nowrap">설문조사 수</th>
							<th class="whitespace-nowrap">응답 수</th>
							<th class="whitespace-nowrap">최근 응답일</th>
						</tr>
					</thead>
					<tbody class="text-center font-medium">
						@forelse($data as $item)
						<tr onclick="location.href='/admin/checkResult/{{ $item->idx }}'" style="cursor: pointer;">
							<td class="text-left">{{ $item->product_name }}</td>
							<td>{{ $item->partners->partner_name ?? '-' }}</td>
							<td>{{ number_format($item->surveys_count) }}개</td>
							<td>{{ number_format($item->response_count) }}건</td>
							<td>{{ $item->last_response_at ? substr($item->last_response_at, 0, 10) : '-' }}</td>
						</tr>
						@empty
						<tr>
							<td colspan="5" class="py-10 text-slate-400 text-center">테스트베드 상품이 없습니다.</td>
						</tr>
						@endforelse
					</tbody>
				</table>
				@php
					$currentPage = $data->currentPage();
					$lastPage = $data->lastPage();

					$blockSize = 10;
					$currentBlock = (int) ceil($currentPage / $blockSize);

					$start = ($currentBlock - 1) * $blockSize + 1;
					$end = min($start + $blockSize - 1, $lastPage);
				@endphp
				<nav class="w-full mt-5">
					<ul class="pagination justify-center">
						{{-- 첫 페이지 --}}
						<li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $data->url(1) }}"> <i data-lucide="chevrons-left" class="w-4 h-4"></i></a>
						</li>
						{{-- 이전 페이지 --}}
						<li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $data->previousPageUrl() }}"> <i data-lucide="chevron-left" class="w-4 h-4"></i></a>
						</li>
						{{-- 페이지 번호 --}}
						@for ($i = $start; $i <= $end; $i++)
							<li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
								<a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
							</li>
						@endfor
						{{-- 다음 페이지 --}}
						<li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $data->nextPageUrl() }}"> <i data-lucide="chevron-right" class="w-4 h-4"></i></a>
						</li>
						{{-- 마지막 페이지 --}}
						<li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $data->url($lastPage) }}"> <i data-lucide="chevrons-right" class="w-4 h-4"></i></a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>

<!-- END: Content -->
</div>
</div>
@include('admin.footer._footer')
