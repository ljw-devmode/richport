{{--
	발주 상태 탭 (전체 / 재고소진 / 즉시발주 / 발주필요)
	$counts : RipsForecast::productList 의 상태별 건수, $baseUrl : 탭 링크 주소 (다른 검색조건 유지)
--}}
@php
	$current = request('status');
	$tabUrl  = fn ($status) => $baseUrl.'?'.http_build_query(array_filter(array_merge(request()->except(['status', 'page']), ['status' => $status])));
@endphp
<div class="rp-status-tabs">
	<a href="{{ $tabUrl(null) }}" class="{{ $current ? '' : 'active' }}">전체 <b>{{ number_format($counts['all']) }}</b></a>
	@foreach (\App\Services\RipsForecast::ALERT_TYPES as $type => $label)
		<a href="{{ $tabUrl($type) }}" class="{{ $current === $type ? 'active' : '' }} is-{{ $type }}">{{ $label }} <b>{{ number_format($counts[$type]) }}</b></a>
	@endforeach
</div>
