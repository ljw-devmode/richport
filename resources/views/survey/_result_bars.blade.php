{{-- 설문 결과 가로 막대 (rows : [label, count], base : 비율 기준 응답 수) --}}
@php $unit = $unit ?? '명'; @endphp
<div class="sr-bars">
	@foreach ($rows as $row)
		@php $percent = $base > 0 ? round($row['count'] / $base * 100, 1) : 0; @endphp
		<div class="sr-bar-row" title="{{ $row['label'] }} : {{ number_format($row['count']) }}{{ $unit }} ({{ $percent }}%)">
			<span class="sr-bar-label">{{ $row['label'] }}</span>
			<span class="sr-bar-track"><span class="sr-bar-fill" style="width:{{ $percent }}%;"></span></span>
			<span class="sr-bar-value">{{ number_format($row['count']) }}{{ $unit }} <em>{{ $percent }}%</em></span>
		</div>
	@endforeach
</div>
