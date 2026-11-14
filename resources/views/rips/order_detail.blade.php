@use('App\Models\RipsOrder')
@php
	$menu = 'order';
	$steps = collect(RipsOrder::STATUSES)->except('canceled');
	$currentStep = array_search($order->status, $steps->keys()->all());
@endphp
@include('rips.header._header')
<div class="rp-page">
	<div class="rp-page-head">
		<div>
			<h3>발주 상세 <span class="rp-badge {{ $order->status }} ml-2">{{ $order->status_text }}</span></h3>
			<p class="rp-desc">발주번호 {{ $order->order_no }} · 발주일 {{ $order->order_date->format('Y-m-d H:i') }}</p>
		</div>
		<div class="rp-actions">
			<a href="{{ route('rips.order', ['rid' => $partner->rips_id]) }}" class="btn btn-outline-secondary">목록으로</a>
			@if ($order->isEditable())
				<button type="button" class="btn btn-outline-danger" onclick="cancelOrder()">발주 취소</button>
				<a href="{{ route('rips.order.edit', ['rid' => $partner->rips_id, 'idx' => $order->idx]) }}" class="btn btn-primary">발주 수정</a>
			@endif
		</div>
	</div>

	<div class="rp-box">
		<div class="rp-box-title">진행 상태</div>
		@if ($order->status === 'canceled')
			<p class="rp-muted">이 발주는 {{ $order->canceled_at?->format('Y-m-d H:i') }}에 취소되었습니다.</p>
		@else
			<ul class="rp-steps">
				@foreach ($steps as $key => $label)
					<li class="{{ $currentStep !== false && $loop->index <= $currentStep ? 'done' : '' }}">{{ $label }}</li>
				@endforeach
			</ul>
			<p class="rp-help">· 예상입고일 : {{ $order->expected_date?->format('Y-m-d') ?? '미정' }} &nbsp;· 상품준비 이후 단계는 리치포트에서 진행 상황에 맞춰 변경합니다.</p>
		@endif
	</div>

	<div class="rp-box">
		<div class="rp-box-title">발주업체 정보</div>
		<table class="rp-info-table">
			<tr>
				<th>본점/지점</th>
				<td>{{ $partner->branch_type_text }}</td>
				<th>소속 본점</th>
				<td>{{ $partner->branch_type === 'branch' ? ($partner->head->partner_name ?? '-') : '-' }}</td>
			</tr>
			<tr>
				<th>도시</th>
				<td>{{ $partner->cities->name_kr ?? '-' }}</td>
				<th>구역</th>
				<td>{{ $partner->districts->name_kr ?? '-' }}</td>
			</tr>
			<tr>
				<th>업체명</th>
				<td>{{ $partner->partner_name }}</td>
				<th>매장명</th>
				<td>{{ $partner->store_name ?: '-' }}</td>
			</tr>
			<tr>
				<th>발주자</th>
				<td>{{ $order->orderer_dept ? $order->orderer_dept.' / ' : '' }}{{ $order->orderer_name }}</td>
				<th>연락처</th>
				<td>{{ $order->orderer_tel }}@if($order->orderer_email) <span class="rp-muted">· {{ $order->orderer_email }}</span>@endif</td>
			</tr>
		</table>
	</div>

	<div class="rp-box">
		<div class="rp-box-title">발주 상품</div>
		<div class="rp-table-wrap">
			<table class="table table-bordered rp-order-items">
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="rp-nowrap">번호</th>
						<th class="rp-nowrap">카테고리</th>
						<th class="rp-nowrap">업체명</th>
						<th class="rp-nowrap">상품명</th>
						<th class="rp-nowrap">단가</th>
						<th class="rp-nowrap">수량</th>
						<th class="rp-nowrap">발주금액</th>
					</tr>
				</thead>
				<tbody class="text-center">
					@foreach ($order->items as $i => $item)
						<tr>
							<td>{{ $i + 1 }}</td>
							<td class="text-left">{{ $categoryMap[$item->category_idx] ?? '-' }}</td>
							<td>{{ $item->company_name }}</td>
							<td class="text-left">{{ $item->product_name }}</td>
							<td class="rp-num">{{ number_format($item->unit_price) }}</td>
							<td class="rp-num">{{ number_format($item->qty) }}</td>
							<td class="rp-num">{{ number_format($item->amount) }}</td>
						</tr>
					@endforeach
				</tbody>
				<tfoot class="text-center">
					<tr>
						<td colspan="2">총계</td>
						<td colspan="3" class="text-left">총 {{ number_format($order->total_items) }}개 상품</td>
						<td class="rp-num">{{ number_format($order->total_qty) }}</td>
						<td class="rp-num">{{ number_format($order->total_amount) }}</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>

	<div class="rp-box">
		<div class="rp-box-title">발주자 서명</div>
		@if ($order->sign_path)
			<img src="{{ Storage::disk('public')->url($order->sign_path) }}" alt="서명" class="rp-sign-img">
		@else
			<p class="rp-muted">서명 이미지가 없습니다.</p>
		@endif
	</div>
</div>
@include('rips.footer._footer')
@if ($order->isEditable())
<script>
function cancelOrder() {
    if (!confirm('발주를 취소하시겠습니까?\n취소한 발주는 되돌릴 수 없습니다.')) return;

    ripsAjax(RIPS_BASE + '/order/{{ $order->idx }}/cancel', 'POST', {}, function () {
        showToast('발주가 취소되었습니다.', 'success');
        setTimeout(function () { location.reload(); }, 600);
    });
}
</script>
@endif
