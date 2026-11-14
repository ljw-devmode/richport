{{-- 발주 필요 표시 ($row->alert : RipsForecast::alert, $row->recommend_qty) - 관리자는 발주하지 않고 정보만 표시 --}}
@if ($row->alert)
	<span class="rs-badge order">발주필요</span>
	<div class="rs-alert-reason {{ $row->alert['type'] === 'soon' ? 'soon' : '' }}">
		{{ $row->alert['label'] }}@if ($row->recommend_qty) · 추천 {{ number_format($row->recommend_qty) }}개@endif
	</div>
@else
	<span class="rs-muted">-</span>
@endif
