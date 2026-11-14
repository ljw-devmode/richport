@php
	$menu = 'order';
	$isEdit = (bool) $order;
	$productsJson = $products->map(fn ($p) => [
		'idx'           => $p->idx,
		'category_idx'  => $p->category_idx,
		'name'          => $p->product_name,
		'company'       => $p->company_name,
		'unit_price'    => $p->orderPrice()[0],
		'stock_qty'     => (int) $p->stock_qty,
		'recommend_qty' => $p->recommend_qty,
	])->values();
	$categoriesJson = $categories->map(fn ($c) => ['idx' => $c->idx, 'parent_idx' => $c->parent_idx, 'depth' => $c->depth, 'name' => $c->name])->values();
	$submitUrl = $isEdit
		? route('rips.order.modify', ['rid' => $partner->rips_id, 'idx' => $order->idx])
		: route('rips.order.regist', ['rid' => $partner->rips_id]);
	$doneMessage = $isEdit ? '발주가 수정되었습니다.' : '발주가 완료되었습니다.';
@endphp
@include('rips.header._header')
<div class="rp-page">
	<div class="rp-page-head">
		<div>
			<h3>{{ $isEdit ? '발주 수정' : '발주하기' }}</h3>
			<p class="rp-desc">
				@if ($isEdit)
					발주번호 {{ $order->order_no }} · 수정 후 다시 서명하면 발주 내용이 변경됩니다.
				@else
					발주자 정보를 입력하고 [상품 추가]로 발주할 상품을 구성해주세요.
				@endif
			</p>
		</div>
		<div class="rp-actions">
			<a href="{{ $isEdit ? route('rips.order.detail', ['rid' => $partner->rips_id, 'idx' => $order->idx]) : route('rips.order', ['rid' => $partner->rips_id]) }}" class="btn btn-outline-secondary">목록으로</a>
		</div>
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
				<th>발주자 부서</th>
				<td><input type="text" id="orderer_dept" class="form-control" maxlength="100" value="{{ $lastOrder->orderer_dept ?? '' }}" placeholder="예: 구매팀"></td>
				<th>발주자명 <span class="text-danger">*</span></th>
				<td><input type="text" id="orderer_name" class="form-control" maxlength="50" value="{{ $lastOrder->orderer_name ?? '' }}" placeholder="발주자 이름"></td>
			</tr>
			<tr>
				<th>전화번호 <span class="text-danger">*</span></th>
				<td><input type="text" id="orderer_tel" class="form-control" maxlength="30" value="{{ $lastOrder->orderer_tel ?? '' }}" placeholder="연락 가능한 전화번호"></td>
				<th>이메일</th>
				<td><input type="email" id="orderer_email" class="form-control" maxlength="100" value="{{ $lastOrder->orderer_email ?? '' }}" placeholder="example@email.com"></td>
			</tr>
		</table>
	</div>

	<div class="rp-box">
		<div class="rp-box-title">
			<span>발주 상품</span>
			<button type="button" class="btn btn-primary" onclick="addItem()"><i data-lucide="plus" class="w-4 h-4 mr-1"></i> 상품 추가</button>
		</div>
		<div class="rp-table-wrap">
			<table class="table table-bordered rp-order-items">
				<thead class="bg-slate-50 text-center">
					<tr>
						<th class="rp-nowrap" style="width:50px;">번호</th>
						<th class="rp-nowrap">카테고리</th>
						<th class="rp-nowrap">상품</th>
						<th class="rp-nowrap">업체명</th>
						<th class="rp-nowrap">단가</th>
						<th class="rp-nowrap">잔여수량</th>
						<th class="rp-nowrap">수량</th>
						<th class="rp-nowrap">발주금액</th>
						<th class="rp-nowrap" style="width:60px;">삭제</th>
					</tr>
				</thead>
				<tbody id="itemBody" class="text-center"></tbody>
				<tfoot class="text-center">
					<tr>
						<td colspan="2">총계</td>
						<td colspan="4" class="text-left">총 <span id="sumItems">0</span>개 상품</td>
						<td class="rp-num"><span id="sumQty">0</span></td>
						<td class="rp-num"><span id="sumAmount">0</span></td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
		<p class="rp-help">· 추천수량은 AI 예측값(없으면 최근 판매 기준 계산값)입니다. &nbsp;· 예상입고일은 상품별 예약 입고 소요기간 중 가장 긴 기간으로 계산됩니다.</p>
	</div>

	<div class="flex items-center justify-center gap-3 mt-6">
		<a href="{{ $isEdit ? route('rips.order.detail', ['rid' => $partner->rips_id, 'idx' => $order->idx]) : route('rips.order', ['rid' => $partner->rips_id]) }}" class="btn btn-outline-secondary w-24">취소</a>
		<button type="button" class="btn btn-dark w-32" onclick="openConfirm()">{{ $isEdit ? '수정 발주하기' : '발주하기' }}</button>
	</div>

	<!-- 발주 확인 모달 -->
	<div id="confirm-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">발주 확인</h2>
					<button onclick="modalClose('confirm-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<p>아래 내용으로 {{ $isEdit ? '발주를 수정' : '발주' }}하시겠습니까?</p>
					<table class="rp-info-table mt-3">
						<tr><th>발주자</th><td id="confirmOrderer"></td></tr>
						<tr><th>총 상품 가지수</th><td id="confirmItems"></td></tr>
						<tr><th>총 수량</th><td id="confirmQty"></td></tr>
						<tr><th>총 금액</th><td id="confirmAmount" class="font-bold"></td></tr>
					</table>
					<div class="form-check mt-4">
						<input id="confirm_agree" class="form-check-input" type="checkbox">
						<label class="form-check-label" for="confirm_agree">위 발주 내용을 확인했으며, 발주에 동의합니다.</label>
					</div>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('confirm-modal')">취소</button>
					<button type="button" class="btn btn-primary w-24" onclick="openSign()">동의 및 서명</button>
				</div>
			</div>
		</div>
	</div>

	<!-- 서명 모달 -->
	<div id="sign-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">발주자 서명</h2>
					<button onclick="modalClose('sign-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<p class="mb-3">아래 영역에 서명해주세요. (마우스 또는 손가락으로 그려주세요)</p>
					<div class="rp-sign-wrap">
						<canvas id="signCanvas"></canvas>
						<div class="guide" id="signGuide">여기에 서명</div>
					</div>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-24" onclick="clearSign()"><i data-lucide="eraser" class="w-4 h-4 mr-1"></i> 지우기</button>
					<button type="button" class="btn btn-dark w-32" id="submitBtn" onclick="submitOrder()">서명 완료 및 발주</button>
				</div>
			</div>
		</div>
	</div>
