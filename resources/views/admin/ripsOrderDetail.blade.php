@php
	use App\Models\RipsOrder;
	$header_skip = false;
	$depth1_num = '7';
	$depth2_num = '3';
	$canceled  = $order->status === 'canceled';
	$canUpdate = auth()->user()->hasPerm('ripsOrder.update') && !$canceled;
	$steps     = collect(RipsOrder::STATUSES)->except('canceled');
	$stepKeys  = $steps->keys()->all();
	$current   = array_search($order->status, $stepKeys);
	$dateVal   = fn ($d) => $d ? $d->format('Y-m-d') : '';
@endphp
@include('admin.header._header')
<style>
	.ro-badge {display:inline-block; padding:3px 10px; border-radius:999px; font-size:12px; font-weight:600; white-space:nowrap; background:#f1f5f9; color:#475569; }
	.ro-badge.ordered   {background:#e0f2fe; color:#0369a1; }
	.ro-badge.preparing {background:#fef3c7; color:#b45309; }
	.ro-badge.shipping  {background:#ede9fe; color:#6d28d9; }
	.ro-badge.customs   {background:#fce7f3; color:#be185d; }
	.ro-badge.received  {background:#dcfce7; color:#15803d; }
	.ro-badge.canceled  {background:#f1f5f9; color:#94a3b8; text-decoration:line-through; }
	.ro-info {width:100%; font-size:14px; }
	.ro-info th {width:130px; background:#f8fafc; padding:9px 12px; text-align:left; font-weight:600; color:#475569; border:1px solid #e2e8f0; }
	.ro-info td {padding:9px 12px; border:1px solid #e2e8f0; }
	.ro-steps {display:flex; gap:0; }
	.ro-steps button {flex:1; position:relative; padding:14px 8px; border:1px solid #e2e8f0; background:#fff; color:#94a3b8; font-weight:600; font-size:14px; }
	.ro-steps button + button {border-left:0; }
	.ro-steps button small {display:block; font-size:11px; font-weight:400; margin-top:2px; }
	.ro-steps button.done {background:#f0f9ff; color:#0369a1; }
	.ro-steps button.current {background:rgb(var(--color-primary)); color:#fff; border-color:rgb(var(--color-primary)); }
	.ro-steps button:not(:disabled):not(.current):hover {background:#f8fafc; color:rgb(var(--color-primary)); }
	.ro-steps button:disabled {cursor:default; }
	.ro-sched {display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:16px; }
	.ro-sched .box {border:1px solid #e2e8f0; border-radius:8px; padding:12px 14px; }
	.ro-sched .box p {font-weight:600; margin-bottom:8px; color:#334155; }
	.ro-items th, .ro-items td {white-space:nowrap; vertical-align:middle; }
	.ro-items .group-rp {background:#f0f9ff; }
	.ro-items .form-control, .ro-items .form-select {height:32px; padding-top:3px; padding-bottom:3px; font-size:13px; }
	.ro-items input.num {width:110px; text-align:right; }
	.ro-items tfoot td {background:#f8fafc; font-weight:700; }
	.ro-log li {display:flex; gap:10px; padding:6px 0; border-bottom:1px dashed #e2e8f0; font-size:13px; }
	.ro-log li:last-child {border-bottom:0; }
	@media all and (max-width:1200px){ .ro-sched {grid-template-columns:minmax(0, 1fr); } }
</style>
<div class="mt-6 px-5">
	<div class="flex items-center justify-between gap-3 flex-wrap">
		<h3 class="intro-y text-xl font-bold">발주 상세 <span class="text-slate-500 font-normal text-base ml-1">{{ $order->order_no }}</span> <span class="ro-badge {{ $order->status }} ml-2">{{ $order->status_text }}</span></h3>
		<a href="{{ url()->previous() !== url()->current() && str_contains(url()->previous(), '/admin/ripsOrder') ? url()->previous() : '/admin/ripsOrder' }}" class="btn btn-outline-secondary">목록으로</a>
	</div>

	<!-- 발주 정보 -->
	<div class="intro-y border border-slate-200 mt-6 p-5 rounded-lg bg-white">
		<h3 class="text-base font-semibold mb-3">발주 정보 <span class="text-xs font-normal text-slate-500">(RIPS 업체 → 리치포트)</span></h3>
		<div class="flex gap-5 flex-wrap">
			<table class="ro-info flex-1">
				<tr>
					<th>발주 업체</th>
					<td>
						{{ $order->partner->partner_name ?? '-' }}
						@if($order->partner?->store_name) · {{ $order->partner->store_name }}@endif
						<span class="text-xs text-slate-500">({{ $order->partner?->branch_type_text }}@if($order->partner?->branch_type === 'branch') · 본점 {{ $order->partner->head->partner_name ?? '-' }}@endif)</span>
					</td>
					<th>발주일</th>
					<td>{{ $order->order_date?->format('Y-m-d H:i') }}</td>
				</tr>
				<tr>
					<th>발주자</th>
					<td>{{ $order->orderer_dept ? $order->orderer_dept . ' / ' : '' }}{{ $order->orderer_name }}</td>
					<th>연락처</th>
					<td>{{ $order->orderer_tel }}@if($order->orderer_email) · {{ $order->orderer_email }}@endif</td>
				</tr>
				<tr>
					<th>상품 / 수량</th>
					<td>{{ number_format($order->total_items) }}개 상품 · {{ number_format($order->total_qty) }}개</td>
					<th>발주금액</th>
					<td class="font-bold">{{ number_format($order->total_amount) }}</td>
				</tr>
				<tr>
					<th>예상입고일</th>
					<td>{{ $order->expected_date?->format('Y-m-d') ?? '미정' }}</td>
					<th>{{ $canceled ? '취소일시' : '최종 수정' }}</th>
					<td>{{ $canceled ? $order->canceled_at?->format('Y-m-d H:i') : ($order->updated_at?->format('Y-m-d H:i') ?? '-') . ($order->updated_by ? ' · ' . $order->updated_by : '') }}</td>
				</tr>
			</table>
			@if ($order->sign_path)
			<div class="text-center">
				<img src="{{ Storage::disk('public')->url($order->sign_path) }}" alt="서명" style="width:220px; border:1px solid #e2e8f0; border-radius:6px;">
				<p class="text-xs text-slate-500 mt-1">발주자 서명</p>
			</div>
			@endif
		</div>
	</div>

	<!-- 진행 상태 -->
	<div class="intro-y border border-slate-200 mt-5 p-5 rounded-lg bg-white">
		<h3 class="text-base font-semibold mb-3">진행 상태</h3>
		@if ($canceled)
			<p class="text-danger font-semibold">RIPS 업체가 취소한 발주입니다. 상태와 발주 정보를 변경할 수 없습니다.</p>
		@else
			<div class="ro-steps">
				@foreach ($steps as $key => $label)
				@php $i = $loop->index; @endphp
				<button type="button" class="{{ $i < $current ? 'done' : '' }} {{ $i === $current ? 'current' : '' }}" data-status="{{ $key }}" {{ $canUpdate && $i !== $current ? '' : 'disabled' }}>
					{{ $i < $current ? '✓ ' : '' }}{{ $label }}
					<small>{{ match ($key) {
						'ordered'  => $order->order_date?->format('m-d'),
						'shipping' => $order->logistics_start ? $order->logistics_start->format('m-d') . ' ~ ' . ($order->logistics_end?->format('m-d') ?? '') : '',
						'customs'  => $order->customs_start ? $order->customs_start->format('m-d') . ' ~ ' . ($order->customs_end?->format('m-d') ?? '') : '',
						'received' => $order->received_date?->format('m-d'),
						default    => '',
					} }}&nbsp;</small>
				</button>
				@endforeach
			</div>
			<p class="mt-2 text-xs text-slate-500">
				· 단계를 누르면 해당 상태로 변경됩니다. (잘못 변경한 경우 이전 단계를 눌러 되돌릴 수 있습니다)<br>
				· 변경 시 비어 있는 일정은 오늘 날짜로 채워집니다 : 물류진행중 → 물류 시작일, 통관진행중 → 통관 시작일·물류 종료일, 입고완료 → 입고일·통관 종료일<br>
				· 발주접수 이외의 상태에서는 RIPS 업체가 발주를 수정·취소할 수 없습니다.
			</p>
		@endif

		@if ($order->logs->count())
		<details class="mt-3">
			<summary class="cursor-pointer text-sm text-slate-600">상태 변경 이력 ({{ $order->logs->count() }})</summary>
			<ul class="ro-log mt-2">
				@foreach ($order->logs as $log)
				<li>
					<span class="text-slate-500 w-36">{{ $log->created_at?->format('Y-m-d H:i') }}</span>
					<span>{{ $log->prev_status ? (RipsOrder::STATUSES[$log->prev_status] ?? $log->prev_status) . ' → ' : '' }}<span class="ro-badge {{ $log->status }}">{{ $log->status_text }}</span></span>
					<span class="text-slate-500 ml-auto">{{ $log->created_by }}</span>
				</li>
				@endforeach
			</ul>
		</details>
		@endif
	</div>

	<!-- 일정 / 메모 -->
	<div class="intro-y border border-slate-200 mt-5 p-5 rounded-lg bg-white">
		<h3 class="text-base font-semibold mb-3">물류 · 통관 · 입고 일정</h3>
		<div class="ro-sched">
			<div class="box">
				<p>물류</p>
				<div class="flex items-center gap-1">
					<input type="date" id="logistics_start" class="form-control" value="{{ $dateVal($order->logistics_start) }}" {{ $canUpdate ? '' : 'disabled' }}>
					<span>~</span>
					<input type="date" id="logistics_end" class="form-control" value="{{ $dateVal($order->logistics_end) }}" {{ $canUpdate ? '' : 'disabled' }}>
				</div>
			</div>
			<div class="box">
				<p>통관</p>
				<div class="flex items-center gap-1">
					<input type="date" id="customs_start" class="form-control" value="{{ $dateVal($order->customs_start) }}" {{ $canUpdate ? '' : 'disabled' }}>
					<span>~</span>
					<input type="date" id="customs_end" class="form-control" value="{{ $dateVal($order->customs_end) }}" {{ $canUpdate ? '' : 'disabled' }}>
				</div>
			</div>
			<div class="box">
				<p>입고</p>
				<input type="date" id="received_date" class="form-control" value="{{ $dateVal($order->received_date) }}" {{ $canUpdate ? '' : 'disabled' }}>
			</div>
		</div>
		<div class="mt-4">
			<p class="font-semibold mb-1 text-slate-700">관리 메모 <span class="text-xs font-normal text-slate-500">(관리자만 확인)</span></p>
			<textarea id="admin_memo" class="form-control h-20" placeholder="공급업체 연락 내용, 특이사항 등" {{ $canUpdate ? '' : 'disabled' }}>{{ $order->admin_memo }}</textarea>
		</div>
	</div>

	<!-- 상품별 리치포트 발주 정보 -->
	<div class="intro-y border border-slate-200 mt-5 p-5 rounded-lg bg-white">
		<div class="flex items-center justify-between gap-3 mb-3 flex-wrap">
			<h3 class="text-base font-semibold">상품별 리치포트 발주 정보 <span class="text-xs font-normal text-slate-500">(리치포트 → 공급업체)</span></h3>
			@if ($canUpdate)
			<button type="button" class="btn btn-outline-primary btn-sm" onclick="fillDefaults()">빈 칸을 요청 내용으로 채우기</button>
			@endif
		</div>
		<div class="overflow-x-auto">
			<table class="table table-sm table-bordered ro-items">
				<thead class="text-center">
					<tr class="bg-slate-50">
						<th colspan="6">업체 발주 (요청)</th>
						<th colspan="6" class="group-rp">리치포트 발주</th>
					</tr>
					<tr class="bg-slate-50">
						<th>번호</th>
						<th>업체 카테고리</th>
						<th>상품명 / 업체명</th>
						<th>단가</th>
						<th>수량</th>
						<th>금액</th>
						<th class="group-rp">리치포트 상품</th>
						<th class="group-rp">발주 업체</th>
						<th class="group-rp">발주일</th>
						<th class="group-rp">수량</th>
						<th class="group-rp">금액</th>
						<th class="group-rp">차액<div class="text-xs font-normal">요청 − 발주</div></th>
					</tr>
				</thead>
				<tbody>
					@foreach ($order->items as $item)
					@php
						// 저장된 리치포트 상품 (없으면 현재 매핑 기준)
						$rp        = $item->richportProduct ?? $item->product?->richportProduct;
						$rpSaved   = (bool) $item->richportProduct;
						$defSupplier = $rp?->partner_idx;
					@endphp
					<tr data-idx="{{ $item->idx }}" data-qty="{{ $item->qty }}" data-amount="{{ $item->amount }}" data-def-supplier="{{ $defSupplier }}">
						<td class="text-center">{{ $loop->iteration }}</td>
						<td class="text-xs text-slate-500">{{ $categoryMap[$item->category_idx] ?? '-' }}</td>
						<td>{{ $item->product_name }}<div class="text-xs text-slate-500">{{ $item->company_name }}</div></td>
						<td class="text-right">{{ number_format($item->unit_price) }}</td>
						<td class="text-right">{{ number_format($item->qty) }}</td>
						<td class="text-right">{{ number_format($item->amount) }}</td>
						<td class="text-xs">
							@if ($rp)
								{{ $rp->product_name }}
								<div class="text-slate-500">{{ $rp->partners->partner_name ?? '' }}{{ $rpSaved ? '' : ' · 현재 매핑' }}</div>
							@else
								<span class="text-danger">미매핑</span>
							@endif
						</td>
						<td>
							<select class="form-select p-supplier" style="min-width:150px;" {{ $canUpdate ? '' : 'disabled' }}>
								<option value="">{{ $defSupplier ? '선택 (예정: ' . ($rp->partners->partner_name ?? '') . ')' : '선택' }}</option>
								@foreach ($suppliers as $s)
								<option value="{{ $s->idx }}" {{ $item->supplier_idx == $s->idx ? 'selected' : '' }}>{{ $s->partner_name }}</option>
								@endforeach
							</select>
						</td>
						<td><input type="date" class="form-control p-date" value="{{ $dateVal($item->purchase_date) }}" {{ $canUpdate ? '' : 'disabled' }}></td>
						<td><input type="number" class="form-control num p-qty" min="0" value="{{ $item->purchase_qty }}" placeholder="{{ $item->qty }}" {{ $canUpdate ? '' : 'disabled' }}></td>
						<td><input type="number" class="form-control num p-amount" min="0" value="{{ $item->purchase_amount }}" placeholder="0" {{ $canUpdate ? '' : 'disabled' }}></td>
						<td class="text-right p-diff"></td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" class="text-center">합계</td>
						<td class="text-right">{{ number_format($order->total_qty) }}</td>
						<td class="text-right">{{ number_format($order->total_amount) }}</td>
						<td colspan="3"></td>
						<td class="text-right" id="sumQty"></td>
						<td class="text-right" id="sumAmount"></td>
						<td class="text-right" id="sumDiff"></td>
					</tr>
				</tfoot>
			</table>
		</div>
		<p class="mt-2 text-xs text-slate-500">
			· 발주 업체는 리치포트 상품의 파트너가 기본(예정)이며, 다른 업체에서 발주한 경우 변경해주세요.<br>
			· 리치포트 상품은 발주 정보를 처음 저장할 때의 매핑 상품으로 기록되며, 이후 매핑이 바뀌어도 유지됩니다.<br>
			· 차액은 금액이 입력된 상품만 계산합니다.
		</p>
	</div>

	@if ($canUpdate)
	<div class="flex items-center justify-center gap-3 mt-6 mb-10">
		<button type="button" class="btn btn-primary w-40" onclick="saveOrder()">일정 · 발주 정보 저장</button>
	</div>
	@else
	<div class="mb-10"></div>
	@endif
</div>
@include('admin.footer._footer')
<script>
const ORDER_URL = '/admin/ripsOrder/{{ $order->idx }}';
const STATUSES  = @json(RipsOrder::STATUSES);

function numberFormat(v) {
    return Number(v || 0).toLocaleString('ko-KR');
}

function csrf() {
    return { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') };
}

function orderAjax(url, data, message) {
    $.ajax({
        url: url,
        method: 'POST',
        headers: csrf(),
        data: data,
        success: function (res) {
            if (res.status !== 'success') return showToast(res.message || '저장 실패', 'error');
            showToast(message, 'success');
            setTimeout(() => location.reload(), 500);
        },
        error: function (xhr) {
            showToast(xhr.responseJSON?.message || '오류 발생', 'error');
        }
    });
}

// 상태 변경
$('.ro-steps').on('click', 'button[data-status]', function () {
    const status = $(this).data('status');
    if (!confirm('발주 상태를 [' + STATUSES[status] + '] (으)로 변경하시겠습니까?')) return;
    orderAjax(ORDER_URL + '/status', { status: status }, '발주 상태가 변경되었습니다.');
});

// 차액 / 합계
function refreshSum() {
    let qty = 0, amount = 0, diff = 0, entered = 0;
    $('.ro-items tbody tr').each(function () {
        const $tr = $(this);
        const q   = $tr.find('.p-qty').val();
        const a   = $tr.find('.p-amount').val();
        if (q !== '') qty += Number(q);
        if (a !== '') {
            const d = Number($tr.data('amount')) - Number(a);
            amount += Number(a);
            diff   += d;
            entered++;
            $tr.find('.p-diff').html('<span class="' + (d < 0 ? 'text-danger' : 'text-success') + '">' + numberFormat(d) + '</span>');
        } else {
            $tr.find('.p-diff').html('<span class="text-slate-300">-</span>');
        }
    });
    $('#sumQty').text(numberFormat(qty));
    $('#sumAmount').text(entered ? numberFormat(amount) : '-');
    $('#sumDiff').html(entered ? '<span class="' + (diff < 0 ? 'text-danger' : 'text-success') + '">' + numberFormat(diff) + '</span>' : '-');
}
$('.ro-items').on('input change', '.p-qty, .p-amount', refreshSum);
refreshSum();

// 빈 칸 채우기 : 발주 업체 = 매핑 상품 파트너, 발주일 = 오늘, 수량 = 요청 수량
function fillDefaults() {
    const today = new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 10);
    $('.ro-items tbody tr').each(function () {
        const $tr = $(this);
        if (!$tr.find('.p-supplier').val() && $tr.data('def-supplier')) $tr.find('.p-supplier').val(String($tr.data('def-supplier')));
        if (!$tr.find('.p-date').val()) $tr.find('.p-date').val(today);
        if ($tr.find('.p-qty').val() === '') $tr.find('.p-qty').val($tr.data('qty'));
    });
    refreshSum();
    showToast('빈 칸을 채웠습니다. 금액을 입력한 뒤 저장해주세요.', 'success');
}

function saveOrder() {
    const data = {
        logistics_start: $('#logistics_start').val(),
        logistics_end:   $('#logistics_end').val(),
        customs_start:   $('#customs_start').val(),
        customs_end:     $('#customs_end').val(),
        received_date:   $('#received_date').val(),
        admin_memo:      $('#admin_memo').val(),
    };
    $('.ro-items tbody tr').each(function () {
        const idx = $(this).data('idx');
        data['items[' + idx + '][supplier_idx]']    = $(this).find('.p-supplier').val();
        data['items[' + idx + '][purchase_date]']   = $(this).find('.p-date').val();
        data['items[' + idx + '][purchase_qty]']    = $(this).find('.p-qty').val();
        data['items[' + idx + '][purchase_amount]'] = $(this).find('.p-amount').val();
    });

    if (!confirm('일정과 리치포트 발주 정보를 저장하시겠습니까?')) return;
    orderAjax(ORDER_URL + '/save', data, '저장되었습니다.');
}
</script>
