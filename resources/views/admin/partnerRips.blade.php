@php
	$header_skip = false;
	$depth1_num = '7';
	$depth2_num = '1';
	$canUpdate = auth()->user()->hasPerm('rips.update');
	$isMapping = in_array($tab, ['category', 'product']);
@endphp
@include('admin.header._header')
<style>
	/* 좌 업체 | 우 리치포트 (카테고리 매핑 / 상품 매핑 탭) */
	.map-tabs {display:flex; gap:4px; }
	.map-tabs a {padding:10px 22px; border:1px solid #e2e8f0; border-bottom:0; border-radius:8px 8px 0 0; background:#f8fafc; color:#64748b; font-weight:600; }
	.map-tabs a.active {background:#fff; color:rgb(var(--color-primary)); }
	.map-grid {display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:20px; }
	.map-panel {display:flex; flex-direction:column; min-width:0; }
	.map-panel .panel-head {display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:12px; min-height:34px; }
	.map-panel .panel-head h3 {font-size:16px; font-weight:600; min-width:0; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }
	.map-panel .panel-head h3 small {font-size:13px; font-weight:400; color:#64748b; }
	.map-scroll {overflow-y:auto; border-top:1px solid #e2e8f0; }
	.map-scroll.cat {height:560px; }
	.map-scroll.prod {height:420px; }

	.map-cat-list li {display:flex; align-items:center; gap:8px; padding:8px 10px; border-bottom:1px solid #f1f5f9; cursor:pointer; }
	.map-cat-list li:hover {background:#f8fafc; }
	.map-cat-list li.active {background:#e6f0f3; }
	.map-cat-list li .name {flex:1; min-width:0; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }
	.map-cat-list li .mapped {flex-shrink:0; max-width:55%; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; font-size:12px; color:#0369a1; }
	.map-cat-list li .mapped.inherit {color:#94a3b8; }
	.map-cat-list li .mapped.none {color:#dc2626; }

	.forms-cols {display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); border:1px solid #e2e8f0; border-radius:6px; overflow:hidden; }
	.forms-col {border-right:1px solid #e2e8f0; min-width:0; }
	.forms-col:last-child {border-right:0; }
	.forms-col .head {padding:6px 10px; background:#f8fafc; border-bottom:1px solid #e2e8f0; font-size:12px; font-weight:600; color:#475569; }
	.forms-col ul {height:490px; overflow-y:auto; }
	.forms-col li {display:flex; align-items:center; gap:4px; padding:6px 10px; font-size:13px; cursor:pointer; border-bottom:1px solid #f8fafc; }
	.forms-col li:hover {background:#f8fafc; }
	.forms-col li.active {background:#e6f0f3; font-weight:600; }
	.forms-col li.mapped {box-shadow:inset 3px 0 0 #0369a1; }
	.forms-col li .name {flex:1; min-width:0; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }
	.forms-col li .arrow {color:#94a3b8; font-size:10px; }
	.forms-col li.empty {color:#94a3b8; cursor:default; justify-content:center; padding:20px 6px; text-align:center; font-size:12px; }
	.forms-col li.empty:hover {background:none; }
	.forms-pick-bar {display:flex; align-items:center; gap:8px; margin-top:10px; font-size:13px; }
	.forms-pick-bar .pick {flex:1; min-width:0; }

	.map-table {width:100%; font-size:13px; }
	.map-table th {position:sticky; top:0; background:#f8fafc; padding:7px 8px; border-bottom:1px solid #e2e8f0; font-weight:600; white-space:nowrap; z-index:1; }
	.map-table td {padding:7px 8px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
	.map-table tbody tr[data-idx] {cursor:pointer; }
	.map-table tbody tr[data-idx]:hover {background:#f8fafc; }
	.map-table tbody tr.active {background:#e6f0f3; }
	.map-table tbody tr.mapped {background:#f0f9ff; box-shadow:inset 3px 0 0 #0369a1; }
	.map-table .empty {text-align:center; color:#94a3b8; padding:40px 10px; }

	.map-badge {display:inline-block; padding:2px 8px; border-radius:999px; font-size:12px; font-weight:600; white-space:nowrap; }
	.map-badge.custom {background:#fef3c7; color:#b45309; }
	.map-badge.default {background:#e0f2fe; color:#0369a1; }
	.map-badge.partner {background:#f1f5f9; color:#64748b; }
	.map-badge.none {background:#fef2f2; color:#dc2626; }
	.map-summary {display:flex; gap:16px; flex-wrap:wrap; font-size:13px; color:#475569; }
	.map-summary b {color:#0f172a; }

	.price-box {margin-top:12px; padding-top:12px; border-top:1px solid #e2e8f0; font-size:13px; }
	.price-box .row {display:flex; align-items:center; gap:10px; margin-top:6px; }
	.price-box .row > p:first-child {flex-shrink:0; width:90px; font-weight:600; color:#475569; }
	@media all and (max-width:1200px){
		.map-grid {grid-template-columns:minmax(0, 1fr); }
	}
</style>
<div class="mt-6 px-5">
	<div class="flex items-center justify-between gap-3 flex-wrap">
		<div>
			<h3 class="intro-y text-xl font-bold">RIPS 업체 상세</h3>
			<p class="mt-1 text-slate-500">
				{{ $partner->partner_name }}
				({{ $partner->branch_type_text }}@if($partner->branch_type === 'branch') · 본점 {{ $partner->head->partner_name ?? '-' }}@endif)
				@if($partner->store_name) · 매장 {{ $partner->store_name }}@endif
				· RIPS {{ $partner->rips_use ? ($partner->rips_id ?: '-') : '미사용' }}
			</p>
		</div>
		<a href="/admin/ripsPartner" class="btn btn-outline-secondary">목록으로</a>
	</div>

	<div class="map-tabs mt-6">
		<a href="{{ route('admin.ripsPartner.info', ['idx' => $partner->idx]) }}" class="{{ $tab === 'info' ? 'active' : '' }}">업체 정보</a>
		<a href="{{ route('admin.ripsPartner.category', ['idx' => $partner->idx]) }}" class="{{ $tab === 'category' ? 'active' : '' }}">카테고리 매핑</a>
		<a href="{{ route('admin.ripsPartner.products', ['idx' => $partner->idx]) }}" class="{{ $tab === 'product' ? 'active' : '' }}">상품 매핑 · 발주금액</a>
		<a href="{{ route('admin.ripsPartner.stock', ['idx' => $partner->idx]) }}" class="{{ $tab === 'stock' ? 'active' : '' }}">상품 현황</a>
		<a href="{{ route('admin.ripsPartner.sales', ['idx' => $partner->idx]) }}" class="{{ $tab === 'sales' ? 'active' : '' }}">판매 현황</a>
		<a href="{{ route('admin.ripsPartner.orders', ['idx' => $partner->idx]) }}" class="{{ $tab === 'orders' ? 'active' : '' }}">발주 현황</a>
	</div>

	@if ($tab === 'info')
		<div class="intro-y border border-slate-200 p-5 rounded-b-lg rounded-tr-lg bg-slate-50">
			@include('admin.ripsPartnerForm', ['partner' => $partner, 'canSave' => auth()->user()->hasPerm('rips.update')])
		</div>
	@elseif (!$isMapping)
		@include('admin.partnerRipsStatus')
	@else

	<div class="intro-y border border-slate-200 p-5 rounded-b-lg rounded-tr-lg bg-white">
		<div class="map-summary" id="summary"></div>
		<p class="mt-2 text-xs text-slate-500">
			@if($tab === 'category')
			· 왼쪽에서 업체 카테고리를 선택하고 오른쪽에서 리치포트 카테고리(1~4차 중 어느 단계든)를 골라 매핑합니다. 업체와 리치포트의 단계가 달라도 됩니다.<br>
			· 매핑하지 않은 업체 카테고리는 가장 가까운 상위 업체 카테고리의 매핑을 따릅니다.
			@else
			· 왼쪽에서 업체 상품을 선택하고 오른쪽에서 리치포트 상품을 매핑합니다. 리치포트 상품은 업체 카테고리에 매핑된 리치포트 카테고리(상위·하위 포함) 기준으로 먼저 보여줍니다.<br>
			· 발주금액은 매핑한 리치포트 상품의 기본 발주금액(관리자 &gt; 제품)을 기본으로 사용하고, 이 업체에만 다른 금액으로 납품할 때는 개별 입력합니다.<br>
			· 발주 단가 적용 순서 : 개별금액 → 매핑된 리치포트 상품의 기본 발주금액 → 업체가 입력한 단가
			@endif
		</p>
	</div>

	<div class="map-grid mt-5">
		@if($tab === 'category')
		<!-- 1. 업체 카테고리 -->
		<div class="map-panel intro-y border border-slate-200 p-5 rounded-lg bg-white">
			<div class="panel-head">
				<h3>업체 카테고리 <small>→ 매핑된 리치포트 카테고리</small></h3>
			</div>
			<ul class="map-scroll cat map-cat-list" id="catList"></ul>
		</div>

		<!-- 2. 리치포트 카테고리 -->
		<div class="map-panel intro-y border border-slate-200 p-5 rounded-lg bg-white">
			<div class="panel-head">
				<h3>리치포트 카테고리 <small id="formsTitle"></small></h3>
			</div>
			<div class="forms-cols" id="formsCols">
				@foreach ([1, 2, 3, 4] as $depth)
				<div class="forms-col" data-depth="{{ $depth }}">
					<div class="head">{{ $depth }}차 카테고리</div>
					<ul></ul>
				</div>
				@endforeach
			</div>
			<div class="forms-pick-bar">
				<div class="pick" id="formsPick"></div>
				@if($canUpdate)
				<button type="button" class="btn btn-primary btn-sm shrink-0" id="btnFormsSave" onclick="saveCategoryMap()">선택한 카테고리로 매핑</button>
				<button type="button" class="btn btn-outline-danger btn-sm shrink-0" id="btnFormsClear" onclick="clearCategoryMap()">매핑 해제</button>
				@endif
			</div>
		</div>

		@else
		<!-- 3. 업체 상품 -->
		<div class="map-panel intro-y border border-slate-200 p-5 rounded-lg bg-white">
			<div class="panel-head">
				<h3>업체 상품 <small id="productTitle"></small></h3>
			</div>
			<div class="flex items-center gap-2 mb-2">
				<select id="catFilter" class="form-select form-select-sm" style="max-width:260px;"></select>
				<select id="mapFilter" class="form-select form-select-sm" style="max-width:130px;">
					<option value="">매핑 전체</option>
					<option value="N">미매핑</option>
					<option value="Y">매핑완료</option>
				</select>
				<input type="text" id="productKeyword" class="form-control form-control-sm" style="max-width:180px;" placeholder="상품명 검색">
			</div>
			<div class="map-scroll prod" style="height:560px;">
				<table class="map-table">
					<thead>
						<tr>
							<th class="text-left">상품명</th>
							<th>업체명</th>
							<th class="text-left">리치포트 상품</th>
							<th class="text-right">발주금액</th>
						</tr>
					</thead>
					<tbody id="productBody"></tbody>
				</table>
			</div>
		</div>

		<!-- 4. 리치포트 상품 -->
		<div class="map-panel intro-y border border-slate-200 p-5 rounded-lg bg-white">
			<div class="panel-head">
				<h3>리치포트 상품 <small id="rpTitle"></small></h3>
				<input type="text" id="rpKeyword" class="form-control form-control-sm" style="max-width:180px;" placeholder="상품명 검색">
			</div>
			<!-- 리치포트 카테고리 1~4차 (기본 : 업체 카테고리에 매핑된 카테고리) -->
			<div class="flex items-center gap-2 mb-1">
				<div class="grid grid-cols-4 gap-1 flex-1 min-w-0" id="rpForms">
					@foreach ([1, 2, 3, 4] as $depth)
					<select data-depth="{{ $depth }}" class="form-select form-select-sm" title="{{ $depth }}차 카테고리"></select>
					@endforeach
				</div>
				<div class="form-check shrink-0">
					<input id="rpAllForms" class="form-check-input" type="checkbox">
					<label class="form-check-label" for="rpAllForms">전체</label>
				</div>
			</div>
			<p class="text-xs text-slate-500 truncate mb-2" id="rpHint"></p>
			<div class="map-scroll prod" style="height:370px;">
				<table class="map-table">
					<thead>
						<tr>
							<th class="text-left">상품명</th>
							<th>파트너</th>
							<th class="text-left">카테고리</th>
							<th class="text-right">기본금액</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="rpBody"></tbody>
				</table>
			</div>

			<!-- 선택한 업체 상품의 발주금액 -->
			<div class="price-box" id="priceBox">
				<div class="row">
					<p>금액 기준</p>
					<div class="flex items-center gap-4">
						<div class="form-check">
							<input id="price_default" name="price_type" class="form-check-input" type="radio" value="default">
							<label class="form-check-label" for="price_default">기본</label>
						</div>
						<div class="form-check">
							<input id="price_custom" name="price_type" class="form-check-input" type="radio" value="custom">
							<label class="form-check-label" for="price_custom">개별 입력</label>
						</div>
					</div>
				</div>
				<div class="row">
					<p>기본금액</p>
					<p id="defaultPrice"></p>
				</div>
				<div class="row">
					<p>개별금액</p>
					<input type="number" id="customPrice" class="form-control form-control-sm" style="max-width:180px;" min="0" placeholder="비우면 기본금액 적용" disabled>
					<p class="ml-auto">적용 <b id="appliedPrice"></b></p>
					@if($canUpdate)
					<button type="button" class="btn btn-primary btn-sm shrink-0" id="btnPriceSave" onclick="savePrice()">금액 저장</button>
					@endif
				</div>
			</div>
		</div>
		@endif
	</div>
	@endif
</div>
@include('admin.footer._footer')
@if ($isMapping)
<script>
const PARTNER_IDX = {{ $partner->idx }};
const CAN_UPDATE  = @json($canUpdate);
const FORMS       = @json($forms);
const BASE_URL    = '/admin/ripsPartner/' + PARTNER_IDX;
const TAB         = @json($tab);   // category | product

let categories = [], products = [], richportProducts = [];
let selectedCategory = 0;   // 업체 카테고리 (0 : 전체)
let selectedProduct  = 0;   // 업체 상품
let formsPick = {};         // depth => 고른 리치포트 카테고리 idx
let rpFormsIdx = null;      // 리치포트 상품 목록의 카테고리 (오른쪽 1~4차 셀렉트)

function csrf() {
    return { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') };
}

function numberFormat(v) {
    return Number(v || 0).toLocaleString('ko-KR');
}

function escapeHtml(v) {
    return String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}

function loadData() {
    $.get(BASE_URL + '/data', function (res) {
        categories       = res.categories || [];
        products         = res.products || [];
        richportProducts = res.richportProducts || [];
        if (selectedProduct && !productOf(selectedProduct)) selectedProduct = 0;
        renderSummary();
        if (TAB === 'category') {
            renderCategories();
            renderForms();
        } else {
            renderCatFilter();
            renderProducts();
            renderRichportProducts();
        }
    });
}

function categoryOf(idx) {
    return categories.find(c => c.idx === idx);
}

function productOf(idx) {
    return products.find(p => p.idx === idx);
}

function richportProductOf(idx) {
    return richportProducts.find(p => p.idx === idx);
}

function formOf(idx) {
    return FORMS.find(f => f.idx === idx);
}

// 업체 카테고리와 모든 하위 카테고리
function descendantIds(idx) {
    const ids = [idx];
    for (let i = 0; i < ids.length; i++) {
        categories.filter(c => c.parent_idx === ids[i]).forEach(c => ids.push(c.idx));
    }
    return ids;
}

// 자신 또는 가장 가까운 상위 업체 카테고리의 리치포트 카테고리 매핑
function mappedFormsIdx(categoryIdx) {
    let c = categoryOf(categoryIdx);
    while (c) {
        if (c.forms_idx) return c.forms_idx;
        c = categoryOf(c.parent_idx);
    }
    return null;
}

// 리치포트 카테고리 경로 (1차 > 2차 > ...)
function formsPath(formsIdx) {
    const names = [];
    let f = formOf(formsIdx);
    while (f) {
        names.unshift(f.subject);
        f = formOf(f.parent_idx);
    }
    return names.join(' > ');
}

// 리치포트 카테고리 자신 + 모든 하위 (리치포트 상품 목록 필터용)
function formsDescendants(formsIdx) {
    const ids = [formsIdx];
    for (let i = 0; i < ids.length; i++) {
        FORMS.filter(f => f.parent_idx === ids[i]).forEach(f => ids.push(f.idx));
    }
    return ids;
}

// 1차부터 해당 리치포트 카테고리까지의 idx 목록
function formsChain(formsIdx) {
    const chain = [];
    for (let f = formOf(formsIdx); f && chain.length < 4; f = formOf(f.parent_idx)) chain.unshift(f.idx);
    return chain;
}

function byName(a, b) {
    return String(a.product_name).localeCompare(String(b.product_name), 'ko');
}

function priceBadge(price, from) {
    const label = { custom: '개별', default: '기본', partner: '업체입력' }[from];
    return '<span class="map-badge ' + from + '">' + label + '</span> ' + numberFormat(price);
}

function renderSummary() {
    const catMapped  = categories.filter(c => c.forms_idx).length;
    const prodMapped = products.filter(p => p.product_idx).length;
    const custom     = products.filter(p => p.price_from === 'custom').length;
    $('#summary').html(
        '<span>업체 카테고리 매핑 <b>' + catMapped + '</b> / ' + categories.length + '</span>' +
        '<span>업체 상품 매핑 <b>' + prodMapped + '</b> / ' + products.length + '</span>' +
        '<span>개별 발주금액 상품 <b>' + custom + '</b>개</span>'
    );
}

// ---------------- 1. 업체 카테고리 ----------------
function renderCategories() {
    const $list = $('#catList').empty();
    $list.append('<li data-idx="0" class="' + (selectedCategory === 0 ? 'active' : '') + '"><span class="name font-semibold">전체 상품 (' + products.length + ')</span></li>');

    if (!categories.length) {
        $list.append('<li style="cursor:default;" class="text-slate-500">업체가 등록한 카테고리가 없습니다.</li>');
        return;
    }

    categories.forEach(function (c) {
        const count     = products.filter(p => descendantIds(c.idx).includes(p.category_idx)).length;
        const inherited = c.forms_idx ? null : mappedFormsIdx(c.idx);
        let mapped = '<span class="mapped none">미매핑</span>';
        if (c.forms_idx)    mapped = '<span class="mapped" title="' + escapeHtml(formsPath(c.forms_idx)) + '">' + escapeHtml(formsPath(c.forms_idx)) + '</span>';
        else if (inherited) mapped = '<span class="mapped inherit" title="상위 업체 카테고리 매핑 적용">↑ ' + escapeHtml(formsPath(inherited)) + '</span>';

        $list.append(
            '<li data-idx="' + c.idx + '" class="' + (selectedCategory === c.idx ? 'active' : '') + '">' +
                '<span class="name" style="padding-left:' + ((c.depth - 1) * 16) + 'px;" title="' + escapeHtml(c.path) + '">' +
                    (c.depth > 1 ? '└ ' : '') + escapeHtml(c.name) + ' <span class="text-xs text-slate-500">(' + count + ')</span>' +
                '</span>' +
                mapped +
            '</li>'
        );
    });
}

$('#catList').on('click', 'li[data-idx]', function () {
    selectedCategory = Number($(this).data('idx'));
    $('#catList li').removeClass('active');
    $(this).addClass('active');
    resetFormsPick();
    renderForms();
});

// ---------------- 2. 리치포트 카테고리 (1~4차 컬럼) ----------------
// 업체 카테고리 선택 시 현재 매핑된 리치포트 카테고리 경로를 펼쳐서 보여줌
function resetFormsPick() {
    formsPick = {};
    const c = categoryOf(selectedCategory);
    let f = c && c.forms_idx ? formOf(c.forms_idx) : null;
    while (f) {
        formsPick[f.depth] = f.idx;
        f = formOf(f.parent_idx);
    }
}

function pickedFormsIdx() {
    for (let d = 4; d >= 1; d--) if (formsPick[d]) return formsPick[d];
    return null;
}

function renderForms() {
    const c = categoryOf(selectedCategory);
    $('#formsTitle').text(c ? '· ' + c.path : '');

    for (let depth = 1; depth <= 4; depth++) {
        const $ul    = $('.forms-col[data-depth="' + depth + '"] ul').empty();
        const parent = depth === 1 ? 0 : formsPick[depth - 1];

        if (!c) {
            if (depth === 1) $ul.append('<li class="empty">왼쪽에서 업체 카테고리를 선택해주세요.</li>');
            continue;
        }
        if (parent === undefined) continue;

        const items = FORMS.filter(f => f.parent_idx === parent);
        if (!items.length) {
            $ul.append('<li class="empty">하위 카테고리 없음</li>');
            continue;
        }
        items.forEach(function (f) {
            const hasChild = FORMS.some(x => x.parent_idx === f.idx);
            $ul.append(
                '<li data-idx="' + f.idx + '" class="' + (formsPick[depth] === f.idx ? 'active ' : '') + (c.forms_idx === f.idx ? 'mapped' : '') + '" title="' + escapeHtml(f.subject) + '">' +
                    '<span class="name">' + escapeHtml(f.subject) + '</span>' +
                    (hasChild ? '<span class="arrow">▶</span>' : '') +
                '</li>'
            );
        });
    }

    // 하단 : 현재 매핑 / 고른 카테고리
    const picked = pickedFormsIdx();
    let html = '';
    if (c) {
        html = '현재 매핑 : ' + (c.forms_idx ? '<b class="text-primary">' + escapeHtml(formsPath(c.forms_idx)) + '</b>' : '<span class="text-danger">없음</span>');
        if (!c.forms_idx && mappedFormsIdx(c.idx)) html += ' <span class="text-slate-500">(상위 매핑 적용 : ' + escapeHtml(formsPath(mappedFormsIdx(c.idx))) + ')</span>';
        if (picked && picked !== c.forms_idx) html += '<br>선택 : <b>' + escapeHtml(formsPath(picked)) + '</b>';
    }
    $('#formsPick').html(html);
    $('#btnFormsSave').prop('disabled', !c || !picked || picked === c.forms_idx);
    $('#btnFormsClear').prop('disabled', !c || !c.forms_idx);
}

$('#formsCols').on('click', 'li[data-idx]', function () {
    const depth = Number($(this).closest('.forms-col').data('depth'));
    formsPick[depth] = Number($(this).data('idx'));
    for (let d = depth + 1; d <= 4; d++) delete formsPick[d];
    renderForms();
});

function setCategoryMap(formsIdx) {
    const idx = selectedCategory;
    if (!idx) return;
    $.ajax({
        url: BASE_URL + '/category/' + idx,
        method: 'POST',
        headers: csrf(),
        data: { forms_idx: formsIdx || '' },
        success: function (res) {
            if (res.status !== 'success') return showToast(res.message || '저장 실패', 'error');
            categoryOf(idx).forms_idx = formsIdx;
            resetFormsPick();
            renderSummary();
            renderCategories();
            renderForms();
            showToast(formsIdx ? '카테고리 매핑이 저장되었습니다.' : '카테고리 매핑이 해제되었습니다.', 'success');
        },
        error: function () { showToast('오류 발생', 'error'); }
    });
}

function saveCategoryMap() {
    const picked = pickedFormsIdx();
    if (!picked) return showToast('리치포트 카테고리를 선택해주세요.', 'error');
    setCategoryMap(picked);
}

function clearCategoryMap() {
    if (!confirm('카테고리 매핑을 해제하시겠습니까?')) return;
    setCategoryMap(null);
}

// ---------------- 3. 업체 상품 ----------------
// 업체 카테고리 필터 (하위 포함)
function renderCatFilter() {
    const $sel = $('#catFilter').empty().append('<option value="0">업체 카테고리 전체</option>');
    categories.forEach(c => $sel.append($('<option>').val(c.idx).text('　'.repeat(c.depth - 1) + (c.depth > 1 ? '└ ' : '') + c.name)));
    if (selectedCategory && !categoryOf(selectedCategory)) selectedCategory = 0;
    $sel.val(String(selectedCategory));
}

function renderProducts() {
    const keyword = $('#productKeyword').val().trim();
    const mapped  = $('#mapFilter').val();
    const ids     = selectedCategory ? descendantIds(selectedCategory) : null;
    const rows    = products.filter(p =>
        (!ids || ids.includes(p.category_idx)) &&
        (!keyword || p.product_name.indexOf(keyword) >= 0) &&
        (!mapped || (mapped === 'Y') === !!p.product_idx)
    );
    const $body   = $('#productBody').empty();

    $('#productTitle').text('(' + rows.length + ')');

    if (!rows.length) {
        $body.append('<tr><td colspan="4" class="empty">상품이 없습니다.</td></tr>');
        return;
    }

    rows.forEach(function (p) {
        const rp = p.product_idx ? richportProductOf(p.product_idx) : null;
        $body.append(
            '<tr data-idx="' + p.idx + '" class="' + (selectedProduct === p.idx ? 'active' : '') + '">' +
                '<td>' + escapeHtml(p.product_name) + '<div class="text-xs text-slate-400">' + escapeHtml(categoryOf(p.category_idx)?.path || '-') + '</div></td>' +
                '<td class="text-center">' + escapeHtml(p.company_name) + '</td>' +
                '<td>' + (rp ? escapeHtml(rp.product_name) + ' <span class="text-xs text-slate-500">' + escapeHtml(rp.partner_name) + '</span>' : '<span class="map-badge none">미매핑</span>') + '</td>' +
                '<td class="whitespace-nowrap text-right">' + priceBadge(p.order_price, p.price_from) + '</td>' +
            '</tr>'
        );
    });
}

$('#productBody').on('click', 'tr[data-idx]', function () {
    selectedProduct = Number($(this).data('idx'));
    $('#productBody tr').removeClass('active');
    $(this).addClass('active');
    resetRpForms();
    renderRichportProducts();
});

$('#productKeyword').on('input', renderProducts);
$('#mapFilter').on('change', renderProducts);
$('#catFilter').on('change', function () {
    selectedCategory = Number($(this).val());

    // 선택 중인 상품이 필터 범위 밖이면 선택 해제 (오른쪽은 고른 카테고리 기준으로 표시)
    const selected = productOf(selectedProduct);
    if (selected && selectedCategory && !descendantIds(selectedCategory).includes(selected.category_idx)) selectedProduct = 0;

    resetRpForms();
    renderProducts();
    renderRichportProducts();
});

// ---------------- 4. 리치포트 상품 + 발주금액 ----------------
// 업체 상품(또는 업체 카테고리)의 매핑 카테고리를 오른쪽 1~4차 셀렉트 기본값으로
function baseFormsIdx() {
    const p = productOf(selectedProduct);
    const c = p ? null : categoryOf(selectedCategory);
    return p || c ? mappedFormsIdx(p ? p.category_idx : c.idx) : null;
}

function resetRpForms() {
    rpFormsIdx = baseFormsIdx();
    $('#rpAllForms').prop('checked', false);
    $('#rpKeyword').val('');
}

// 리치포트 카테고리 1~4차 : 상위를 골라야 다음 단계 활성화 (전체 체크 시 비활성)
function renderRpForms(enabled) {
    const chain = rpFormsIdx ? formsChain(rpFormsIdx) : [];
    $('#rpForms select[data-depth]').each(function () {
        const depth  = Number($(this).data('depth'));
        const parent = depth === 1 ? 0 : chain[depth - 2];
        const items  = parent === undefined ? [] : FORMS.filter(f => f.parent_idx === parent);
        const $sel   = $(this).empty().append($('<option>').val('').text(depth + '차 전체'));
        items.forEach(f => $sel.append($('<option>').val(f.idx).text(f.subject)));
        $sel.val(chain[depth - 1] ? String(chain[depth - 1]) : '').prop('disabled', !enabled || !items.length);
    });
}

$('#rpForms').on('change', 'select[data-depth]', function () {
    const depth = Number($(this).data('depth'));
    // 비우면 상위 단계까지만 선택
    const idx   = $(this).val() || (depth > 1 ? $('#rpForms select[data-depth="' + (depth - 1) + '"]').val() : '');
    rpFormsIdx  = idx ? Number(idx) : null;
    renderRichportProducts();
});

// 업체 상품을 선택하면 매핑 가능, 업체 카테고리만 선택하면 해당 카테고리의 상품을 보여주기만 함
function renderRichportProducts() {
    const p     = productOf(selectedProduct);
    const c     = p ? null : categoryOf(selectedCategory);
    const $body = $('#rpBody').empty();

    $('#priceBox').toggle(!!p);
    $('#rpAllForms, #rpKeyword').prop('disabled', !p && !c);

    if (!p && !c) {
        renderRpForms(false);
        $('#rpTitle').text('');
        $('#rpHint').text('');
        $body.append('<tr><td colspan="5" class="empty">왼쪽에서 업체 카테고리 또는 상품을 선택해주세요.</td></tr>');
        return;
    }

    $('#rpTitle').text('· ' + (p ? p.product_name : c.path));

    const mappedIdx = p ? p.product_idx : null;
    const baseIdx   = baseFormsIdx();
    // 전체 체크 또는 카테고리 미선택 → 전체 상품
    const all       = $('#rpAllForms').is(':checked') || !rpFormsIdx;
    const ids       = all ? [] : formsDescendants(rpFormsIdx);
    const keyword   = $('#rpKeyword').val().trim();
    const list      = richportProducts.filter(rp =>
        (all || ids.includes(rp.forms_idx)) &&
        (!keyword || String(rp.product_name).indexOf(keyword) >= 0)
    );

    renderRpForms(!$('#rpAllForms').is(':checked'));

    $('#rpHint').text(
        (baseIdx ? '업체 카테고리 매핑 : ' + formsPath(baseIdx) : '업체 카테고리가 매핑되지 않았습니다.') +
        ' · ' + (all ? '전체 상품 ' : '[' + formsPath(rpFormsIdx) + '] 상품 ') + list.length + '개' +
        (p ? '' : ' · 매핑하려면 왼쪽에서 업체 상품을 선택해주세요.')
    );

    // 가나다순, 카테고리로 볼 때는 현재 매핑된 상품을 맨 위로
    list.sort(byName);
    if (!all) list.sort((a, b) => (b.idx === mappedIdx) - (a.idx === mappedIdx));

    if (!list.length) {
        $body.append('<tr><td colspan="5" class="empty">해당 카테고리에 등록된 리치포트 상품이 없습니다. 카테고리를 변경하거나 "전체"를 체크해주세요.</td></tr>');
    }
    list.forEach(function (rp) {
        const isMapped = rp.idx === mappedIdx;
        let btn = '';
        if (CAN_UPDATE && p) {
            btn = isMapped
                ? '<button type="button" class="btn btn-outline-danger btn-sm py-0" data-act="unmap">해제</button>'
                : '<button type="button" class="btn btn-outline-primary btn-sm py-0" data-act="map">매핑</button>';
        }
        $body.append(
            '<tr data-rp="' + rp.idx + '" class="' + (isMapped ? 'mapped' : '') + '">' +
                '<td>' + (isMapped ? '<b>✓ ' + escapeHtml(rp.product_name) + '</b>' : escapeHtml(rp.product_name)) + '</td>' +
                '<td class="text-center">' + escapeHtml(rp.partner_name) + '</td>' +
                '<td class="text-xs text-slate-500">' + escapeHtml(formsPath(rp.forms_idx)) + '</td>' +
                '<td class="whitespace-nowrap text-right">' + (rp.order_price !== null ? numberFormat(rp.order_price) : '<span class="text-slate-400">없음</span>') + '</td>' +
                '<td class="text-center whitespace-nowrap">' + btn + '</td>' +
            '</tr>'
        );
    });

    if (!p) return;

    // 발주금액
    $('input[name="price_type"][value="' + (p.price_type === 'custom' ? 'custom' : 'default') + '"]').prop('checked', true);
    $('#customPrice').val(p.custom_price ?? '');
    $('input[name="price_type"]').prop('disabled', !CAN_UPDATE);
    refreshPrice();
}

// 기본금액 = 매핑된 리치포트 상품의 기본 발주금액, 없으면 업체 입력 단가
function refreshPrice() {
    const p = productOf(selectedProduct);
    if (!p) return;

    const rp       = p.product_idx ? richportProductOf(p.product_idx) : null;
    const base     = rp && rp.order_price !== null
        ? { price: rp.order_price, label: '관리자 > 제품의 기본 발주금액' }
        : { price: p.unit_price, label: rp ? '업체 입력 단가 (매핑 상품의 기본금액 없음)' : '업체 입력 단가 (리치포트 상품 미매핑)' };
    const isCustom = $('input[name="price_type"]:checked').val() === 'custom';
    const custom   = $('#customPrice').val();

    $('#customPrice').prop('disabled', !isCustom || !CAN_UPDATE);
    $('#defaultPrice').text(numberFormat(base.price) + '  (' + base.label + ')');
    $('#appliedPrice').text(isCustom && custom !== '' ? numberFormat(custom) + ' (개별)' : numberFormat(base.price) + ' (기본)');
}

$('input[name="price_type"]').on('change', refreshPrice);
$('#customPrice').on('input', refreshPrice);
$('#rpAllForms').on('change', renderRichportProducts);
$('#rpKeyword').on('input', renderRichportProducts);

// 상품 매핑/금액 저장 (매핑과 금액은 같은 API 로 함께 저장)
function saveProduct(productIdx, priceType, customPrice, message) {
    $.ajax({
        url: BASE_URL + '/product/' + selectedProduct,
        method: 'POST',
        headers: csrf(),
        data: { product_idx: productIdx || '', price_type: priceType, custom_price: customPrice ?? '' },
        success: function (res) {
            if (res.status !== 'success') return showToast(res.message || '저장 실패', 'error');
            showToast(message, 'success');
            loadData();
        },
        error: function () { showToast('오류 발생', 'error'); }
    });
}

$('#rpBody').on('click', 'button[data-act]', function () {
    const p   = productOf(selectedProduct);
    const rp  = richportProductOf(Number($(this).closest('tr').data('rp')));
    const act = $(this).data('act');

    if (act === 'unmap') {
        if (!confirm('[' + p.product_name + '] 상품의 리치포트 상품 매핑을 해제하시겠습니까?')) return;
        return saveProduct(null, p.price_type, p.custom_price, '상품 매핑이 해제되었습니다.');
    }
    if (p.product_idx && !confirm('[' + p.product_name + '] 상품을 [' + rp.product_name + '] 으로 다시 매핑하시겠습니까?')) return;
    saveProduct(rp.idx, p.price_type, p.custom_price, '상품 매핑이 저장되었습니다.');
});

function savePrice() {
    const p        = productOf(selectedProduct);
    const isCustom = $('input[name="price_type"]:checked').val() === 'custom';
    saveProduct(p.product_idx, isCustom ? 'custom' : 'default', isCustom ? $('#customPrice').val() : '', '발주금액이 저장되었습니다.');
}

loadData();
</script>
@endif