</div>
@include('rips.footer._footer')
<script>
const PRODUCTS   = @json($productsJson);
const CATEGORIES = @json($categoriesJson);
const INIT_ITEMS = @json($initItems);
const CAT_MAX_DEPTH = 4;
const SUBMIT_URL = @json($submitUrl);

// 카테고리와 모든 하위 카테고리 idx
function descendantIds(categoryIdx) {
    const ids = [categoryIdx];
    for (let i = 0; i < ids.length; i++) {
        CATEGORIES.filter(c => c.parent_idx === ids[i]).forEach(c => ids.push(c.idx));
    }
    return ids;
}

function findProduct(idx) {
    return PRODUCTS.find(p => p.idx === Number(idx));
}

function findCategory(idx) {
    return CATEGORIES.find(c => c.idx === Number(idx));
}

// ---------------- 카테고리 (1~4단계 한 단계씩 선택) ----------------
// 행의 카테고리 셀을 다시 그림 : 선택한 단계까지 + 하위가 있으면 다음 단계 select 하나 더
function renderCategorySelects($row, chain) {
    const $box = $row.find('.item-cats').empty();
    let parent = 0;

    for (let depth = 1; depth <= CAT_MAX_DEPTH; depth++) {
        const children = CATEGORIES.filter(c => c.parent_idx === parent);
        if (!children.length) break;

        const $sel = $('<select class="form-select item-category"></select>').attr('data-depth', depth)
            .append('<option value="">' + (depth === 1 ? '전체' : depth + '차 전체') + '</option>');
        children.forEach(c => $sel.append($('<option>').val(c.idx).text(c.name)));
        $box.append($sel);

        const picked = chain[depth - 1];
        if (!picked) break;
        $sel.val(String(picked));
        parent = picked;
    }
}

// 현재 행에서 선택한 가장 깊은 카테고리 (없으면 0 : 전체)
function selectedCategory($row) {
    let idx = 0;
    $row.find('.item-category').each(function () {
        if ($(this).val()) idx = Number($(this).val());
    });
    return idx;
}

// 카테고리 idx → [1차, 2차, ...] 경로
function categoryChain(categoryIdx) {
    const chain = [];
    let c = findCategory(categoryIdx);
    while (c) {
        chain.unshift(c.idx);
        c = findCategory(c.parent_idx);
    }
    return chain;
}

