@php 
	$header_skip = false; 
	$depth1_num = '7'; 
	$depth2_num = '2'; 
@endphp 
@include('admin.header._header')
<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">고객문의</h3>

	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/inquireRips">
			<div class="flex items-end gap-3 flex-wrap">
				<div>
					<p class="mb-1">업체구분</p>
					<select id="types_idx" name="types_idx" class="form-select">
						<option value="" {{ request('types_idx') === null || request('types_idx') === '' ? 'selected' : '' }}>전체</option>
						@foreach ($inquireTypes as $item)
							<option value="{{$item->idx}}" {{ request('types_idx') == $item->idx ? 'selected' : '' }}>{{$item->subject}}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">처리상태</p>
					<select id="status" name="status" class="form-select">
						<option value=""  {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>전체</option>
						<option value="1" {{ request('status') === '1' ? 'selected' : '' }}>접수</option>
						<option value="2" {{ request('status') === '2' ? 'selected' : '' }}>답변준비중</option>
						<option value="3" {{ request('status') === '3' ? 'selected' : '' }}>답변완료</option>
					</select>
				</div>
				<div>
					<p class="mb-1">회사명</p>
					<input type="text" id="inquire_company" name="inquire_company" class="form-control" placeholder="회사명 또는 성명 입력" value="{{ request('inquire_company') }}">
				</div>
				<div>
					<p class="mb-1">연락처</p>
					<input type="text" id="inquire_hp" name="inquire_hp" class="form-control" placeholder="예 : 01012345678" value="{{ request('inquire_hp') }}">
				</div>
				<div>
					<p class="mb-1">담당자명</p>
					<input type="text" id="inquire_address" name="inquire_address" class="form-control" placeholder="담당자명 입력" value="{{ request('inquire_address') }}">
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">

		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">총 {{ $data->total() }}개</div>
			@if(auth()->user()->hasPerm('inquireRips.excel'))
			<div class="flex items-center gap-2">
				<button type="button" class="btn border-[#1b6c40] bg-[#1b6c40] text-white px-4" onclick="window.location.href='{{ route('admin.inquireRips.export', request()->query()) }}'">엑셀 다운로드</button>
			</div>
			@endif
		</div>

		<div class="p-6">
			<table class="table table-sm table-bordered table-hover">
				<colgroup>
					<col width="5%">
					<col width="12%">
					<col width="14%">
					<col width="12%">
					<col width="*">
					<col width="9%">
					<col width="9%">
					<col width="9%">
				</colgroup>
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="whitespace-nowrap">번호</th>
						<th class="whitespace-nowrap">업체구분</th>
						<th class="whitespace-nowrap">회사명</th>
						<th class="whitespace-nowrap">연락처</th>
						<th class="whitespace-nowrap">담당자명</th>
						<th class="whitespace-nowrap">등록일</th>
						<th class="whitespace-nowrap">처리상태</th>
						<th class="whitespace-nowrap">처리완료일</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $index => $item)
					@php
				        $number = ($data->currentPage() - 1) * $data->perPage() + ($index + 1);
				    @endphp
						<tr 
						@if(auth()->user()->hasPerm('inquireRips.save')) 
							onclick="setModifyForm('{{ $item->idx }}')"
							style="cursor: pointer;" 
							class="hover:bg-slate-100"
						@else 
							style="cursor: default;" 
						@endif
						>
							<td>{{ $number }}</td>
							<td>{{ optional($item->types)->subject ?? '-' }}</td>
							<td>{{ $item->inquire_company }}</td>
							<td>{{ \App\Support\Privacy::phone($item->inquire_hp) }}</td>
							<td>{{ \App\Support\Privacy::name($item->inquire_address) }}</td>
							<td>{{ $item->inquire_date }}</td>
							<td>{{ $item->status_kr }}</td>
							<td>{{ $item->complete_at }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="8" class="text-center">등록된 콘텐츠가 없습니다.</td>
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

	<!-- 문의상세 모달 -->
	<div id="inquiry_detail-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">문의 상세</h2>
					<button onclick="modalClose('inquiry_detail-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<h3 class="mb-1 text-lg font-semibold">문의내용</h3>
					<div class="flex items-center gap-10">
						<input type="hidden" id="modify_idx" name="modify_idx">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">등록일시</p>
							<input type="text" id="modify_inquire_date" name="modify_inquire_date" class="form-control" disabled>
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">업체구분</p>
							<input type="text" id="modify_types_subject" name="modify_types_subject" class="form-control" disabled>
						</div>
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">회사명</p>
							<input type="text" id="modify_inquire_company" name="modify_inquire_company" class="form-control" disabled>
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">연락처</p>
							<input type="text" id="modify_inquire_hp" name="modify_inquire_hp" class="form-control" disabled>
						</div>
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">담당자명</p>
						<input type="text" id="modify_inquire_address" name="modify_inquire_address" class="form-control" disabled>
					</div>
					<div class="flex items-start gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium pt-2">문의 내용</p>
						<textarea id="modify_inquire_contents" name="modify_inquire_contents" class="form-control h-32" disabled>지붕 임대 시 계약 기간과 임대 수익 구조 문의드립니다.</textarea>
					</div>

					<h3 class="mt-3 mb-2 text-lg font-semibold">처리내용</h3>
					<div class="flex items-center gap-2">
						<div class="flex-1">
							<p class="mb-1 font-medium">확인일시</p>
							<input type="text" id="modify_response_at" name="modify_response_at" class="form-control" placeholder="" value="2025-03-30 10:12:00" disabled>
						</div>
						<div class="flex-1">
							<p class="mb-1 font-medium">처리상태</p>
							<select id="modify_status" name="modify_status" class="form-select">
								<option value="1">접수</option>
								<option value="2">답변준비중</option>
								<option value="3">답변완료</option>
							</select>
						</div>
						<div class="flex-1">
							<p class="mb-1 font-medium">처리완료일</p>
							<input type="text" id="modify_complete_at" name="modify_complete_at" class="form-control" placeholder="답변완료시 업데이트됩니다." disabled>
						</div>
					</div>
					<div class="mt-3">
						<p class="mb-1 font-medium">처리내용</p>
						<textarea id="modify_response_contents" name="modify_response_contents" class="form-control h-32" placeholder="고객에게 응대한 내용, 메모 등을 입력하세요."></textarea>
					</div>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('inquiry_detail-modal')">목록</button>
					@if(auth()->user()->hasPerm('inquireRips.save'))
					<button type="button" class="btn btn-primary w-20" onclick="modify()">저장</button>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- END: Content -->
</div>
@include('admin.footer._footer')
<script>
function setModifyForm(idx) {
    $.ajax({
    	url: '/admin/inquireRips/detailForm/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
        	if (res.status !== 'success') {
        		showToast('오류가 발생하였습니다.','error');
                return;
            }

            const d = res.data;

            $('#modify_idx').val(d.idx);
			$('#modify_inquire_date').val(d.inquire_date);
			$('#modify_types_subject').val(d.types.subject);
			$('#modify_inquire_company').val(d.inquire_company);
			$('#modify_inquire_hp').val(d.inquire_hp);
			$('#modify_inquire_address').val(d.inquire_address);
			$('#modify_inquire_contents').val(d.inquire_contents);
			$('#modify_response_at').val(d.response_at);
			$('#modify_status').val(d.status);
			$('#modify_complete_at').val(d.complete_at);
			$('#modify_response_contents').val(d.response_contents);
			
			modalOpen('inquiry_detail-modal');
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}

function modify() {
	const idx					= $('#modify_idx').val();
  	const status				= $('#modify_status').val();
  	const response_contents		= $('#modify_response_contents').val();

  	if (!status)		return showToast('처리상태를 선택해주세요.','error');
	if (!response_contents)	return showToast('처리내용을 입력해주세요.','error');

	const fd = new FormData();
	fd.append('status', status);
	fd.append('response_contents', response_contents);
	
	if (!confirm('문의를 처리하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/inquireRips/modify/'+idx,
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('문의가 처리되었습니다.','success');
                location.reload();
            } else {
            	showToast(res.message || '저장 실패','error');
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}
</script>
