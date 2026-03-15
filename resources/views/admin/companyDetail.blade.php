@php 
	$header_skip = false; 
	$depth1_num = '3'; 
	$depth2_num = '31'; 
@endphp 
@include('admin.header._header')

	<div class="mt-6 px-5">
		<h3 class="intro-y text-xl font-bold">발전소 상세</h3>
		<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
			<div class="modal-body">
				<p class="mb-1 font-medium " id="modalCompanyCount">발전소 정보</p>

				<div class="flex items-center gap-2 mt-3">
					<p class="shrink-0 w-32 font-medium ">발전소명 <span class="text-danger">*</span></p>
					<input type="text" id="modify_comp_name" name="modify_comp_name" class="form-control" placeholder="발전소명을 입력해주세요" value="{{ $data->comp_name }}">
				</div>

				<div class="flex items-center gap-10 mt-3">
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">지역(도/시) <span class="text-danger">*</span></p>
						<select id="modify_regions_idx" name="modify_regions_idx" class="form-select">
							<option value="">지역을 선택해주세요</option>
							@foreach ($regions as $item)
								<option value="{{$item->idx}}"  {{ $data->regions_idx == $item->idx ? 'selected' : '' }}>{{$item->name_kr}}</option>
							@endforeach
						</select>
					</div>
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">도시(시/구/군) <span class="text-danger">*</span></p>
						<select id="modify_cities_idx" name="modify_cities_idx" class="form-select">
							<option value="">도시를 선택해주세요</option>
						</select>
					</div>
				</div>

				<div class="flex items-center gap-10 mt-3">
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium">용량(kW) <span class="text-danger">*</span></p>
						<input type="text" id="modify_volume" name="modify_volume" class="form-control" placeholder="용량을 입력해주세요" value="{{ $data->volume_formatted }}">
					</div>
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium">상업운전 개시일</p>
						<div class="relative w-full">
							<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
								<i data-lucide="calendar" class="w-4 h-4"></i>
							</div>
							<input type="text" id="modify_start_date" name="modify_start_date" class="datepicker form-control pl-12" data-single-mode="true" value="{{$data->start_date}}">
						</div>
					</div>
				</div>

				<div class="flex items-center gap-10 mt-3">
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium">일사량 관측소 <span class="text-danger">*</span></p>
						<div class="relative w-full">
							<select id="modify_kma_idx" name="modify_kma_idx" class="form-select">
								<option value="">일사량 관측소를 선택해주세요</option>
								@foreach ($kmaobv as $item)
								<option value="{{ $item->idx }}" {{ $data->kma_idx == $item->idx ? 'selected' : '' }}>{{ $item->stn_name }} ({{ $item->idx }})</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium">소속 SPC</p>
						<div class="relative w-full">
							<select id="modify_group_idx" name="modify_group_idx" class="form-select">
								<option value="">SPC를 선택해주세요</option>
								@foreach ($companygroup as $item)
								<option value="{{ $item->idx }}" {{ $data->group_idx == $item->idx ? 'selected' : '' }}>{{ $item->group_name }}</option>
								@endforeach
							</select>
						</div>
					</div>						
				</div>

				<div class="flex items-center gap-10 mt-3">
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">목표 발전시간(h) </p>
					<input type="text" id="modify_tgt_kwh" name="modify_tgt_kwh" class="form-control" placeholder="목표 발전시간을 입력해주세요" value="{{$data->tgt_kwh}}">
					</div>
					<div class="flex-1 flex items-center gap-2">
						<p class="shrink-0 w-32 font-medium ">목표 발전효율(%) </p>
					<input type="text" id="modify_tgt_rate" name="modify_tgt_rate" class="form-control" placeholder="목표 발전효율을 입력해주세요" value="{{$data->tgt_rate}}">
					</div>						
				</div>

				<input type="hidden" id="modify_idx" name="modify_idx" value="">
			</div>
		</div>

		<!-- 저장, 목록, 삭제 버튼 -->
		<div class="modal-footer flex items-center justify-center gap-3">
			<button type="button" class="btn btn-outline-pending w-20 " onclick="goList();">목록</button>
			<button type="button" class="btn btn-primary w-20" onclick="savekwh()">저장</button>
			<button type="button" id="btnDeleteGroup" class="btn btn-outline-danger w-20" onclick="deletekwh()">삭제</button>
		</div>

		<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
			<!-- 탭 영역 -->
			<div class="flex items-center gap-10 border-b border-slate-200 py-3 px-6">
				<!-- <button type="button" class="detail-tab-btn active text-primary" data-tab="tab-sales">현황</button> -->
				<button type="button" class="detail-tab-btn active text-primary" data-tab="tab-sales">매출</button>
				<button type="button" class="detail-tab-btn" data-tab="tab-kwh">발전량</button>
				<button type="button" class="detail-tab-btn" data-tab="tab-om">정기 점검</button>
				<button type="button" class="detail-tab-btn" data-tab="tab-issue">이슈</button>
			</div>


			<div class="detail-tabs-wrap">
				<div class="detail-tab-panels">
					<!-- 현황 -->
					<div id="tab-summary" class="detail-tab-panel">
						<div class="text-base mb-2">총 {{ $data->companysales->count() }}개월</div>
						<div id="summaryTableArea">
						</div>
					</div>

					<!-- 매출 -->
					<div id="tab-sales" class="detail-tab-panel active">
						<!-- 매출 등록 -->
						<div class="card-body">
							<input type="hidden" id="issue_idx">

							<div class="om gap-2">
								<div class="w-40">
									<p class="mb-1">날짜</p>
									<div class="relative mx-auto">
										<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
											<i data-lucide="calendar" class="w-4 h-4"></i>
										</div>
										<input type="text" id="sales_day" name="sales_day" class="datepicker form-control pl-12" data-single-mode="true" value="">									
									</div>
								</div>
								<div class="w-24">
									<p class="mb-1">구분</p>
									<select id="sales_type" name="sales_type" class="form-select">
										<option value="REC">REC</option>
										<option value="SMP">SMP</option>
										<option value="운영비">운영비</option>
									</select>
								</div>								
								<div class="w-32">
									<p class="mb-1">수량</p>
									<input type="text" id="sales_quantity" name="sales_quantity" class="form-control">
								</div>
								<div class="w-24">
									<p class="mb-1">단가</p>
									<input type="text" id="sales_unitprice" name="sales_unitprice" class="form-control">
								</div>
								<div class="w-36">
									<p class="mb-1">매출</p>
									<input type="text" id="sales_sales" name="sales_sales" class="form-control">
								</div>
								<div class="w-36">
									<p class="mb-1">공급가</p>
									<input type="text" id="sales_supplyprice" name="sales_supplyprice" class="form-control">
								</div>
								<div class="w-36">
									<p class="mb-1">부가세</p>
									<input type="text" id="sales_vat" name="sales_vat" class="form-control">
								</div>
								<div class="w-72">
									<p class="mb-1">비고</p>
									<input type="text" id="sales_memo" name="sales_memo" class="form-control">
								</div>
								<div class="col-md-1 d-flex gap-1">
									<button type="button" id="btnSalesSave" class="btn btn-primary">저장</button>
									<button type="button" id="btnSalesCancel" class="btn btn-secondary">취소</button>
								</div>
							</div>
						</div>

						<!-- 매출 하단 목록 -->
						<div id="salesTableArea"></div>
					</div>					

					<!-- 발전량 -->
					<div id="tab-kwh" class="detail-tab-panel">
						<div class="text-base mb-2">총 {{ $data->companykwh->count() }}개월</div>
						<div id="kwhTableArea">
						</div>
					</div>

					<!-- O&M -->
					<div id="tab-om" class="detail-tab-panel">
						<!-- O&M 등록 -->
						<div class="card-body">
							<input type="hidden" id="om_idx">

							<div class="om gap-2">
								<div class="col-md-2 w-40">
									<p class="mb-1">날짜</p>
									<div class="relative mx-auto">
										<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
											<i data-lucide="calendar" class="w-4 h-4"></i>
										</div>
										<input type="text" id="om_date" name="om_date" class="datepicker form-control pl-12" data-single-mode="true" value="{{$data->start_date}}">									
									</div>
								</div>

								<div class="col-md-2 w-28">
									<p class="mb-1">구분</p>
									<select id="om_type" name="om_type" class="form-select">
										<option value="">선택</option>
										<option value="정기 점검">정기 점검</option>
										<option value="긴급 출동">긴급 출동</option>
										<option value="소모품 교체">소모품 교체</option>
									</select>
								</div>

								<div class="col-md-3 w-60p">
									<p class="mb-1">내용</p>
									<input type="text" id="om_content" name="om_content" class="form-control">
								</div>

								<div class="col-md-1 d-flex gap-1">
									<button type="button" id="btnOmSave" class="btn btn-primary">저장</button>
									<button type="button" id="btnOmCancel" class="btn btn-secondary">취소</button>
								</div>
							</div>
						</div>

						<!-- O&M 하단 목록 -->
						<div id="omTableArea"></div>
					</div>

					<!-- 이슈 -->
					<div id="tab-issue" class="detail-tab-panel">
						<!-- 이슈 등록 -->
						<div class="card-body">
							<input type="hidden" id="issue_idx">

							<div class="om gap-2">
								<div class="col-md-2 w-40">
									<p class="mb-1">날짜</p>
									<div class="relative mx-auto">
										<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
											<i data-lucide="calendar" class="w-4 h-4"></i>
										</div>
										<input type="text" id="issue_date" name="issue_date" class="datepicker form-control pl-12" data-single-mode="true" value="{{$data->start_date}}">									
									</div>
								</div>

								<input type="hidden" id="om_type" name="om_type" value="9">
								<div class="col-md-5 w-70p">
									<p class="mb-1">이슈 내용</p>
									<input type="text" id="issue_content" name="issue_content" class="form-control">
								</div>

								<div class="col-md-1 d-flex gap-1">
									<button type="button" id="btnIssueSave" class="btn btn-primary">저장</button>
									<button type="button" id="btnIssueCancel" class="btn btn-secondary">취소</button>
								</div>
							</div>
						</div>

						<!-- 이슈 하단 목록 -->
						<div id="issueTableArea"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- 발전량 수정 모달 -->
	<div id="company_kwh-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">발전량 저장</h2>
					<button onclick="modalClose('company_kwh-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<p class="mb-1 font-medium " id="kwhModalTitle"></p>	
				
					<div class="flex items-center gap-2 mt-3" id="ori_kwh_text">
						<p class="shrink-0 w-32 font-medium ">기존 발전량 </p>
						<input type="text" id="ori_kwh" name="ori_kwh" class="form-control" placeholder="발전소명을 입력해주세요" value="" readonly="readonly">
					</div>
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">발전량 <span class="text-danger">*</span></p>
						<input type="text" id="inputKwh" name="inputKwh" value="" class="form-control" placeholder="발전량을 입력해주세요">
					</div>

					<input type="hidden" id="kwh_idx">
					<input type="hidden" id="kwh_comp_idx">
					<input type="hidden" id="kwh_year">
					<input type="hidden" id="kwh_month">
				</div>
				
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20 " onclick="modalClose('company_kwh-modal')">취소</button>
					<button type="button" class="btn btn-primary w-20" onclick="savekwh()">저장</button>
					<button type="button" id="btnDeleteGroup" class="btn btn-outline-danger w-20" onclick="deletekwh()">삭제</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END: Content -->
