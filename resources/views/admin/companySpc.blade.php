@php 
	$header_skip = false; 
	$depth1_num = '5'; 
	$depth2_num = '21'; 
@endphp 
@include('admin.header._header')

<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">SPC 관리</h3>
	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/spc">
			<div class="flex items-end gap-3">
				<div class="w-2/5">
					<p class="mb-1">SPC명</p>
					<input type="text" id="group_name" name="group_name" class="form-control" placeholder="SPC명을 입력해주세요" value="{{ request('group_name') }}" >
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">총 {{ $data->total() }}개</div>
			@if(auth()->user()->hasPerm('spc.create'))
			<div class="flex items-center gap-2">
				<a href="javascript:;" class="btn btn-dark px-4" onclick="modalOpen('spc_regist-modal')">SPC 등록</a>
			</div>
			@endif
		</div>

		<div class="p-6">
			<table class="table table-bordered table-hover">
				<colgroup>
					<col width="*">
					<col width="*">
					<col width="*">
					<col width="20%">
				</colgroup>
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="whitespace-nowrap">번호</th>
						<th class="whitespace-nowrap">SPC명</th>
						<th class="whitespace-nowrap">발전소 수량</th>
						<th class="whitespace-nowrap">등록일</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $index => $item)
					@php
				        $number = ($data->currentPage() - 1) * $data->perPage() + ($index + 1);
				    @endphp
						<tr 
						@if(auth()->user()->hasPerm('spc.update') || auth()->user()->hasPerm('spc.delete')) 
							onclick="setModifyForm('{{ $item->idx }}')"
							style="cursor: pointer;" 
							class="hover:bg-slate-100"
						@else 
							style="cursor: default;" 
						@endif
						>
							<td>{{ $index + 1 }}</td>
							<td>{{ $item->group_name }}</td>
							<td>{{ $item->company_count }}</td>
							<td>{{ $item->created_at }}</td>
						</tr>
					@empty
					<tr>
						<td colspan="4" class="text-center">등록된 SPC가 없습니다.</td>
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

	<!-- SPC 등록 모달 -->
	<div id="spc_regist-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto"SPC 등록</h2>
					<button onclick="modalClose('spc_regist-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class=" mt-3">
						<p class="mb-1 font-medium ">SPC명 <span class="text-danger">*</span></p>
						<input type="text" id="regist_group_name" name="regist_group_name" class="form-control" placeholder="SPC명을 입력해주세요">
					</div>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('spc_regist-modal')">취소</button>
					@if(auth()->user()->hasPerm('spc.create'))
					<button type="button" class="btn btn-primary w-20" onclick="regist();">등록</button>
					@endif
				</div>
			</div>
		</div>
	</div>

	<!-- SPC 수정 모달 -->
	<div id="spc_modify-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">SPC 수정</h2>
					<button onclick="modalClose('spc_modify-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class=" mt-3">
						<p class="mb-1 font-medium ">SPC명<span class="text-danger">*</span></p>
						<input type="text" id="modify_group_name" name="modify_group_name" class="form-control" placeholder="SPC명을 입력해주세요" value="">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium " id="modalCompanyCount">발전소 목록 0개소</p>
						<table border="1" class="table">
							<thead>
								<tr>
									<th>번호</th>
									<th>지역</th>
									<th>도시</th>
									<th>회사명</th>
								</tr>
							</thead>
							<tbody id="modalCompanyTableBody">
							</tbody>
						</table>
					</div>
					<input type="hidden" id="modify_idx" name="modify_idx" value="">
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20 " onclick="modalClose('spc_modify-modal')">취소</button>
					@if(auth()->user()->hasPerm('spc.update'))
					<button type="button" class="btn btn-primary w-20" onclick="modify()">수정</button>
					@endif
					@if(auth()->user()->hasPerm('spc.delete'))
					<button type="button" id="btnDeleteGroup" class="btn btn-outline-danger w-20" onclick="deleteSpc()">삭제</button>
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
	const group_name		= $('#regist_group_name').val();

  	if (!group_name)		return showToast('SPC명을 입력해주세요.','error');
	
	const fd = new FormData();
	fd.append('group_name', group_name);
	
	if (!confirm('SPC를 등록하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/spc/regist',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	showToast('SPC가 저장되었습니다.','success');
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
    	url: '/admin/spc/detailForm/'+idx,
        method: 'POST',
        /* processData: false,
        contentType: false, */
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				$('#modify_group_name').val(res.group_name);

				let companyHtml = '';

				if (res.company && res.company.length > 0) {
                    res.company.forEach(function (item, index) {
						const regionName = item.regions ? item.regions.name_kr : '';
                        const cityName = item.cities ? item.cities.name_kr : '';
                        const companyName = item.comp_name ?? '';
                        
						companyHtml += `<tr>
                                <td style="text-align:center;">${index + 1}</td>
                                <td>${companyName}</td>
								<td style="text-align:center;">${regionName}</td>
                                <td style="text-align:center;">${cityName}</td>
                            </tr>`;
                    });
                } else {
                    companyHtml = '<tr><td colspan="4" style="text-align:center;">등록된 발전소가 없습니다.</td></tr>';
                }
				
				$('#modalCompanyTableBody').html(companyHtml);

				// 삭제 버튼 표시/숨김
				if (res.company_count > 0) {
					$('#btnDeleteGroup').hide();
					$('#modalCompanyCount').text('발전소 목록 ' + res.company_count + '개소');
				} else {
					$('#btnDeleteGroup').show();
					$('#modalCompanyCount').text('발전소 목록 0개소');
				}		
				
				$('#modify_idx').val(idx);
				

                modalOpen('spc_modify-modal');
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
	const group_name	= $('#modify_group_name').val();

  	if (!group_name)	return showToast('SPC명을 입력해주세요.','error');
	
	const fd = new FormData();
	fd.append('group_name', group_name);

	if (!confirm('SPC명을 수정하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/spc/modify/'+idx,
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

function deleteSpc() {
	const idx		= $('#modify_idx').val();
  	if (!idx)		return showToast('오류가 발생하였습니다.','error');
	
	if (!confirm('정말로 SPC를 삭제하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/spc/delete/'+idx,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('SPC가 삭제되었습니다.','success');
                location.href = '/admin/spc';
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