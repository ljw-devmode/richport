@php
	$menu = 'survey';
@endphp
@include('rips.header._header')
<div class="rp-page">
	<div class="rp-page-head">
		<div>
			<h3>설문조사 결과</h3>
			<p class="rp-desc">테스트베드 진행이 완료된 상품의 소비자 설문조사 결과와 매장 판매 현황을 확인합니다.</p>
		</div>
		<div class="rp-actions">
			<a href="{{ route('rips.testbed', ['rid' => $partner->rips_id]) }}" class="btn btn-outline-secondary">테스트베드 신청 내역</a>
		</div>
	</div>

	<div class="rp-box">
		<form method="GET" action="{{ route('rips.survey', ['rid' => $partner->rips_id]) }}" class="rp-filter">
			<div class="rp-field">
				<p>상품명</p>
				<input type="text" name="product_name" value="{{ request('product_name') }}" class="form-control" placeholder="상품명 검색">
			</div>
			<div class="rp-actions">
				<button type="submit" class="btn btn-primary w-20">검색</button>
				<a href="{{ route('rips.survey', ['rid' => $partner->rips_id]) }}" class="btn btn-outline-secondary w-20">초기화</a>
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
						<th class="rp-nowrap">이미지</th>
						<th class="rp-nowrap">상품명</th>
						<th class="rp-nowrap">업체명</th>
						<th class="rp-nowrap">테스트베드 완료일</th>
						<th class="rp-nowrap">설문 수</th>
						<th class="rp-nowrap">응답 수</th>
						<th class="rp-nowrap">최근 응답일</th>
						<th class="rp-nowrap">판매수량</th>
						<th class="rp-nowrap">판매금액</th>
						<th class="rp-nowrap">결과</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@forelse ($data as $item)
						<tr>
							<td>
								@if ($item->pc_up_name)
									<img src="{{ Storage::disk('public')->url($item->pc_up_name) }}" alt="" class="rp-thumb">
								@else
									<span class="rp-thumb is-empty"><i data-lucide="image" class="w-5 h-5"></i></span>
								@endif
							</td>
							<td class="text-left">{{ $item->product_name }}</td>
							<td>{{ $item->partners->partner_name ?? '-' }}</td>
							<td class="rp-nowrap">{{ !empty($completeDates[$item->idx]) ? \Carbon\Carbon::parse($completeDates[$item->idx])->format('Y-m-d') : '-' }}</td>
							<td class="rp-num">{{ number_format($item->surveys_count) }}</td>
							<td class="rp-num">{{ number_format($item->response_count) }}</td>
							<td class="rp-nowrap">{{ $item->last_response_at ? \Carbon\Carbon::parse($item->last_response_at)->format('Y-m-d') : '-' }}</td>
							@if (isset($salesMap[$item->idx]))
								<td class="rp-num">{{ number_format($salesMap[$item->idx]->qty) }}</td>
								<td class="rp-num">{{ number_format($salesMap[$item->idx]->amount) }}</td>
							@else
								<td colspan="2" class="rp-muted text-xs" title="관리자 > RIPS 업체 > 상품 매핑에서 업체 상품과 연결하면 판매 데이터가 집계됩니다.">판매 데이터 없음</td>
							@endif
							<td>
								<a href="{{ route('rips.survey.detail', ['rid' => $partner->rips_id, 'idx' => $item->idx]) }}" class="btn btn-sm btn-primary rp-nowrap">결과보기</a>
								@if ($item->surveys_count == 0)
									<div class="rp-muted text-xs mt-1">등록된 설문 없음</div>
								@endif
							</td>
						</tr>
					@empty
						<tr><td colspan="10" class="rp-muted">테스트베드 진행이 완료된 상품이 없습니다.<br>테스트베드가 진행완료되면 이곳에서 설문조사 결과를 확인할 수 있습니다.</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@include('rips.common._pagination', ['data' => $data])
	</div>
</div>
@include('rips.footer._footer')
