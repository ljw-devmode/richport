@php 
	$header_skip = false; 
	$depth1_num = '5'; 
	$depth2_num = '11'; 
@endphp 
@include('admin.header._header')

<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">관리자</h3>
	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/user">
			<div class="flex items-end gap-3">
				<div class="w-4/5">
					<p class="mb-1">성명</p>
					<input type="text" id="name" name="name" class="form-control" placeholder="성명을 입력해주세요" value="{{ request('name') }}" >
				</div>
				<div class="w-4/5">
					<p class="mb-1">연락처</p>
					<input type="text" id="hp" name="hp" class="form-control" placeholder="연락처를 입력해주세요" value="{{ request('hp') }}" >
				</div>
				<div class="w-4/5">
					<p class="mb-1">이메일</p>
					<input type="text" id="email" name="email" class="form-control" placeholder="이메일을 입력해주세요" value="{{ request('email') }}" >
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">총 {{ $data->total() }}명</div>
			@if(auth()->user()->hasPerm('user.create'))
			<div class="flex items-center gap-2">
				<a href="javascript:;" class="btn btn-dark px-4" onclick="modalOpen('user_regist-modal')">사용자 등록</a>
			</div>
        	@endif
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
				</colgroup>
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="whitespace-nowrap">번호</th>
						<th class="whitespace-nowrap">아이디</th>
						<th class="whitespace-nowrap">성명</th>
						<th class="whitespace-nowrap">연락처</th>
						<th class="whitespace-nowrap">이메일</th>
						<th class="whitespace-nowrap">등록일</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $index => $item)
					@php
				        $number = ($data->currentPage() - 1) * $data->perPage() + ($index + 1);
				    @endphp

						<tr 
						@if(auth()->user()->hasPerm('user.update') || auth()->user()->hasPerm('user.delete')) 
							onclick="setModifyForm('{{ $item->idx }}')" 
							style="cursor: pointer;" 
							class="hover:bg-slate-100"
						@else 
							style="cursor: default;" 
						@endif
						>
							<td>{{ $item->idx }}</td>
							<td>{{ $item->id }}</td>
							<td>{{ $item->name }}</td>
							<td>{{ $item->hp }}</td>
							<td>{{ $item->email }}</td>
							<td>{{ $item->created_at }}</td>
						</tr>
					@empty
					<tr>
						<td colspan="6" class="text-center">등록된 사용자가 없습니다.</td>
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

	<!-- 사용자 등록 모달 -->
	<div id="user_regist-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">사용자 등록</h2>
					<button onclick="modalClose('user_regist-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class=" mt-3">
						<p class="mb-1 font-medium ">성명 <span class="text-danger">*</span></p>
						<input type="text" id="regist_name" name="regist_name" class="form-control" placeholder="성명을 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">아이디 <span class="text-danger">*</span></p>
						<input type="text" id="regist_id" name="regist_id" class="form-control" placeholder="아이디를 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">비밀번호 <span class="text-danger">*</span></p>
						<input type="password" id="regist_pw" name="regist_pw" class="form-control" placeholder="비밀번호를 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">연락처 </p>
						<input type="text" id="regist_hp" name="regist_hp" class="form-control" placeholder="연락처를 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">이메일 </p>
						<input type="text" id="regist_email" name="regist_email" class="form-control" placeholder="이메일을 입력해주세요">
					</div>
				</div>

				<div class="modal-body">
					<div class="flex items-center justify-between mb-2">
						<p class="font-bold text-blue-600">권한 설정 (등록)</p>
						<label class="cursor-pointer text-xs bg-slate-200 px-2 py-1 rounded">
							<input type="checkbox" id="reg_all_check"> 전체 선택
						</label>
					</div>
					<div class="permission">
						@foreach(config('permission') as $groupName => $sections)
							<div class="mt-5 reg-group-container">
								<div class="flex justify-between border-b mb-2">
									<span class="font-bold text-sm">{{ $groupName }}</span>
									<label class="text-xs text-slate-400 cursor-pointer">
										<input type="checkbox" class="reg-group-check"> 그룹전체
									</label>
								</div>

								@foreach($sections as $secName => $actions)
									@if(is_array($actions))
										<div class="ml-2 mb-2 reg-menu-container">
											<div class="flex justify-between mb-2 mt-2">
												<span class="text-xs font-semibold text-slate-400">[{{ $secName }}]</span>
												<label class="text-xs text-slate-400 cursor-pointer">
													<input type="checkbox" class="reg-menu-check"> 메뉴전체
												</label>
											</div>											
											<div class="flex flex-wrap gap-2 mt-1">
												@foreach($actions as $key => $val)
													<label class="text-xs cursor-pointer">
														<input type="checkbox" name="regist_permissions[]" value="{{ $key }}" class="reg-item"> {{ $val }}
													</label>
												@endforeach
											</div>
										</div>
									@else
										<label class="text-xs cursor-pointer ml-2">
											<input type="checkbox" name="regist_permissions[]" value="{{ $secName }}" class="reg-item"> {{ $actions }}
										</label>
									@endif
								@endforeach
							</div>
						@endforeach
					</div>
				</div>

				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('user_regist-modal')">취소</button>
					@if(auth()->user()->hasPerm('user.create'))
		        	<button type="button" class="btn btn-primary w-20" onclick="regist();">등록</button>
					@endif
				</div>
			</div>
		</div>
	</div>

	<!-- 사용자 수정 모달 -->
	<div id="user_modify-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">사용자 수정</h2>
					<button onclick="modalClose('user_modify-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class=" mt-3">
						<p class="mb-1 font-medium ">성명 <span class="text-danger">*</span></p>
						<input type="text" id="modify_name" name="modify_name" class="form-control" placeholder="성명을 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">아이디 <span class="text-danger">*</span></p>
						<input type="text" id="modify_id" name="modify_id" class="form-control" placeholder="아이디를 입력해주세요" readonly="readonly">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">비밀번호 <span class="text-danger">*</span></p>
						<input type="password" id="modify_pw" name="modify_pw" class="form-control" placeholder="비밀번호를 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">연락처 </p>
						<input type="text" id="modify_hp" name="modify_hp" class="form-control" placeholder="연락처를 입력해주세요">
					</div>
					<div class=" mt-3">
						<p class="mb-1 font-medium ">이메일 </p>
						<input type="text" id="modify_email" name="modify_email" class="form-control" placeholder="이메일을 입력해주세요">
					</div>
					<input type="hidden" id="modify_idx" name="modify_idx" value="">
				</div>

				<div class="modal-body">
					<div class="flex items-center justify-between mb-2">
						<p class="font-bold text-red-600">권한 설정 (수정)</p>
						<label class="cursor-pointer text-xs bg-slate-200 px-2 py-1 rounded">
							<input type="checkbox" id="mod_all_check"> 전체 선택
						</label>
					</div>
					<div class="permission">
						@foreach(config('permission') as $groupName => $sections)
							<div class="mt-5 mod-group-container">
								<div class="flex justify-between border-b mb-2">
									<span class="font-bold text-sm">{{ $groupName }}</span>
									<label class="text-xs text-slate-400 cursor-pointer">
										<input type="checkbox" class="mod-group-check"> 그룹전체
									</label>
								</div>
								@foreach($sections as $secName => $actions)
									@if(is_array($actions))
										<div class="ml-2 mb-2 mod-menu-container">
											<div class="flex justify-between mb-2 mt-2">
												<span class="text-xs font-semibold text-slate-400">[{{ $secName }}]</span>
												<label class="text-xs text-slate-400 cursor-pointer">
													<input type="checkbox" class="mod-menu-check"> 메뉴전체
												</label>
											</div>

											<div class="flex flex-wrap gap-2 mt-1">
												@foreach($actions as $key => $val)
													<label class="text-xs cursor-pointer">
														<input type="checkbox" name="modify_permissions" value="{{ $key }}" class="mod-item"> {{ $val }}
													</label>
												@endforeach
											</div>
										</div>
									@else
										<label class="text-xs cursor-pointer ml-2">
											<input type="checkbox" name="modify_permissions" value="{{ $secName }}" class="mod-item"> {{ $actions }}
										</label>
									@endif
								@endforeach
							</div>
						@endforeach
					</div>
				</div>	

				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20 " onclick="modalClose('user_modify-modal')">취소</button>
					@if(auth()->user()->hasPerm('user.update'))
					<button type="button" class="btn btn-primary w-20" onclick="modify()">수정</button>
					@endif
					@if(auth()->user()->hasPerm('user.delete'))
					<button type="button" class="btn btn-outline-danger w-20" onclick="deleteUser()">삭제</button>
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

