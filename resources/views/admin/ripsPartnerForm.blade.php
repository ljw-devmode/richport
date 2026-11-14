{{--
	RIPS 업체 등록 / 업체 정보 수정 공통 폼 (RIPS > 설정 > 내 정보 수정 과 같은 항목)
	$partner   : 수정할 업체 (등록이면 null)
	$ripsTypes : 업체구분, $cities : 시
	$canSave   : 저장 권한
--}}
@php
	$p        = $partner ?? null;
	$isEdit   = (bool) $p;
	$photoUrl = $p && $p->photo_up_name ? Storage::disk('public')->url($p->photo_up_name) : null;
	$saveUrl  = $isEdit ? route('admin.ripsPartner.info.save', ['idx' => $p->idx]) : route('admin.ripsPartner.regist');
	// 업체구분 → 국가 기본값 (한국 제조/수출 : korea, 베트남 유통/수입 : vietnam)
	$typeCountry = [1 => 'korea', 2 => 'korea', 3 => 'vietnam', 4 => 'vietnam'];
@endphp
<style>
	.rf-box {padding:24px; border:1px solid #e2e8f0; border-radius:8px; background:#fff; }
	.rf-box + .rf-box {margin-top:20px; }
	.rf-title {display:flex; align-items:center; justify-content:space-between; margin-bottom:6px; font-size:16px; font-weight:700; }
	.rf-title small {font-size:12px; font-weight:400; color:#64748b; }
	.rf-grid {display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:0 28px; }
	.rf-row {display:flex; align-items:center; gap:8px; margin-top:12px; }
	.rf-row > p {flex-shrink:0; width:120px; font-weight:500; }
	.rf-row .rf-inline {display:flex; align-items:center; gap:8px; width:100%; }
	.rf-company {display:flex; gap:28px; align-items:flex-start; }
	.rf-photo {flex-shrink:0; width:180px; text-align:center; }
	.rf-photo .photo {display:flex; align-items:center; justify-content:center; width:180px; height:180px; border:1px solid #e2e8f0; border-radius:10px; background:#f8fafc center/cover no-repeat; color:#94a3b8; font-size:12px; }
	.rf-help {margin-top:6px; font-size:12px; color:#64748b; }
	@media (max-width:1200px) { .rf-grid {grid-template-columns:minmax(0, 1fr); } .rf-company {flex-direction:column; } }
</style>

<form id="rips-partner-form" onsubmit="return false;" autocomplete="off">
	<!-- 회사정보 -->
	<div class="rf-box intro-y">
		<div class="rf-title">회사정보 <small>업체구분 · 회사명은 업체 화면(내 정보 수정)에서 수정할 수 없습니다.</small></div>
		<div class="rf-company">
			<div class="rf-photo">
				<div class="photo" id="rf_photo_preview" style="{{ $photoUrl ? 'background-image:url('.$photoUrl.')' : '' }}">@unless ($photoUrl)업체 사진@endunless</div>
				<label class="btn btn-sm btn-outline-secondary mt-2 w-full" style="cursor:pointer;">
					사진 선택 <input type="file" id="rf_photo" accept=".jpg,.jpeg,.png,.webp" class="hidden">
				</label>
				@if ($photoUrl)
					<div class="form-check mt-2 justify-center">
						<input id="rf_remove_photo" class="form-check-input" type="checkbox" value="1">
						<label class="form-check-label text-xs" for="rf_remove_photo">사진 삭제</label>
					</div>
				@endif
				<p class="rf-help">jpg, png, webp · 5MB 이하</p>
			</div>

			<div class="flex-1 min-w-0">
				<div class="rf-grid">
					<div class="rf-row">
						<p>업체구분 <span class="text-danger">*</span></p>
						<select name="rips_type_idx" id="rf_rips_type_idx" class="form-select">
							<option value="">선택</option>
							@foreach ($ripsTypes as $type)
								<option value="{{ $type->idx }}" {{ $p && $p->rips_type_idx == $type->idx ? 'selected' : '' }}>{{ $type->subject }}</option>
							@endforeach
						</select>
					</div>
					<div class="rf-row">
						<p>국가 <span class="text-danger">*</span></p>
						<select name="country" id="rf_country" class="form-select">
							<option value="vietnam" {{ !$p || $p->country === 'vietnam' ? 'selected' : '' }}>베트남</option>
							<option value="korea" {{ $p && $p->country === 'korea' ? 'selected' : '' }}>한국</option>
						</select>
					</div>
					<div class="rf-row">
						<p>회사명 <span class="text-danger">*</span></p>
						<input type="text" name="partner_name" class="form-control" maxlength="200" value="{{ $p->partner_name ?? '' }}" placeholder="예: 베트남 롯데마트">
					</div>
					<div class="rf-row">
						<p>매장명</p>
						<input type="text" name="store_name" class="form-control" maxlength="200" value="{{ $p->store_name ?? '' }}" placeholder="발주서에 표시될 매장명 (예: 남사이공점)">
					</div>
					<div class="rf-row">
						<p>지역 <span class="text-danger" id="rf_region_required">*</span></p>
						<div class="rf-inline">
							<select name="cities_idx" id="rf_cities_idx" class="form-select">
								<option value="">시 선택</option>
								@foreach ($cities as $city)
									<option value="{{ $city->idx }}" {{ $p && $p->cities_idx == $city->idx ? 'selected' : '' }}>{{ $city->name_kr }}</option>
								@endforeach
							</select>
							<select name="districts_idx" id="rf_districts_idx" class="form-select" data-value="{{ $p->districts_idx ?? '' }}">
								<option value="">구 선택</option>
							</select>
						</div>
					</div>
					<div class="rf-row">
						<p>회사 주소</p>
						<input type="text" name="company_address" class="form-control" maxlength="255" value="{{ $p->company_address ?? '' }}" placeholder="회사 주소">
					</div>
					<div class="rf-row">
						<p>회사 대표전화</p>
						<input type="text" name="company_tel" class="form-control" maxlength="50" value="{{ $p->company_tel ?? '' }}" placeholder="예: 028-3776-1234">
					</div>
					<div class="rf-row">
						<p>대표자명</p>
						<input type="text" name="ceo_name" class="form-control" maxlength="100" value="{{ $p->ceo_name ?? '' }}" placeholder="대표자명">
					</div>
					<div class="rf-row">
						<p>통관고유번호</p>
						<input type="text" name="customs_code" class="form-control" maxlength="50" value="{{ $p->customs_code ?? '' }}" placeholder="영문, 숫자, -">
					</div>
					<div class="rf-row">
						<p>사업자번호</p>
						<input type="text" name="biz_no" class="form-control" maxlength="50" value="{{ $p->biz_no ?? '' }}" placeholder="영문, 숫자, -">
					</div>
				</div>
				<p class="rf-help">· 지역은 베트남 업체만 필수입니다. (RIPS 발주서에 표시) · 통관고유번호 · 사업자번호는 업체가 한 번 입력하면 업체 화면에서는 수정할 수 없고, 관리자는 여기서 수정할 수 있습니다.</p>
			</div>
		</div>
	</div>

	<!-- 담당자정보 -->
	<div class="rf-box intro-y">
		<div class="rf-title">담당자정보 <small>RIPS 이용신청의 담당자명 · 전화번호를 참고해 입력하고, 이메일은 계정을 만들 때 받아 입력합니다.</small></div>
		<div class="rf-grid">
			<div class="rf-row">
				<p>담당자명 <span class="text-danger">*</span></p>
				<input type="text" name="manager_name" class="form-control" maxlength="100" value="{{ $p->manager_name ?? '' }}" placeholder="담당자명">
			</div>
			<div class="rf-row">
				<p>담당자 연락처 <span class="text-danger">*</span></p>
				<input type="text" name="manager_hp" class="form-control" maxlength="50" value="{{ $p->manager_hp ?? '' }}" placeholder="예: 090-123-4567">
			</div>
			<div class="rf-row">
				<p>담당자 이메일 <span class="text-danger">*</span></p>
				<input type="email" name="manager_email" class="form-control" maxlength="200" value="{{ $p->manager_email ?? '' }}" placeholder="example@company.com">
			</div>
			<div class="rf-row">
				<p>담당업무</p>
				<input type="text" name="manager_task" class="form-control" maxlength="200" value="{{ $p->manager_task ?? '' }}" placeholder="예: 발주 · 재고 관리">
			</div>
		</div>
	</div>

	<!-- 계정정보 -->
	<div class="rf-box intro-y">
		<div class="rf-title">계정정보 <small>업체는 /rips/login 에서 아이디 · 비밀번호로 로그인합니다. (접속주소 : /rips/아이디)</small></div>
		<div class="rf-grid">
			<div class="rf-row">
				<p>RIPS 사용</p>
				<div class="form-check form-switch">
					<input id="rf_rips_use" name="rips_use" value="1" class="form-check-input" type="checkbox" {{ !$p || $p->rips_use ? 'checked' : '' }}>
					<label class="form-check-label" for="rf_rips_use">사용 (해제하면 로그인 불가)</label>
				</div>
			</div>
			<div class="rf-row">
				<p>아이디 <span class="text-danger">*</span></p>
				<input type="text" name="rips_id" class="form-control" maxlength="50" value="{{ $p->rips_id ?? '' }}" placeholder="영문, 숫자, -, _ 4~50자">
			</div>
			<div class="rf-row">
				<p>비밀번호 @unless ($isEdit)<span class="text-danger">*</span>@endunless</p>
				<input type="password" name="rips_pw" class="form-control" autocomplete="new-password" placeholder="{{ $isEdit ? '변경할 때만 입력 (6자 이상)' : '6자 이상' }}">
			</div>
			<div class="rf-row">
				<p>비밀번호 확인 @unless ($isEdit)<span class="text-danger">*</span>@endunless</p>
				<input type="password" name="rips_pw_confirmation" class="form-control" autocomplete="new-password" placeholder="비밀번호를 한 번 더 입력">
			</div>
		</div>
	</div>

	@if ($canSave)
	<div class="flex justify-center gap-3 mt-6 mb-10">
		@unless ($isEdit)<a href="/admin/ripsPartner" class="btn btn-outline-secondary w-24">취소</a>@endunless
		<button type="button" class="btn btn-primary w-32" id="rf_save_btn" onclick="saveRipsPartner()">{{ $isEdit ? '업체 정보 저장' : '업체 등록' }}</button>
	</div>
	@endif
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const TYPE_COUNTRY = @json($typeCountry);

    // 업체구분 → 국가 기본값, 국가 → 지역 필수 표시
    $('#rf_rips_type_idx').on('change', function () {
        if (TYPE_COUNTRY[this.value]) $('#rf_country').val(TYPE_COUNTRY[this.value]).trigger('change');
    });
    $('#rf_country').on('change', function () {
        $('#rf_region_required').toggle(this.value === 'vietnam');
    }).trigger('change');

    // 시 → 구
    function loadDistricts(selected) {
        const city = $('#rf_cities_idx').val();
        const $sel = $('#rf_districts_idx').empty().append('<option value="">구 선택</option>');
        if (!city) return;
        $.getJSON('/admin/districts/' + city, function (rows) {
            rows.forEach(d => $sel.append($('<option>').val(d.idx).text(d.name_kr)));
            if (selected) $sel.val(String(selected));
        });
    }
    $('#rf_cities_idx').on('change', function () { loadDistricts(); });
    loadDistricts($('#rf_districts_idx').data('value'));

    // 사진 미리보기
    $('#rf_photo').on('change', function () {
        const file = this.files[0];
        if (!file) return;
        if (!/\.(jpe?g|png|webp)$/i.test(file.name)) { this.value = ''; return showToast('jpg, png, webp 이미지만 올릴 수 있습니다.', 'error'); }
        if (file.size > 5 * 1024 * 1024) { this.value = ''; return showToast('5MB 이하 이미지만 올릴 수 있습니다.', 'error'); }
        const reader = new FileReader();
        reader.onload = e => $('#rf_photo_preview').css('background-image', 'url(' + e.target.result + ')').text('');
        reader.readAsDataURL(file);
        $('#rf_remove_photo').prop('checked', false);
    });
});

function saveRipsPartner() {
    const $f  = $('#rips-partner-form');
    const val = name => ($f.find('[name="' + name + '"]').val() || '').trim();
    const isEdit = @json($isEdit);

    if (!val('rips_type_idx')) return showToast('업체구분을 선택해주세요.', 'error');
    if (!val('partner_name'))  return showToast('회사명을 입력해주세요.', 'error');
    if (val('country') === 'vietnam' && (!val('cities_idx') || !val('districts_idx'))) return showToast('지역(시 / 구)을 선택해주세요.', 'error');
    if (!val('manager_name'))  return showToast('담당자명을 입력해주세요.', 'error');
    if (!val('manager_hp'))    return showToast('담당자 연락처를 입력해주세요.', 'error');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val('manager_email'))) return showToast('담당자 이메일을 올바르게 입력해주세요.', 'error');
    if (!/^[A-Za-z0-9_\-]{4,50}$/.test(val('rips_id'))) return showToast('아이디는 영문, 숫자, -, _ 4~50자로 입력해주세요.', 'error');

    const pw = $f.find('[name="rips_pw"]').val();
    if (!isEdit || pw) {
        if (pw.length < 6) return showToast('비밀번호는 6자 이상 입력해주세요.', 'error');
        if (pw !== $f.find('[name="rips_pw_confirmation"]').val()) return showToast('비밀번호와 비밀번호 확인이 일치하지 않습니다.', 'error');
    }
    if (!confirm(isEdit ? '업체 정보를 저장하시겠습니까?' : 'RIPS 업체를 등록하시겠습니까?')) return;

    const fd = new FormData($f[0]);
    const photo = $('#rf_photo')[0].files[0];
    if (photo) fd.append('photo', photo);
    if ($('#rf_remove_photo').is(':checked')) fd.append('remove_photo', 1);

    $('#rf_save_btn').prop('disabled', true);
    $.ajax({
        url: @json($saveUrl),
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status !== 'success') return showToast(res.message || '저장에 실패했습니다.', 'error');
            showToast(isEdit ? '저장되었습니다.' : '등록되었습니다.', 'success');
            setTimeout(function () { location.href = res.redirect || location.href; }, 600);
        },
        error: function (xhr) {
            showToast(xhr.responseJSON?.message || '오류가 발생했습니다.', 'error');
            console.error(xhr.responseText);
        },
        complete: function () { $('#rf_save_btn').prop('disabled', false); }
    });
}
</script>
