@php 
	$header_skip = false; 
	$depth1_num = '3'; 
	$depth2_num = '21'; 
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
				@if(auth()->user()->hasPerm('company.create'))
				<div class="flex items-center gap-2">
					<a href="javascript:;" class="btn btn-dark px-4" onclick="modalOpen('company_regist-modal')">발전소 등록</a>
				</div>
        		@endif
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
							<tr 
							@if(auth()->user()->hasPerm('company.detail') || auth()->user()->hasPerm('company.detail') || auth()->user()->hasPerm('company.delete')) 
								onclick="companyDetail('{{ $item->idx }}')"
								style="cursor: pointer;" 
								class="hover:bg-slate-100"
							@else 
								style="cursor: default;" 
							@endif
							>
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
					@if(auth()->user()->hasPerm('company.create'))
					<button type="button" class="btn btn-primary w-20" onclick="regist();">등록</button>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END: Content -->
</div>
@include('admin.footer._footer')

<script>
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
</script>