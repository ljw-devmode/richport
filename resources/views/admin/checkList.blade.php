@php 
	$header_skip = false; 
	$depth1_num = '4'; 
	$depth2_num = '1'; 
@endphp 
@include('admin.header._header')

	<div class="mt-6 px-5">
		<h3 class="intro-y text-xl font-bold">테스트베드 설문조사</h3>
		<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
			<form method="GET" action="/admin/checklist">
				<div class="flex items-end gap-3">
					<div>
						<p class="mb-1">테스트베드 상품</p>
						<select id="product_idx" name="product_idx" class="form-select w-56">
							<option value="">전체</option>
							@foreach($products as $product)
								<option value="{{ $product->idx }}" {{ request('product_idx') == $product->idx ? 'selected' : '' }}>{{ $product->product_name }}</option>
							@endforeach
						</select>
					</div>
					<div>
						<p class="mb-1">설문조사명</p>
						<input type="text" id="check_name" name="check_name" class="form-control" placeholder="설문조사명을 입력해주세요" value="{{ request('check_name') }}" >
					</div>				
					<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
				</div>
			</form>
		</div>

		<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
			<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
				<div class="text-base text-primary">총 {{ $data->total() }}개</div>
				@if(auth()->user()->hasPerm('checklist.create'))
				<div class="flex items-center gap-2">
					<a href="/admin/checkregist" class="btn btn-dark px-4">설문조사 등록</a>
				</div>
        		@endif
			</div>

			<div class="p-6">
				<table class="table table-bordered table-hover">
					<thead class="bg-slate-50 text-center">
						<tr>
							<th class="whitespace-nowrap">번호</th>
							<th class="whitespace-nowrap">테스트베드 상품</th>
							<th class="whitespace-nowrap">설문조사명</th>
							<th class="whitespace-nowrap">설문 URL</th>
							<th class="whitespace-nowrap">응답 건수</th>
							<th class="whitespace-nowrap">등록일</th>
							@if(auth()->user()->hasPerm('checklist.update') || auth()->user()->hasPerm('checklist.copy') || auth()->user()->hasPerm('checklist.delete')) 							
							<th class="whitespace-nowrap">관리</th>
							@endif
						</tr>
					</thead>
					<tbody class="text-center font-medium">
						
						@forelse($data as $item)
						<tr>
							<td>{{ $item->idx }}</td>
							<td>{{ $item->product->product_name ?? '-' }}</td>
							<td class="text-left">
								{{ $item->check_name }}
								@if($item->use_yn !== 'Y')<span class="text-xs text-slate-400">(비활성)</span>@endif
							</td>
							<td class="whitespace-nowrap">
								@if($item->use_yn === 'Y' && $item->product && $item->survey_token)
								<button type="button" onclick="copySurveyUrl('{{ route('survey.show', $item->survey_token) }}')" class="btn btn-sm btn-outline-primary">복사</button>
								<a href="{{ route('survey.show', $item->survey_token) }}" target="_blank" class="btn btn-sm btn-outline-secondary">열기</a>
								@else
								<span class="text-slate-400">-</span>
								@endif
							</td>
							<td>{{ number_format($item->checkexecution_count) }}건</td>
							<td>{{ $item->created_at?->format('Y-m-d') }}</td>
							@if(auth()->user()->hasPerm('checklist.view') || auth()->user()->hasPerm('checklist.update') || auth()->user()->hasPerm('checklist.copy') || auth()->user()->hasPerm('checklist.delete')) 
							<td>
								<div class="flex justify-center gap-1">
									@if(auth()->user()->hasPerm('checklist.view') || auth()->user()->hasPerm('checklist.update') || auth()->user()->hasPerm('checklist.update')) 
									<a href="/admin/checkmodify/{{$item->idx}}" class="btn btn-sm btn-outline-secondary">수정</a>
									@endif
									@if(auth()->user()->hasPerm('checklist.copy')) 
									<button type="button" onclick="copyCheck('{{ $item->idx }}')" class="btn btn-sm btn-outline-primary">복사</button>
									@endif
									@if(auth()->user()->hasPerm('checklist.delete')) 
									<button type="button" onclick="deleteCheck('{{ $item->idx }}')" class="btn btn-sm btn-outline-danger">삭제</button>
									@endif
								</div>
							</td>
							@endif
						</tr>
						@empty

							@php
							// 관리 컬럼 권한 여부에 따라 colspan 결정
							$hasManagePerm = auth()->user()->hasPerm('checklist.update') || auth()->user()->hasPerm('checklist.copy') || auth()->user()->hasPerm('checklist.delete');
							$colSpanCount = $hasManagePerm ? 7 : 6;
							// 번호 / 상품 / 설문조사명 / URL / 응답 건수 / 등록일 + 관리
							@endphp						
							<tr><td colspan="{{ $colSpanCount }}" class="p-10 text-gray-500">등록된 설문조사가 없습니다.</td></tr>
							
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

<script>
// 설문 URL 복사 (http 환경에서는 clipboard API 를 쓸 수 없어 textarea 로 처리)
function copySurveyUrl(url) {
    const $temp = $('<textarea>').val(url).css({ position: 'fixed', opacity: 0 }).appendTo('body');
    $temp[0].select();
    const copied = document.execCommand('copy');
    $temp.remove();

    if (copied) return showToast('설문 URL이 복사되었습니다.', 'success');
    prompt('설문 URL을 복사해주세요.', url);
}

// 설문조사 복사
function copyCheck(idx) {
    if (!confirm('해당 설문조사를 복사하시겠습니까?')) return;

    $.ajax({
        url: `/admin/checkcopy/${idx}`,
        type: 'POST',
        data: {},
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            if (res.status === 'success') {
                alert('설문조사가 성공적으로 복사되었습니다.');
                location.reload(); // 목록 새로고침하여 복사본 확인
            } else {
                alert(res.message || '복사 중 오류가 발생했습니다.');
            }
        },
        error: function(xhr) {
            const err = xhr.responseJSON;
            alert(err ? err.message : '서버 통신 중 오류가 발생했습니다.');
        }
    });
}

// 설문조사 삭제
function deleteCheck(idx) {
    if (!confirm('정말 삭제하시겠습니까? 결과 데이터가 있는 경우 비활성화 처리됩니다.')) return;

    $.ajax({
        // 아까 설정한 라우트: Route::post('/checkdelete/{idx}', ...)
        url: `/admin/checkdelete/${idx}`,
        type: 'POST',
        data: {
        },
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },		
        success: function(res) {
            if (res.status === 'success') {
                alert(res.message || '삭제되었습니다.');
                location.reload();
            } else {
                alert(res.message);
            }
        }
    });
}
</script>