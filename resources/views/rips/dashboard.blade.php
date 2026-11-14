@php
	$menu = 'dashboard';
	$maxDaily = max(1, collect($daily)->max('amount'));
@endphp
@include('rips.header._header')
<div class="rp-page">
	<div class="rp-page-head">
		<div>
			<h3>대시보드</h3>
			<p class="rp-desc">{{ $partner->partner_name }}의 재고·판매·발주 현황입니다.</p>
		</div>
		<div class="rp-actions">
			<a href="{{ route('rips.sales', ['rid' => $partner->rips_id]) }}" class="btn btn-outline-secondary">판매 데이터 등록</a>
			<a href="{{ route('rips.order.create', ['rid' => $partner->rips_id]) }}" class="btn btn-primary">발주하기</a>
		</div>
	</div>

	<div class="rp-stats">
		<div class="rp-stat">
			<p class="label">등록 상품</p>
			<p class="value">{{ number_format($productCount) }}<small>개</small></p>
		</div>
		<div class="rp-stat {{ $soldOutCount > 0 ? 'is-warn' : '' }}">
			<p class="label">재고 소진 상품 (잔여수량 0 이하)</p>
			<p class="value">{{ number_format($soldOutCount) }}<small>개</small></p>
		</div>
		<div class="rp-stat">
			<p class="label">최근 30일 판매</p>
			<p class="value">{{ number_format($sales30->amount) }}<small> / {{ number_format($sales30->qty) }}개</small></p>
		</div>
		<div class="rp-stat">
			<p class="label">진행중 발주</p>
			<p class="value">{{ number_format($activeOrderCount) }}<small>건</small></p>
		</div>
	</div>

	<div class="rp-row">
		<div class="rp-box">
			<div class="rp-box-title">최근 14일 판매금액</div>
			<div class="rp-bars">
				@foreach ($daily as $day)
					<div class="bar" title="{{ $day['date'] }} : {{ number_format($day['amount']) }} ({{ number_format($day['qty']) }}개)">
						<span style="height: {{ round($day['amount'] / $maxDaily * 100) }}%;"></span>
						<em>{{ \Carbon\Carbon::parse($day['date'])->format('m/d') }}</em>
					</div>
				@endforeach
			</div>
		</div>
		<div class="rp-box">
			<div class="rp-box-title">
				<span>재고소진 임박 <span class="rp-muted text-xs font-normal">소진 예상일이 가까운 5개</span></span>
				<a href="{{ route('rips.product', ['rid' => $partner->rips_id]) }}" class="text-xs rp-muted">더보기</a>
			</div>
			@if ($urgentProducts->isEmpty())
				<div class="rp-ai-empty">
					<i data-lucide="package-check" class="w-6 h-6"></i>
					<p>소진 예상일을 계산할 수 있는 상품이 없습니다.<br>판매 데이터를 등록하면 자동으로 계산됩니다.</p>
				</div>
			@else
				<table class="table table-bordered table-sm">
					<thead class="bg-slate-50 text-center">
						<tr>
							<th>상품명</th>
							<th>잔여<br>수량</th>
							<th>재고<br>소진일자</th>
							<th>발주<br>추천수량</th>
							<th class="rp-nowrap">발주</th>
						</tr>
					</thead>
					<tbody class="text-center">
						@foreach ($urgentProducts as $item)
							@php
								$daysLeft = (int) \Carbon\Carbon::today()->diffInDays($item->expect_out_date, false);
							@endphp
							<tr>
								<td class="text-left" style="min-width:120px;">{{ $item->product_name }}</td>
								<td class="rp-num {{ $item->stock_qty <= 0 ? 'text-danger' : '' }}">{{ number_format($item->stock_qty) }}</td>
								<td class="rp-nowrap {{ $daysLeft <= 7 ? 'text-danger' : '' }}">
									{{ $item->expect_out_date->format('Y-m-d') }}
									<div class="text-xs {{ $daysLeft <= 7 ? 'text-danger' : 'rp-muted' }}">{{ $daysLeft <= 0 ? '소진' : 'D-'.$daysLeft }}</div>
								</td>
								<td class="rp-num">{!! $item->recommend_qty ? number_format($item->recommend_qty) : '<span class="rp-muted">발주 불필요</span>' !!}</td>
								<td><a href="{{ route('rips.order.create', ['rid' => $partner->rips_id, 'product' => $item->idx]) }}" class="btn btn-sm btn-primary rp-nowrap">발주하기</a></td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<p class="rp-help">· AI 예측값 기준이며, AI 값이 없는 상품은 최근 판매 데이터 기준 단순 계산값입니다.</p>
			@endif
		</div>
	</div>

	<div class="rp-row">
		<div class="rp-box">
			<div class="rp-box-title">
				최근 30일 판매 상위 상품
				<a href="{{ route('rips.sales', ['rid' => $partner->rips_id]) }}" class="text-xs rp-muted">더보기</a>
			</div>
			<table class="table table-bordered">
				<thead class="bg-slate-50 text-center">
					<tr>
						<th>순위</th>
						<th>상품명</th>
						<th>판매수량</th>
						<th>판매금액</th>
					</tr>
				</thead>
				<tbody class="text-center">
					@forelse ($topProducts as $i => $row)
						<tr>
							<td>{{ $i + 1 }}</td>
							<td class="text-left">{{ $row->product->product_name ?? '-' }}</td>
							<td class="rp-num">{{ number_format($row->qty) }}</td>
							<td class="rp-num">{{ number_format($row->amount) }}</td>
						</tr>
					@empty
						<tr><td colspan="4" class="rp-muted">판매 데이터가 없습니다.</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div class="rp-box">
			<div class="rp-box-title">
				최근 발주
				<a href="{{ route('rips.order', ['rid' => $partner->rips_id]) }}" class="text-xs rp-muted">더보기</a>
			</div>
			<table class="table table-bordered">
				<thead class="bg-slate-50 text-center">
					<tr>
						<th>발주일</th>
						<th>발주번호</th>
						<th>발주총액</th>
						<th>상태</th>
					</tr>
				</thead>
				<tbody class="text-center">
					@forelse ($recentOrders as $order)
						<tr onclick="location.href='{{ route('rips.order.detail', ['rid' => $partner->rips_id, 'idx' => $order->idx]) }}'" class="hover:bg-slate-100" style="cursor:pointer;">
							<td>{{ $order->order_date->format('Y-m-d') }}</td>
							<td>{{ $order->order_no }}</td>
							<td class="rp-num">{{ number_format($order->total_amount) }}</td>
							<td><span class="rp-badge {{ $order->status }}">{{ $order->status_text }}</span></td>
						</tr>
					@empty
						<tr><td colspan="4" class="rp-muted">발주 내역이 없습니다.</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
	<div class="rp-row">
		<div class="rp-box">
			<div class="rp-box-title">
				리치포트 신상품
				<a href="{{ route('rips.richport', ['rid' => $partner->rips_id, 'new' => 1]) }}" class="text-xs rp-muted">더보기</a>
			</div>
			@if ($newProducts->isEmpty())
				<div class="rp-ai-empty"><p>등록된 신상품이 없습니다.</p></div>
			@else
				<div class="rp-new-list">
					@foreach ($newProducts as $item)
						<a href="{{ route('rips.richport', ['rid' => $partner->rips_id, 'view' => $item->idx]) }}" class="rp-new-item">
							@if ($item->pc_up_name)
								<img src="{{ Storage::disk('public')->url($item->pc_up_name) }}" alt="">
							@else
								<span class="rp-new-noimg"><i data-lucide="image" class="w-6 h-6"></i></span>
							@endif
							<span class="rp-muted text-xs">{{ $item->forms?->subject ?? '' }}{{ $item->partners ? ' · '.$item->partners->partner_name : '' }}</span>
							<strong>{{ $item->product_name }}</strong>
						</a>
					@endforeach
				</div>
			@endif
		</div>
		<div class="rp-box">
			<div class="rp-box-title">통관 이슈</div>
			<table class="table table-bordered">
				<thead class="bg-slate-50 text-center">
					<tr>
						<th>제목</th>
						<th class="rp-nowrap">첨부</th>
						<th class="rp-nowrap">등록일</th>
					</tr>
				</thead>
				<tbody class="text-center">
					@forelse ($customsIssues as $issue)
						<tr onclick="openCustomsIssue({{ $issue->idx }})" class="hover:bg-slate-100" style="cursor:pointer;">
							<td class="text-left">{{ $issue->title }}</td>
							<td>@if ($issue->ori_name)<i data-lucide="paperclip" class="w-4 h-4 inline-block"></i>@else - @endif</td>
							<td class="rp-nowrap">{{ $issue->created_at?->format('Y-m-d') }}</td>
						</tr>
					@empty
						<tr><td colspan="3" class="rp-muted">등록된 통관 이슈가 없습니다.</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>

	<!-- 통관 이슈 상세 -->
	<div id="customs-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto" id="customs_title">통관 이슈</h2>
					<button onclick="modalClose('customs-modal')"><i data-lucide="x" class="w-6 h-6"></i></button>
				</div>
				<div class="modal-body">
					<p class="rp-muted text-sm" id="customs_date"></p>
					<div class="rp-detail-contents" id="customs_contents"></div>
					<div class="mt-4" id="customs_file"></div>
				</div>
				<div class="modal-footer flex justify-center">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('customs-modal')">닫기</button>
				</div>
			</div>
		</div>
	</div>
</div>
@include('rips.footer._footer')
<script>
function openCustomsIssue(idx) {
	ripsAjax(RIPS_BASE + '/customs/' + idx, 'POST', {}, function (res) {
		const d = res.data;
		$('#customs_title').text(d.title);
		$('#customs_date').text('등록일 ' + (d.created_at || '-'));
		$('#customs_contents').text(d.contents || '');
		$('#customs_file').html(d.download
			? '<a href="' + escapeHtml(d.download) + '" class="btn btn-sm btn-outline-secondary">첨부파일 : ' + escapeHtml(d.ori_name) + '</a>'
			: '');
		modalOpen('customs-modal');
	});
}
</script>
