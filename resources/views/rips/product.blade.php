@php
	$menu = 'product';
@endphp
@include('rips.header._header')
<div class="rp-page">
	<div class="rp-page-head">
		<div>
			<h3>상품</h3>
			<p class="rp-desc">발주·판매 관리에 사용할 상품을 등록합니다. 행을 클릭하면 수정할 수 있습니다.</p>
		</div>
		<div class="rp-actions">
			<a href="javascript:;" class="btn btn-dark" onclick="openProductModal()">상품 등록</a>
		</div>
	</div>

	<div class="rp-box">
		<form method="GET" action="{{ route('rips.product', ['rid' => $partner->rips_id]) }}" class="rp-filter">
			<div class="rp-field">
				<p>카테고리</p>
				@include('rips.common._category_select', ['id' => 'filter_category', 'name' => 'category_idx', 'value' => request('category_idx'), 'placeholder' => '전체'])
			</div>
			<div class="rp-field">
				<p>검색어</p>
				<input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="상품명, 업체명 또는 바코드">
				<input type="hidden" name="status" value="{{ request('status') }}">
			</div>
			<div class="rp-actions">
				<button type="submit" class="btn btn-primary w-20">검색</button>
				<a href="{{ route('rips.product', ['rid' => $partner->rips_id]) }}" class="btn btn-outline-secondary w-20">초기화</a>
			</div>
		</form>
	</div>

	<div class="rp-box">
		<div class="rp-box-title">
			<span>상품 목록 <span class="rp-muted text-sm font-normal">총 {{ number_format($data->total()) }}개</span></span>
		</div>
		@include('rips.common._status_tabs', ['counts' => $counts, 'baseUrl' => route('rips.product', ['rid' => $partner->rips_id])])

		@if (request('status') && $orderIdxs)
			<div class="rp-bulk-order">
				<span>{{ \App\Services\RipsForecast::ALERT_TYPES[request('status')] }} 상품 <b>{{ number_format(count($orderIdxs)) }}</b>개를 추천수량으로 한 번에 발주할 수 있습니다.</span>
				<a href="{{ route('rips.order.create', ['rid' => $partner->rips_id, 'products' => $orderIdxs]) }}" class="btn btn-primary btn-sm">{{ \App\Services\RipsForecast::ALERT_TYPES[request('status')] }} 상품 발주하기</a>
			</div>
		@endif

		@include('rips.common._product_table', ['data' => $data, 'categoryMap' => $categoryMap, 'orderRid' => $partner->rips_id, 'rowClick' => true])
	</div>

	<!-- 상품 등록/수정 모달 -->
	<div id="product-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto" id="product_modal_title">상품 등록</h2>
					<button onclick="modalClose('product-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="product_idx" value="">
					<div class="rp-form-row">
						<p>카테고리 <span class="text-danger">*</span></p>
						@include('rips.common._category_select', ['id' => 'product_category_box'])
					</div>
					<p class="rp-help is-indent">· 상위 카테고리를 고르면 하위 카테고리가 열립니다. 마지막으로 선택한 카테고리에 상품이 등록됩니다.</p>
					<div class="rp-form-row">
						<p>업체명 <span class="text-danger">*</span></p>
						<input type="text" id="product_company_name" class="form-control" maxlength="200" placeholder="제조사 또는 공급업체명 (예: 삼양식품)">
					</div>
					<div class="rp-form-row">
						<p>상품명 <span class="text-danger">*</span></p>
						<input type="text" id="product_product_name" class="form-control" maxlength="200" placeholder="상품명을 입력해주세요">
					</div>
					<div class="rp-form-row">
						<p>바코드 <span class="text-danger" id="product_barcode_required">*</span></p>
						<input type="text" id="product_barcode" class="form-control" maxlength="50" placeholder="상품 바코드 번호 (영문, 숫자, - 4~50자, 예: 8801073110502)">
					</div>
					<div class="rp-form-row">
						<p>발주 단가</p>
						<input type="number" id="product_unit_price" class="form-control" min="0" placeholder="0">
					</div>
					<div class="rp-form-row">
						<p>잔여수량</p>
						<input type="number" id="product_stock_qty" class="form-control" placeholder="0">
					</div>
					<div class="rp-form-row">
						<p>예약 입고 소요기간</p>
						<div class="rp-inline">
							<input type="number" id="product_lead_days" class="form-control" min="0" placeholder="발주 후 입고까지 걸리는 일수">
							<span class="shrink-0">일</span>
						</div>
					</div>
					<p class="rp-help">· 잔여수량은 판매 데이터에 잔여수량을 입력하면 가장 최근 값으로 자동 갱신됩니다.<br>· 리치포트에서 발주금액을 지정한 상품은 발주 시 지정 금액이 우선 적용됩니다.</p>
				</div>
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-danger w-20" id="product_delete_btn" onclick="deleteProduct()" style="display:none;">삭제</button>
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('product-modal')">취소</button>
					<button type="button" class="btn btn-primary w-20" onclick="saveProduct()">저장</button>
				</div>
			</div>
		</div>
	</div>
