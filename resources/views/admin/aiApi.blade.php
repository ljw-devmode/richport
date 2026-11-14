@php
	use App\Models\AiApiSetting;
	$header_skip = false;
	$depth1_num = '7';
	$depth2_num = '8';
	$canUpdate = auth()->user()->hasPerm('aiApi.update');
	$baseUrl = url('/ai-api/v1');
@endphp
@include('admin.header._header')
<style>
	.ai-grid {display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:20px; }
	.ai-row {display:flex; align-items:center; gap:10px; margin-top:12px; }
	.ai-row > p:first-child {flex-shrink:0; width:140px; font-weight:600; color:#334155; }
	.ai-help {margin-top:6px; font-size:12px; color:#64748b; }
	.ai-code {font-family:Consolas, monospace; font-size:13px; }
	.ai-badge {display:inline-block; padding:2px 9px; border-radius:999px; font-size:12px; font-weight:600; }
	.ai-badge.on {background:#dcfce7; color:#15803d; } .ai-badge.off {background:#f1f5f9; color:#64748b; }
	.ai-badge.success {background:#dcfce7; color:#15803d; } .ai-badge.fail {background:#fee2e2; color:#dc2626; }
	.ai-table td, .ai-table th {white-space:nowrap; vertical-align:middle; }
	@media (max-width:1200px) { .ai-grid {grid-template-columns:minmax(0, 1fr); } }
</style>
<div class="mt-6 px-5 pb-10">
	<h3 class="intro-y text-xl font-bold">AI 연동</h3>
	<p class="mt-1 text-slate-500">AI 서버가 판매·재고 데이터를 가져가 예측하고, 잔여소진 예상일 · 추천 발주일 · 추천 발주수량을 보내면 RIPS 화면에 반영합니다. 자세한 내용은 API 연동가이드를 참고하세요.</p>

	<div class="ai-grid mt-6">
		<!-- 설정 -->
		<div class="intro-y border border-slate-200 p-6 rounded-lg bg-white">
			<div class="flex items-center justify-between">
				<h4 class="text-base font-bold">연동 설정</h4>
				<span class="ai-badge {{ $setting->api_use ? 'on' : 'off' }}">{{ $setting->api_use ? 'API 사용중' : 'API 미사용' }}</span>
			</div>
			<form id="ai-form" onsubmit="return false;">
				<div class="ai-row">
					<p>API 사용</p>
					<div class="form-check form-switch">
						<input id="api_use" name="api_use" value="1" class="form-check-input" type="checkbox" {{ $setting->api_use ? 'checked' : '' }}>
						<label class="form-check-label" for="api_use">사용 (해제하면 모든 AI API 요청을 거부)</label>
					</div>
				</div>
				<div class="ai-row" style="align-items:flex-start;">
					<p class="pt-1">화면 예측값</p>
					<div>
						@foreach (AiApiSetting::MODES as $key => $label)
						<div class="form-check mb-1">
							<input id="mode_{{ $key }}" name="forecast_mode" value="{{ $key }}" class="form-check-input" type="radio" {{ $setting->forecast_mode === $key ? 'checked' : '' }}>
							<label class="form-check-label" for="mode_{{ $key }}">{{ $label }}</label>
						</div>
						@endforeach
					</div>
				</div>
				<div class="ai-row">
					<p>AI 값 유효기간</p>
					<input type="number" name="valid_days" class="form-control w-24" min="0" max="30" value="{{ $setting->valid_days }}">
					<span>일</span>
				</div>
				<p class="ai-help" style="padding-left:150px;">예측 기준일부터 이 기간이 지난 AI 값은 사용하지 않습니다. (0 : 오늘 기준일 값만)</p>
				<div class="ai-row">
					<p>허용 IP</p>
					<input type="text" name="allowed_ips" class="form-control" value="{{ $setting->allowed_ips }}" placeholder="AI 서버 IP (쉼표로 구분, 비우면 제한 없음)">
				</div>
				@if ($canUpdate)
				<div class="flex justify-end mt-5">
					<button type="button" class="btn btn-primary w-24" onclick="saveSetting()">저장</button>
				</div>
				@endif
			</form>
		</div>

		<!-- 인증 · 현황 -->
		<div class="intro-y border border-slate-200 p-6 rounded-lg bg-white">
			<h4 class="text-base font-bold">인증 · 연동 정보</h4>
			<div class="ai-row">
				<p>API 주소</p>
				<span class="ai-code">{{ $baseUrl }}</span>
			</div>
			<div class="ai-row">
				<p>인증키</p>
				@if ($setting->api_key)
					<input type="password" id="api_key" class="form-control ai-code" value="{{ $setting->api_key }}" readonly>
					<button type="button" class="btn btn-sm btn-outline-secondary shrink-0" onclick="toggleKey()">보기</button>
					<button type="button" class="btn btn-sm btn-outline-secondary shrink-0" onclick="copyKey()">복사</button>
					@if ($canUpdate)<button type="button" class="btn btn-sm btn-outline-danger shrink-0" onclick="regenerate()">재발급</button>@endif
				@else
					<span class="text-slate-400">API 사용으로 저장하면 발급됩니다.</span>
				@endif
			</div>
			<p class="ai-help" style="padding-left:150px;">요청 헤더 : Authorization: Bearer {인증키} · 재발급하면 이전 키는 바로 사용할 수 없습니다.</p>
			<div class="ai-row">
				<p>마지막 요청</p>
				<span>{{ $setting->last_request_at?->format('Y-m-d H:i:s') ?? '-' }}</span>
			</div>
			<div class="ai-row">
				<p>마지막 예측 수신</p>
				<span>{{ $setting->last_push_at?->format('Y-m-d H:i:s') ?? '-' }}</span>
			</div>
		</div>
	</div>

	<!-- 업체별 예측 수신 현황 -->
	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between border-b border-slate-200 py-3 px-6">
			<div class="text-base font-bold">업체별 예측 수신 현황</div>
			<div class="text-xs text-slate-500">RIPS 업체 상품 {{ number_format($totalProducts) }}개</div>
		</div>
		<div class="p-6 overflow-x-auto">
			<table class="table table-sm table-bordered ai-table">
				<thead class="bg-slate-50 text-center">
					<tr><th>업체</th><th>최근 기준일</th><th>예측 상품 수</th><th>모델 버전</th><th>수신일시</th><th>화면 반영</th></tr>
				</thead>
				<tbody class="text-center">
					@forelse ($forecasts as $f)
					@php $fresh = \Carbon\Carbon::parse($f->base_date)->gte(today()->subDays((int) $setting->valid_days)); @endphp
					<tr>
						<td class="text-left">{{ $f->partner_name }}@if ($f->store_name) <span class="text-xs text-slate-500">· {{ $f->store_name }}</span>@endif</td>
						<td>{{ $f->base_date }}</td>
						<td>{{ number_format($f->products) }}</td>
						<td>{{ $f->model_version }}</td>
						<td>{{ $f->received_at }}</td>
						<td>
							@if ($setting->forecast_mode === 'calc')<span class="ai-badge off">계산식 사용 중</span>
							@elseif ($fresh)<span class="ai-badge on">반영</span>
							@else<span class="ai-badge fail">유효기간 지남</span>@endif
						</td>
					</tr>
					@empty
					<tr><td colspan="6" class="text-slate-400">아직 수신한 예측 결과가 없습니다. (현재 화면은 계산식 값 사용)</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>

	<!-- 최근 요청 -->
	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="border-b border-slate-200 py-3 px-6 text-base font-bold">최근 API 요청 <span class="text-xs font-normal text-slate-500">(최근 30건)</span></div>
		<div class="p-6 overflow-x-auto">
			<table class="table table-sm table-bordered ai-table">
				<thead class="bg-slate-50 text-center">
					<tr><th>일시</th><th>요청</th><th>응답</th><th>결과</th><th>건수</th><th>메시지</th><th>IP</th></tr>
				</thead>
				<tbody class="text-center">
					@forelse ($logs as $log)
					<tr>
						<td>{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
						<td class="text-left ai-code">{{ $log->method }} {{ $log->endpoint }}</td>
						<td>{{ $log->status_code }}</td>
						<td><span class="ai-badge {{ $log->result }}">{{ $log->result === 'success' ? '성공' : '실패' }}</span></td>
						<td>{{ $log->item_count !== null ? number_format($log->item_count) : '-' }}</td>
						<td class="text-left" style="white-space:normal; min-width:200px;">{{ $log->message ?? '' }}</td>
						<td>{{ $log->ip }}</td>
					</tr>
					@empty
					<tr><td colspan="7" class="text-slate-400">요청 기록이 없습니다.</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@include('admin.footer._footer')
<script>
function post(url, data, done) {
    $.ajax({ url: url, method: 'POST', data: data, headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: res => res.status === 'success' ? done(res) : showToast(res.message || '처리 실패', 'error'),
        error: xhr => showToast((xhr.responseJSON && xhr.responseJSON.message) || '오류가 발생했습니다.', 'error') });
}
function saveSetting() {
    const $f = $('#ai-form');
    post('/admin/aiApi/save', {
        api_use: $('#api_use').is(':checked') ? 1 : 0,
        forecast_mode: $f.find('[name=forecast_mode]:checked').val(),
        valid_days: $f.find('[name=valid_days]').val(),
        allowed_ips: $f.find('[name=allowed_ips]').val(),
    }, () => { showToast('저장되었습니다.', 'success'); setTimeout(() => location.reload(), 600); });
}
function regenerate() {
    if (!confirm('인증키를 재발급하시겠습니까?\n이전 인증키로는 더 이상 요청할 수 없습니다.')) return;
    post('/admin/aiApi/regenerate', {}, () => { showToast('재발급되었습니다.', 'success'); setTimeout(() => location.reload(), 600); });
}
function toggleKey() { const $k = $('#api_key'); $k.attr('type', $k.attr('type') === 'password' ? 'text' : 'password'); }
function copyKey() { navigator.clipboard.writeText($('#api_key').val()).then(() => showToast('복사되었습니다.', 'success')); }
</script>
