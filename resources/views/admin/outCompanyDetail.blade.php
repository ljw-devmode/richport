@php 
	$header_skip = false; 
	$depth1_num = '5'; 
	$depth2_num = '31'; 
@endphp 
@include('admin.header._header')

	<div class="mt-6 px-5 tabrelative">
		<h3 class="intro-y text-xl font-bold">외주사 상세</h3>
			
		<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
			<div class="modal-body">
				<p class="mb-1 font-medium " id="modalCompanyCount">외주사 정보</p>

				<div class="flex items-center gap-2 mt-3">
					<p class="shrink-0 w-32 font-medium ">외주사명 <span class="text-danger">*</span></p>
					<input type="text" id="modify_out_name" name="modify_out_name" class="form-control" placeholder="외주사명을 입력해주세요" value="{{ $data->out_name }}">
				</div>

				<div class="flex items-center gap-10 mt-3">
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium">아이디</p>
						<input type="text" id="modify_id" name="modify_id" class="form-control" value="{{ $data->id }}" disabled>
					</div>
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium">비밀번호 <span class="text-danger">*</span></p>
						<input type="password" id="modify_password" name="modify_password" class="form-control" placeholder="비밀번호 수정시에만 입력해주세요" value="">
					</div>
				</div>


				<div class="flex items-center gap-10 mt-3">
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">대표자명 <span class="text-danger">*</span></p>
						<input type="text" id="modify_ceo" name="modify_ceo" class="form-control" placeholder="대표자명을 입력해주세요" value="{{ $data->ceo }}">					
					</div>
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">사업자번호 <span class="text-danger">*</span></p>
						<input type="text" id="modify_business_number" name="modify_business_number" class="form-control" placeholder="사업자 번호를 입력해주세요" value="{{ $data->business_number }}">
					</div>
				</div>
				

				<div class="flex items-center gap-10 mt-3">
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium">전화번호</p>
						<input type="text" id="modify_phone" name="modify_phone" class="form-control" placeholder="전화번호를 입력해주세요" value="{{ $data->phone }}">
					</div>
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium">이메일 <span class="text-danger">*</span></p>
						<input type="text" id="modify_email" name="modify_email" class="form-control" placeholder="이메일을 입력해주세요" value="{{ $data->email }}">
					</div>
				</div>
				<div class="flex items-center gap-2 mt-3">
					<p class="shrink-0 w-32 font-medium ">주소 <span class="text-danger">*</span></p>
					<input type="text" id="modify_address" name="modify_address" class="form-control" placeholder="외주사명을 입력해주세요" value="{{ $data->address }}">
				</div>							

				<input type="hidden" id="modify_idx" name="modify_idx" value="{{ $data->idx }}">
			</div>
		</div>

		<!-- 저장, 목록, 삭제 버튼 -->
		<div class="modal-footer flex items-center justify-center gap-3 ">
			<button type="button" class="btn btn-outline-pending w-20 " onclick="history.back();">목록</button>
			@if(auth()->user()->hasPerm('outcompany.update'))
			<button type="button" class="btn btn-primary w-20" onclick="modify()">저장</button>
			@endif
			@if(auth()->user()->hasPerm('outcompany.delete'))
			<button type="button" class="btn btn-outline-danger w-20" onclick="deleteOutCompany()">삭제</button>
			@endif
		</div>

		<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
			<!-- 탭 영역 -->
			<div class="flex items-center gap-10 border-b border-slate-200 py-3 px-6">
				<button type="button" class="detail-tab-btn active text-primary" data-tab="tab-company">발전소</button>
				<button type="button" class="detail-tab-btn" data-tab="tab-user">담당자</button>
			</div>

			<div class="detail-tabs-wrap">
				<div class="detail-tab-panels tabrelative">
					<!-- 발전소 -->
					<div id="tab-company" class="detail-tab-panel active">
						<!-- 발전소 등록 -->
						@if(auth()->user()->hasPerm('outcompany.create')) 
						<div class="card-body">
							<input type="hidden" id="companyout_idx" name="companyout_idx" value="">
							<input type="hidden" id="out_idx" name="out_idx" value="{{$data->idx}}">

							<div class="om gap-2">
								<div class="col-md-3">
									<p class="mb-1">발전소</p>
									<select id="comp_idx" name="comp_idx" class="form-select w-36">
										@foreach ($companyList as $item)
										<option value="{{$item->idx}}">{{$item->comp_name}}</option>
										@endforeach
									</select>	
								</div>
								<div class="col-md-3">
									<p class="mb-1">업무 구분</p>
									<select id="work_type" name="work_type" class="form-select w-36">
										<option value="구분 없음">구분 없음</option>
										<option value="현장 관리">현장 관리</option>
										<option value="안전 관리">안전 관리</option>
										<option value="정기 점검">정기 점검</option>
										<option value="영업 대행">영업 대행</option>
									</select>	
								</div>
								<div class="col-md-2 w-40">
									<p class="mb-1">업무 시작일</p>
									<div class="relative mx-auto">
										<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
											<i data-lucide="calendar" class="w-4 h-4"></i>
										</div>
										<input type="text" id="start_date" name="start_date" class="datepicker form-control pl-12" data-single-mode="true" value="{{$data->start_date}}">									
									</div>
								</div>
								<div class="col-md-2  w-88">
									<p class="mb-1">담당자</p>
									<select id="user_idx" name="user_idx" class="form-select w-88">
										<option value="">미지정</option>
										@foreach ($outuserList as $item)
										<option value="{{$item->idx}}">{{$item->user_name}} | {{$item->work_type}} | {{$item->user_phone}}</option>
										@endforeach
									</select>
								</div>
								<div class="col-md-1 d-flex gap-1">
									<button type="button" id="btnCompanySave" class="btn btn-primary">저장</button>
									<button type="button" id="btnCompanyCancel" class="btn btn-secondary">취소</button>
								</div>
							</div>
						</div>
						@endif

						<!-- 발전소 내역 -->
						<div id="companyTableArea"></div>
					</div>

					<!-- 담당자 -->
					<div id="tab-user" class="detail-tab-panel">
						<!-- 담당자 등록 -->
						@if(auth()->user()->hasPerm('outcompany.create')) 
						<div class="card-body">
							<input type="hidden" id="user_useridx" name="user_useridx" value="">
							<input type="hidden" id="out_idx" name="out_idx" value="{{$data->idx}}">

							<div class="om gap-2">
								<div class="col-md-3">
									<p class="mb-1">담당자명</p>
									<input type="text" id="user_name" name="user_name" class="form-control" data-single-mode="true" value="">	
								</div>
								<div class="col-md-3">
									<p class="mb-1">업무 구분</p>
									<select id="user_work_type" name="user_work_type" class="form-select w-36">
										<option value="구분 없음">구분 없음</option>
										<option value="현장 관리">현장 관리</option>
										<option value="안전 관리">안전 관리</option>
										<option value="정기 점검">정기 점검</option>
										<option value="영업 대행">영업 대행</option>
									</select>	
								</div>
								<div class="col-md-2">
									<p class="mb-1">전화번호</p>
									<input type="text" id="user_phone" name="user_phone" class="form-control" data-single-mode="true" value="">									
								</div>
								<div class="col-md-1 d-flex gap-1">
									<button type="button" id="btnUserSave" class="btn btn-primary">저장</button>
									<button type="button" id="btnUserCancel" class="btn btn-secondary">취소</button>
								</div>
							</div>
						</div>
						@endif

						<!-- 담당자 내역 -->
						<div id="userTableArea"></div>
					</div>					
				</div>
			</div>
		</div>
	</div>

