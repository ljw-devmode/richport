{{--
	테스트베드 설문조사 결과 (요약 / 개별 설문)
	필요 변수 : $surveys, $survey, $total, $demographics, $questions
	           $surveyUrl   : 설문 선택 시 이동할 주소 (?list_idx= 붙음)
	           $responseUrl : 개별 설문 JSON 주소 (?no= 붙음, $survey 가 있을 때)
--}}
<style>
	.sr-wrap {max-width:1000px; margin-left:auto; margin-right:auto; }
	.sr-card {padding:22px 24px; border:1px solid #e2e8f0; border-radius:8px; background:#fff; }
	.sr-section {margin-top:28px; font-size:15px; font-weight:700; }
	.sr-tabs {display:flex; gap:4px; }
	.sr-tabs button {padding:10px 22px; border:1px solid #e2e8f0; border-bottom:0; border-radius:8px 8px 0 0; background:#f8fafc; color:#64748b; font-weight:600; }
	.sr-tabs button.active {background:#fff; color:rgb(var(--color-primary)); }
	.sr-tabs button b {margin-left:4px; }
	.sr-panel {padding:20px; border:1px solid #e2e8f0; border-radius:0 8px 8px 8px; background:#f8fafc; }
	.sr-total {font-size:30px; font-weight:700; line-height:1.2; }
	.sr-total small {margin-left:2px; font-size:13px; font-weight:500; color:#64748b; }
	.sr-grid {display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:14px; margin-top:14px; }
	.sr-title {font-size:14px; font-weight:600; overflow-wrap:anywhere; }
	.sr-path {font-size:12px; color:#64748b; margin-bottom:2px; }
	.sr-count {margin:2px 0 14px; font-size:12px; color:#64748b; }
	.sr-bars {display:flex; flex-direction:column; gap:10px; }
	.sr-bar-row {display:grid; grid-template-columns:minmax(0, 200px) minmax(0, 1fr) 96px; align-items:center; gap:10px; font-size:13px; }
	.sr-bar-label {overflow-wrap:anywhere; }
	.sr-bar-track {display:block; height:10px; border-radius:4px; background:#f1f5f9; }
	.sr-bar-fill {display:block; height:100%; border-radius:4px; background:rgb(var(--color-primary)); }
	.sr-bar-value {text-align:right; white-space:nowrap; font-variant-numeric:tabular-nums; }
	.sr-bar-value em {font-style:normal; color:#64748b; font-size:12px; }
	.sr-texts {max-height:280px; overflow-y:auto; }
	.sr-texts li {padding:9px 12px; margin-top:6px; border-radius:6px; background:#f8fafc; font-size:13px; white-space:pre-wrap; overflow-wrap:anywhere; }
	.sr-texts-title {margin-top:14px; font-size:12px; font-weight:600; color:#64748b; }
	.sr-empty {padding:30px 10px; text-align:center; color:#94a3b8; }

	/* 개별 설문 (좌우 슬라이드) */
	.rs-nav {display:flex; align-items:center; justify-content:center; gap:10px; padding:10px; border:1px solid #e2e8f0; border-radius:8px; background:#fff; position:sticky; top:0; z-index:2; }
	.rs-nav button {display:flex; align-items:center; justify-content:center; width:36px; height:36px; border:1px solid #e2e8f0; border-radius:6px; background:#fff; }
	.rs-nav button:disabled {opacity:.35; cursor:default; }
	.rs-nav input {width:72px; text-align:center; }
	.rs-stage {position:relative; overflow:hidden; margin-top:14px; touch-action:pan-y; }
	.rs-slide {width:100%; transition:transform .28s ease; }
	.rs-slide.leaving {position:absolute; top:0; left:0; }
	.rs-info {display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); gap:12px; }
	.rs-info p:first-child {font-size:12px; color:#64748b; }
	.rs-info p:last-child {margin-top:2px; font-weight:600; }
	.rs-q {margin-top:14px; }
	.rs-option {display:flex; align-items:center; gap:9px; padding:5px 0; font-size:13px; color:#94a3b8; overflow-wrap:anywhere; }
	.rs-option.on {color:inherit; font-weight:600; }
	.rs-mark {flex-shrink:0; width:16px; height:16px; border:2px solid #cbd5e1; background:#fff; }
	.rs-mark.radio {border-radius:50%; }
	.rs-mark.checkbox {border-radius:3px; }
	.rs-option.on .rs-mark {border-color:rgb(var(--color-primary)); background:rgb(var(--color-primary)); box-shadow:inset 0 0 0 2px #fff; }
	.rs-text {margin-top:8px; padding:9px 12px; border-radius:6px; background:#f8fafc; font-size:13px; white-space:pre-wrap; overflow-wrap:anywhere; }
	.rs-none {margin-top:8px; font-size:13px; color:#94a3b8; }
	@media (prefers-reduced-motion: reduce) {
		.rs-slide {transition:none; }
	}
</style>

	<div class="sr-wrap mt-6">
		@if(!$survey)
			<div class="sr-card sr-empty">이 상품에 등록된 설문조사가 없습니다.</div>
		@else
			@if($surveys->count() > 1)
			<div class="mb-4">
				<p class="mb-1">설문조사</p>
				<select class="form-select" onchange="location.href=@js($surveyUrl) + '?list_idx=' + this.value">
					@foreach($surveys as $item)
					<option value="{{ $item->idx }}" {{ $item->idx === $survey->idx ? 'selected' : '' }}>
						{{ $item->check_name }} ({{ number_format($item->checkexecution_count) }}건){{ $item->use_yn !== 'Y' ? ' - 비활성' : '' }}
					</option>
					@endforeach
				</select>
			</div>
			@endif

			<div class="sr-tabs">
				<button type="button" class="active" data-tab="summary" onclick="showTab('summary')">요약</button>
				<button type="button" data-tab="responses" onclick="showTab('responses')">개별 설문 보기 <b>{{ number_format($total) }}</b></button>
			</div>

			<div class="sr-panel">
				<!-- 요약 -->
				<div id="tab-summary">
					<div class="sr-card">
						<p class="text-slate-500">{{ $survey->check_name }}</p>
						<p class="sr-total mt-1">{{ number_format($total) }}<small>건 응답</small></p>
					</div>

					@if($total === 0)
						<div class="sr-card sr-empty mt-4">아직 응답이 없습니다.</div>
					@else
						<p class="sr-section">응답자 정보</p>
						<div class="sr-grid">
							@foreach ([['연령대', 'age'], ['성별', 'gender'], ['거주지 (시)', 'city'], ['거주지 (구역)', 'district']] as [$title, $key])
							<div class="sr-card">
								<p class="sr-title" style="margin-bottom:14px;">{{ $title }}</p>
								@include('survey._result_bars', ['rows' => $demographics[$key], 'base' => $total])
							</div>
							@endforeach
						</div>

						@foreach ($questions as $section)
							<p class="sr-section">{{ $section['name'] ?: '문항' }}</p>
							@foreach ($section['items'] as $item)
							<div class="sr-card mt-3">
								@if($item['path'])<p class="sr-path">{{ $item['path'] }}</p>@endif
								<p class="sr-title">{{ $item['name'] }}</p>
								<p class="sr-count">응답 {{ number_format($item['answered']) }}개{{ $item['type'] === 'checkbox' ? ' · 복수 선택' : '' }}</p>

								@if($item['type'] === 'input')
									@if($item['texts'])
									<ul class="sr-texts">
										@foreach ($item['texts'] as $text)<li>{{ $text }}</li>@endforeach
									</ul>
									@else
									<p class="text-slate-400">답변이 없습니다.</p>
									@endif
								@else
									@include('survey._result_bars', ['rows' => $item['options'], 'base' => $item['answered'], 'unit' => '건'])
									@if($item['has_text'] && $item['texts'])
									<p class="sr-texts-title">비고 {{ number_format(count($item['texts'])) }}개</p>
									<ul class="sr-texts">
										@foreach ($item['texts'] as $text)<li>{{ $text }}</li>@endforeach
									</ul>
									@endif
								@endif
							</div>
							@endforeach
						@endforeach
					@endif
				</div>

				<!-- 개별 설문 -->
				<div id="tab-responses" style="display:none;">
					@if($total === 0)
						<div class="sr-card sr-empty">아직 응답이 없습니다.</div>
					@else
						<div class="rs-nav">
							<button type="button" id="rs-prev" aria-label="이전 응답" onclick="moveResponse(-1)"><i data-lucide="chevron-left" class="w-5 h-5"></i></button>
							<input type="number" id="rs-no" class="form-control" min="1" max="{{ $total }}" value="1" aria-label="응답 번호">
							<span class="text-slate-500">/ {{ number_format($total) }}</span>
							<button type="button" id="rs-next" aria-label="다음 응답" onclick="moveResponse(1)"><i data-lucide="chevron-right" class="w-5 h-5"></i></button>
						</div>
						<div class="rs-stage" id="rs-stage"></div>
					@endif
				</div>
			</div>
		@endif
	</div>

@if($survey && $total > 0)
<script>
const RS_URL       = @js($responseUrl);
const RS_TOTAL     = {{ $total }};
const RS_QUESTIONS = @json($questions);
let rsNo = 0, rsLoading = false;

function srEscape(v) {
    return String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}

// 개별 설문 카드
function responseHtml(r) {
    let html = '<div class="sr-card rs-info">' +
        [['연령대', r.age], ['성별', r.gender], ['거주지', r.city + ' ' + r.district], ['응답일시', r.created_at]]
            .map(([label, value]) => '<div><p>' + label + '</p><p>' + srEscape(value) + '</p></div>').join('') +
        '</div>';

    RS_QUESTIONS.forEach(function (section) {
        html += '<p class="sr-section">' + srEscape(section.name || '문항') + '</p>';
        section.items.forEach(function (item) {
            const answer = r.answers[item.idx] || { options: [], text: null };
            let body = '';

            if (item.type === 'input') {
                body = answer.text ? '<div class="rs-text">' + srEscape(answer.text) + '</div>' : '<p class="rs-none">응답 없음</p>';
            } else {
                body = item.options.map(o =>
                    '<div class="rs-option ' + (answer.options.includes(o.idx) ? 'on' : '') + '"><span class="rs-mark ' + item.type + '"></span>' + srEscape(o.label) + '</div>'
                ).join('');
                if (answer.text) body += '<div class="rs-text">' + srEscape(answer.text) + '</div>';
            }

            html += '<div class="sr-card rs-q">' +
                (item.path ? '<p class="sr-path">' + srEscape(item.path) + '</p>' : '') +
                '<p class="sr-title mb-2">' + srEscape(item.name) + '</p>' + body +
                '</div>';
        });
    });

    return html;
}

function slideTo(html, direction) {
    const $stage = $('#rs-stage');
    const $old   = $stage.children('.rs-slide');
    const $new   = $('<div class="rs-slide"></div>').html(html);

    if (!$old.length || !direction) {
        $stage.empty().append($new);
        return;
    }

    // 현재 카드는 밀려나가고 새 카드는 반대쪽에서 들어옴
    $new.css({ transition: 'none', transform: 'translateX(' + (direction * 100) + '%)' });
    $old.addClass('leaving');
    $stage.append($new);
    $new[0].offsetWidth;
    $new.css({ transition: '', transform: 'translateX(0)' });
    $old.css('transform', 'translateX(' + (-direction * 100) + '%)');
    setTimeout(() => $old.remove(), 320);
}

function loadResponse(no) {
    if (rsLoading) return;
    no = Math.max(1, Math.min(RS_TOTAL, Number(no) || 1));
    if (no === rsNo) return $('#rs-no').val(no);

    rsLoading = true;
    $.getJSON(RS_URL, { no: no }, function (res) {
        if (res.status !== 'success') return showToast(res.message || '불러오기 실패', 'error');

        slideTo(responseHtml(res.response), rsNo ? Math.sign(res.no - rsNo) : 0);
        rsNo = res.no;
        $('#rs-no').val(rsNo);
        $('#rs-prev').prop('disabled', rsNo <= 1);
        $('#rs-next').prop('disabled', rsNo >= RS_TOTAL);
    }).fail(function () {
        showToast('오류 발생', 'error');
    }).always(function () {
        rsLoading = false;
    });
}

function moveResponse(step) {
    const next = rsNo + step;
    if (next >= 1 && next <= RS_TOTAL) loadResponse(next);
}

let rsTouchX = null;

// 화면 하단(footer)에서 jQuery 를 불러오므로 로드 후 이벤트 연결
document.addEventListener('DOMContentLoaded', function () {
    $('#rs-no').on('change', function () { loadResponse(this.value); });

    // 키보드 좌우, 스와이프
    $(document).on('keydown', function (e) {
        if (!$('#tab-responses').is(':visible') || $(e.target).is('input, select, textarea')) return;
        if (e.key === 'ArrowLeft') moveResponse(-1);
        if (e.key === 'ArrowRight') moveResponse(1);
    });

    $('#rs-stage').on('touchstart', function (e) {
        rsTouchX = e.originalEvent.touches[0].clientX;
    }).on('touchend', function (e) {
        if (rsTouchX === null) return;
        const moved = e.originalEvent.changedTouches[0].clientX - rsTouchX;
        rsTouchX = null;
        if (Math.abs(moved) > 50) moveResponse(moved < 0 ? 1 : -1);
    });
});
</script>
@endif
<script>
function showTab(tab) {
    $('.sr-tabs button').each(function () { $(this).toggleClass('active', $(this).data('tab') === tab); });
    $('#tab-summary').toggle(tab === 'summary');
    $('#tab-responses').toggle(tab === 'responses');
    if (tab === 'responses' && typeof loadResponse === 'function' && !rsNo) loadResponse(1);
}
</script>
