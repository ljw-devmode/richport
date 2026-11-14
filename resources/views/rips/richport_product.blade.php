@php
	$menu = 'richport';
@endphp
@include('rips.header._header')
<div class="rp-page">
	<div class="rp-page-head">
		<div>
			<h3>리치포트 상품</h3>
			<p class="rp-desc">리치포트가 소개하는 한국 상품입니다. 신상품은 테스트베드를 신청할 수 있습니다.</p>
		</div>
		<div class="rp-actions">
			<a href="{{ route('rips.testbed', ['rid' => $partner->rips_id]) }}" class="btn btn-outline-secondary">테스트베드 신청 내역</a>
		</div>
	</div>

	<div class="rp-box">
		<form method="GET" action="{{ route('rips.richport', ['rid' => $partner->rips_id]) }}" class="rp-filter">
			<div class="rp-field">
				<p>카테고리</p>
				<select name="forms_idx" class="form-select">
					<option value="">전체</option>
					@foreach ($categories as $category)
						<option value="{{ $category->idx }}" {{ (string) request('forms_idx') === (string) $category->idx ? 'selected' : '' }}>{{ $category->subject }}</option>
					@endforeach
				</select>
			</div>
			<div class="rp-field">
				<p>구분</p>
				<select name="new" class="form-select">
					<option value="">전체</option>
					<option value="1" {{ request('new') === '1' ? 'selected' : '' }}>신상품</option>
				</select>
			</div>
			<div class="rp-field">
				<p>상품명</p>
				<input type="text" name="product_name" value="{{ request('product_name') }}" class="form-control" placeholder="상품명 검색">
			</div>
			<div class="rp-actions">
				<button type="submit" class="btn btn-primary w-20">검색</button>
				<a href="{{ route('rips.richport', ['rid' => $partner->rips_id]) }}" class="btn btn-outline-secondary w-20">초기화</a>
			</div>
		</form>
	</div>

	<div class="rp-box">
		<div class="rp-box-title">
			<span>상품 목록 <span class="rp-muted text-sm font-normal">총 {{ number_format($data->total()) }}건</span></span>
		</div>
		<div class="rp-table-wrap">
			<table class="table table-bordered">
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="rp-nowrap" style="width:110px;">이미지</th>
						<th class="rp-nowrap">카테고리</th>
						<th class="rp-nowrap">업체명</th>
						<th class="rp-nowrap">상품명</th>
						<th class="rp-nowrap">구분</th>
						<th class="rp-nowrap" style="width:230px;">관리</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $item)
						@php $isNew = (string) $item->new === '1'; @endphp
						<tr>
							<td>
								@if ($item->pc_up_name)
									<img src="{{ Storage::disk('public')->url($item->pc_up_name) }}" alt="" class="rp-thumb">
								@else
									<span class="rp-thumb is-empty"><i data-lucide="image" class="w-5 h-5"></i></span>
								@endif
							</td>
							<td>{{ $item->forms?->subject ?? '-' }}</td>
							<td>{{ $item->partners?->partner_name ?? '-' }}</td>
							<td class="text-left">{{ $item->product_name }}</td>
							<td>
								@if ($isNew)
									<span class="rp-badge new">신상품</span>
								@else
									<span class="rp-muted">-</span>
								@endif
							</td>
							<td>
								<div class="flex items-center justify-center gap-2">
									<button type="button" class="btn btn-sm btn-outline-secondary rp-nowrap" onclick="openProductDetail({{ $item->idx }})">자세히보기</button>
									@if ($isNew)
										<button type="button" class="btn btn-sm btn-primary rp-nowrap" onclick="openTestbedForm({{ $item->idx }}, @js($item->product_name))">테스트베드 신청</button>
									@endif
								</div>
								@if (in_array($item->idx, $appliedIdxs))
									<div class="rp-muted text-xs mt-1">신청 이력 있음</div>
								@endif
							</td>
						</tr>
					@empty
						<tr><td colspan="6" class="rp-muted">등록된 상품이 없습니다.</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@include('rips.common._pagination', ['data' => $data])
	</div>

	<!-- 상품 상세 모달 -->
	<div id="richport-detail-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">상품 상세</h2>
					<button onclick="modalClose('richport-detail-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body" id="richport_detail_body"></div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('richport-detail-modal')">닫기</button>
					<button type="button" class="btn btn-primary" id="richport_detail_testbed_btn" style="display:none;">테스트베드 신청</button>
				</div>
			</div>
		</div>
	</div>

	<!-- 테스트베드 신청 모달 -->
	<div id="testbed-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">테스트베드 이용 신청</h2>
					<button onclick="modalClose('testbed-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<p class="rp-help mt-0">아래 내용을 작성해주시면 리치포트 담당자가 확인 후 연락드립니다. 신청 내역은 [테스트베드 신청] 메뉴에서 확인할 수 있습니다.</p>
					<input type="hidden" id="testbed_product_idx" value="">
					<div class="rp-form-row">
						<p>상품명</p>
						<input type="text" id="testbed_product_name" class="form-control" readonly>
					</div>
					<div class="rp-form-row">
						<p>회사명 <span class="text-danger">*</span></p>
						<input type="text" id="testbed_inquire_company" class="form-control" maxlength="200">
					</div>
					<div class="rp-form-row">
						<p>연락처 <span class="text-danger">*</span></p>
						<input type="text" id="testbed_inquire_hp" class="form-control" maxlength="50" placeholder="담당자 연락처">
					</div>
					<div class="rp-form-row">
						<p>주소 <span class="text-danger">*</span></p>
						<input type="text" id="testbed_inquire_address" class="form-control" maxlength="255" placeholder="테스트베드 진행 매장 주소">
					</div>
					<div class="rp-form-row items-start">
						<p class="pt-2">요청 내용 <span class="text-danger">*</span></p>
						<textarea id="testbed_inquire_contents" class="form-control h-32" placeholder="요청 사항을 입력해주세요."></textarea>
					</div>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('testbed-modal')">취소</button>
					<button type="button" class="btn btn-primary" id="testbed_submit_btn" onclick="submitTestbed()">테스트베드 신청하기</button>
				</div>
			</div>
		</div>
	</div>