</div>
@include('admin.footer._footer')

<script>
$(document).on('click', '.detail-tab-btn', function () {
    const tabId = $(this).data('tab');

    $('.detail-tab-btn').removeClass('active text-primary');
	$(this).addClass('active text-primary');

    $('.detail-tab-panel').removeClass('active').hide();
    $('#' + tabId).addClass('active').show();
});

document.addEventListener('DOMContentLoaded', function () {
    let reqRegion  = "{{ $data->regions_idx }}";
    let reqCity    = "{{ $data->cities_idx }}";

	// 지역이 선택된 상태라면 → 도시 목록 자동 로드 + select 유지
    if (reqRegion) {
		const $citySelect = $('#modify_cities_idx');
		$citySelect.empty().append('<option value="">도시를 선택해주세요</option>');

		$.ajax({
			url: '/admin/cities/' + reqRegion,
			method: 'GET',
			success: function (cities) {
				cities.forEach(function (city) {
					$citySelect.append(
						`<option value="${city.idx}">${city.name_kr}</option>`
					);
				});

				if (reqCity) {
					$citySelect.val(reqCity);
				}
			},
			error: function (xhr) {
				console.error(xhr.responseText);
				showToast('도시 목록을 불러오지 못했습니다.','error');
			}
		});
    }

	// 현황 표시
	//setSummaryTable(@json($data), @json($sales));

	// 매출 표시
	setSalesTable(@json($data), @json($sales));

	// 발전량 표시
	setKwhTable(@json($data), @json($kwh));
	
	// O&M 표시
	setOmTable(@json($ckeck), @json($emergency), @json($change));

	// 이슈 표시
	setIssueTable(@json($issue));
});

