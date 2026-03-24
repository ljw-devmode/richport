@php 
	$header_skip = false; 
	$depth1_num = '3'; 
	$depth2_num = '31'; 
@endphp 
@include('admin.header._header')

	<div class="mt-6 px-5">
		<h3 class="intro-y text-xl font-bold">SPC 리포트</h3>
		<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
			<form method="GET" action="/admin/company">
				<div class="flex items-end gap-3">
					<div>
						<p class="mb-1">년도</p>
						<select id="regions_idx" name="regions_idx" class="form-select w-36">
							<option value="2026">2026</option>
							<option value="2025">2025</option>
						</select>
					</div>
					<div>
						<p class="mb-1">월</p>
						<select id="regions_idx" name="regions_idx" class="form-select w-36">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
						</select>
					</div>				
					<div>
						<p class="mb-1">SPC명</p>
						<input type="text" id="spc_name" name="spc_name" class="form-control" placeholder="SPC명을 입력해주세요" value="{{ request('spc_name') }}" >
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
					<colgroup>
						<col width="*">
						<col width="*">
						<col width="*">
						<col width="*">
						<col width="*">
						<col width="*">
						<col width="*">
						<col width="*">
					</colgroup>
					<thead class="bg-slate-50 text-center">
						<tr>
							<th class="whitespace-nowrap">번호</th>
							<th class="whitespace-nowrap">년도</th>
							<th class="whitespace-nowrap">월</th>
							<th class="whitespace-nowrap">SPC명</th>
							<th class="whitespace-nowrap">발전소 수</th>
							<th class="whitespace-nowrap">총 발전량</th>
							<th class="whitespace-nowrap">총 매출</th>
							@if(auth()->user()->hasPerm('spc_report.detail'))
							<th class="whitespace-nowrap">리포트보기</th>
							@endif
						</tr>
					</thead>
					<tbody class="text-center font-medium">
						@forelse ($data as $index => $item)
						@php
							$number = ($data->currentPage() - 1) * $data->perPage() + ($index + 1);
						@endphp
							<tr>
								<td>{{ $index + 1 }}</td>
								<td>{{ $item->year }}</td>
								<td>{{ $item->month }}</td>
								<td>{{ $item->group_name }}</td>
								<td>{{ number_format($item->comp_count) }}</td>
								<td>{{ number_format($item->total_kwh) }}</td>
								<td>{{ number_format($item->total_sales) }}</td>
								@if(auth()->user()->hasPerm('spc_report.detail'))
								<td>
									<button type="button" id="btn-report-view" name="btn-report-view"
										class="btn btn-sm btn-outline-primary"
										data-idx="{{ $index + 1 }}"
										data-year="{{ $item->year }}"
										data-month="{{ $item->month }}"
										data-group_idx="{{ $item->group_idx }}"
										data-comp_count="{{ $item->comp_count }}"
										data-total_kwh="{{ $item->total_kwh }}"
										data-total_sale="{{ $item->total_sales }}"
										data-group_name="{{ $item->group_name }}" 
									">리포트 보기</button>
								</td>
								@endif
							</tr>
						@empty
						<tr>
							<td colspan="9" class="text-center">등록된 발전소가 없습니다.</td>
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



</div>
<!-- END: Content -->
</div>
@include('admin.footer._footer')

<script>
document.addEventListener("DOMContentLoaded", function () {
    const select = document.getElementById("regions_idx");

    const now = new Date();
    let lastMonth = now.getMonth(); 
    
	if (lastMonth === 0) {
        lastMonth = 12; // 1월이면 지난달은 12월
    }

    select.value = lastMonth;
});


// $(document).on('click', '#btn-report-view', function () {
// 	const year = $(this).data('year') || '';
// 	const month = $(this).data('month') || '';
// 	const group_idx = $(this).data('group_idx') || '';

//     const url = `/admin/report/spc/monthly?year=${year}&month=${month}&spc_idx=${group_idx}`;

// 	window.open(url, '_blank');
// });

$(document).on('click', '#btn-report-view', function () {
    const year = $(this).data('year') || '';
    const month = $(this).data('month') || '';
    const group_idx = $(this).data('group_idx') || '';


	// 1. 토큰 값 가져오기
    const token = $('meta[name="csrf-token"]').attr('content');
    
    if (!token) {
    	showToast('CSRF 토큰을 찾을 수 없습니다. HTML 헤더를 확인하세요.','error');    
        return;
    }
	
    // 2. 임시 폼 생성
    const $form = $('<form>', {
        action: '/admin/report/spc/monthly',
        method: 'POST',
        target: '_blank' // 새 창으로 열기
    });

    // 3. 파라미터 추가
	// 핵심: name은 반드시 '_token' 이어야 합니다.
	$form.append($('<input>', { type: 'hidden', name: '_token', value: $('meta[name="csrf-token"]').attr('content') }));
    $form.append($('<input>', { type: 'hidden', name: 'year', value: year }));
    $form.append($('<input>', { type: 'hidden', name: 'month', value: month }));
    $form.append($('<input>', { type: 'hidden', name: 'group_idx', value: group_idx }));

    // 4. 폼을 문서에 붙이고 전송 후 제거
    $form.appendTo('body').submit().remove();
});
</script>