</div>
	<!-- 계산 방식 안내 -->
	<div id="forecast-help-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">잔여소진 예상일 · 추천 발주일 · 추천수량 계산 방식</h2>
					<button onclick="modalClose('forecast-help-modal')"><i data-lucide="x" class="w-6 h-6"></i></button>
				</div>
				<div class="modal-body text-sm leading-relaxed">
					<p class="font-semibold">① 일평균 판매량</p>
					<p class="rp-help mt-1">최근 판매 데이터 기준 최대 {{ \App\Services\RipsForecast::WINDOW_DAYS }}일간 판매수량 합계 ÷ 기간 일수 (판매 데이터가 {{ \App\Services\RipsForecast::WINDOW_DAYS }}일보다 짧으면 첫 판매일부터)</p>
					<p class="font-semibold mt-3">② 잔여소진 예상일</p>
					<p class="rp-help mt-1">잔여수량 입력일 + (잔여수량 ÷ 일평균 판매량)</p>
					<p class="font-semibold mt-3">③ 추천 발주일</p>
					<p class="rp-help mt-1">(잔여수량 + 입고 대기수량)이 소진되는 날 − 예약 입고 소요기간 − 안전재고 {{ \App\Services\RipsForecast::SAFETY_DAYS }}일<br>오늘보다 이전이면 '즉시 발주'로 표시합니다.</p>
					<p class="font-semibold mt-3">④ 추천수량</p>
					<p class="rp-help mt-1">일평균 판매량 × (예약 입고 소요기간 + 안전재고 {{ \App\Services\RipsForecast::SAFETY_DAYS }}일 + 발주 주기 {{ \App\Services\RipsForecast::CYCLE_DAYS }}일) − 잔여수량 − 입고 대기수량<br>0 이하이면 '발주 불필요'로 표시합니다.</p>
					<p class="rp-help mt-4">· 입고 대기수량 : 입고완료·발주취소를 제외한 진행중 발주 수량<br>· 예약 입고 소요기간을 입력하지 않은 상품은 {{ \App\Services\RipsForecast::DEFAULT_LEAD_DAYS }}일로 계산합니다.<br>· 최근 {{ \App\Services\RipsForecast::RECENT_DAYS }}일 안에 판매 데이터가 없으면 계산하지 않습니다.<br>· AI 예측값이 있는 상품은 AI 값을 표시합니다. (날짜 옆 AI 표시, 셀에 마우스를 올리면 예측 기준일 · 모델 표시)</p>
				</div>
				<div class="modal-footer flex justify-center">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('forecast-help-modal')">닫기</button>
				</div>
			</div>
		</div>
	</div>
@include('rips.footer._footer')
<script>
const CATEGORIES = @json($categories);

// 조회 필터 / 등록 모달 카테고리 1~4차
RipsCategorySelect('#filter_category', CATEGORIES);
const productCategory = RipsCategorySelect('#product_category_box', CATEGORIES);

function openProductModal(idx) {
    $('#product_idx').val('');
    $('#product-modal').find('input:not([type=hidden])').val('');
    productCategory.reset();

    $('#product_barcode_required').toggle(!idx);
    if (!idx) {
        $('#product_modal_title').text('상품 등록');
        $('#product_delete_btn').hide();
        return modalOpen('product-modal');
    }

    ripsAjax(RIPS_BASE + '/product/detailForm/' + idx, 'POST', {}, function (res) {
        const d = res.data;
        $('#product_idx').val(d.idx);
        productCategory.set(d.category_idx);
        $('#product_company_name').val(d.company_name);
        $('#product_product_name').val(d.product_name);
        $('#product_barcode').val(d.barcode || '');
        $('#product_unit_price').val(d.unit_price);
        $('#product_stock_qty').val(d.stock_qty);
        $('#product_lead_days').val(d.lead_days ?? '');
        $('#product_modal_title').text('상품 수정');
        $('#product_delete_btn').show();
        modalOpen('product-modal');
    });
}

function saveProduct() {
    const idx  = $('#product_idx').val();
    const data = {
        category_idx: productCategory.get(),
        company_name: $('#product_company_name').val().trim(),
        product_name: $('#product_product_name').val().trim(),
        barcode:      $('#product_barcode').val().trim(),
        unit_price:   $('#product_unit_price').val(),
        stock_qty:    $('#product_stock_qty').val(),
        lead_days:    $('#product_lead_days').val(),
    };

    if (!data.category_idx) return showToast('카테고리를 선택해주세요.', 'error');
    if (!data.company_name) return showToast('업체명을 입력해주세요.', 'error');
    if (!data.product_name) return showToast('상품명을 입력해주세요.', 'error');
    if (!idx && !data.barcode) return showToast('바코드를 입력해주세요.', 'error');
    if (data.barcode && !/^[0-9A-Za-z\-]{4,50}$/.test(data.barcode)) return showToast('바코드는 영문, 숫자, 하이픈(-) 4~50자로 입력해주세요.', 'error');

    const url = idx ? RIPS_BASE + '/product/modify/' + idx : RIPS_BASE + '/product/regist';
    ripsAjax(url, 'POST', data, function () {
        showToast(idx ? '수정되었습니다.' : '등록되었습니다.', 'success');
        setTimeout(function () { location.reload(); }, 500);
    });
}

function deleteProduct() {
    const idx = $('#product_idx').val();
    if (!idx || !confirm('상품을 삭제하시겠습니까?')) return;

    ripsAjax(RIPS_BASE + '/product/delete/' + idx, 'DELETE', {}, function () {
        showToast('삭제되었습니다.', 'success');
        setTimeout(function () { location.reload(); }, 500);
    });
}
</script>
