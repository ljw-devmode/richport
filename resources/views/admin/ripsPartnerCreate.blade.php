@php
	$header_skip = false;
	$depth1_num = '7';
	$depth2_num = '6';
@endphp
@include('admin.header._header')
<div class="mt-6 px-5">
	<div class="flex items-center justify-between gap-3 flex-wrap">
		<div>
			<h3 class="intro-y text-xl font-bold">RIPS 업체 등록</h3>
			<p class="mt-1 text-slate-500">RIPS 이용신청 내용을 참고해 업체와 로그인 계정을 등록합니다. 등록한 정보는 업체가 RIPS &gt; 설정 &gt; 내 정보 수정에서 확인 · 수정합니다.</p>
		</div>
		<div class="flex gap-2">
			@if (auth()->user()->hasPerm('inquireRips.view'))
				<a href="/admin/inquireRips" target="_blank" class="btn btn-outline-secondary">RIPS 이용신청 보기</a>
			@endif
			<a href="/admin/ripsPartner" class="btn btn-outline-secondary">목록으로</a>
		</div>
	</div>

	<div class="mt-6">
		@include('admin.ripsPartnerForm', ['partner' => null, 'canSave' => true])
	</div>
</div>
@include('admin.footer._footer')