function modify() {
	const idx			= $('#modify_idx').val();
	const comp_name		= $('#modify_comp_name').val().trim();
	const regions_idx	= $('#modify_regions_idx').val();
	const cities_idx	= $('#modify_cities_idx').val();
	const volume		= $('#modify_volume').val().trim();
	const start_date	= $('#modify_start_date').val();
	const kma_idx		= $('#modify_kma_idx').val();
	const group_idx		= $('#modify_group_idx').val();
	const tgt_kwh		= $('#modify_tgt_kwh').val().trim();
	const tgt_rate		= $('#modify_tgt_rate').val().trim();

  	if (!comp_name)		return showToast('발전소명을 입력해주세요.','error');
  	if (!regions_idx)	return showToast('지역(도/시)을 선택해주세요.','error');
  	if (!cities_idx)	return showToast('도시(시/구/군) 선택해주세요.','error');
  	if (!volume)		return showToast('용량을 입력해주세요.','error');
  	// if (!start_date)	return showToast('상업운전 개시일을 선택해주세요.','error');
  	if (!kma_idx)		return showToast('일사량 관측소를 선택해주세요.','error');
  	// if (!group_idx)		return showToast('소속 SPC를 선택해주세요.','error');
  	// if (!tgt_kwh)		return showToast('년간 목표 발전 시간을 입력해주세요.','error');
  	// if (!tgt_rate)		return showToast('년간 목표 발전 효율을 입력해주세요.','error');

	const fd = new FormData();
	fd.append('comp_name', comp_name);
	fd.append('regions_idx', regions_idx);
	fd.append('cities_idx', cities_idx);
	fd.append('volume', volume);
	fd.append('kma_idx', kma_idx);
	fd.append('start_date', start_date);
	fd.append('group_idx', group_idx);
	fd.append('tgt_kwh', tgt_kwh);
	fd.append('tgt_rate', tgt_rate);
	
	if (!confirm('발전소명을 수정하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/company/modify/'+idx,
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('발전소가 수정되었습니다.','success');
            	location.reload();
            } else {
            	showToast(res.message || '저장 실패','error');
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}

function deleteCompany() {
	const idx		= $('#modify_idx').val();
  	if (!idx)		return showToast('오류가 발생하였습니다.','error');
	
	if (!confirm('정말로 발전소를 삭제하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/company/delete/'+idx,
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status) {
            	showToast('발전소가 삭제되었습니다.','success');
                location.href = '/admin/company';
            } else {
            	showToast(res.message || '저장 실패','error');
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}


/*******************************  현황  *******************************/
// 현황 세팅
function setSummaryTable(data, sales) {
	console.log(data);
	console.log(sales);
	let html = `<table class="table">
			<thead>
				<tr>
					<th>년도</th>
					<th>구분</th>`;
					// 1~12월 header
					for(let m=1; m<=12; m++){ html += `<th>${m}월</th>`; }
	html += `</tr></thead><tbody>`;

	if(sales && Object.keys(sales).length > 0){
		// year 목록 추출
		let years = Object.keys(sales).map(year => Number(year));

		// 마지막 year + 1 추가
		const maxYear = Math.max(...years);
		years.push(maxYear + 1);

		years.forEach(function(year){
			const items = sales[year] || [];
			html += `<tr>
					<td rowspan="5">${year}</td>
						<td>SMP</td>`;
			for(let month=1; month<=12; month++){
				const found = items.find(i => Number(i.month) === month);
				html += `<td class="sales-cell" 
						data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
						${smprec ? smprec : ''}
					</td>`;
			}
			html += `</tr>`;
			
			html += `<tr>
						<td>REC</td>`;
			for(let month=1; month<=12; month++){
				const found = items.find(i => Number(i.month) === month);
				html += `<td class="sales-cell" 
						data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
						${found ? Number(found.quantity).toLocaleString() : ''}
					</td>`;
			}
			html += `</tr>`;
			
			html += `<tr>
						<td>운영비</td>`;
			for(let month=1; month<=12; month++){
				const found = items.find(i => Number(i.month) === month);
				html += `<td class="sales-cell" 
						data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
						${found ? Number(found.unitprice).toLocaleString() : ''}
					</td>`;
			}
			html += `</tr>`;
		});

		$('#btnDeleteGroup').hide();
	}else{
		alert(2);
		const currentYear = new Date().getFullYear();

		for (let year = currentYear; year <= currentYear + 1; year++) {
			html += `<tr>`;
			html += `<td>${year}</td>
					<td></td>`;

			for (let month = 1; month <= 12; month++) {
				html += `
					<td class="sales-cell"
						data-idx="" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="" data-quantity="" data-unitprice="" data-sales="" data-supplyprice="" data-vat="">						
					</td>
				`;
			}

			html += `</tr>`;
		}
	}
	html += `</tbody></table>`;

	$('#summaryTableArea').html(html);
}


// 현황 변경사항 반영
function renderSummaryTable(idx) {
    $.ajax({
    	url: '/admin/company/sales/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				
				let html = `<table class="table">
						<thead>
							<tr>
								<th>년도</th>
								<th>구분</th>`;
								// 1~12월 header
								for(let m=1; m<=12; m++){ html += `<th>${m}월</th>`; }
				html += `</tr></thead><tbody>`;

				if(res.sales && Object.keys(res.sales).length > 0){
					// year 목록 추출
					let years = Object.keys(res.sales).map(year => Number(year));

					// 마지막 year + 1 추가
					const maxYear = Math.max(...years);
					years.push(maxYear + 1);

					years.forEach(function(year){
						const items = res.sales[year] || [];
						html += `<tr>
								<td rowspan="5">${year}</td>
									<td>SMP/REC</td>`;
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="sales-cell" 
									data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
									${smprec ? smprec : ''}
								</td>`;
						}
						html += `</tr>`;
						
						html += `<tr>
									<td>수량</td>`;
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="sales-cell" 
									data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
									${found ? Number(found.quantity).toLocaleString() : ''}
								</td>`;
						}
						html += `</tr>`;
						
						html += `<tr>
									<td>단가</td>`;
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="sales-cell" 
									data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
									${found ? Number(found.unitprice).toLocaleString() : ''}
								</td>`;
						}
						html += `</tr>`;
						
						html += `<tr>
									<td>매출</td>`;
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="sales-cell" 
									data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
									${found ? Number(found.sales).toLocaleString() : ''}
								</td>`;
						}
						html += `</tr>`;
						
						html += `<tr>
									<td>공급가</td>`;
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="sales-cell" 
									data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
									${found ? Number(found.supplyprice).toLocaleString() : ''}
								</td>`;
						}
						html += `</tr>`;
						
						html += `<tr>
									<td>부가세</td>`;
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="sales-cell" 
									data-idx="${found ? found.idx : ''}" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">
									${found ? Number(found.vat).toLocaleString() : ''}
								</td>`;
						}
						html += `</tr>`;			
					});

					$('#btnDeleteGroup').hide();


				}else{
					const currentYear = new Date().getFullYear();

					for (let year = currentYear; year <= currentYear + 1; year++) {
						html += `<tr>`;
						html += `<td>${year}</td>`;

						for (let month = 1; month <= 12; month++) {
							html += `
								<td class="kwh-cell"
									data-idx="" data-comp-name="${data.comp_name}" data-comp="${data.idx}" data-year="${year}" data-month="${month}" data-smprec="${found.smprec}" data-quantity="${found.quantity}" data-unitprice="${found.unitprice}" data-sales="${found.sales}" data-supplyprice="${found.supplyprice}" data-vat="${found.vat}">						
								</td>
							`;
						}

						html += `</tr>`;
					}
				}
				html += `</tbody></table>`;

				$('#summaryTableArea').html(html);
			
            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}



/*******************************  매출  *******************************/
// 매출 수정버튼 클릭
$(document).on('click', '.btn-sales-edit', function () {
    $('#sales_idx').val($(this).data('idx'));
    $('#sales_day').val($(this).data('day'));
    $('#sales_type').val($(this).data('type'));
    $('#sales_quantity').val($(this).data('quantity'));
    $('#sales_unitprice').val($(this).data('unitprice'));
    $('#sales_sales').val($(this).data('sales'));
    $('#sales_supplyprice').val($(this).data('supplyprice'));
    $('#sales_vat').val($(this).data('vat'));
    $('#sales_memo').val($(this).data('memo'));

});

// 이슈 취소버튼 클릭
$(document).on('click', '#btnSalesCancel', function () {
    $('#sales_idx').val('');
    $('#sales_type').val('');
    $('#sales_quantity').val('');
	$('#sales_unitprice').val('');
	$('#sales_sales').val('');
	$('#sales_supplyprice').val('');
	$('#sales_vat').val('');
	$('#sales_memo').val('');
});

// 매출 저장
$(document).on('click', '#btnSalesSave', function () {
	if (!$('#sales_day').val())			return showToast('날짜를 선택해주세요.','error');
	if (!$('#sales_sales').val())		return showToast('매출을 입력해주세요.','error');
	
    $.ajax({
        url: '/admin/company/sales/save',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
			idx: $('#sales_idx').val(),
            comp_idx: "{{ $data->idx }}",
			day: $('#sales_day').val(),
			type: $('#sales_type').val(),
           	quantity: $('#sales_quantity').val(),
           	unitprice: $('#sales_unitprice').val(),
           	sales: $('#sales_sales').val(),
           	supplyprice: $('#sales_supplyprice').val(),
           	vat: $('#sales_vat').val(),
			memo: $('#sales_memo').val()
        },
        success: function (res) {
            if (res.status === 'success') {
                renderSalesTable("{{ $data->idx }}");
                $('#btnSalesCancel').click();
            } else {
                alert(res.message || '저장 실패');
            }
        }
    });
});

// 매출 삭제
$(document).on('click', '.btn-sales-delete', function () {
    const idx = $(this).data('idx');

    if (!confirm('삭제하시겠습니까?')) return;

    $.ajax({
        url: '/admin/company/sales/delete',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            idx: idx,
            comp_idx: "{{ $data->idx }}"
        },
        success: function (res) {
            if (res.status === 'success') {
                renderSalesTable("{{ $data->idx }}");
            } else {
                alert(res.message || '삭제 실패');
            }
        }
    });
});


// 매출 세팅
function setSalesTable(salesList) {
    let html = `
		<div class="text-base mt-5 mb-2">매출 내역</div>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>날짜</th>
                    <th>구분</th>
                    <th>수량</th>
                    <th>매출</th>
					<th>공급가</th>
					<th>부가세</th>
					<th>비고</th>
					<th>수정</th>
					<th>삭제</th>
                </tr>
            </thead>
            <tbody>
    `;

    if (salesList && salesList.length > 0) {
        salesList.forEach(function(item) {
            html += `
                <tr>
                    <td>${item.day}</td>
                    <td>${item.type ?? ''}</td>
                    <td>${item.quantity ?? ''}</td>
                    <td>${item.unitprice ?? ''}</td>
                    <td>${item.sales ?? ''}</td>
                    <td>${item.supplyprice ?? ''}</td>
                    <td>${item.vat ?? ''}</td>
                    <td>${item.memo ?? ''}</td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-primary btn-issue-edit"
                            data-idx="${item.idx}"
                            data-day="${item.day ?? ''}"
							data-type="${item.type ?? ''}"
							data-quantity="${item.quantity ?? ''}"
							data-unitprice="${item.unitprice ?? ''}"
                            data-sales="${item.sales ?? ''}"
                            data-supplyprice="${item.supplyprice ?? ''}"
                            data-vat="${item.vat ?? ''}"
                            data-memo="${item.memo ?? ''}">
                            수정
                        </button>
                    </td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger btn-issue-delete"
                            data-idx="${item.idx}">
                            삭제
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        html += `<tr><td colspan="9">매출 내역이 없습니다.</td></tr>`;
    }

    html += `</tbody></table>`;
    $('#salesTableArea').html(html);
}

// 매출 수정사항 반영
function renderSalesTable(idx) {
    $.ajax({
    	url: '/admin/company/sales/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				const salesList = res.salesList;

				let html = `
					<div class="text-base mt-5 mb-2">이슈 내역</div>
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th>날짜</th>
								<th>구분</th>
								<th>수량</th>
								<th>매출</th>
								<th>공급가</th>
								<th>부가세</th>
								<th>비고</th>
								<th>수정</th>
								<th>삭제</th>
							</tr>
						</thead>
						<tbody>
				`;

				if (salesList && salesList.length > 0) {
					salesList.forEach(function(item) {
						html += `
							<tr>
								<td>${item.day}</td>
								<td>${item.manage ?? ''}</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-primary btn-issue-edit"
										data-idx="${item.idx}"
										data-day="${item.day ?? ''}"
										data-type="${item.type ?? ''}"
										data-quantity="${item.quantity ?? ''}"
										data-unitprice="${item.unitprice ?? ''}"
										data-sales="${item.sales ?? ''}"
										data-supplyprice="${item.supplyprice ?? ''}"
										data-vat="${item.vat ?? ''}"
										data-memo="${item.memo ?? ''}">
										수정
									</button>
								</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-danger btn-issue-delete"
										data-idx="${item.idx}">
										삭제
									</button>
								</td>
							</tr>
						`;
					});
				} else {
					html += `<tr><td colspan="6">매출 내역이 없습니다.</td></tr>`;
				}

				html += `</tbody></table>`;
				$('#salesTableArea').html(html);

            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}



/*******************************  발전량  *******************************/
// 수정 모달에서 TD를 클릭해서 발전량 등록,수정 모달 표시
$(document).on('click', '.kwh-cell', function () {
    const idx = $(this).data('idx') || '';
    const compIdx = $(this).data('comp') || '';
    const compName = $(this).data('comp-name') || '';
    const year = $(this).data('year') || '';
    const month = $(this).data('month') || '';
    const kwh = $(this).data('kwh') || '';

    $('#kwh_idx').val(idx);
    $('#kwh_comp_idx').val(compIdx);
    $('#kwh_year').val(year);
    $('#kwh_month').val(month);

    $('#kwhModalTitle').text(`${compName} / ${year}년 ${month}월`);

	if(kwh) {
		$('#ori_kwh_text').show();
		$('#ori_kwh').val(Number(kwh).toLocaleString());
		$('#inputKwh').val('');
	} else {
		$('#ori_kwh_text').hide();
		$('#ori_kwh').val('');
		$('#inputKwh').val('');
	}

    if (idx) {
        // 수정 모드
        $('#btnKwhDelete').show();
    } else {
        // 등록 모드
        $('#btnKwhDelete').hide();
    }

    modalOpen2('company_kwh-modal');
});

// 발전량 저장
function savekwh() {
    const idx 		= $('#kwh_idx').val();
    const comp_idx 	= $('#kwh_comp_idx').val();
    const year 		= $('#kwh_year').val();
    const month 	= $('#kwh_month').val();
    const kwh 		= $('#inputKwh').val();

    if (kwh === '') {
        alert('발전량을 입력해주세요.');
        return;
    }

	const fd = new FormData();
	fd.append('idx', idx);
	fd.append('comp_idx', comp_idx);
	fd.append('year', year);
	fd.append('month', month);
	fd.append('kwh', kwh);

    $.ajax({
        url: '/admin/company/kwh/save',
        type: 'POST',
        data: fd,
		processData: false,
        contentType: false,
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
			 if (res.status) {
            	showToast('발전량이 저장되었습니다.','success');
            	modalClose('company_kwh-modal');

				// 발전량 변경사항 반영
				renderKwhTable(comp_idx);
            } else {
            	showToast(res.message || '저장 실패','error');
            }
        },
        error: function () {
            alert('저장 중 오류가 발생했습니다.');
        }
    });
}

// 발전량 삭제
function deletekwh() {
    const idx = $('#kwh_idx').val();
	const comp_idx 	= $('#kwh_comp_idx').val();

    if (!idx) return;

    if (!confirm('해당 발전량 데이터를 삭제하시겠습니까?')) {
        return;
    }

	const fd = new FormData();
	fd.append('idx', idx);	

    $.ajax({
        url: '/admin/company/kwh/delete',
        type: 'POST',
        data: fd,
		processData: false,
        contentType: false,
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },		
        success: function (res) {
			 if (res.status) {
            	showToast('발전량이 삭제되었습니다.','success');
            	modalClose('company_kwh-modal');

				// 발전량 변경사항 반영
				renderKwhTable(comp_idx);
            } else {
            	showToast(res.message || '삭제 실패','error');
            }
        },
        error: function () {
            alert('삭제 중 오류가 발생했습니다.');
        }
    });
}


// 발전량 변경사항 반영
function setKwhTable(data, kwh) {

	let html = `<table class="table">
			<thead>
				<tr>
					<th>년도</th>`;
					// 1~12월 header
					for(let m=1; m<=12; m++){ html += `<th>${m}월</th>`; }
	html += `</tr></thead><tbody>`;

	if(kwh && Object.keys(kwh).length > 0){
		// year 목록 추출
		let years = Object.keys(kwh).map(year => Number(year));

		// 마지막 year + 1 추가
		const maxYear = Math.max(...years);
		years.push(maxYear + 1);

		years.forEach(function(year){
			const items = kwh[year] || [];
			html += `<tr>`;
			html += `<td>${year}</td>`;

			// 1~12월 반복
			for(let month=1; month<=12; month++){
				const found = items.find(i => Number(i.month) === month);
				html += `<td class="kwh-cell" 
						data-idx="${found ? found.idx : ''}" 
						data-comp-name="${ data.comp_name }" 
						data-comp="${ data.idx }" 
						data-year="${year}" 
						data-month="${month}"
						data-kwh="${found ? Number(found.act_kwh) : ''}">
						${found ? Number(found.act_kwh).toLocaleString() : ''}
					</td>`;
			}

			html += `</tr>`;
		});

		$('#btnDeleteGroup').hide();
	}else{
		const currentYear = new Date().getFullYear();

		for (let year = currentYear; year <= currentYear + 1; year++) {
			html += `<tr>`;
			html += `<td>${year}</td>`;

			for (let month = 1; month <= 12; month++) {
				html += `
					<td class="kwh-cell"
						data-idx=""
						data-comp-name="${data.comp_name}"
						data-comp="${data.idx}"
						data-year="${year}"
						data-month="${month}"
						data-kwh="">
					</td>
				`;
			}

			html += `</tr>`;
		}
	}
	html += `</tbody></table>`;

	$('#kwhTableArea').html(html);
}


// 발전량 변경사항 반영
function renderKwhTable(idx) {
    $.ajax({
    	url: '/admin/company/kwh/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				// kwh 랜더링
				let html = `<table class="table">
						<thead>
							<tr>
								<th>년도</th>`;
								// 1~12월 header
								for(let m=1; m<=12; m++){ html += `<th>${m}월</th>`; }
				html += `</tr></thead><tbody>`;

				if(res.kwh && Object.keys(res.kwh).length > 0){
					// year 목록 추출
					let years = Object.keys(res.kwh).map(year => Number(year));

					// 마지막 year + 1 추가
					const maxYear = Math.max(...years);
					years.push(maxYear + 1);

					years.forEach(function(year){
						const items = res.kwh[year] || [];
						html += `<tr>`;
						html += `<td>${year}</td>`;

						// 1~12월 반복
						for(let month=1; month<=12; month++){
							const found = items.find(i => Number(i.month) === month);
							html += `<td class="kwh-cell" 
									data-idx="${found ? found.idx : ''}" 
									data-comp-name="${res.data.comp_name}"
									data-comp="${res.data.idx}" 
									data-year="${year}" 
									data-month="${month}"
									data-kwh="${found ? Number(found.act_kwh) : ''}">
									${found ? Number(found.act_kwh).toLocaleString() : ''}
								</td>`;
						}

						html += `</tr>`;
					});

					$('#btnDeleteGroup').hide();
				}else{
					const currentYear = new Date().getFullYear();

					for (let year = currentYear; year <= currentYear + 1; year++) {
						html += `<tr>`;
						html += `<td>${year}</td>`;

						for (let month = 1; month <= 12; month++) {
							html += `
								<td class="kwh-cell"
									data-idx=""
									data-comp-name="${res.data.comp_name}"
									data-comp="${res.data.idx}"
									data-year="${year}"
									data-month="${month}"
									data-kwh="">
								</td>
							`;
						}

						html += `</tr>`;
					}
				}
				html += `</tbody></table>`;

				$('#kwhTableArea').html(html);

            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}




/*******************************  O&M  *******************************/
// O&M 수정버튼 클릭
$(document).on('click', '.btn-om-edit', function () {
    $('#om_idx').val($(this).data('idx'));
    $('#om_day').val($(this).data('day'));
    $('#om_type').val($(this).data('type'));
    $('#om_content').val($(this).data('content'));
});

// O&M 수정취소 클릭
$(document).on('click', '#btnOmCancel', function () {
    $('#om_idx').val('');
    $('#om_day').val('');
    $('#om_type').val('');
    $('#om_content').val('');
});

// O&M 저장
$(document).on('click', '#btnOmSave', function () {
	if (!$('#om_type').val())		return showToast('구분을 선택해주세요.','error');
	if (!$('#om_content').val())		return showToast('내용을 입력해주세요.','error');

    $.ajax({
        url: '/admin/company/manage/save',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            idx: $('#om_idx').val(),
            comp_idx: "{{ $data->idx }}",
            day: $('#om_date').val(),
            type: $('#om_type').val(),
            manage: $('#om_content').val()
        },
        success: function (res) {
            if (res.status === 'success') {
                renderOmTable("{{ $data->idx }}");
                $('#btnOmCancel').click();
            } else {
                alert(res.message || '저장 실패');
            }
        }
    });
});

// O&M 삭제
$(document).on('click', '.btn-om-delete', function () {
    const idx = $(this).data('idx');

    if (!confirm('삭제하시겠습니까?')) return;

    $.ajax({
        url: '/admin/company/manage/delete',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            idx: idx,
            comp_idx: "{{ $data->idx }}"
        },
        success: function (res) {
            if (res.status === 'success') {
                renderOmTable("{{ $data->idx }}");
            } else {
                alert(res.message || '삭제 실패');
            }
        }
    });
});

// O&M 세팅
function setOmTable(checkList, emergencyList, changeList) {

    let html = `
		<div class="text-base mt-5 mb-2">정기 점검 내역</div>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>날짜</th>
                    <th>구분</th>
                    <th>내용</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
    `;

    if (checkList && checkList.length > 0) {
        checkList.forEach(function(item) {
            html += `
                <tr>
                    <td>${item.day}</td>
                    <td>${item.type ?? ''}</td>
                    <td>${item.manage ?? ''}</td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-primary btn-om-edit"
                            data-idx="${item.idx}"
                            data-day="${item.day}"
                            data-type="${item.type ?? ''}"
                            data-content="${item.manage ?? ''}">
                            수정
                        </button>
                    </td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger btn-om-delete"
                            data-idx="${item.idx}">
                            삭제
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        html += `<tr><td colspan="7">정기 점검 내역이 없습니다.</td></tr>`;
    }

    html += `</tbody></table>`;


    html += `
		<div class="text-base mt-5 mb-2">긴급 출동 내역</div>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>날짜</th>
                    <th>구분</th>
                    <th>내용</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
    `;

    if (emergencyList && emergencyList.length > 0) {
        emergencyList.forEach(function(item) {
            html += `
                <tr>
                    <td>${item.day}</td>
                    <td>${item.type ?? ''}</td>
                    <td>${item.manage ?? ''}</td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-primary btn-om-edit"
                            data-idx="${item.idx}"
                            data-day="${item.day}"
                            data-type="${item.type ?? ''}"
                            data-content="${item.manage ?? ''}">
                            수정
                        </button>
                    </td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger btn-om-delete"
                            data-idx="${item.idx}">
                            삭제
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        html += `<tr><td colspan="7">긴급 출동 내역이 없습니다.</td></tr>`;
    }

    html += `</tbody></table>`;


    html += `
		<div class="text-base mt-5 mb-2">소모품 교체 내역</div>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>날짜</th>
                    <th>구분</th>
                    <th>내용</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
    `;

    if (changeList && changeList.length > 0) {
        changeList.forEach(function(item) {
            html += `
                <tr>
                    <td>${item.day}</td>
                    <td>${item.type ?? ''}</td>
                    <td>${item.manage ?? ''}</td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-primary btn-om-edit"
                            data-idx="${item.idx}"
                            data-day="${item.day}"
                            data-type="${item.type ?? ''}"
                            data-content="${item.manage ?? ''}">
                            수정
                        </button>
                    </td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger btn-om-delete"
                            data-idx="${item.idx}">
                            삭제
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        html += `<tr><td colspan="7">소모품 교체 내역이 없습니다.</td></tr>`;
    }

    html += `</tbody></table>`;

    $('#omTableArea').html(html);
}

// O&M 수정사항 반영
function renderOmTable(idx) {
	$.ajax({
    	url: '/admin/company/manage/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
					const checkList = res.checkList;
					const emergencyList = res.emergencyList;					
					const changeList = res.changeList;		

				let html = `
					<div class="text-base mt-5 mb-2">정기 점검 내역</div>
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th>날짜</th>
								<th>구분</th>
								<th>내용</th>
								<th>수정</th>
								<th>삭제</th>
							</tr>
						</thead>
						<tbody>
				`;

				if (checkList && checkList.length > 0) {
					checkList.forEach(function(item) {
						html += `
							<tr>
								<td>${item.day}</td>
								<td>${item.type ?? ''}</td>
								<td>${item.manage ?? ''}</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-primary btn-om-edit"
										data-idx="${item.idx}"
										data-day="${item.day}"
										data-type="${item.type ?? ''}"
										data-content="${item.manage ?? ''}">
										수정
									</button>
								</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-danger btn-om-delete"
										data-idx="${item.idx}">
										삭제
									</button>
								</td>
							</tr>
						`;
					});
				} else {
					html += `<tr><td colspan="7">정기 점검 내역이 없습니다.</td></tr>`;
				}

				html += `</tbody></table>`;


				html += `
					<div class="text-base mt-5 mb-2">긴급 출동 내역</div>
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th>날짜</th>
								<th>구분</th>
								<th>내용</th>
								<th>수정</th>
								<th>삭제</th>
							</tr>
						</thead>
						<tbody>
				`;

				if (emergencyList && emergencyList.length > 0) {
					emergencyList.forEach(function(item) {
						html += `
							<tr>
								<td>${item.day}</td>
								<td>${item.type ?? ''}</td>
								<td>${item.manage ?? ''}</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-primary btn-om-edit"
										data-idx="${item.idx}"
										data-day="${item.day}"
										data-type="${item.type ?? ''}"
										data-content="${item.manage ?? ''}">
										수정
									</button>
								</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-danger btn-om-delete"
										data-idx="${item.idx}">
										삭제
									</button>
								</td>
							</tr>
						`;
					});
				} else {
					html += `<tr><td colspan="7">긴급 출동 내역이 없습니다.</td></tr>`;
				}

				html += `</tbody></table>`;


				html += `
					<div class="text-base mt-5 mb-2">소모품 교체 내역</div>
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th>날짜</th>
								<th>구분</th>
								<th>내용</th>
								<th>수정</th>
								<th>삭제</th>
							</tr>
						</thead>
						<tbody>
				`;

				if (changeList && changeList.length > 0) {
					changeList.forEach(function(item) {
						html += `
							<tr>
								<td>${item.day}</td>
								<td>${item.type ?? ''}</td>
								<td>${item.manage ?? ''}</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-primary btn-om-edit"
										data-idx="${item.idx}"
										data-day="${item.day}"
										data-type="${item.type ?? ''}"
										data-content="${item.manage ?? ''}">
										수정
									</button>
								</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-danger btn-om-delete"
										data-idx="${item.idx}">
										삭제
									</button>
								</td>
							</tr>
						`;
					});
				} else {
					html += `<tr><td colspan="7">소모품 교체 내역이 없습니다.</td></tr>`;
				}

				html += `</tbody></table>`;

				$('#omTableArea').html(html);

            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}


/*******************************  이슈  *******************************/
// 이슈 수정버튼 클릭
$(document).on('click', '.btn-issue-edit', function () {
    $('#issue_idx').val($(this).data('idx'));
    $('#issue_day').val($(this).data('day'));
    $('#issue_content').val($(this).data('content'));
});

// 이슈 취소버튼 클릭
$(document).on('click', '#btnIssueCancel', function () {
    $('#issue_idx').val('');
    $('#issue_day').val('');
    $('#issue_content').val('');
});

// 이슈 저장
$(document).on('click', '#btnIssueSave', function () {
	if (!$('#issue_content').val())		return showToast('내용을 입력해주세요.','error');

    $.ajax({
        url: '/admin/company/manage/save',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            idx: $('#issue_idx').val(),
            comp_idx: "{{ $data->idx }}",
            day: $('#issue_date').val(),
           	type: '이슈',			
            manage: $('#issue_content').val()
        },
        success: function (res) {
            if (res.status === 'success') {
                renderIssueTable("{{ $data->idx }}");
                $('#btnIssueCancel').click();
            } else {
                alert(res.message || '저장 실패');
            }
        }
    });
});

// 이슈 삭제
$(document).on('click', '.btn-issue-delete', function () {
    const idx = $(this).data('idx');

    if (!confirm('삭제하시겠습니까?')) return;

    $.ajax({
        url: '/admin/company/manage/delete',
        type: 'POST',
        dataType: 'json',
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            idx: idx,
            comp_idx: "{{ $data->idx }}"
        },
        success: function (res) {
            if (res.status === 'success') {
                renderIssueTable("{{ $data->idx }}");
            } else {
                alert(res.message || '삭제 실패');
            }
        }
    });
});

// 이슈 세팅
function setIssueTable(issueList) {
    let html = `
		<div class="text-base mt-5 mb-2">이슈 내역</div>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>날짜</th>
                    <th>내용</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
    `;

    if (issueList && issueList.length > 0) {
        issueList.forEach(function(item) {
            html += `
                <tr>
                    <td>${item.day}</td>
                    <td>${item.manage ?? ''}</td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-primary btn-issue-edit"
                            data-idx="${item.idx}"
                            data-day="${item.day}"
                            data-content="${item.manage ?? ''}">
                            수정
                        </button>
                    </td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger btn-issue-delete"
                            data-idx="${item.idx}">
                            삭제
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        html += `<tr><td colspan="6">이슈 내역이 없습니다.</td></tr>`;
    }

    html += `</tbody></table>`;
    $('#issueTableArea').html(html);
}

// 이슈 수정사항 반영
function renderIssueTable(idx) {
    $.ajax({
    	url: '/admin/company/manage/'+idx,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				const issueList = res.issueList;

				let html = `
					<div class="text-base mt-5 mb-2">이슈 내역</div>
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th>날짜</th>
								<th>내용</th>
								<th>수정</th>
								<th>삭제</th>
							</tr>
						</thead>
						<tbody>
				`;

				if (issueList && issueList.length > 0) {
					issueList.forEach(function(item) {
						html += `
							<tr>
								<td>${item.day}</td>
								<td>${item.manage ?? ''}</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-primary btn-issue-edit"
										data-idx="${item.idx}"
										data-day="${item.day}"
										data-content="${item.manage ?? ''}">
										수정
									</button>
								</td>
								<td>
									<button type="button"
										class="btn btn-sm btn-outline-danger btn-issue-delete"
										data-idx="${item.idx}">
										삭제
									</button>
								</td>
							</tr>
						`;
					});
				} else {
					html += `<tr><td colspan="6">이슈 내역이 없습니다.</td></tr>`;
				}

				html += `</tbody></table>`;
				$('#issueTableArea').html(html);

            } else {
            	showToast(res.message || '불러오기 실패','error');
                window.location.reload();
            }
        },
        error: function (xhr) {
        	showToast('오류 발생','error');
            console.error(xhr.responseText);
        }
    });
}


</script>