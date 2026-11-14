@php
	$menu = 'profile';
	$photoUrl = $partner->photo_up_name ? Storage::disk('public')->url($partner->photo_up_name) : null;
@endphp
@include('rips.header._header')
<div class="rp-page">
	<div class="rp-page-head">
		<div>
			<h3>내 정보 수정</h3>
			<p class="rp-desc">회사정보와 담당자정보를 확인하고 수정합니다. 회색으로 표시된 항목은 RIPS 신청 시 등록된 정보로 직접 수정할 수 없습니다. (변경이 필요하면 리치포트에 문의해주세요)</p>
		</div>
	</div>

	<form id="profile-form" onsubmit="return false;">
		<!-- 회사정보 -->
		<div class="rp-box">
			<div class="rp-box-title">회사정보</div>
			<div class="rp-profile">
				<div class="rp-profile-photo">
					<div class="photo" id="photo_preview" style="{{ $photoUrl ? 'background-image:url('.$photoUrl.')' : '' }}">
						@unless ($photoUrl)<span><i data-lucide="building-2" class="w-8 h-8"></i><br>업체 사진</span>@endunless
					</div>
					<label class="btn btn-sm btn-outline-secondary mt-2 w-full">
						사진 선택 <input type="file" id="photo" accept=".jpg,.jpeg,.png,.webp" class="hidden">
					</label>
					@if ($photoUrl)
						<div class="form-check mt-2 justify-center">
							<input id="remove_photo" class="form-check-input" type="checkbox" value="1">
							<label class="form-check-label text-xs" for="remove_photo">사진 삭제</label>
						</div>
					@endif
					<p class="rp-help text-center">jpg, png, webp · 5MB 이하</p>
				</div>

				<div class="flex-1 min-w-0">
					<div class="rp-form-row">
						<p>업체구분</p>
						<input type="text" class="form-control" value="{{ $partner->ripsType->subject ?? '-' }}" disabled>
					</div>
					<div class="rp-form-row">
						<p>회사명</p>
						<input type="text" class="form-control" value="{{ $partner->partner_name }}{{ $partner->store_name ? ' · '.$partner->store_name : '' }}" disabled>
					</div>
					<div class="rp-form-row">
						<p>회사 주소</p>
						<input type="text" name="company_address" class="form-control" maxlength="255" value="{{ $partner->company_address }}" placeholder="회사 주소를 입력해주세요">
					</div>
					<div class="rp-form-row">
						<p>회사 대표전화</p>
						<input type="text" name="company_tel" class="form-control" maxlength="50" value="{{ $partner->company_tel }}" placeholder="예: 028-3776-1234">
					</div>
					<div class="rp-form-row">
						<p>대표자명</p>
						<input type="text" name="ceo_name" class="form-control" maxlength="100" value="{{ $partner->ceo_name }}" placeholder="대표자명을 입력해주세요">
					</div>
					@foreach (['customs_code' => '통관고유번호', 'biz_no' => '사업자번호'] as $key => $label)
						<div class="rp-form-row">
							<p>{{ $label }}</p>
							@if ($partner->{$key})
								<div class="rp-inline">
									<input type="text" class="form-control" value="{{ $partner->{$key} }}" disabled>
									<span class="rp-muted text-xs shrink-0">등록 완료 · 수정 불가</span>
								</div>
							@else
								<div class="rp-inline">
									<input type="text" name="{{ $key }}" class="form-control rp-once" data-label="{{ $label }}" maxlength="50" placeholder="{{ $label }}를 입력해주세요 (영문, 숫자, - )">
									<span class="text-danger text-xs shrink-0">최초 1회 입력 · 저장 후 수정 불가</span>
								</div>
							@endif
						</div>
					@endforeach
				</div>
			</div>
		</div>

		<!-- 담당자정보 -->
		<div class="rp-box">
			<div class="rp-box-title">담당자정보</div>
			<div class="rp-row">
				<div>
					<div class="rp-form-row">
						<p>담당자명 <span class="text-danger">*</span></p>
						<input type="text" name="manager_name" class="form-control" maxlength="100" value="{{ $partner->manager_name }}" placeholder="담당자명을 입력해주세요">
					</div>
					<div class="rp-form-row">
						<p>담당자 연락처 <span class="text-danger">*</span></p>
						<input type="text" name="manager_hp" class="form-control" maxlength="50" value="{{ $partner->manager_hp }}" placeholder="예: 090-123-4567">
					</div>
				</div>
				<div>
					<div class="rp-form-row">
						<p>담당자 이메일</p>
						<input type="email" name="manager_email" class="form-control" maxlength="200" value="{{ $partner->manager_email }}" placeholder="example@company.com">
					</div>
					<div class="rp-form-row">
						<p>담당업무</p>
						<input type="text" name="manager_task" class="form-control" maxlength="200" value="{{ $partner->manager_task }}" placeholder="예: 발주 · 재고 관리">
					</div>
				</div>
			</div>
			<div class="flex justify-center mt-6">
				<button type="button" class="btn btn-primary w-32" id="profile_save_btn" onclick="saveProfile()">정보 저장</button>
			</div>
		</div>
	</form>

	<!-- 계정정보 -->
	<div class="rp-box">
		<div class="rp-box-title">계정정보</div>
		<div class="rp-row">
			<div>
				<div class="rp-form-row">
					<p>아이디</p>
					<input type="text" class="form-control" value="{{ $partner->rips_id }}" disabled>
				</div>
				<div class="rp-form-row">
					<p>현재 비밀번호 <span class="text-danger">*</span></p>
					<input type="password" id="current_password" class="form-control" autocomplete="current-password" placeholder="현재 비밀번호">
				</div>
			</div>
			<div>
				<div class="rp-form-row">
					<p>새 비밀번호 <span class="text-danger">*</span></p>
					<input type="password" id="new_password" class="form-control" autocomplete="new-password" placeholder="6자 이상">
				</div>
				<div class="rp-form-row">
					<p>비밀번호 확인 <span class="text-danger">*</span></p>
					<input type="password" id="new_password_confirmation" class="form-control" autocomplete="new-password" placeholder="새 비밀번호를 한 번 더 입력">
				</div>
			</div>
		</div>
		<div class="flex justify-center mt-6">
			<button type="button" class="btn btn-dark w-32" onclick="changePassword()">비밀번호 변경</button>
		</div>
	</div>