<!-- END: Content -->
</div>
</div>
@include('admin.footer._footer')

<script>
// 탭 클릭시 영역 활성화
$(document).on('click', '.detail-tab-btn', function () {
    const tabId = $(this).data('tab');

    $('.detail-tab-btn').removeClass('active text-primary');
	$(this).addClass('active text-primary');

    $('.detail-tab-panel').removeClass('active').hide();
    $('#' + tabId).addClass('active').show();
});

$(document).ready(function() {
    // 페이지 로드 시 발전소 목록 불러오기
    setComapnyTable();
	setUserTable();
});


/*******************************  외주사  *******************************/
// 외주사 정보 수정
function modify() {
	const idx				= $('#modify_idx').val();
	const out_name			= $('#modify_out_name').val().trim();
	const password			= $('#modify_password').val();
	const ceo				= $('#modify_ceo').val();
	const business_number	= $('#modify_business_number').val().trim();
	const phone				= $('#modify_phone').val();
	const email				= $('#modify_email').val();
	const address			= $('#modify_address').val();

  	if (!out_name)		return showToast('외주사명을 입력해주세요.','error');
  	if (!ceo)			return showToast('대표자명을 입력해주세요.','error');
  	if (!phone)			return showToast('연락처를 입력해주세요.','error');

	const fd = new FormData();
	fd.append('idx', idx);
	fd.append('out_name', out_name);
	fd.append('password', password);
	fd.append('ceo', ceo);
	fd.append('business_number', business_number);
	fd.append('phone', phone);
	fd.append('email', email);
	fd.append('address', address);
	
	if (!confirm('외주사 정보를 수정하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/outcompany/modify/'+idx,
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('외주사 정보가 수정되었습니다.','success');
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

// 외주사 삭제
function deleteOutCompany() {
	const idx		= $('#modify_idx').val();
  	if (!idx)		return showToast('오류가 발생하였습니다.','error');
	
	if (!confirm('정말로 외주사를 삭제하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/outcompany/delete/'+idx,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('외주사가 삭제되었습니다.','success');
                location.href = '/admin/outcompany';
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


/*******************************  발전소  *******************************/
// 발전소 수정버튼 클릭
$(document).on('click', '.btn-company-edit', function () {
    $('#companyout_idx').val($(this).data('idx'));
    $('#comp_idx').val($(this).data('comp_idx'));
    $('#work_type').val($(this).data('work_type'));
    $('#start_date').val($(this).data('start_date'));
	$('#user_idx').val($(this).data('user_idx'));
});

// 발전소 수정취소 클릭
$(document).on('click', '#btnCompanyCancel', function () {
	$('#companyout_idx').val('');
    $('#comp_idx').val($('#comp_idx option:first').val());
    $('#work_type').val('현장 관리');
	$('#start_date').val('');
	$('#user_idx').val('');
});

// 발전소 저장
$(document).on('click', '#btnCompanySave', function () {
    $.ajax({
        url: '/admin/outcompany/companysave',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
			idx: $('#companyout_idx').val(),
            out_idx: $('#out_idx').val(), 
            comp_idx: $('#comp_idx').val(),
            work_type: $('#work_type').val(),
            start_date: $('#start_date').val(),
            user_idx: $('#user_idx').val(),
        },
        success: function (res) {
            if (res.status === 'success') {
				showToast('발전소가 저장되었습니다.','success');
                setComapnyTable();
                $('#btnCompanyCancel').click();
            } else {
                showToast('오류가 발생했습니다.','error');
            }
        }
    });
});

// 발전소 삭제
$(document).on('click', '.btn-company-delete', function () {
    const idx = $(this).data('idx');

    if (!confirm('삭제하시겠습니까?')) return;

    $.ajax({
        url: '/admin/outcompany/companydelete',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { idx: idx, },
        success: function (res) {
            if (res.status === 'success') {
				showToast('발전소가 삭제되었습니다.','success');
                setComapnyTable();
            } else {
                showToast('오류가 발생했습니다.','error');
            }
        }
    });
});

// 발전소 세팅
function setComapnyTable() {
	$.ajax({
    	url: '/admin/outcompany/companylist/{{ $data->idx }}',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				let html = `
					<div class="text-base mt-5 mb-2">외주 업무 발전소 목록</div>
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th>번호</th>
								<th>업무 구분</th>
								<th>발전소명</th>
								<th>시작일</th>
								<th>담당자</th>
								<th>관리</th>
							</tr>
						</thead>
						<tbody>
				`;

				if (res.data && res.data.length > 0) {
					res.data.forEach(function(item) {
						const compName = item.company ? item.company.comp_name : '';
						const userName = item.outuser ? item.outuser.user_name : '미지정';
						const userIdx  = item.user_idx || ''; 

						html += `
							<tr>
								<td>${item.idx}</td>
								<td>${item.work_type ?? ''}</td>
								<td>${compName}</td>
								<td>${item.start_date ?? ''}</td>
								<td>${userName}</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-primary btn-company-edit"
										data-idx="${item.idx}"
										data-comp_idx="${item.comp_idx}"
										data-out_idx="{{ $data->idx }}"
										data-work_type="${item.work_type}"
										data-start_date="${item.start_date}"
										data-user_idx="${userIdx}">
										수정
									</button>
									<button type="button"
										class="btn btn-sm btn-outline-danger btn-company-delete"
										data-idx="${item.idx}">
										삭제
									</button>
								</td>
							</tr>
						`;
					});
				} else {
					html += `<tr><td colspan="6">외주 관리 발전소가 없습니다.</td></tr>`;
				}

				html += `</tbody></table>`;

				$('#companyTableArea').html(html);

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


/*******************************  담당자  *******************************/
// 담당자 수정버튼 클릭
$(document).on('click', '.btn-user-edit', function () {
	$('#user_useridx').val($(this).data('idx'));
	$('#user_name').val($(this).data('user_name'));
	$('#user_work_type').val($(this).data('work_type'));
    $('#user_phone').val($(this).data('user_phone'));
});

// 담당자 수정취소 클릭
$(document).on('click', '#btnUserCancel', function () {
	$('#user_useridx').val('');
	$('#user_name').val('');
    $('#user_work_type').val('구분 없음');
	$('#user_phone').val('');
});

// 담당자 저장
$(document).on('click', '#btnUserSave', function () {
    $.ajax({
        url: '/admin/outcompany/usersave',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
			idx: $('#user_useridx').val(),
            out_idx: $('#out_idx').val(), 
            user_name: $('#user_name').val(),
            work_type: $('#user_work_type').val(),
            user_phone: $('#user_phone').val(),
        },
        success: function (res) {
            if (res.status === 'success') {
				showToast('담당자가 저장되었습니다.','success');
                setUserTable();
                $('#btnUserCancel').click();
            } else {
                showToast('오류가 발생했습니다.','error');
            }
        }
    });
});

// 담당자 삭제
$(document).on('click', '.btn-user-delete', function () {
    const idx = $(this).data('idx');

    if (!confirm('삭제하시겠습니까?')) return;

    $.ajax({
        url: '/admin/outcompany/userdelete',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { idx: idx, },
        success: function (res) {
            if (res.status === 'success') {
				showToast('담당자가 삭제되었습니다.','success');
                setUserTable();
                $('#btnUserCancel').click();
            } else {
                showToast('오류가 발생했습니다.','error');
            }
        }
    });
});

// 발전소 세팅
function setUserTable() {
	$.ajax({
    	url: '/admin/outcompany/userlist/{{ $data->idx }}',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				
				let html = `
					<div class="text-base mt-5 mb-2">담당자 목록</div>
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th>업무 구분</th>
								<th>담당자명</th>
								<th>전화번호</th>
								<th>등록 구분</th>
								<th>관리</th>
							</tr>
						</thead>
						<tbody>
				`;

				let selectHtml = '<option value="">미지정</option>';

				if (res.data && res.data.length > 0) {
					res.data.forEach(function(item) {
						const regtype = item['reg_type'] == 'R' ? '관리자 등록' : '체크리스트 등록'
						html += `
							<tr>
								<td>${item.work_type ?? ''}</td>
								<td>${item.user_name ?? ''}</td>
								<td>${item.user_phone ?? ''}</td>
								<td>${regtype}</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-primary btn-user-edit"
										data-idx="${item.idx}"
										data-work_type="${item.work_type}"
										data-user_name="${item.user_name}"
										data-user_phone="${item.user_phone}">
										수정
									</button>
									<button type="button"
										class="btn btn-sm btn-outline-danger btn-user-delete"
										data-idx="${item.idx}">
										삭제
									</button>
								</td>
							</tr>
						`;

						selectHtml += `<option value="${item.idx}">${item.user_name} | ${item.user_phone} | ${item.work_type}</option>`;
					});
				} else {
					html += `<tr><td colspan="5">담당자가 없습니다.</td></tr>`;
				}

				html += `</tbody></table>`;
            
				// 목록 업데이트
				$('#userTableArea').html(html);

				// 발전소 탭의 담당자 select box ID 반영
				$('#tab-company #user_idx').html(selectHtml);


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

</script>