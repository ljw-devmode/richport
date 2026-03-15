@php 
	$header_skip = false; 
	$depth1_num = '3'; 
	$depth2_num = '33'; 
@endphp 
@include('admin.header._header')

<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">기상청 일사량</h3>
	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/kma">
			<div class="flex items-end gap-3">
				<div class="w-1/5">
					<p class="mb-1">관측소</p>
					<select id="stnId" name="stnId" class="form-select">
					@foreach($stations as $s)
						<option value="{{ $s->idx }}" {{ (int)$stnId === (int)$s->idx ? 'selected' : '' }}>
						{{ $s->stn_name }} ({{ $s->idx }})
						</option>
					@endforeach
					</select>					
				</div>
				<div class="w-1/5">
					<p class="mb-1">년도</p>
					<select id="year" name="year" class="form-select">
					@for($y=2024; $y<=2027; $y++)
						<option value="{{ $y }}" {{ (int)$year === $y ? 'selected' : '' }}>{{ $y }}년</option>
					@endfor
					</select>
				</div>
				<div class="w-1/5">
					<p class="mb-1">월</p>
					<select id="month" name="month" class="form-select">
					@for($m=1; $m<=12; $m++)
						<option value="{{ $m }}" {{ (int)$month === $m ? 'selected' : '' }}>{{ $m }}월</option>
					@endfor
					</select>
				</div>
				        
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base text-primary">
				선택월: {{ $target->format('Y-m') }} 
					@if($isCurrentMonth)
					(현재월: 1일 ~ {{ $targetEndDay }}일(어제)까지만 조회)
					@else
					(1일 ~ 말일 조회)
					@endif
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
				</colgroup>
				<thead class="bg-slate-50 text-center">
					<tr>
						<th rowspan="2">일</th>
						<th colspan="2">{{ $prevYear->format('Y-m') }}</th>
						<th colspan="2">{{ $prevMonth->format('Y-m') }}</th>
						<th colspan="2">{{ $target->format('Y-m') }}</th>
					</tr>
					<tr>
						<th>일사량</th><th>kWh/m²</th>
						<th>일사량</th><th>kWh/m²</th>
						<th>일사량</th><th>kWh/m²</th>
					</tr>
				</thead>
				<tbody class="text-center font-medium">
					@foreach($rows as $r)
						@php
						$py_mj = $r['prev_year'];
						$pm_mj = $r['prev_month'];
						$tg_mj = $r['target'];

						$py_kwh = $py_mj !== null ? $py_mj * $mjToKwh : null;
						$pm_kwh = $pm_mj !== null ? $pm_mj * $mjToKwh : null;
						$tg_kwh = $tg_mj !== null ? $tg_mj * $mjToKwh : null;
						@endphp

						<tr>
						<td>{{ $r['day'] }}</td>

						<td>{{ $py_mj !== null ? number_format($py_mj, 2) : '' }}</td>
						<td>{{ $py_kwh !== null ? number_format($py_kwh, 2) : '' }}</td>

						<td>{{ $pm_mj !== null ? number_format($pm_mj, 2) : '' }}</td>
						<td>{{ $pm_kwh !== null ? number_format($pm_kwh, 2) : '' }}</td>

						<td>{{ $tg_mj !== null ? number_format($tg_mj, 2) : '' }}</td>
						<td>{{ $tg_kwh !== null ? number_format($tg_kwh, 2) : '' }}</td>
						</tr>
					@endforeach
				</tbody>
				<tfoot>
				<tr>
					<th>월 합계</th>
					<th>{{ number_format($sumPrevYear, 2) }}</th>
					<th class="small">{{ number_format($sumPrevYear * $mjToKwh, 2) }}kWh/m²</th>
					<th>{{ number_format($sumPrevMon, 2) }}</th>
					<th class="small">{{ number_format($sumPrevMon * $mjToKwh, 2) }}kWh/m²</th>
					<th>{{ number_format($sumTarget, 2) }}</th>
					<th class="small">{{ number_format($sumTarget * $mjToKwh, 2) }}kWh/m²</th>
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

</script>