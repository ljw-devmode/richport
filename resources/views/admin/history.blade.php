@php 
	$header_skip = false; 
	$depth1_num = '2'; 
	$depth2_num = '1'; 
@endphp 
@include('admin.header._header')

<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">연혁</h3>
	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/history">
			<div class="flex items-end gap-3">
				<div class="w-1/5">
					<p class="mb-1">년도</p>
					<select id="year" name="year" class="form-select">
						<option value="">전체</option>
						@foreach ($year as $item)
							<option value="{{$item}}" {{ request('year') == $item ? 'selected' : '' }}>{{$item}}</option>
						@endforeach
					</select>
				</div>
				<div class="w-4/5">
					<p class="mb-1">제목</p>
					<input type="text" id="subject" name="subject" class="form-control" placeholder="제목을 입력해주세요" value="{{ request('subject') }}" >
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">총 {{ $data->total() }}개</div>
			@if(auth()->user()->hasPerm('history.create') || auth()->user()->hasPerm('history.excel'))
			<div class="flex items-center gap-2">
				@if(auth()->user()->hasPerm('history.create'))
				<a href="javascript:;" class="btn btn-dark px-4" onclick="modalOpen('history_regist-modal')">연혁 등록</a>
				@endif
				@if(auth()->user()->hasPerm('history.excel'))
				<button type="button" class="btn border-[#1b6c40] bg-[#1b6c40] text-white px-4" onclick="window.location.href='{{ route('admin.history.export', request()->query()) }}'">엑셀 다운로드</button>
				@endif
			</div>
			@endif
		</div>

		<div class="p-6">
			<table class="table table-bordered table-hover">
				<colgroup>
					<col width="10%">
					<col width="10%">
					<col width="8%">
					<col width="*">
					<col width="*">
					<col width="*">
					<col width="20%">
				</colgroup>
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="whitespace-nowrap">번호</th>
						<th class="whitespace-nowrap">년도</th>
						<th class="whitespace-nowrap">월</th>
						<th class="whitespace-nowrap">제목(ko)</th>
						<th class="whitespace-nowrap">제목(en)</th>
						<th class="whitespace-nowrap">제목(cn)</th>
						<th class="whitespace-nowrap">등록일</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $index => $item)
					@php
				        $number = ($data->currentPage() - 1) * $data->perPage() + ($index + 1);
				    @endphp
						<tr onclick="setModifyForm('{{ $item->idx }}')">
							<td>{{ $item->idx }}</td>
							<td>{{ $item->year }}</td>
							<td>{{ $item->month }}</td>
							<td>{{ $item->subject }}</td>
							<td>{{ $item->subject_en }}</td>
							<td>{{ $item->subject_cn }}</td>
							<td>{{ $item->created_at }}</td>
						</tr>
					@empty
					<tr>
						<td colspan="5" class="text-center">등록된 콘텐츠가 없습니다.</td>
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

	<!-- 연혁 등록 모달 -->
	<div id="history_regist-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">연혁 등록</h2>
					<button onclick="modalClose('history_regist-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class="flex items-center gap-5">
						<div class="flex-1 ">
							<p class="mb-1 font-medium ">년도 <span class="text-danger">*</span></p>
							<select id="regist_year" name="regist_year" class="form-select">
							    @for ($year = date('Y'); $year >= 2015; $year--)
							        <option value="{{ $year }}">{{ $year }}</option>
							    @endfor
							</select>
						</div>
						<div class="flex-1 ">
							<p class="mb-1 font-medium ">월 <span class="text-danger">*</span></p>
							<select id="regist_month" name="regist_month" class="form-select">
								@for ($m = 1; $m <= 12; $m++)
							        <option value="{{ $m }}">{{ $m }}</option>
							    @endfor
							</select>
						</div>
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">제목(ko) <span class="text-danger">*</span></p>
						<input type="text" id="regist_subject" name="regist_subject" class="form-control" placeholder="제목을 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">제목(en) <span class="text-danger">*</span></p>
						<input type="text" id="regist_subject_en" name="regist_subject_en" class="form-control" placeholder="제목을 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">제목(cn) <span class="text-danger">*</span></p>
						<input type="text" id="regist_subject_cn" name="regist_subject_cn" class="form-control" placeholder="제목을 입력해주세요">
					</div>

				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('history_regist-modal')">취소</button>
					@if(auth()->user()->hasPerm('history.create'))
					<button type="button" class="btn btn-primary w-20" onclick="regist();">등록</button>
					@endif
				</div>
			</div>
		</div>
	</div>

	<!-- 연혁 수정 모달 -->
	<div id="history_modify-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">연혁 수정</h2>
					<button onclick="modalClose('history_modify-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class="flex items-center gap-5">
						<div class="flex-1 ">
							<p class="mb-1 font-medium ">년도 <span class="text-danger">*</span></p>
							<select id="modify_year" name="modify_year" class="form-select">
								@for ($year = date('Y'); $year >= 2015; $year--)
							        <option value="{{ $year }}">{{ $year }}</option>
							    @endfor
							</select>
						</div>
						<div class="flex-1 ">
							<p class="mb-1 font-medium ">월 <span class="text-danger">*</span></p>
							<select id="modify_month" name="modify_month" class="form-select">
								@for ($m = 1; $m <= 12; $m++)
							        <option value="{{ $m }}">{{ $m }}</option>
							    @endfor
							</select>
						</div>
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">제목(ko) <span class="text-danger">*</span></p>
						<input type="text" id="modify_subject" name="modify_subject" class="form-control" placeholder="제목을 입력해주세요" value="">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">제목(en) <span class="text-danger">*</span></p>
						<input type="text" id="modify_subject_en" name="modify_subject_en" class="form-control" placeholder="제목을 입력해주세요" value="">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">제목(cn) <span class="text-danger">*</span></p>
						<input type="text" id="modify_subject_cn" name="modify_subject_cn" class="form-control" placeholder="제목을 입력해주세요" value="">
					</div>

					<input type="hidden" id="modify_idx" name="modify_idx" value="">
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20 " onclick="modalClose('history_modify-modal')">취소</button>
					@if(auth()->user()->hasPerm('history.update'))
					<button type="button" class="btn btn-primary w-20" onclick="modify()">수정</button>
					@endif
					@if(auth()->user()->hasPerm('history.delete'))
					<button type="button" class="btn btn-outline-danger w-20" onclick="deleteHistory()">삭제</button>
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
function regist() {
	const year		= $('#regist_year').val();
  	const month		= $('#regist_month').val();
  	const subject	= $('#regist_subject').val().trim();
	const subject_en	= $('#regist_subject_en').val().trim();
	const subject_cn	= $('#regist_subject_cn').val().trim();

  	if (!year)		return showToast('년도를 선택해주세요.','error');
  	if (!month)		return showToast('월을 선택해주세요.','error');  
	if (!subject)	return showToast('제목(ko)을 입력해주세요.','error');
	if (!subject_en)	return showToast('제목(en)을 입력해주세요.','error');
	if (!subject_cn)	return showToast('제목(cn)을 입력해주세요.','error');
	
	const fd = new FormData();
	fd.append('subject', subject);
	fd.append('subject_en', subject_en);
	fd.append('subject_cn', subject_cn);
	fd.append('year', year);
	fd.append('month', month);
	
	if (!confirm('연혁을 등록하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/history/regist',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	showToast('연혁이 저장되었습니다.','success');
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

function setModifyForm(idx) {
    $.ajax({
    	url: '/admin/history/detailForm/'+idx,
        method: 'POST',
        /* processData: false,
        contentType: false, */
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				$('#modify_subject').val(res.data.subject);
				$('#modify_subject_en').val(res.data.subject_en);
				$('#modify_subject_cn').val(res.data.subject_cn);
				$('#modify_year').val(res.data.year);
				$('#modify_month').val(res.data.month);
				$('#modify_idx').val(res.data.idx);

                modalOpen('history_modify-modal');
            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}

function modify() {
	const idx		= $('#modify_idx').val();
  	const year		= $('#modify_year').val();
  	const month		= $('#modify_month').val();
  	const subject	= $('#modify_subject').val().trim();
  	const subject_en	= $('#modify_subject_en').val().trim();
  	const subject_cn	= $('#modify_subject_cn').val().trim();

  	if (!idx)		return showToast('오류가 발생하였습니다.','error');
  	if (!year)		return showToast('년도를 선택해주세요.','error');
  	if (!month)		return showToast('월을 선택해주세요.','error');  
	if (!subject)	return showToast('제목(ko)을 입력해주세요.','error');
	if (!subject_en)	return showToast('제목(en)을 입력해주세요.','error');
	if (!subject_cn)	return showToast('제목(cn)을 입력해주세요.','error');
	
	
	const fd = new FormData();
	fd.append('subject', subject);
	fd.append('subject_en', subject_en);
	fd.append('subject_cn', subject_cn);
	fd.append('year', year);
	fd.append('month', month);

	if (!confirm('연혁을 수정하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/history/modify/'+idx,
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('연혁이 수정되었습니다.','success');
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

function deleteHistory() {
	const idx		= $('#modify_idx').val();
  	if (!idx)		return showToast('오류가 발생하였습니다.','error');
	
	if (!confirm('정말로 해당 연혁을 삭제하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/history/delete/'+idx,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('연혁이 삭제되었습니다.','success');
                location.href = '/admin/history';
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