// ---------------- 상품 행 ----------------
function addItem(init) {
    if (!PRODUCTS.length) return showToast('등록된 상품이 없습니다. [상품] 메뉴에서 먼저 상품을 등록해주세요.', 'error');

    const $row = $(
        '<tr class="item-row">' +
            '<td class="row-no"></td>' +
            '<td><div class="item-cats"></div></td>' +
            '<td><select class="form-select item-product"></select></td>' +
            '<td class="item-company">-</td>' +
            '<td class="rp-num item-price">-</td>' +
            '<td class="rp-num item-stock">-</td>' +
            '<td><div class="qty-box">' +
                '<input type="number" class="form-control item-qty" min="1" placeholder="0">' +
                '<button type="button" class="btn btn-sm btn-outline-secondary rp-nowrap item-recommend" disabled title="AI 추천수량 준비중">추천 -</button>' +
            '</div></td>' +
            '<td class="rp-num item-amount">0</td>' +
            '<td><button type="button" class="btn btn-sm btn-outline-danger item-remove">✕</button></td>' +
        '</tr>'
    );

    $('#itemBody .empty').remove();
    $('#itemBody').append($row);

    if (init) {
        renderCategorySelects($row, categoryChain(init.category_idx));
        renderProductOptions($row, init.product_idx);
        $row.find('.item-qty').val(init.qty);
        applyProduct($row);
    } else {
        renderCategorySelects($row, []);
        renderProductOptions($row);
    }
    refresh();
}

function renderProductOptions($row, selectedIdx) {
    const categoryIdx = selectedCategory($row);
    const ids  = categoryIdx ? descendantIds(categoryIdx) : null;
    const $sel = $row.find('.item-product').empty().append('<option value="">상품 선택</option>');

    PRODUCTS.filter(p => !ids || ids.includes(p.category_idx)).forEach(function (p) {
        $sel.append($('<option>').val(p.idx).text(p.name));
    });
    if (selectedIdx) $sel.val(String(selectedIdx));
}

function applyProduct($row) {
    const product = findProduct($row.find('.item-product').val());
    const $rec    = $row.find('.item-recommend');

    $row.find('.item-company').text(product ? product.company : '-');
    $row.find('.item-price').text(product ? numberFormat(product.unit_price) : '-');
    $row.find('.item-stock').text(product ? numberFormat(product.stock_qty) : '-');

    if (product && product.recommend_qty !== null) {
        $rec.prop('disabled', false).attr('title', '추천수량 적용').text('추천 ' + numberFormat(product.recommend_qty)).data('qty', product.recommend_qty);
    } else {
        $rec.prop('disabled', true).attr('title', 'AI 추천수량 준비중').text('추천 -').removeData('qty');
    }
}

function refresh() {
    let items = 0, qty = 0, amount = 0;

    $('#itemBody .item-row').each(function (i) {
        const $row    = $(this);
        const product = findProduct($row.find('.item-product').val());
        const q       = Math.max(0, parseInt($row.find('.item-qty').val(), 10) || 0);
        const a       = product ? product.unit_price * q : 0;

        $row.find('.row-no').text(i + 1);
        $row.find('.item-amount').text(numberFormat(a));

        if (product) items++;
        qty    += q;
        amount += a;
    });

    if (!$('#itemBody .item-row').length && !$('#itemBody .empty').length) {
        $('#itemBody').append('<tr class="empty"><td colspan="9">[상품 추가] 버튼을 눌러 발주할 상품을 추가해주세요.</td></tr>');
    }

    $('#sumItems').text(numberFormat(items));
    $('#sumQty').text(numberFormat(qty));
    $('#sumAmount').text(numberFormat(amount));

    return { items: items, qty: qty, amount: amount };
}

$('#itemBody')
.on('change', '.item-category', function () {
    const $row  = $(this).closest('tr');
    const depth = Number($(this).data('depth'));
    // 바꾼 단계까지만 유지하고 그 아래 단계는 새로 표시 ('전체' 선택 시 바로 윗 단계까지)
    const idx   = Number($(this).val()) || Number($row.find('.item-category[data-depth="' + (depth - 1) + '"]').val()) || 0;
    renderCategorySelects($row, categoryChain(idx));
    renderProductOptions($row);
    applyProduct($row);
    refresh();
})
.on('change', '.item-product', function () {
    const $row = $(this).closest('tr');
    const idx  = $(this).val();

    // 중복 상품 방지
    const dup = idx && $('#itemBody .item-product').not(this).filter(function () { return $(this).val() === idx; }).length;
    if (dup) {
        showToast('이미 추가된 상품입니다. 수량을 조정해주세요.', 'error');
        $(this).val('');
    }
    applyProduct($row);
    refresh();
})
.on('input', '.item-qty', refresh)
.on('click', '.item-recommend', function () {
    const $row = $(this).closest('tr');
    $row.find('.item-qty').val($(this).data('qty'));
    refresh();
})
.on('click', '.item-remove', function () {
    $(this).closest('tr').remove();
    refresh();
});

