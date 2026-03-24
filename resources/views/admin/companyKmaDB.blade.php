@php 
	$header_skip = false; 
	$depth1_num = '3'; 
	$depth2_num = '42'; 
@endphp 
@include('admin.header._header')

<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">기상청 일사량 (2022.01.01~2026.03.16 데이터)</h3>
	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="{{ route('admin.kma.db') }}">
			<div class="flex items-end gap-3">
				<div>
					<p class="mb-1">관측소</p>
					<select id="stnId" name="stnId" class="form-select">
					@foreach($stations as $s)
						<option value="{{ $s->idx }}" {{ (int)$stnId === (int)$s->idx ? 'selected' : '' }}>
						{{ $s->stn_name }} ({{ $s->idx }})
						</option>
					@endforeach
					</select>					
				</div>
				<div >
					<p class="mb-1">시작일</p>
					<div class="relative w-40 mx-auto">
						<div class="absolute rounded-l w-10 h-42px flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
							<i data-lucide="calendar" class="w-4 h-4"></i>
						</div>
						<input type="text" id="start_date" name="start_date" class="form-control pl-12" data-single-mode="true" value="{{ request('start_date') }}">
					</div>
				</div>
				<div >
					<p class="mb-1">종료일</p>
					<div class="relative w-40 mx-auto">
						<div class="absolute rounded-l w-10 h-42px flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
							<i data-lucide="calendar" class="w-4 h-4"></i>
						</div>
						<input type="text" id="end_date" name="end_date" class="form-control pl-12" data-single-mode="true" value="{{ request('end_date') }}">
					</div>
				</div>
				
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
				@if(auth()->user()->hasPerm('kma_db.excel'))
				<button type="button" class="btn border-[#1b6c40] bg-[#1b6c40] text-white px-4" onclick="window.location.href='{{ route('admin.kma.dbexport', request()->query()) }}'">엑셀 다운로드</button>
				@endif
				<!-- <button type="button" id="callsyncYearlyKma" name="callsyncYearlyKma" class="btn btn-primary">일사량 동기화</button> -->
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">선택 기간 : {{ request('start_date') }} ~ {{ request('end_date') }}</div>
		</div>

		<div class="p-6">
			<table class="table table-bordered table-hover">
				<colgroup>
					<col width="*">
					<col width="*">
					<col width="*">
				</colgroup>
				<thead class="bg-slate-50 text-center">
					<tr>
						<th>일자</th>
						<th>일사량</th>
						<th>kWh/m²</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@foreach($rows as $r)
						@php
							$sumgsr = $r['sumgsr'];
							$sumgsr_kwh = $sumgsr !== null ? $sumgsr * $mjToKwh : null;
						@endphp

						<tr>
						<td>{{ $r['date'] }}</td>
						<td>{{ $sumgsr !== null ? number_format($sumgsr, 2) : '' }}</td>
						<td>{{ $sumgsr_kwh !== null ? number_format($sumgsr_kwh, 2) : '' }}</td>
						</tr>
					@endforeach
				</tbody>
				<tfoot>
				<tr>
					<th>총 합계</th>
					<th>{{ number_format($totalsumgsr, 2) }}</th>
					<th class="small">{{ number_format($totalsumgsr * $mjToKwh, 2) }}kWh/m²</th>
				</tr>
				</tfoot>				
			</table>
		</div>
	</div>
</div>
</div>
<!-- END: Content -->
</div>
@include('admin.footer._footer')

<script>
$(document).on('click', '#callsyncYearlyKma', function () {
$.ajax({
	url: '/admin/kma/syncYearlyKma',
	type: 'POST',
	dataType: 'json',
	headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
	data: {
		year: 2026,
	},
	success: function (res) {
		if (res.status === 'success') {
			showToast('기상청 일사량이 동기화 되었습니다.','success');
		} else {
			showToast('오류가 발생했습니다.','error');
		}
	}
});
});


const defaultStartDate = "{{ request('start_date') }}";
const defaultEndDate = "{{ request('end_date') }}";

const start_date = new Litepicker({
    element: document.querySelector('#start_date'),
    singleMode: true,
    firstDay: 0,
    format: 'YYYY-MM-DD',
    lang: "ko-KR",
    numberOfColumns: 1,
    numberOfMonths: 1,
    tooltipText: {
        other: "일"
    },
    dropdowns: {
        minYear: 2023,
        maxYear: new Date().getFullYear(),
        months: true,
        years: true
    },
    setup: (picker) => {
        if (defaultStartDate) {
            picker.setDate(defaultStartDate);
        }
    },
});

const end_date = new Litepicker({
    element: document.querySelector('#end_date'),
    singleMode: true,
    firstDay: 0,
    format: 'YYYY-MM-DD',
    lang: "ko-KR",
    numberOfColumns: 1,
    numberOfMonths: 1,
    tooltipText: {
        other: "일"
    },
    dropdowns: {
        minYear: 2023,
        maxYear: new Date().getFullYear(),
        months: true,
        years: true
    },
    setup: (picker) => {
        if (defaultEndDate) {
            picker.setDate(defaultEndDate);
        }
    },
});
</script>