// 등록 모달 권한 전체 선택
$(document).on('change', '#reg_all_check', function() {
    $('.reg-item, .reg-group-check').prop('checked', $(this).is(':checked'));
});

// 등록 모달 권한 그룹별 선택
$(document).on('change', '.reg-group-check', function() {
    $(this).closest('.reg-group-container').find('.reg-item').prop('checked', $(this).is(':checked'));
});

// 등록 모달 권한 그룹별 선택
$(document).on('change', '.reg-menu-check', function() {
    $(this).closest('.reg-menu-container').find('.reg-item').prop('checked', $(this).is(':checked'));
});

// 사용자 등록
function regist() {
  	const name	= $('#regist_name').val().trim();
  	const id	= $('#regist_id').val().trim();
  	const pw	= $('#regist_pw').val().trim();
  	const hp	= $('#regist_hp').val().trim();
  	const email	= $('#regist_email').val().trim();

    // 체크된 권한 수집
    const permissions = [];
    $("input[name='regist_permissions[]']:checked").each(function() {
        permissions.push($(this).val());
    });	

  	if (!name)	return showToast('성명을 입력해주세요.','error');
  	if (!id)	return showToast('아이디를 입력해주세요.','error');  
	if (!pw)	return showToast('비밀번호를 입력해주세요.','error');
	if (!hp)	return showToast('연락처를 입력해주세요.','error');
	if (!email)	return showToast('이메일을 입력해주세요.','error');
	
	const fd = new FormData();
	fd.append('name', name);
	fd.append('id', id);
	fd.append('pw', pw);
	fd.append('hp', hp);
	fd.append('email', email);

	// 권한 배열을 JSON 문자열로 보내거나 개별 append
    permissions.forEach(p => fd.append('permissions[]', p));

	console.log(permissions);
	
	if (!confirm('사용자를 등록하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/user/regist',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	showToast('사용자가 등록되었습니다.','success');
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

// 수정 모달 전체 선택
$(document).on('change', '#mod_all_check', function() {
    $('.mod-item, .mod-group-check').prop('checked', $(this).is(':checked'));
});

// 수정 모달 그룹별 선택
$(document).on('change', '.mod-group-check', function() {
    $(this).closest('.mod-group-container').find('.mod-item').prop('checked', $(this).is(':checked'));
});

// 수정 모달 그룹별 선택
$(document).on('change', '.mod-menu-check', function() {
    $(this).closest('.mod-menu-container').find('.mod-item').prop('checked', $(this).is(':checked'));
});

// 사용자 수정정보 설정
function setModifyForm(idx) {
	$('input[name="modify_permissions"]').prop('checked', false);

    $.ajax({
    	url: '/admin/user/detailForm/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				$('#modify_idx').val(res.data.idx);	
				$('#modify_id').val(res.data.id);
				$('#modify_name').val(res.data.name);
				$('#modify_hp').val(res.data.hp);
				$('#modify_email').val(res.data.email);

                // 권한 체크 처리 (res.permissions가 ['history.view', 'notice.create'] 형태라고 가정)
                if (res.permissions && res.permissions.length > 0) {
                    res.permissions.forEach(function(permKey) {
                        $('input[name="modify_permissions"][value="' + permKey + '"]').prop('checked', true);
                    });
                }

                modalOpen('user_modify-modal');
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

// 사용자 정보 수정
function modify() {
	const idx	= $('#modify_idx').val();
	const name  = $('#modify_name').val();
	const pw 	= $('#modify_pw').val();
	const hp 	= $('#modify_hp').val();
	const email	= $('#modify_email').val();
  	
	if (!name)  return showToast('이름을 입력해주세요.','error');
	if (!hp)	return showToast('연락처를 입력해주세요.','error');
	if (!email)	return showToast('이메일을 입력해주세요.','error'); 
	
	const fd = new FormData();
	fd.append('name', name);
	fd.append('pw', pw);
	fd.append('hp', hp);
	fd.append('email', email);

    const permissions = [];
    $('input[name="modify_permissions"]:checked').each(function() {
        fd.append('permissions[]', $(this).val()); // 'permissions[]'로 전송
    });

	if (!confirm('사용자 정보를 수정하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/user/modify/'+idx,
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('사용자 정보가 수정되었습니다.','success');
            	location.reload();
            } else {
            	showToast(res.message || '수정 실패','error');
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}

// 사용자 삭제
function deleteUser() {
	const idx		= $('#modify_idx').val();
  	if (!idx)		return showToast('오류가 발생하였습니다.','error');
	
	if (!confirm('정말로 해당 사용자를 삭제하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/user/delete/'+idx,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('사용자가 삭제되었습니다.','success');
                location.href = '/admin/user';
            } else {
            	showToast(res.message || '삭제 실패','error');
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}
</script>