@use('App\Models\RipsSales')
@php
	$menu = 'sales';
	$productsJson = $products->map(fn ($p) => [
		'idx'           => $p->idx,
		'name'          => $p->product_name,
		'company'       => $p->company_name,
		'stock_qty'     => $p->stock_qty,
		'category_idx'  => $p->category_idx,
	])->values();
@endphp
@include('rips.header._header')
<div class="rp-page">
	<div class="rp-page-head">
		<div>
			<h3>판매관리</h3>
			<p class="rp-desc">판매 데이터를 직접 입력하거나, 엑셀 업로드 또는 API로 연동해 관리합니다. 아직 판매가 없는 상품은 [잔여재고 등록]으로 재고만 등록할 수 있습니다. 행을 클릭하면 수정할 수 있습니다.</p>
		</div>
		<div class="rp-actions">
			<a href="javascript:;" class="btn btn-outline-secondary" onclick="modalOpen('setting-modal')">
				<i data-lucide="settings" class="w-4 h-4 mr-1"></i> API 연동 설정
				@if ($setting->api_use)<span class="rp-badge received ml-1">사용중</span>@endif
			</a>
			<a href="javascript:;" class="btn btn-outline-secondary" onclick="openUploadModal()">
				<i data-lucide="file-spreadsheet" class="w-4 h-4 mr-1"></i> 엑셀 업로드
			</a>
			<a href="javascript:;" class="btn btn-outline-secondary" onclick="openStockModal()">
				<i data-lucide="boxes" class="w-4 h-4 mr-1"></i> 잔여재고 등록
			</a>
			<a href="javascript:;" class="btn btn-dark" onclick="openSalesModal()">직접 입력</a>
		</div>
	</div>

	<div class="rp-box">
		<form method="GET" action="{{ route('rips.sales', ['rid' => $partner->rips_id]) }}" class="rp-filter">
			<div class="rp-field">
				<p>판매일자</p>
				<div class="flex items-center gap-2">
					<input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
					<span>~</span>
					<input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
				</div>
			</div>
			<div class="rp-field">
				<p>카테고리</p>
				@include('rips.common._category_select', ['id' => 'filter_category', 'name' => 'category_idx', 'value' => request('category_idx'), 'placeholder' => '전체'])
			</div>
			<div class="rp-field">
				<p>구분</p>
				<select name="input_type" class="form-select">
					<option value="">전체</option>
					@foreach (RipsSales::INPUT_TYPES as $key => $label)
						<option value="{{ $key }}" {{ request('input_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
					@endforeach
				</select>
			</div>
			<div class="rp-field">
				<p>등록경로</p>
				<select name="source" class="form-select">
					<option value="">전체</option>
					@foreach (RipsSales::SOURCES as $key => $label)
						<option value="{{ $key }}" {{ request('source') === $key ? 'selected' : '' }}>{{ $label }}</option>
					@endforeach
				</select>
			</div>
			<div class="rp-field">
				<p>상품명</p>
				<input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="상품명">
			</div>
			<div class="rp-actions">
				<button type="submit" class="btn btn-primary w-20">검색</button>
				<a href="{{ route('rips.sales', ['rid' => $partner->rips_id]) }}" class="btn btn-outline-secondary w-20">초기화</a>
			</div>
		</form>
	</div>

	<div class="rp-box">
		<div class="rp-box-title">
			<span>판매 목록 <span class="rp-muted text-sm font-normal">총 {{ number_format($summary->cnt) }}건</span></span>
			<span class="text-sm font-normal">총 판매수량 <b>{{ number_format($summary->qty) }}</b> · 총 판매금액 <b>{{ number_format($summary->amount) }}</b></span>
		</div>
		@include('rips.common._sales_table', ['data' => $data, 'categoryMap' => $categoryMap, 'clickable' => true])
	</div>

	<!-- 판매 직접입력 / 수정 모달 -->
	<div id="sales-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto" id="sales_modal_title">판매 데이터 입력</h2>
					<button onclick="modalClose('sales-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="sales_idx" value="">
					<div class="rp-form-row">
						<p>구분 <span class="text-danger">*</span></p>
						<div class="rp-inline">
							<div class="form-check mr-4">
								<input id="sales_type_daily" name="sales_input_type" class="form-check-input" type="radio" value="daily" checked>
								<label class="form-check-label" for="sales_type_daily">일별합계</label>
							</div>
							<div class="form-check">
								<input id="sales_type_case" name="sales_input_type" class="form-check-input" type="radio" value="case">
								<label class="form-check-label" for="sales_type_case">건별</label>
							</div>
						</div>
					</div>
					<div class="rp-form-row">
						<p>판매일자 <span class="text-danger">*</span></p>
						<div class="rp-inline">
							<input type="date" id="sales_date" class="form-control">
							<input type="time" id="sales_time" class="form-control" style="display:none;" title="판매시각 (건별)">
						</div>
					</div>
					<div class="rp-form-row">
						<p>카테고리</p>
						@include('rips.common._category_select', ['id' => 'sales_category', 'placeholder' => '전체'])
					</div>
					<p class="rp-help is-indent">· 카테고리를 단계별로 고르면 해당 카테고리(하위 포함)의 상품만 상품명 목록에 표시됩니다.</p>
					<div class="rp-form-row">
						<p>상품명 <span class="text-danger">*</span></p>
						<select id="sales_product_idx" class="form-select"></select>
					</div>
					<div class="rp-form-row">
						<p>총 판매수량 <span class="text-danger">*</span></p>
						<input type="number" id="sales_qty" class="form-control" min="1" placeholder="0">
					</div>
					<div class="rp-form-row">
						<p>총 판매가 <span class="text-danger">*</span></p>
						<input type="number" id="sales_total_amount" class="form-control" min="0" placeholder="0">
					</div>
					<div class="rp-form-row">
						<p>상품별 가격</p>
						<div class="w-full">
							<input type="text" id="sales_unit_price" class="form-control" readonly placeholder="총 판매가 / 총 수량 (반올림)">
						</div>
					</div>
					<div class="rp-form-row">
						<p>잔여수량</p>
						<input type="number" id="sales_stock_qty" class="form-control" placeholder="판매 후 남은 수량">
					</div>
					<div class="rp-form-row">
						<p>프로모션 여부</p>
						<div class="rp-inline">
							<div class="form-check mr-4">
								<input id="sales_promo_n" name="sales_promo_yn" class="form-check-input" type="radio" value="N" checked>
								<label class="form-check-label" for="sales_promo_n">N</label>
							</div>
							<div class="form-check mr-4">
								<input id="sales_promo_y" name="sales_promo_yn" class="form-check-input" type="radio" value="Y">
								<label class="form-check-label" for="sales_promo_y">Y</label>
							</div>
							<select id="sales_promotion" class="form-select" style="max-width:160px; display:none;">
								<option value="discount">할인</option>
								<option value="1+1">1+1</option>
								<option value="2+1">2+1</option>
							</select>
						</div>
					</div>
					<div class="rp-form-row" id="sales_discount_row" style="display:none;">
						<p>할인율</p>
						<div class="rp-inline">
							<input type="number" id="sales_discount_rate" class="form-control" min="0" max="100" step="0.01" placeholder="예: 10" style="max-width:160px;">
							<span class="shrink-0">%</span>
						</div>
					</div>
					<div class="rp-form-row">
						<p>판매매대</p>
						<select id="sales_display_place" class="form-select">
							@foreach (RipsSales::DISPLAY_PLACES as $key => $label)
								<option value="{{ $key }}">{{ $label }}</option>
							@endforeach
						</select>
					</div>
					<p class="rp-help">· 잔여수량을 입력하면 해당 상품의 잔여수량이 가장 최근 판매 데이터 기준으로 갱신됩니다.</p>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-danger w-20" id="sales_delete_btn" onclick="deleteSales()" style="display:none;">삭제</button>
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('sales-modal')">취소</button>
					<button type="button" class="btn btn-primary w-20" onclick="saveSales()">저장</button>
				</div>
			</div>
		</div>
	</div>

	<!-- 잔여재고 등록 / 수정 모달 (판매 없이 잔여수량만) -->
	<div id="stock-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto" id="stock_modal_title">잔여재고 등록</h2>
					<button onclick="modalClose('stock-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="stock_idx" value="">
					<div class="rp-form-row">
						<p>기준일자 <span class="text-danger">*</span></p>
						<input type="date" id="stock_date" class="form-control">
					</div>
					<div class="rp-form-row">
						<p>카테고리</p>
						@include('rips.common._category_select', ['id' => 'stock_category', 'placeholder' => '전체'])
					</div>
					<div class="rp-form-row">
						<p>상품명 <span class="text-danger">*</span></p>
						<select id="stock_product_idx" class="form-select"></select>
					</div>
					<div class="rp-form-row">
						<p>현재 잔여수량</p>
						<input type="text" id="stock_current" class="form-control" readonly>
					</div>
					<div class="rp-form-row">
						<p>잔여수량 <span class="text-danger">*</span></p>
						<input type="number" id="stock_qty" class="form-control" min="0" placeholder="기준일자의 실제 재고 수량">
					</div>
					<p class="rp-help">· RIPS 도입 후 아직 판매가 없는 상품, 입고·재고조사로 수량이 바뀐 상품의 잔여수량만 등록합니다. (판매수량·금액은 0으로 저장)<br>· 상품의 잔여수량은 가장 최근 일자의 판매/재고 데이터 기준으로 갱신됩니다.<br>· 여러 상품은 엑셀 업로드에서 구분을 '재고'로 입력해 한 번에 등록할 수 있습니다.</p>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-danger w-20" id="stock_delete_btn" onclick="deleteStock()" style="display:none;">삭제</button>
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('stock-modal')">취소</button>
					<button type="button" class="btn btn-primary w-20" onclick="saveStock()">저장</button>
				</div>
			</div>
		</div>
	</div>

	<!-- 엑셀 업로드 모달 -->
	<div id="upload-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">엑셀 업로드</h2>
					<button onclick="modalClose('upload-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<ol class="text-sm" style="line-height:1.9;">
						<li>1. 양식을 내려받아 2행부터 판매 데이터를 입력합니다. (예시 행은 지우고 입력)</li>
						<li>2. 상품명은 [상품] 메뉴에 등록된 이름과 똑같이 입력해야 합니다.</li>
						<li>3. 판매 없이 잔여재고만 등록할 때는 구분에 '재고'를 입력하고 판매수량·판매가는 비워둡니다.</li>
						<li>4. 한 행이라도 오류가 있으면 전체가 등록되지 않으니, 오류 내용을 고친 뒤 다시 올려주세요.</li>
					</ol>
					<a href="{{ route('rips.sales.template', ['rid' => $partner->rips_id]) }}" class="btn btn-outline-secondary mt-3">
						<i data-lucide="download" class="w-4 h-4 mr-1"></i> 업로드 양식 다운로드
					</a>
					<div class="rp-form-row" style="margin-top:20px;">
						<p>엑셀 파일 <span class="text-danger">*</span></p>
						<input type="file" id="upload_file" class="form-control" accept=".xlsx,.xls,.csv">
					</div>
					<div id="upload_errors" class="rp-error-list" style="display:none;"></div>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('upload-modal')">취소</button>
					<button type="button" class="btn btn-primary w-20" id="upload_btn" onclick="uploadSales()">업로드</button>
				</div>
			</div>
		</div>
	</div>

	<!-- API 연동 설정 모달 -->
	<div id="setting-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">API 연동 설정</h2>
					<button onclick="modalClose('setting-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<p class="text-sm">POS·ERP 등 외부 시스템에서 아래 주소로 판매 데이터를 보내면 자동으로 등록됩니다.</p>
					<div class="rp-form-row">
						<p>API 연동</p>
						<div class="form-check form-switch">
							<input id="setting_api_use" class="form-check-input" type="checkbox" {{ $setting->api_use ? 'checked' : '' }}>
							<label class="form-check-label" for="setting_api_use">사용</label>
						</div>
					</div>
					<div class="rp-form-row">
						<p>수신 주소</p>
						<input type="text" class="form-control" readonly value="POST {{ route('rips.api.sales') }}">
					</div>
					<div class="rp-form-row">
						<p>인증키</p>
						<div class="rp-inline">
							<input type="text" id="setting_api_key" class="form-control" readonly value="{{ $setting->api_key }}" placeholder="API 연동을 켜고 저장하면 발급됩니다.">
							<button type="button" class="btn btn-outline-secondary shrink-0" onclick="saveSetting(true)">재발급</button>
						</div>
					</div>
					<div class="rp-form-row">
						<p>마지막 수신</p>
						<span>{{ $setting->last_received_at?->format('Y-m-d H:i:s') ?? '-' }}</span>
					</div>

					<p class="mt-5 mb-2 font-semibold">요청 예시</p>
<div class="rp-code">POST {{ route('rips.api.sales') }}
X-RIPS-KEY: (인증키)
Content-Type: application/json

{
  "rows": [
    {
      "input_type": "daily",          // daily(일별합계) | case(건별) | stock(재고등록: stock_qty 만)
      "sales_date": "{{ now()->toDateString() }}",
      "sales_time": "13:30",          // 건별일 때만 (선택)
      "product_name": "{{ $products->first()->product_name ?? '등록된 상품명' }}",   // 또는 "product_idx": 상품번호
      "qty": 10,                      // 총 판매수량
      "total_amount": 120000,         // 총 판매가
      "stock_qty": 35,                // 잔여수량 (선택)
      "promotion": "discount",        // N | discount | 1+1 | 2+1
      "discount_rate": 10,            // 할인율(%) - 할인일 때만 (선택)
      "display_place": "basic"        // "" | basic | counter | event
    }
  ]
}</div>
					<p class="rp-help">· 한 번에 최대 1000건까지 보낼 수 있으며, 한 건이라도 오류가 있으면 전체가 등록되지 않고 오류 목록이 응답됩니다.<br>· 인증키를 재발급하면 이전 키로는 더 이상 전송할 수 없습니다.</p>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('setting-modal')">닫기</button>
					<button type="button" class="btn btn-primary w-20" onclick="saveSetting(false)">저장</button>
				</div>
			</div>
		</div>
	</div>
</div>
@include('rips.footer._footer')
<script>
const PRODUCTS   = @json($productsJson);
const CATEGORIES = @json($categories);

// 조회 필터 / 등록 모달 카테고리 1~4차
RipsCategorySelect('#filter_category', CATEGORIES);
const salesCategory = RipsCategorySelect('#sales_category', CATEGORIES, function () { renderProductOptions(); });

// 선택한 카테고리(하위 포함)의 상품만 표시
function renderProductOptions(selectedIdx) {
    const categoryIdx = salesCategory.get();
    const ids  = categoryIdx ? ripsDescendantIds(CATEGORIES, categoryIdx) : null;
    const $sel = $('#sales_product_idx').empty().append('<option value="">상품을 선택해주세요</option>');

    PRODUCTS.filter(p => !ids || ids.includes(p.category_idx))
    .forEach(function (p) {
        $sel.append($('<option>').val(p.idx).text(p.name + ' (' + p.company + ')'));
    });
    if (selectedIdx) $sel.val(String(selectedIdx));
}

function calcUnitPrice() {
    const qty    = Number($('#sales_qty').val());
    const amount = Number($('#sales_total_amount').val());
    $('#sales_unit_price').val(qty > 0 && amount >= 0 ? numberFormat(Math.round(amount / qty)) : '');
}

function toggleSalesFields() {
    $('#sales_time').toggle($('input[name="sales_input_type"]:checked').val() === 'case');
    $('#sales_promotion').toggle($('input[name="sales_promo_yn"]:checked').val() === 'Y');
    $('#sales_discount_row').toggle(isDiscount());
}

// 프로모션 Y + 할인일 때만 할인율 입력
function isDiscount() {
    return $('input[name="sales_promo_yn"]:checked').val() === 'Y' && $('#sales_promotion').val() === 'discount';
}

$('#sales_qty, #sales_total_amount').on('input', calcUnitPrice);
$('input[name="sales_input_type"], input[name="sales_promo_yn"], #sales_promotion').on('change', toggleSalesFields);

function openSalesModal(idx) {
    $('#sales_idx').val('');
    salesCategory.reset();
    renderProductOptions();
    $('#sales_type_daily').prop('checked', true);
    $('#sales_promo_n').prop('checked', true);
    $('#sales_date').val(new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 10));
    $('#sales_time, #sales_qty, #sales_total_amount, #sales_unit_price, #sales_stock_qty, #sales_discount_rate').val('');
    $('#sales_promotion').val('discount');
    $('#sales_display_place').val('');

    if (!idx) {
        $('#sales_modal_title').text('판매 데이터 입력');
        $('#sales_delete_btn').hide();
        toggleSalesFields();
        return modalOpen('sales-modal');
    }

    ripsAjax(RIPS_BASE + '/sales/detailForm/' + idx, 'POST', {}, function (res) {
        const d = res.data;
        $('#sales_idx').val(d.idx);
        $('input[name="sales_input_type"][value="' + d.input_type + '"]').prop('checked', true);
        $('#sales_date').val(d.sales_date_text);
        $('#sales_time').val(d.sales_time_text);
        salesCategory.set(d.category_idx);
        renderProductOptions(d.product_idx);
        $('#sales_qty').val(d.qty);
        $('#sales_total_amount').val(d.total_amount);
        $('#sales_stock_qty').val(d.stock_qty ?? '');
        if (d.promotion !== 'N') {
            $('#sales_promo_y').prop('checked', true);
            $('#sales_promotion').val(d.promotion);
        }
        $('#sales_discount_rate').val(d.discount_rate !== null ? Number(d.discount_rate) : '');
        $('#sales_display_place').val(d.display_place);
        calcUnitPrice();
        toggleSalesFields();
        $('#sales_modal_title').text('판매 데이터 수정');
        $('#sales_delete_btn').show();
        modalOpen('sales-modal');
    });
}

function saveSales() {
    const idx       = $('#sales_idx').val();
    const inputType = $('input[name="sales_input_type"]:checked').val();
    const data = {
        input_type:    inputType,
        sales_date:    $('#sales_date').val(),
        sales_time:    inputType === 'case' ? $('#sales_time').val() : '',
        product_idx:   $('#sales_product_idx').val(),
        qty:           $('#sales_qty').val(),
        total_amount:  $('#sales_total_amount').val(),
        stock_qty:     $('#sales_stock_qty').val(),
        promotion:     $('input[name="sales_promo_yn"]:checked').val() === 'Y' ? $('#sales_promotion').val() : 'N',
        discount_rate: isDiscount() ? $('#sales_discount_rate').val() : '',
        display_place: $('#sales_display_place').val(),
    };

    const rate = Number(data.discount_rate);
    if (data.discount_rate !== '' && (!(rate > 0) || rate > 100)) return showToast('할인율은 0보다 크고 100 이하로 입력해주세요.', 'error');

    if (!data.sales_date) return showToast('판매일자를 입력해주세요.', 'error');
    if (!data.product_idx) return showToast('상품을 선택해주세요.', 'error');
    if (!(Number(data.qty) > 0)) return showToast('총 판매수량을 입력해주세요.', 'error');
    if (data.total_amount === '') return showToast('총 판매가를 입력해주세요.', 'error');

    const url = idx ? RIPS_BASE + '/sales/modify/' + idx : RIPS_BASE + '/sales/regist';
    ripsAjax(url, 'POST', data, function () {
        showToast(idx ? '수정되었습니다.' : '등록되었습니다.', 'success');
        setTimeout(function () { location.reload(); }, 500);
    });
}

function deleteSales() {
    const idx = $('#sales_idx').val();
    if (!idx || !confirm('판매 데이터를 삭제하시겠습니까?')) return;

    ripsAjax(RIPS_BASE + '/sales/delete/' + idx, 'DELETE', {}, function () {
        showToast('삭제되었습니다.', 'success');
        setTimeout(function () { location.reload(); }, 500);
    });
}

// 잔여재고 등록
const stockCategory = RipsCategorySelect('#stock_category', CATEGORIES, function () { renderStockProductOptions(); });

function renderStockProductOptions(selectedIdx) {
    const categoryIdx = stockCategory.get();
    const ids  = categoryIdx ? ripsDescendantIds(CATEGORIES, categoryIdx) : null;
    const $sel = $('#stock_product_idx').empty().append('<option value="">상품을 선택해주세요</option>');

    PRODUCTS.filter(p => !ids || ids.includes(p.category_idx))
    .forEach(function (p) {
        $sel.append($('<option>').val(p.idx).text(p.name + ' (' + p.company + ')'));
    });
    if (selectedIdx) $sel.val(String(selectedIdx));
    showCurrentStock();
}

function showCurrentStock() {
    const product = PRODUCTS.find(p => String(p.idx) === String($('#stock_product_idx').val()));
    $('#stock_current').val(product ? numberFormat(product.stock_qty) : '');
}
$('#stock_product_idx').on('change', showCurrentStock);

function openStockModal(idx) {
    $('#stock_idx').val('');
    stockCategory.reset();
    renderStockProductOptions();
    $('#stock_date').val(new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 10));
    $('#stock_qty').val('');

    if (!idx) {
        $('#stock_modal_title').text('잔여재고 등록');
        $('#stock_delete_btn').hide();
        return modalOpen('stock-modal');
    }

    ripsAjax(RIPS_BASE + '/sales/detailForm/' + idx, 'POST', {}, function (res) {
        const d = res.data;
        $('#stock_idx').val(d.idx);
        $('#stock_date').val(d.sales_date_text);
        stockCategory.set(d.category_idx);
        renderStockProductOptions(d.product_idx);
        $('#stock_qty').val(d.stock_qty ?? '');
        $('#stock_modal_title').text('잔여재고 수정');
        $('#stock_delete_btn').show();
        modalOpen('stock-modal');
    });
}

function saveStock() {
    const idx  = $('#stock_idx').val();
    const data = {
        input_type:  'stock',
        sales_date:  $('#stock_date').val(),
        product_idx: $('#stock_product_idx').val(),
        stock_qty:   $('#stock_qty').val(),
    };

    if (!data.sales_date) return showToast('기준일자를 입력해주세요.', 'error');
    if (!data.product_idx) return showToast('상품을 선택해주세요.', 'error');
    if (data.stock_qty === '' || Number(data.stock_qty) < 0) return showToast('잔여수량을 0 이상으로 입력해주세요.', 'error');

    const url = idx ? RIPS_BASE + '/sales/modify/' + idx : RIPS_BASE + '/sales/regist';
    ripsAjax(url, 'POST', data, function () {
        showToast(idx ? '수정되었습니다.' : '잔여재고가 등록되었습니다.', 'success');
        setTimeout(function () { location.reload(); }, 500);
    });
}

function deleteStock() {
    const idx = $('#stock_idx').val();
    if (!idx || !confirm('잔여재고 등록 내역을 삭제하시겠습니까?')) return;

    ripsAjax(RIPS_BASE + '/sales/delete/' + idx, 'DELETE', {}, function () {
        showToast('삭제되었습니다.', 'success');
        setTimeout(function () { location.reload(); }, 500);
    });
}

function openUploadModal() {
    $('#upload_file').val('');
    $('#upload_errors').hide().empty();
    modalOpen('upload-modal');
}

function uploadSales() {
    const file = $('#upload_file')[0].files[0];
    if (!file) return showToast('엑셀 파일을 선택해주세요.', 'error');

    const fd = new FormData();
    fd.append('file', file);

    $('#upload_btn').prop('disabled', true);
    $('#upload_errors').hide().empty();

    $.ajax({
        url: RIPS_BASE + '/sales/upload',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        success: function (res) {
            if (res.status === 'success') {
                showToast(res.count + '건이 등록되었습니다.', 'success');
                setTimeout(function () { location.reload(); }, 800);
                return;
            }
            showToast(res.message || '업로드에 실패했습니다.', 'error');
            if (res.errors && res.errors.length) {
                $('#upload_errors').html(res.errors.map(escapeHtml).join('<br>')).show();
            }
        },
        error: function (xhr) {
            showToast('오류가 발생했습니다.', 'error');
            console.error(xhr.responseText);
        },
        complete: function () {
            $('#upload_btn').prop('disabled', false);
        }
    });
}

function saveSetting(regenerate) {
    if (regenerate && !confirm('인증키를 재발급하시겠습니까?\n이전 인증키로는 더 이상 전송할 수 없습니다.')) return;

    ripsAjax(RIPS_BASE + '/sales/setting', 'POST', {
        api_use: $('#setting_api_use').is(':checked') ? 1 : 0,
        regenerate: regenerate ? 1 : 0,
    }, function (res) {
        $('#setting_api_key').val(res.data.api_key || '');
        showToast(regenerate ? '인증키가 재발급되었습니다.' : '저장되었습니다.', 'success');
    });
}
</script>
