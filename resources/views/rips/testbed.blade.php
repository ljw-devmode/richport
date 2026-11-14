@php
	$menu = 'testbed';
	$statuses = \App\Models\Testbedinquire::STATUSES;
@endphp
@include('rips.header._header')
<div class="rp-page">
	<div class="rp-page-head">
		<div>
			<h3>테스트베드 신청</h3>
			<p class="rp-desc">리치포트 신상품에 신청한 테스트베드 내역과 처리 상태를 확인합니다. 진행완료된 상품은 설문조사 결과를 볼 수 있습니다.</p>
		</div>
		<div class="rp-actions">
			<a href="{{ route('rips.richport', ['rid' => $partner->rips_id, 'new' => 1]) }}" class="btn btn-dark">신상품 보러가기</a>
		</div>
	</div>

	<div class="rp-box">
		<form method="GET" action="{{ route('rips.testbed', ['rid' => $partner->rips_id]) }}" class="rp-filter">
			<div class="rp-field">
				<p>처리상태</p>
				<select name="status" class="form-select">
					<option value="">전체</option>
					@foreach ($statuses as $key => $label)
						<option value="{{ $key }}" {{ (string) request('status') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
					@endforeach
				</select>
			</div>
			<div class="rp-field">
				<p>상품명</p>
				<input type="text" name="product_name" value="{{ request('product_name') }}" class="form-control" placeholder="상품명 검색">
			</div>
			<div class="rp-actions">
				<button type="submit" class="btn btn-primary w-20">검색</button>
				<a href="{{ route('rips.testbed', ['rid' => $partner->rips_id]) }}" class="btn btn-outline-secondary w-20">초기화</a>
			</div>
		</form>
	</div>

	<div class="rp-box">
		<div class="rp-box-title">
			<span>신청 목록 <span class="rp-muted text-sm font-normal">총 {{ number_format($data->total()) }}건</span></span>
		</div>
		<div class="rp-table-wrap">
			<table class="table table-bordered">
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="rp-nowrap">번호</th>
						<th class="rp-nowrap">신청일</th>
						<th class="rp-nowrap">신청상품</th>
						<th class="rp-nowrap">회사명</th>
						<th class="rp-nowrap">연락처</th>
						<th class="rp-nowrap">처리상태</th>
						<th class="rp-nowrap">처리완료일</th>
						<th class="rp-nowrap">피드백</th>
						<th class="rp-nowrap">상세</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $index => $item)
						<tr>
							<td>{{ $data->total() - ($data->currentPage() - 1) * $data->perPage() - $index }}</td>
							<td class="rp-nowrap">{{ $item->inquire_date }}</td>
							<td class="text-left">{{ $item->product?->product_name ?? '-' }}</td>
							<td>{{ $item->inquire_company }}</td>
							<td>{{ $item->inquire_hp }}</td>
							<td><span class="rp-badge testbed-{{ (int) $item->status }}">{{ $item->status_kr }}</span></td>
							<td class="rp-nowrap">{{ $item->complete_at ?: '-' }}</td>
							<td>@if ($item->feedback)<span class="rp-badge received">피드백 있음</span>@else<span class="rp-muted">-</span>@endif</td>
							<td>
								<button type="button" class="btn btn-sm btn-outline-secondary rp-nowrap" onclick="openTestbedDetail({{ $item->idx }})">상세</button>
									@if ((int) $item->status === \App\Models\Testbedinquire::STATUS_DONE && $item->types_idx)
										<a href="{{ route('rips.survey.detail', ['rid' => $partner->rips_id, 'idx' => $item->types_idx]) }}" class="btn btn-sm btn-primary rp-nowrap ml-1">설문결과</a>
									@endif
							</td>
						</tr>
					@empty
						<tr><td colspan="9" class="rp-muted">테스트베드 신청 내역이 없습니다.</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@include('rips.common._pagination', ['data' => $data])
	</div>

	<!-- 신청 상세 모달 -->
	<div id="testbed-detail-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">테스트베드 신청 상세</h2>
					<button onclick="modalClose('testbed-detail-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<ul class="rp-steps" id="testbed_steps">
						@foreach ($statuses as $key => $label)
							<li data-step="{{ $key }}">{{ $label }}</li>
						@endforeach
					</ul>
					<div class="rp-form-row"><p>신청상품</p><input type="text" id="detail_product_name" class="form-control" readonly></div>
					<div class="rp-form-row"><p>신청일</p><input type="text" id="detail_inquire_date" class="form-control" readonly></div>
					<div class="rp-form-row"><p>회사명</p><input type="text" id="detail_inquire_company" class="form-control" readonly></div>
					<div class="rp-form-row"><p>연락처</p><input type="text" id="detail_inquire_hp" class="form-control" readonly></div>
					<div class="rp-form-row"><p>주소</p><input type="text" id="detail_inquire_address" class="form-control" readonly></div>
					<div class="rp-form-row items-start"><p class="pt-2">요청 내용</p><textarea id="detail_inquire_contents" class="form-control h-32" readonly></textarea></div>
					<div class="rp-form-row"><p>확인일시</p><input type="text" id="detail_response_at" class="form-control" readonly></div>
					<div class="rp-form-row"><p>처리완료일</p><input type="text" id="detail_complete_at" class="form-control" readonly></div>
					<div class="rp-feedback">
						<p class="rp-feedback-title">리치포트 피드백 <span class="rp-muted text-xs font-normal" id="detail_feedback_at"></span></p>
						<div class="rp-feedback-body" id="detail_feedback"></div>
					</div>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('testbed-detail-modal')">닫기</button>
				</div>
			</div>
		</div>
	</div>
</div>
@include('rips.footer._footer')
<script>
function openTestbedDetail(idx) {
	ripsAjax(RIPS_BASE + '/testbed/' + idx, 'POST', {}, function (res) {
		const d = res.data;

		$('#testbed_steps li').each(function () {
			$(this).toggleClass('done', Number($(this).data('step')) <= d.status);
		});

		$('#detail_product_name').val(d.product_name);
		$('#detail_inquire_date').val(d.inquire_date || '');
		$('#detail_inquire_company').val(d.inquire_company || '');
		$('#detail_inquire_hp').val(d.inquire_hp || '');
		$('#detail_inquire_address').val(d.inquire_address || '');
		$('#detail_inquire_contents').val(d.inquire_contents || '');
		$('#detail_response_at').val(d.response_at || '리치포트 확인 전');
		$('#detail_complete_at').val(d.complete_at || '-');
		$('#detail_feedback').text(d.feedback || '아직 등록된 피드백이 없습니다.').toggleClass('rp-muted', !d.feedback);
		$('#detail_feedback_at').text(d.feedback_at ? '· ' + d.feedback_at : '');

		modalOpen('testbed-detail-modal');
	});
}
</script>
