@php 
	$header_skip = false; 
	$depth1_num = '5'; 
	$depth2_num = '32'; 
@endphp 
@include('admin.header._header')

<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">카테고리 관리</h3>
	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/category">
			<div class="flex items-end gap-3">
				<div class="w-40">
					<p class="mb-1">부서</p>
					<select id="depart_idx" name="depart_idx" class="form-select">
						<option value="">부서 선택</option>
						@foreach ($department as $item)
						<option value="{{$item->idx}}">{{$item->depart_name}}</option>
						@endforeach
					</select>
				</div>
				<div class="w-2/5">
					<p class="mb-1">카테고리명</p>
					<input type="text" id="cat_name" name="cat_name" class="form-control" placeholder="카테고리명을 입력해주세요" value="{{ request('cat_name') }}" >
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">총 {{ $data->total() }}개</div>
			@if(auth()->user()->hasPerm('category.create'))
			<div class="flex items-center gap-2">
				<a href="javascript:;" class="btn btn-dark px-4" onclick="modalOpen('category_regist-modal')">카테고리 등록</a>
			</div>
			@endif
		</div>

		<div class="p-6">
			<table class="table table-bordered table-hover">
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="whitespace-nowrap">번호</th>
						<th class="whitespace-nowrap">부서명</th>
						<th class="whitespace-nowrap">카테고리명</th>
						<th class="whitespace-nowrap">템플릿수</th>
						<th class="whitespace-nowrap">등록일</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $index => $item)
							<tr 
						@if(auth()->user()->hasPerm('category.update') || auth()->user()->hasPerm('category.delete')) 
							onclick="setModifyForm('{{ $item->idx }}')"
							style="cursor: pointer;" 
							class="hover:bg-slate-100"
						@else 
							style="cursor: default;" 
						@endif
						>
							<td>{{ $index + 1 }}</td>
							<td>{{ $item->department->depart_name }}</td>
							<td>{{ $item->cat_name }}</td>
							<td>{{ $item->template_count }}</td>
							<td>{{ $item->created_at?->format('Y-m-d') }}</td>
						</tr>
					@empty
					<tr>
						<td colspan="4" class="text-center">등록된 카테고리가 없습니다.</td>
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

	<!-- 카테고리 등록 모달 -->
	<div id="category_regist-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">카테고리 등록</h2>
					<button onclick="modalClose('category_regist-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class=" mt-3">
						<p class="mb-1 font-medium ">부서 <span class="text-danger">*</span></p>
						<select id="regist_depart_idx" name="regist_depart_idx" class="form-select">
							<option value="">부서를 선택해주세요</option>
							@foreach ($department as $item)
							<option value="{{$item->idx}}">{{$item->depart_name}}</option>
							@endforeach
						</select>
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">카테고리명 <span class="text-danger">*</span></p>
						<input type="text" id="regist_cat_name" name="regist_cat_name" class="form-control" placeholder="카테고리명을 입력해주세요">
					</div>
				</div>
			
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('category_regist-modal')">취소</button>
					@if(auth()->user()->hasPerm('category.create'))
					<button type="button" class="btn btn-primary w-20" onclick="regist();">등록</button>
					@endif
				</div>
			</div>
		</div>
	</div>

	<!-- 카테고리 수정 모달 -->
	<div id="category_modify-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">카테고리 수정</h2>
					<button onclick="modalClose('category_modify-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class=" mt-3">
						<p class="mb-1 font-medium ">부서 <span class="text-danger">*</span></p>
						<select id="modify_depart_idx" name="modify_depart_idx" class="form-select">
							<option value="">부서를 선택해주세요</option>
							@foreach ($department as $item)
							<option value="{{$item->idx}}">{{$item->depart_name}}</option>
							@endforeach
						</select>
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium">카테고리명<span class="text-danger">*</span></p>
						<input type="text" id="modify_cat_name" name="modify_cat_name" class="form-control" placeholder="카테고리명을 입력해주세요" value="">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium" id="modalTemplateCount">템플릿 목록 0개소</p>
						<table border="1" class="table">
							<thead>
								<tr>
									<th>번호</th>
									<th>템플릿명</th>
								</tr>
							</thead>
							<tbody id="modalTemplateTableBody">
							</tbody>
						</table>
					</div>
					<input type="hidden" id="modify_idx" name="modify_idx" value="">
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20 " onclick="modalClose('category_modify-modal')">취소</button>
					@if(auth()->user()->hasPerm('category.update'))
					<button type="button" class="btn btn-primary w-20" onclick="modify()">수정</button>
					@endif
					@if(auth()->user()->hasPerm('category.delete'))
					<button type="button" id="btnDeleteGroup" class="btn btn-outline-danger w-20" onclick="deleteCategory()">삭제</button>
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
	const depart_idx	= $('#regist_depart_idx').val();
	const cat_name		= $('#regist_cat_name').val();

  	if (!cat_name)		return showToast('카테고리명을 입력해주세요.','error');
	
	const fd = new FormData();
	fd.append('depart_idx', depart_idx);	
	fd.append('cat_name', cat_name);

	if (!confirm('카테고리를 등록하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/category/regist',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	showToast('카테고리가 저장되었습니다.','success');
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
    	url: '/admin/category/detailForm/'+idx,
        method: 'POST',
        /* processData: false,
        contentType: false, */
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				console.log(res);
				$('#modify_idx').val(res.category.idx);
				$('#modify_depart_idx').val(res.category.depart_idx);
				$('#modify_cat_name').val(res.category.cat_name);

				let categoryHtml = '';

				if (res.template && res.template.length > 0) {
                    res.template.forEach(function (item, index) {
						const templateName = item.checktemplate ? item.checktemplate.temp_name : '';
                        
						templateHtml += `<tr>
                                <td style="text-align:center;">${index + 1}</td>
                                <td>${templateName}</td>
                            </tr>`;
                    });
                } else {
                    templateHtml = '<tr><td colspan="2" style="text-align:center;">등록된 카테고리가 없습니다.</td></tr>';
                }
				
				$('#modalTemplateTableBody').html(templateHtml);

				// 삭제 버튼 표시/숨김
				if (res.template_count > 0) {
					$('#btnDeleteGroup').hide();
					$('#modalTemplateCount').text('템플릿 ' + res.template_count + '개');
				} else {
					$('#btnDeleteGroup').show();
					$('#modalTemplateCount').text('템플릿 0개');
				}		
				
				$('#modify_idx').val(idx);
				

                modalOpen('category_modify-modal');
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
	const idx			= $('#modify_idx').val();
	const depart_idx	= $('#modify_depart_idx').val();
	const cat_name 		= $('#modify_cat_name').val();

  	if (!cat_name)	return showToast('카테고리명을 입력해주세요.','error');
	
	const fd = new FormData();
	fd.append('idx', idx);
	fd.append('depart_idx', depart_idx);
	fd.append('cat_name', cat_name);

	if (!confirm('카테고리 정보를 수정하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/category/modify/'+idx,
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('SPC가 수정되었습니다.','success');
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

function deleteCategory() {
	const idx		= $('#modify_idx').val();
  	if (!idx)		return showToast('오류가 발생하였습니다.','error');
	
	if (!confirm('정말로 카테고리를 삭제하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/category/delete/'+idx,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('카테고리가 삭제되었습니다.','success');
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