</div>
@include('rips.footer._footer')
<script>
const DEFAULT_COMPANY = @json(trim($partner->partner_name.($partner->store_name ? ' '.$partner->store_name : '')));

// 상품 상세
function openProductDetail(idx) {
	ripsAjax(RIPS_BASE + '/richport/' + idx, 'POST', {}, function (res) {
		const d = res.data;

		const images = [];
		if (d.thumbnail) images.push(d.thumbnail);
		(d.files || []).forEach(url => images.push(url));

		let html = '<div class="rp-detail-head">'
			+ '<div class="rp-muted text-sm">' + escapeHtml(d.category || '-') + (d.partner_name ? ' · ' + escapeHtml(d.partner_name) : '') + '</div>'
			+ '<div class="flex items-center gap-2 mt-1"><h3 class="text-xl font-bold">' + escapeHtml(d.product_name) + '</h3>'
			+ (d.is_new ? '<span class="rp-badge new">신상품</span>' : '') + '</div>'
			+ '</div>';

		html += '<div class="rp-detail-contents">' + (d.contents ? escapeHtml(d.contents) : '<span class="rp-muted">등록된 소개글이 없습니다.</span>') + '</div>';

		if (images.length) {
			html += '<div class="rp-detail-images">';
			images.forEach(url => { html += '<img src="' + escapeHtml(url) + '" alt="">'; });
			html += '</div>';
		}

		$('#richport_detail_body').html(html);

		const $btn = $('#richport_detail_testbed_btn');
		if (d.is_new) {
			$btn.show().off('click').on('click', function () {
				modalClose('richport-detail-modal');
				openTestbedForm(d.idx, d.product_name);
			});
		} else {
			$btn.hide().off('click');
		}

		modalOpen('richport-detail-modal');
	});
}

// 대시보드 신상품에서 들어온 경우 상세 바로 열기 (?view=상품idx)
$(function () {
	const viewIdx = new URLSearchParams(location.search).get('view');
	if (viewIdx && /^\d+$/.test(viewIdx)) openProductDetail(viewIdx);
});

// 테스트베드 신청 폼
function openTestbedForm(idx, productName) {
	$('#testbed_product_idx').val(idx);
	$('#testbed_product_name').val(productName);
	if (!$('#testbed_inquire_company').val()) $('#testbed_inquire_company').val(DEFAULT_COMPANY);
	$('#testbed_inquire_contents').val('');
	modalOpen('testbed-modal');
}

function submitTestbed() {
	const $btn = $('#testbed_submit_btn');
	if ($btn.prop('disabled')) return;

	const data = {
		product_idx      : $('#testbed_product_idx').val(),
		inquire_company  : $('#testbed_inquire_company').val().trim(),
		inquire_hp       : $('#testbed_inquire_hp').val().trim(),
		inquire_address  : $('#testbed_inquire_address').val().trim(),
		inquire_contents : $('#testbed_inquire_contents').val().trim(),
	};

	if (!data.inquire_company)  return showToast('회사명을 입력해주세요.', 'error');
	if (!data.inquire_hp)       return showToast('연락처를 입력해주세요.', 'error');
	if (!data.inquire_address)  return showToast('주소를 입력해주세요.', 'error');
	if (!data.inquire_contents) return showToast('요청 내용을 입력해주세요.', 'error');

	if (!confirm('테스트베드를 신청하시겠습니까?')) return;

	$btn.prop('disabled', true);
	ripsAjax(RIPS_BASE + '/richport/testbed', 'POST', data, function (res) {
		alert('테스트베드 신청이 접수되었습니다.\n테스트베드 신청 메뉴에서 처리 상태를 확인할 수 있습니다.');
		window.location.href = res.redirect;
	}).always(function () {
		$btn.prop('disabled', false);
	});
}
</script>
