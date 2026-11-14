{{-- 판매 관리 공통 : 스타일 + 월별/일별 탭 ($tab : monthly | daily | null 이면 탭 없음) --}}
<style>
	.rs-tabs {display:flex; gap:4px; margin-top:20px; }
	.rs-tabs a {padding:9px 22px; border:1px solid #e2e8f0; border-bottom:0; border-radius:8px 8px 0 0; background:#f8fafc; color:#64748b; font-weight:600; }
	.rs-tabs a.active {background:#fff; color:rgb(var(--color-primary)); }
	.rs-tab-panel {border-top-left-radius:0 !important; margin-top:0 !important; }
	.rs-table td, .rs-table th {white-space:nowrap; vertical-align:middle; }
	.rs-table td.wrap {white-space:normal; min-width:160px; }
	.rs-num {text-align:right; font-variant-numeric:tabular-nums; }
	.rs-muted {color:#94a3b8; }
	.rs-sum {display:flex; gap:20px; flex-wrap:wrap; font-size:14px; }
	.rs-sum b {color:rgb(var(--color-primary)); }
	.rs-badge {display:inline-block; padding:2px 9px; border-radius:999px; font-size:12px; font-weight:600; white-space:nowrap; }
	.rs-badge.order {background:#fee2e2; color:#dc2626; }
	.rs-badge.promo {background:#fff7ed; color:#c2410c; }
	.rs-alert-reason {margin-top:2px; font-size:11px; color:#dc2626; }
	.rs-alert-reason.soon {color:#2563eb; }
</style>
@if (!empty($tab))
<div class="rs-tabs">
	<a href="/admin/ripsSales" class="{{ $tab === 'monthly' ? 'active' : '' }}">월별</a>
	<a href="/admin/ripsSales/daily" class="{{ $tab === 'daily' ? 'active' : '' }}">일별</a>
</div>
@endif