// ---------------- 발주 확인 / 서명 ----------------
function collectOrder() {
    const order = {
        orderer_dept:  $('#orderer_dept').val().trim(),
        orderer_name:  $('#orderer_name').val().trim(),
        orderer_tel:   $('#orderer_tel').val().trim(),
        orderer_email: $('#orderer_email').val().trim(),
        items: [],
    };

    let error = null;
    if (!order.orderer_name) error = '발주자명을 입력해주세요.';
    else if (!order.orderer_tel) error = '전화번호를 입력해주세요.';
    else if (order.orderer_email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(order.orderer_email)) error = '이메일 형식이 올바르지 않습니다.';

    if (!error) {
        $('#itemBody .item-row').each(function (i) {
            const productIdx = $(this).find('.item-product').val();
            const qty        = parseInt($(this).find('.item-qty').val(), 10) || 0;

            if (!productIdx) { error = (i + 1) + '번 행의 상품을 선택해주세요.'; return false; }
            if (qty <= 0) { error = (i + 1) + '번 행의 수량을 입력해주세요.'; return false; }
            order.items.push({ product_idx: productIdx, qty: qty });
        });
    }
    if (!error && !order.items.length) error = '발주할 상품을 추가해주세요.';

    if (error) {
        showToast(error, 'error');
        return null;
    }
    return order;
}

function openConfirm() {
    const order = collectOrder();
    if (!order) return;

    const sum = refresh();
    $('#confirmOrderer').text((order.orderer_dept ? order.orderer_dept + ' / ' : '') + order.orderer_name + ' (' + order.orderer_tel + ')');
    $('#confirmItems').text(numberFormat(sum.items) + '개');
    $('#confirmQty').text(numberFormat(sum.qty));
    $('#confirmAmount').text(numberFormat(sum.amount));
    $('#confirm_agree').prop('checked', false);
    modalOpen('confirm-modal');
}

function openSign() {
    if (!$('#confirm_agree').is(':checked')) return showToast('발주 동의에 체크해주세요.', 'error');

    modalClose('confirm-modal');
    modalOpen('sign-modal');
    initSignCanvas();
}

// 캔버스 서명
const canvas = document.getElementById('signCanvas');
const ctx    = canvas.getContext('2d');
let drawing = false, signed = false, last = null;

function initSignCanvas() {
    const ratio = window.devicePixelRatio || 1;
    const rect  = canvas.getBoundingClientRect();
    canvas.width  = rect.width * ratio;
    canvas.height = rect.height * ratio;
    ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
    ctx.lineWidth   = 2.5;
    ctx.lineCap     = 'round';
    ctx.lineJoin    = 'round';
    ctx.strokeStyle = '#111827';
    clearSign();
}

function clearSign() {
    ctx.save();
    ctx.setTransform(1, 0, 0, 1, 0, 0);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.restore();
    signed = false;
    $('#signGuide').show();
}

function pointOf(e) {
    const rect = canvas.getBoundingClientRect();
    return { x: e.clientX - rect.left, y: e.clientY - rect.top };
}

canvas.addEventListener('pointerdown', function (e) {
    drawing = true;
    last = pointOf(e);
    canvas.setPointerCapture(e.pointerId);
    $('#signGuide').hide();
});
canvas.addEventListener('pointermove', function (e) {
    if (!drawing) return;
    const p = pointOf(e);
    ctx.beginPath();
    ctx.moveTo(last.x, last.y);
    ctx.lineTo(p.x, p.y);
    ctx.stroke();
    last = p;
    signed = true;
});
['pointerup', 'pointercancel', 'pointerleave'].forEach(function (type) {
    canvas.addEventListener(type, function () { drawing = false; });
});

function submitOrder() {
    if (!signed) return showToast('서명을 해주세요.', 'error');

    const order = collectOrder();
    if (!order) return modalClose('sign-modal');

    // 흰 배경을 깔아 PNG 로 저장
    const out = document.createElement('canvas');
    out.width  = canvas.width;
    out.height = canvas.height;
    const octx = out.getContext('2d');
    octx.fillStyle = '#ffffff';
    octx.fillRect(0, 0, out.width, out.height);
    octx.drawImage(canvas, 0, 0);
    order.sign = out.toDataURL('image/png');

    $('#submitBtn').prop('disabled', true);
    ripsAjax(SUBMIT_URL, 'POST', order, function (res) {
        showToast(@json($doneMessage), 'success');
        setTimeout(function () { location.href = RIPS_BASE + '/order/' + res.idx; }, 600);
    }).always(function () {
        $('#submitBtn').prop('disabled', false);
    });
}

// 초기 행 (수정 / 상품 목록에서 발주하기)
if (INIT_ITEMS.length) {
    INIT_ITEMS.forEach(addItem);
} else {
    refresh();
}
</script>