</div>
@include('rips.footer._footer')
<script>
// 사진 미리보기
$('#photo').on('change', function () {
    const file = this.files[0];
    if (!file) return;
    if (!/\.(jpe?g|png|webp)$/i.test(file.name)) { this.value = ''; return showToast('jpg, png, webp 이미지만 올릴 수 있습니다.', 'error'); }
    if (file.size > 5 * 1024 * 1024) { this.value = ''; return showToast('5MB 이하 이미지만 올릴 수 있습니다.', 'error'); }

    const reader = new FileReader();
    reader.onload = e => $('#photo_preview').css('background-image', 'url(' + e.target.result + ')').empty();
    reader.readAsDataURL(file);
    $('#remove_photo').prop('checked', false);
});

function saveProfile() {
    const $form = $('#profile-form');
    const val   = name => $form.find('[name="' + name + '"]').val().trim();

    if (!val('manager_name')) return showToast('담당자명을 입력해주세요.', 'error');
    if (!val('manager_hp'))   return showToast('담당자 연락처를 입력해주세요.', 'error');
    const email = val('manager_email');
    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return showToast('담당자 이메일 형식이 올바르지 않습니다.', 'error');

    // 통관고유번호 / 사업자번호 : 최초 입력이면 한 번 더 확인
    const once = $('.rp-once').filter(function () { return this.value.trim() !== ''; })
        .map(function () { return $(this).data('label') + ' : ' + this.value.trim(); }).get();
    if (once.length && !confirm(once.join('\n') + '\n\n위 정보는 저장 후 수정할 수 없습니다. 저장하시겠습니까?')) return;

    const fd = new FormData($form[0]);
    const photo = $('#photo')[0].files[0];
    if (photo) fd.append('photo', photo);
    if ($('#remove_photo').is(':checked')) fd.append('remove_photo', 1);

    $('#profile_save_btn').prop('disabled', true);
    ripsAjax(RIPS_BASE + '/profile', 'POST', fd, function () {
        showToast('저장되었습니다.', 'success');
        setTimeout(function () { location.reload(); }, 600);
    }).always(function () { $('#profile_save_btn').prop('disabled', false); });
}

function changePassword() {
    const data = {
        current_password:          $('#current_password').val(),
        new_password:              $('#new_password').val(),
        new_password_confirmation: $('#new_password_confirmation').val(),
    };
    if (!data.current_password) return showToast('현재 비밀번호를 입력해주세요.', 'error');
    if (data.new_password.length < 6) return showToast('새 비밀번호는 6자 이상 입력해주세요.', 'error');
    if (data.new_password !== data.new_password_confirmation) return showToast('새 비밀번호와 비밀번호 확인이 일치하지 않습니다.', 'error');

    ripsAjax(RIPS_BASE + '/profile/password', 'POST', data, function () {
        $('#current_password, #new_password, #new_password_confirmation').val('');
        showToast('비밀번호가 변경되었습니다.', 'success');
    });
}
</script>
