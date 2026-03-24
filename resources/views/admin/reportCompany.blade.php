@php 
	$header_skip = false; 
	$depth1_num = '3'; 
	$depth2_num = '32'; 
@endphp 
@include('admin.header._header')

	<div class="mt-6 px-5">
		<h3 class="intro-y text-xl font-bold">발전소 관리</h3>
		<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
			<form method="GET" action="/admin/company">
				<div class="flex items-end gap-3">
					<div>
						<p class="mb-1">지역</p>
						<select id="regions_idx" name="regions_idx" class="form-select w-36">
							<option value="">전체</option>
							@foreach ($regions as $item)
							<option value="{{$item->idx}}" {{ request('regions_idx') == $item->idx ? 'selected' : '' }}>{{$item->name_kr}}</option>
							@endforeach
						</select>
					</div>
					<div>
						<p class="mb-1">도시</p>
						<select id="cities_idx" name="cities_idx" class="form-select w-36">
							<option value="">전체</option>
						</select>
					</div>				
					<div>
						<p class="mb-1">발전소명</p>
						<input type="text" id="comp_name" name="comp_name" class="form-control" placeholder="발전소명을 입력해주세요" value="{{ request('comp_name') }}" >
					</div>
					<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
				</div>
			</form>
		</div>

		<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
			<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
				<div class="text-base text-primary">총 {{ $data->total() }}개</div>
				<div class="flex items-center gap-2">
					<a href="javascript:;" class="btn btn-dark px-4" onclick="modalOpen('company_regist-modal')">발전소 등록</a>
				</div>
			</div>

			<div class="p-6">
				<table class="table table-bordered table-hover">
					<colgroup>
						<col width="*">
						<col width="*">
						<col width="*">
						<col width="*">
						<col width="*">
						<col width="*">
						<col width="*">
						<col width="*">
						<col width="*">
					</colgroup>
					<thead class="bg-slate-50 text-center">
						<tr>
							<th class="whitespace-nowrap">번호</th>
							<th class="whitespace-nowrap">발전소명</th>
							<th class="whitespace-nowrap">지역</th>
							<th class="whitespace-nowrap">도시</th>
							<th class="whitespace-nowrap">용량(kW)</th>
							<th class="whitespace-nowrap">상업운전</th>
							<th class="whitespace-nowrap">일사량측정</th>
							<th class="whitespace-nowrap">SPC명</th>
							<th class="whitespace-nowrap">등록일</th>
						</tr>
					</thead>
					<tbody class="text-center font-medium">
						@forelse ($data as $index => $item)
						@php
							$number = ($data->currentPage() - 1) * $data->perPage() + ($index + 1);
						@endphp
							<tr onclick="companyDetail('{{ $item->idx }}')">
								<td>{{ $index + 1 }}</td>
								<td>{{ $item->comp_name }}</td>
								<td>{{ $item->regions?->name_kr }}</td>
								<td>{{ $item->cities?->name_kr }}</td>
								<td>{{ $item->volume_formatted }}</td>
								<td>{{ $item->start_date?->format('Y-m-d') }}</td>
								<td>{{ $item->kmaobv?->stn_name }}</td>
								<td>{{ $item->companygroup?->group_name }}</td>
								<td>{{ $item->created_at?->format('Y-m-d') }}</td>
							</tr>
						@empty
						<tr>
							<td colspan="9" class="text-center">등록된 발전소가 없습니다.</td>
						</tr>
						@endforelse
					</tbody>
				</table>
				@php
					$currentPage = $data->currentPage();
					$lastPage = $data->lastPage();
					
					$blockSize = 10;                               
					$currentBlock = (int) ceil($currentPage / $blockSize);
				
					$start = ($currentBlock - 1) * $blockSize + 1; 
					$end = min($start + $blockSize - 1, $lastPage); 
				@endphp
				<nav class="w-full mt-5">
					<ul class="pagination justify-center">
						{{-- 첫 페이지 --}}
						<li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $data->url(1) }}"> <i data-lucide="chevrons-left" class="w-4 h-4"></i></a>
						</li> 
						{{-- 이전 페이지 --}}
						<li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $data->previousPageUrl() }}"> <i data-lucide="chevron-left" class="w-4 h-4"></i></a>
						</li> 
						{{-- 페이지 번호 --}} 
						@for ($i = $start; $i <= $end; $i++)
							<li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
								<a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
							</li>
						@endfor 
						{{-- 다음 페이지 --}}
						<li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $data->nextPageUrl() }}"> <i data-lucide="chevron-right" class="w-4 h-4"></i></a>
						</li> 
						{{-- 마지막 페이지 --}}
						<li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $data->url($lastPage) }}"> <i data-lucide="chevrons-right" class="w-4 h-4"></i></a>
						</li>
					</ul>
				</nav>	
			</div>
		</div>
	</div>

	<!-- 발전소 등록 모달 -->
	<div id="company_regist-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto"발전소 등록</h2>
					<button onclick="modalClose('company_regist-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">발전소명 <span class="text-danger">*</span></p>
						<input type="text" id="regist_comp_name" name="regist_comp_name" class="form-control" placeholder="발전소명을 입력해주세요">
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">지역(도/시) <span class="text-danger">*</span></p>
							<select id="regist_regions_idx" name="regist_regions_idx" class="form-select">
								<option value="">지역을 선택해주세요</option>
								@foreach ($regions as $item)
								<option value="{{$item->idx}}">{{$item->name_kr}}</option>
								@endforeach
							</select>
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">도시(시/구/군) <span class="text-danger">*</span></p>
							<select id="regist_cities_idx" name="regist_cities_idx" class="form-select">
								<option value="">도시를 선택해주세요</option>
							</select>
						</div>
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">용량(kW) <span class="text-danger">*</span></p>
							<input type="text" id="regist_volume" name="regist_volume" class="form-control">
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">상업운전 개시일</p>
							<div class="relative w-full">
								<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
									<i data-lucide="calendar" class="w-4 h-4"></i>
								</div>
								<input type="text" id="regist_start_date" name="regist_start_date" class="datepicker form-control pl-12" data-single-mode="true">
							</div>
						</div>
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">일사량 관측소 <span class="text-danger">*</span></p>
							<div class="relative w-full">
								<select id="regist_kma_idx" name="regist_kma_idx" class="form-select">
									<option value="">일사량 관측소를 선택해주세요</option>
									@foreach ($kmaobv as $item)
									<option value="{{$item->idx}}">{{$item->stn_name}} ({{$item->idx}})</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">소속 SPC</p>
							<div class="relative w-full">
								<select id="regist_group_idx" name="regist_group_idx" class="form-select">
									<option value="">SPC를 선택해주세요</option>
									@foreach ($companygroup as $item)
									<option value="{{$item->idx}}">{{$item->group_name}}</option>
									@endforeach
								</select>
							</div>
						</div>						
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">년간 목표 발전시간(h) </p>
						<input type="text" id="regist_tgt_kwh" name="regist_tgt_kwh" class="form-control" placeholder="목표 발전시간을 입력해주세요">
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">년간 목표 발전효율(%) </p>
						<input type="text" id="regist_tgt_rate" name="regist_tgt_rate" class="form-control" placeholder="목표 발전효율을 입력해주세요">
						</div>						
					</div>										
				</div>
				
				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('company_regist-modal')">취소</button>
					<button type="button" class="btn btn-primary w-20" onclick="regist();">등록</button>
				</div>
			</div>
		</div>
	</div>

	<!-- 발전소 수정 모달 -->
	<div id="company_modify-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">발전소 수정</h2>
					<button onclick="modalClose('company_modify-modal')">
						<i data-lucide="x" class="w-6 h-6"></i>
					</button>
				</div>
				<div class="modal-body">
					<p class="mb-1 font-medium " id="modalCompanyCount">발전소 정보</p>

					<div class="flex items-center gap-2 mt-3">
						<p class="shrink-0 w-32 font-medium ">발전소명 <span class="text-danger">*</span></p>
						<input type="text" id="modify_comp_name" name="modify_comp_name" class="form-control" placeholder="발전소명을 입력해주세요">
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">지역(도/시) <span class="text-danger">*</span></p>
							<select id="modify_regions_idx" name="modify_regions_idx" class="form-select">
								<option value="">지역을 선택해주세요</option>
								@foreach ($regions as $item)
								<option value="{{$item->idx}}">{{$item->name_kr}}</option>
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
							<input type="text" id="modify_volume" name="modify_volume" class="form-control">
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium">상업운전 개시일</p>
							<div class="relative w-full">
								<div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
									<i data-lucide="calendar" class="w-4 h-4"></i>
								</div>
								<input type="text" id="modify_start_date" name="modify_start_date" class="datepicker form-control pl-12" data-single-mode="true">
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
									<option value="{{$item->idx}}">{{$item->stn_name}} ({{$item->idx}})</option>
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
									<option value="{{$item->idx}}">{{$item->group_name}}</option>
									@endforeach
								</select>
							</div>
						</div>						
					</div>
					<div class="flex items-center gap-10 mt-3">
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">목표 발전시간(h) </p>
						<input type="text" id="modify_tgt_kwh" name="modify_tgt_kwh" class="form-control" placeholder="목표 발전시간을 입력해주세요">
						</div>
						<div class="flex-1 flex items-center gap-2">
							<p class="shrink-0 w-32 font-medium ">목표 발전효율(%) </p>
						<input type="text" id="modify_tgt_rate" name="modify_tgt_rate" class="form-control" placeholder="목표 발전효율을 입력해주세요">
						</div>						
					</div>
					<input type="hidden" id="modify_idx" name="modify_idx" value="">
				</div>
				









				<div class="detail-tabs-wrap">
					<div class="detail-tabs">
						<button type="button" class="detail-tab-btn active" data-tab="tab-kwh">발전량</button>
						<button type="button" class="detail-tab-btn" data-tab="tab-om">O&amp;M</button>
						<button type="button" class="detail-tab-btn" data-tab="tab-issue">이슈</button>
					</div>

					<div class="detail-tab-panels">
						<div id="tab-kwh" class="detail-tab-panel active">
							<div id="kwhTableArea"></div>
						</div>

						<div id="tab-om" class="detail-tab-panel">
							<!-- O&M 상단 입력 -->
							<div class="card mb-3">
								<div class="card-body">
									<input type="hidden" id="om_idx">

									<div class="row g-2 align-items-end">
										<div class="col-md-2">
											<label>년</label>
											<select id="om_year" class="form-select"></select>
										</div>

										<div class="col-md-2">
											<label>월</label>
											<select id="om_month" class="form-select"></select>
										</div>

										<div class="col-md-2">
											<label>일</label>
											<select id="om_day" class="form-select"></select>
										</div>

										<div class="col-md-2">
											<label>구분</label>
											<select id="om_type" class="form-select">
												<option value="">선택</option>
												<option value="1">정기 점검</option>
												<option value="2">긴급출동</option>
												<option value="3">소모품 교체</option>
											</select>
										</div>

										<div class="col-md-3">
											<label>내용</label>
											<input type="text" id="om_content" class="form-control">
										</div>

										<div class="col-md-1 d-flex gap-1">
											<button type="button" id="btnOmSave" class="btn btn-primary">저장</button>
											<button type="button" id="btnOmCancel" class="btn btn-secondary">취소</button>
										</div>
									</div>
								</div>
							</div>

							<!-- O&M 하단 목록 -->
							<div id="omTableArea"></div>
						</div>

						<div id="tab-issue" class="detail-tab-panel">
							<!-- 이슈 상단 입력 -->
							<div class="card mb-3">
								<div class="card-body">
									<input type="hidden" id="issue_idx">

									<div class="row g-2 align-items-end">
										<div class="col-md-2">
											<label>년</label>
											<select id="issue_year" class="form-select"></select>
										</div>

										<div class="col-md-2">
											<label>월</label>
											<select id="issue_month" class="form-select"></select>
										</div>

										<div class="col-md-2">
											<label>일</label>
											<select id="issue_day" class="form-select"></select>
										</div>

										<div class="col-md-5">
											<label>이슈 내용</label>
											<input type="text" id="issue_content" class="form-control">
										</div>

										<div class="col-md-1 d-flex gap-1">
											<button type="button" id="btnIssueSave" class="btn btn-primary">저장</button>
											<button type="button" id="btnIssueCancel" class="btn btn-secondary">취소</button>
										</div>
									</div>
								</div>
							</div>

							<!-- 이슈 하단 목록 -->
							<div id="issueTableArea"></div>
						</div>
					</div>
				</div>

















				<div class="modal-footer flex items-center justify-center gap-3">
					<button type="button" class="btn btn-outline-secondary w-20 " onclick="modalClose('company_modify-modal')">취소</button>
					<button type="button" class="btn btn-primary w-20" onclick="modify()">수정</button>
					<button type="button" id="btnDeleteGroup" class="btn btn-outline-danger w-20" onclick="deleteCompany()">삭제</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- 수정 모달 > 발전량 등록,수정 모달 -->
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

    $('.detail-tab-btn').removeClass('active');
    $(this).addClass('active');

    $('.detail-tab-panel').removeClass('active').hide();
    $('#' + tabId).addClass('active').show();
});

