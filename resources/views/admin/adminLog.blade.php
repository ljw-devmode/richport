@php
	use App\Models\AdminLog;
	$header_skip = false;
	$depth1_num = '6';
	$depth2_num = '2';
	// 상세 보기용 (현재 페이지)
	$details = collect($data->items())->mapWithKeys(fn ($log) => [$log->idx => [
		'created_at'     => $log->created_at?->format('Y-m-d H:i:s'),
		'admin'          => trim(($log->admin_name ?? '-').($log->admin_id ? ' ('.$log->admin_id.')' : '')),
		'menu'           => $log->menu,
		'action'         => $log->action_text.($log->action_detail ? ' · '.$log->action_detail : ''),
		'target'         => trim(($log->target_name ?? '').($log->target_idx ? ' #'.$log->target_idx : '')) ?: '-',
		'result'         => AdminLog::RESULTS[$log->result] ?? $log->result,
		'result_message' => $log->result_message,
		'request'        => $log->method.' '.$log->url,
		'ip'             => $log->ip,
		'user_agent'     => $log->user_agent,
		'data'           => $log->request_data ? json_decode($log->request_data, true) : null,
	]]);
@endphp
@include('admin.header._header')
<style>
	.al-table td, .al-table th {white-space:nowrap; vertical-align:middle; }
	.al-table td.wrap {white-space:normal; min-width:200px; max-width:420px; overflow-wrap:anywhere; }
	.al-badge {display:inline-block; padding:2px 9px; border-radius:999px; font-size:12px; font-weight:600; white-space:nowrap; }
	.al-badge.create {background:#dcfce7; color:#15803d; }
	.al-badge.update {background:#e0f2fe; color:#0369a1; }
	.al-badge.delete {background:#fee2e2; color:#dc2626; }
	.al-badge.download {background:#fef3c7; color:#b45309; }
	.al-fail {color:#dc2626; font-weight:600; }
	.al-sum {display:flex; gap:14px; font-size:13px; color:#475569; }
	.al-detail dt {margin-top:10px; font-size:12px; color:#64748b; }
	.al-detail dd {margin-top:2px; overflow-wrap:anywhere; }
	.al-data {max-height:360px; overflow:auto; margin-top:4px; padding:12px; border-radius:6px; background:#f8fafc; font-size:12px; white-space:pre-wrap; overflow-wrap:anywhere; font-family:Consolas, monospace; }
</style>
<div class="mt-6 px-5">
	<h3 class="intro-y text-xl font-bold">관리자 로그</h3>
	<p class="mt-1 text-slate-500">관리자 화면에서 등록 · 수정 · 삭제하거나 엑셀을 다운로드한 내역입니다. 행을 클릭하면 요청 내용을 볼 수 있습니다.</p>

	<div class="intro-y border border-slate-200 mt-6 p-6 rounded-lg bg-white">
		<form method="GET" action="/admin/adminLog">
			<div class="flex items-end gap-3 flex-wrap">
				<div>
					<p class="mb-1">작업일</p>
					<div class="flex items-center gap-1">
						<input type="date" name="start_date" class="form-control w-40" value="{{ $start->toDateString() }}">
						<span>~</span>
						<input type="date" name="end_date" class="form-control w-40" value="{{ $end->toDateString() }}">
					</div>
				</div>
				<div>
					<p class="mb-1">사용자</p>
					<select name="admin_idx" class="form-select w-48">
						<option value="">전체</option>
						@foreach ($admins as $item)
						<option value="{{ $item->idx }}" {{ request('admin_idx') == $item->idx ? 'selected' : '' }}>{{ \App\Support\Privacy::name($item->name) }} ({{ $item->id }})</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">메뉴</p>
					<select name="menu" class="form-select w-40">
						<option value="">전체</option>
						@foreach ($menus as $menu)
						<option value="{{ $menu }}" {{ request('menu') === $menu ? 'selected' : '' }}>{{ $menu }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">작업</p>
					<select name="action" class="form-select w-28">
						<option value="">전체</option>
						@foreach (AdminLog::ACTIONS as $key => $label)
						<option value="{{ $key }}" {{ request('action') === $key ? 'selected' : '' }}>{{ $label }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">결과</p>
					<select name="result" class="form-select w-28">
						<option value="">전체</option>
						@foreach (AdminLog::RESULTS as $key => $label)
						<option value="{{ $key }}" {{ request('result') === $key ? 'selected' : '' }}>{{ $label }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<p class="mb-1">대상</p>
					<input type="text" name="keyword" class="form-control w-44" placeholder="대상 이름" value="{{ request('keyword') }}">
				</div>
				<button type="submit" class="shrink-0 btn btn-primary w-24">검색</button>
				<a href="/admin/adminLog" class="shrink-0 btn btn-outline-secondary">초기화</a>
			</div>
		</form>
	</div>

	<div class="intro-y border border-slate-200 mt-6 rounded-lg bg-white">
		<div class="flex items-center justify-between gap-3 border-b border-slate-200 py-3 px-6 flex-wrap">
			<div class="text-base text-primary">총 {{ number_format($data->total()) }}건 <span class="text-xs text-slate-500">({{ $start->toDateString() }} ~ {{ $end->toDateString() }})</span></div>
			<div class="al-sum">
				@foreach (AdminLog::ACTIONS as $key => $label)
				<span><span class="al-badge {{ $key }}">{{ $label }}</span> {{ number_format($actionCounts[$key] ?? 0) }}</span>
				@endforeach
			</div>
		</div>

		<div class="p-6 overflow-x-auto">
			<table class="table table-sm table-bordered table-hover al-table">
				<thead class="bg-slate-50 text-center">
					<tr>
						<th>번호</th>
						<th>일시</th>
						<th>사용자</th>
						<th>메뉴</th>
						<th>작업</th>
						<th>대상</th>
						<th>결과</th>
						<th>IP</th>
					</tr>
				</thead>
				<tbody class="text-center">
					@forelse ($data as $index => $log)
					<tr onclick="openLog({{ $log->idx }})" style="cursor:pointer;">
						<td>{{ $data->total() - ($data->currentPage() - 1) * $data->perPage() - $index }}</td>
						<td>{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
						<td>{{ $log->admin_name ? \App\Support\Privacy::name($log->admin_name) : '-' }} <span class="text-xs text-slate-500">{{ $log->admin_id }}</span></td>
						<td>{{ $log->menu }}</td>
						<td>
							<span class="al-badge {{ $log->action }}">{{ $log->action_text }}</span>
							@if ($log->action_detail)<div class="text-xs text-slate-500 mt-1">{{ $log->action_detail }}</div>@endif
						</td>
						<td class="text-left wrap">{{ $log->target_name ?: '-' }}@if ($log->target_idx) <span class="text-xs text-slate-400">#{{ $log->target_idx }}</span>@endif</td>
						<td>
							@if ($log->result === 'success')
								성공
							@else
								<span class="al-fail" title="{{ $log->result_message }}">실패</span>
							@endif
						</td>
						<td>{{ $log->ip }}</td>
					</tr>
					@empty
					<tr><td colspan="8" class="text-slate-400">기록된 작업이 없습니다.</td></tr>
					@endforelse
				</tbody>
			</table>
			@include('rips.common._pagination', ['data' => $data])
		</div>
	</div>

	<!-- 로그 상세 -->
	<div id="log-modal" class="modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="font-semibold text-lg mr-auto">작업 상세</h2>
					<button onclick="modalClose('log-modal')"><i data-lucide="x" class="w-6 h-6"></i></button>
				</div>
				<div class="modal-body">
					<dl class="al-detail grid grid-cols-2 gap-x-6" id="log_info"></dl>
					<p class="mt-4 text-xs text-slate-500">요청 내용 <span class="text-slate-400">(비밀번호 등은 ****** 로 가림, 긴 내용은 일부만 저장)</span></p>
					<div class="al-data" id="log_data"></div>
				</div>
				<div class="modal-footer flex justify-center">
					<button type="button" class="btn btn-outline-secondary w-20" onclick="modalClose('log-modal')">닫기</button>
				</div>
			</div>
		</div>
	</div>
</div>
@include('admin.footer._footer')
<script>
const LOGS = @json($details);

function escapeLog(v) {
    return String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}

function openLog(idx) {
    const log = LOGS[idx];
    if (!log) return;

    const rows = [
        ['일시', log.created_at], ['사용자', log.admin], ['메뉴', log.menu], ['작업', log.action],
        ['대상', log.target], ['결과', log.result + (log.result_message ? ' - ' + log.result_message : '')],
        ['요청', log.request], ['IP', log.ip],
    ];
    $('#log_info').html(rows.map(([k, v]) => '<div><dt>' + k + '</dt><dd>' + escapeLog(v || '-') + '</dd></div>').join(''));
    $('#log_data').text(log.data ? JSON.stringify(log.data, null, 2) : '(입력값 없음)');
    modalOpen('log-modal');
}
</script>