document.addEventListener('DOMContentLoaded', function () {

    let reqRegion  = "{{ request('regions_idx') }}";
    let reqCity    = "{{ request('cities_idx') }}";

    // 지역이 선택된 상태라면 → 도시 목록 자동 로드 + select 유지
    if (reqRegion) {

        $.ajax({
            url: '/admin/cities/' + reqRegion,
            method: 'GET',
            success: function (res) {
                const $citySelect = $('#cities_idx');

                // 초기화
                $citySelect.empty().append('<option value="">전체</option>');

                // 옵션 채우기
                res.forEach(function(city) {
                    $citySelect.append(
                        `<option value="${city.idx}" ${reqCity == city.idx ? 'selected' : ''}>
                            ${city.name_kr}
                        </option>`
                    );
                });
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }
});

function bindRegionCity(regionSelector, citySelector, defaultText) {
    $(regionSelector).on('change', function() {
        let regionsIdx = $(this).val();
        let $citySelect = $(citySelector);

        // 초기화
        $citySelect.empty().append(`<option value="">${defaultText}</option>`);

        if (!regionsIdx) return;

        $.ajax({
            url: '/admin/cities/' + regionsIdx,
            method: 'GET',
            success: function (res) {
                res.forEach(function(city) {
                    $citySelect.append(
                        `<option value="${city.idx}">${city.name_kr}</option>`
                    );
                });
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                showToast('도시 목록을 불러오지 못했습니다.','error');
            }
        });
    });
}

$(function () {
    bindRegionCity('#regions_idx', '#cities_idx', '전체');
    bindRegionCity('#regist_regions_idx', '#regist_cities_idx', '도시를 선택해주세요.');
    bindRegionCity('#modify_regions_idx', '#modify_cities_idx', '도시를 선택해주세요.');
});


function regist() {
	const comp_name		= $('#regist_comp_name').val().trim();
	const regions_idx	= $('#regist_regions_idx').val();
	const cities_idx	= $('#regist_cities_idx').val();
	const volume		= $('#regist_volume').val().trim();
	const start_date	= $('#regist_start_date').val();
	const kma_idx		= $('#regist_kma_idx').val();
	const group_idx		= $('#regist_group_idx').val();
	const tgt_kwh		= $('#regist_tgt_kwh').val().trim();
	const tgt_rate		= $('#regist_tgt_rate').val().trim();

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
	
	if (!confirm('발전소를 등록하시겠습니까?')) return;

    $.ajax({
    	url: '/admin/company/regist',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	showToast('발전소가 저장되었습니다.','success');
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

function companyDetail(idx) {
	location.href='/admin/company/detail/'+idx;
}

function setModifyForm(idx, type) {
    $.ajax({
    	url: '/admin/company/detailForm/'+idx,
        method: 'POST',
        /* processData: false,
        contentType: false, */
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
				$('#modify_idx').val(idx);

				$('#modify_comp_name').val(res.data.comp_name);
				$('#modify_volume').val(res.data.volume);
				$('#modify_start_date').val(res.data.start_date?.substr(0, 10));
				$('#modify_kma_idx').val(res.data.kma_idx);
				$('#modify_group_idx').val(res.data.group_idx);
				$('#modify_tgt_kwh').val(res.data.tgt_kwh);
				$('#modify_tgt_rate').val(res.data.tgt_rate);
				
				$('#modify_regions_idx').val(res.data.regions_idx);

				const $citySelect = $('#modify_cities_idx');
				$citySelect.empty().append('<option value="">도시를 선택해주세요</option>');

				if (res.data.regions_idx) {
					$.ajax({
						url: '/admin/cities/' + res.data.regions_idx,
						method: 'GET',
						success: function (cities) {
							cities.forEach(function (city) {
								$citySelect.append(
									`<option value="${city.idx}">${city.name_kr}</option>`
								);
							});

							if (res.data.cities_idx) {
								$citySelect.val(res.data.cities_idx);
							}
						},
						error: function (xhr) {
							console.error(xhr.responseText);
							showToast('도시 목록을 불러오지 못했습니다.','error');
						}
					});
				}

				// kwh 랜더링
				renderKwhTable(res.kwh, res.data.comp_name, res.data.idx);

				if(type === 'open') {
                	modalOpen('company_modify-modal');
				}
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


// 발전량
function renderKwhTable(kwhList, comp_name, com_idx) {
	let html = `<table class="table">
			<thead>
				<tr>
					<th>년도</th>`;
					// 1~12월 header
					for(let m=1; m<=12; m++){ html += `<th>${m}월</th>`; }
	html += `</tr></thead><tbody>`;

	if(kwhList && Object.keys(kwhList).length > 0){
		// year 목록 추출
		let years = Object.keys(kwhList).map(year => Number(year));

		// 마지막 year + 1 추가
		const maxYear = Math.max(...years);
		years.push(maxYear + 1);

		years.forEach(function(year){
			const items = kwhList[year] || [];
			html += `<tr>`;
			html += `<td>${year}</td>`;

			// 1~12월 반복
			for(let month=1; month<=12; month++){
				const found = items.find(i => Number(i.month) === month);
				html += `<td class="kwh-cell" 
						data-idx="${found ? found.idx : ''}" 
						data-comp-name="${comp_name}"
						data-comp="${com_idx}" 
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
						data-comp="${com_idx}"
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

// O&M
function renderOmTable(omList) {
    let html = `
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>년</th>
                    <th>월</th>
                    <th>일</th>
                    <th>구분</th>
                    <th>내용</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
    `;

    if (omList && omList.length > 0) {
        omList.forEach(function(item) {
            html += `
                <tr>
                    <td>${item.year}</td>
                    <td>${item.month}</td>
                    <td>${item.day}</td>
                    <td>${item.om_type ?? ''}</td>
                    <td>${item.content ?? ''}</td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-primary btn-om-edit"
                            data-idx="${item.idx}"
                            data-year="${item.year}"
                            data-month="${item.month}"
                            data-day="${item.day}"
                            data-type="${item.om_type ?? ''}"
                            data-content="${item.content ?? ''}">
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
        html += `<tr><td colspan="7">등록된 O&amp;M 데이터가 없습니다.</td></tr>`;
    }

    html += `</tbody></table>`;
    $('#omTableArea').html(html);
}


// 이슈
function renderIssueTable(issueList) {
    let html = `
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>년</th>
                    <th>월</th>
                    <th>일</th>
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
                    <td>${item.year}</td>
                    <td>${item.month}</td>
                    <td>${item.day}</td>
                    <td>${item.content ?? ''}</td>
                    <td>
                        <button type="button"
                            class="btn btn-sm btn-outline-primary btn-issue-edit"
                            data-idx="${item.idx}"
                            data-year="${item.year}"
                            data-month="${item.month}"
                            data-day="${item.day}"
                            data-content="${item.content ?? ''}">
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
        html += `<tr><td colspan="6">등록된 이슈 데이터가 없습니다.</td></tr>`;
    }

    html += `</tbody></table>`;
    $('#issueTableArea').html(html);
}

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
    	showToast('발전량을 입력해주세요.','error');    
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

				setModifyForm(comp_idx, 'resetting');
            } else {
            	showToast(res.message || '저장 실패','error');
            }
        },
        error: function () {
            showToast('오류가 발생하였습니다.','error');
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

				setModifyForm(comp_idx, 'resetting');
            } else {
            	showToast(res.message || '삭제 실패','error');
            }
        },
        error: function () {
            showToast('오류가 발생하였습니다.','error');
        }
    });
}
</script>