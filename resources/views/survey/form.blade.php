<!DOCTYPE html>
<html lang="{{ ['ko' => 'ko', 'en' => 'en', 'vn' => 'vi'][$lang] }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="{{ asset('admin_assets/dist/images/favicon.svg') }}" rel="shortcut icon">
	<link rel="stylesheet" href="{{ asset('admin_assets/dist/css/pretendard.css') }}">
	<title>{{ $list->check_name }}</title>
	<style>
		:root {--primary:#4C8498; --ink:#1e293b; --muted:#64748b; --line:#e2e8f0; --bg:#f1f5f9; --danger:#dc2626; }
		* {box-sizing:border-box; margin:0; padding:0; }
		body {background:var(--bg); color:var(--ink); font:15px/1.55 "Pretendard", system-ui, sans-serif; }
		.wrap {max-width:680px; margin:0 auto; padding:16px 16px 48px; }
		.langs {display:flex; justify-content:flex-end; gap:4px; margin-bottom:10px; }
		.langs a {padding:3px 10px; border-radius:999px; color:var(--muted); font-size:13px; text-decoration:none; }
		.langs a.on {background:#fff; color:var(--primary); font-weight:700; }
		.card {padding:20px; margin-top:12px; border:1px solid var(--line); border-radius:10px; background:#fff; }
		.card.head {margin-top:0; border-top:6px solid var(--primary); }
		.product {color:var(--primary); font-size:14px; font-weight:600; }
		h1 {margin-top:2px; font-size:21px; line-height:1.35; overflow-wrap:anywhere; }
		h2 {margin-top:24px; font-size:16px; overflow-wrap:anywhere; }
		.path {color:var(--muted); font-size:12px; }
		.label {display:block; font-weight:600; overflow-wrap:anywhere; }
		.req {color:var(--danger); }
		.hint {color:var(--muted); font-size:12px; font-weight:400; }
		.field + .field {margin-top:18px; }
		select, textarea, .remark {width:100%; margin-top:8px; padding:10px 12px; border:1px solid #cbd5e1; border-radius:8px; background:#fff; color:inherit; font:inherit; }
		textarea {min-height:76px; resize:vertical; }
		.remark {font-size:14px; }
		.options {display:flex; flex-direction:column; gap:2px; margin-top:8px; }
		.options.inline {flex-direction:row; flex-wrap:wrap; gap:6px 18px; }
		.options label {display:flex; align-items:flex-start; gap:9px; padding:7px 0; cursor:pointer; overflow-wrap:anywhere; }
		.options input {flex-shrink:0; width:18px; height:18px; margin-top:2px; accent-color:var(--primary); }
		.error {display:none; margin-top:6px; color:var(--danger); font-size:13px; }
		.invalid .error {display:block; }
		.invalid.card, .field.invalid select {border-color:var(--danger); }
		button {width:100%; margin-top:18px; padding:14px; border:0; border-radius:8px; background:var(--primary); color:#fff; font:inherit; font-weight:700; cursor:pointer; }
		button:disabled {opacity:.6; cursor:default; }
		.notice {text-align:center; padding:48px 20px; }
		.form-error {margin-top:12px; color:var(--danger); text-align:center; }
	</style>
</head>
<body>
<div class="wrap">
	<div class="langs">
		@foreach (['ko' => '한국어', 'en' => 'English', 'vn' => 'Tiếng Việt'] as $code => $name)
		<a href="?lang={{ $code }}" class="{{ $lang === $code ? 'on' : '' }}">{{ $name }}</a>
		@endforeach
	</div>

	@if($closed)
		<div class="card notice">{{ $labels['closed'] }}</div>
	@else
	<div class="card head">
		<p class="product">{{ $list->product->tr('product_name') }}</p>
		<h1>{{ $list->check_name }}</h1>
	</div>

	<form id="survey-form" novalidate>
		<input type="hidden" name="lang" value="{{ $lang }}">

		<h2>{{ $labels['respondent'] }}</h2>
		<div class="card">
			<div class="field" data-required="select">
				<label class="label" for="age_group">{{ $labels['age'] }} <span class="req">*</span></label>
				<select id="age_group" name="age_group">
					<option value="">{{ $labels['select'] }}</option>
					@foreach ($labels['ages'] as $key => $label)
					<option value="{{ $key }}">{{ $label }}</option>
					@endforeach
				</select>
				<p class="error">{{ $labels['required'] }}</p>
			</div>
			<div class="field" data-required="choice">
				<span class="label">{{ $labels['gender'] }} <span class="req">*</span></span>
				<div class="options inline">
					@foreach ($labels['genders'] as $key => $label)
					<label><input type="radio" name="gender" value="{{ $key }}"> {{ $label }}</label>
					@endforeach
				</div>
				<p class="error">{{ $labels['required'] }}</p>
			</div>
			<div class="field" data-required="select">
				<label class="label" for="cities_idx">{{ $labels['city'] }} <span class="req">*</span></label>
				<select id="cities_idx" name="cities_idx">
					<option value="">{{ $labels['select'] }}</option>
					@foreach ($cities as $city)
					<option value="{{ $city['idx'] }}">{{ $city['name'] }}</option>
					@endforeach
				</select>
				<p class="error">{{ $labels['required'] }}</p>
			</div>
			<div class="field" data-required="select">
				<label class="label" for="districts_idx">{{ $labels['district'] }} <span class="req">*</span></label>
				<select id="districts_idx" name="districts_idx">
					<option value="">{{ $labels['select'] }}</option>
				</select>
				<p class="error">{{ $labels['required'] }}</p>
			</div>
		</div>

		@foreach ($list->checksection as $section)
			@if($section->section_name)<h2>{{ $section->section_name }}</h2>@endif
			@foreach ($section->checkitem as $item)
			@php
				$path = collect([$section->layout_type >= 2 ? $item->d2_name : null, $section->layout_type == 3 ? $item->d3_name : null])->filter()->implode(' > ');
				$isChoice = in_array($item->input_type, ['radio', 'checkbox']);
			@endphp
			<div class="card" data-item="{{ $item->idx }}" @if($isChoice) data-required="choice" @endif>
				@if($path)<p class="path">{{ $path }}</p>@endif
				<span class="label">
					{{ $item->item_name }}
					@if($isChoice)<span class="req">*</span>@endif
					@if($item->input_type === 'checkbox')<span class="hint">({{ $labels['multi'] }})</span>@endif
				</span>
				@if($isChoice)
					<div class="options">
						@foreach ($item->checkoption as $option)
						<label>
							<input type="{{ $item->input_type }}" name="results[{{ $item->idx }}]{{ $item->input_type === 'checkbox' ? '[]' : '' }}" value="{{ $option->idx }}">
							<span>{{ $option->option_name }}</span>
						</label>
						@endforeach
					</div>
					@if($item->has_text === 'Y')
					<input type="text" class="remark" name="text_results[{{ $item->idx }}]" maxlength="2000" placeholder="{{ $labels['remark'] }}">
					@endif
					<p class="error">{{ $labels['required'] }}</p>
				@else
					<textarea name="results[{{ $item->idx }}]" maxlength="2000" placeholder="{{ $labels['text'] }}"></textarea>
				@endif
			</div>
			@endforeach
		@endforeach

		<button type="submit" id="submit-btn">{{ $labels['submit'] }}</button>
		<p class="form-error" id="form-error" hidden></p>
	</form>

	<div class="card notice" id="done" hidden>{{ $labels['done'] }}</div>

	<script>
	const CITIES  = @json($cities);
	const MESSAGE = @json(['error' => $labels['error'], 'expired' => $labels['expired'], 'select' => $labels['select']]);
	const form    = document.getElementById('survey-form');
	const button  = document.getElementById('submit-btn');
	const failure = document.getElementById('form-error');

	// 시 선택 → 구역 목록
	document.getElementById('cities_idx').addEventListener('change', function () {
		const city   = CITIES.find(c => String(c.idx) === this.value);
		const select = document.getElementById('districts_idx');
		select.length = 1;
		(city ? city.districts : []).forEach(d => select.add(new Option(d.name, d.idx)));
	});

	function isFilled(el) {
		return el.dataset.required === 'select' ? el.querySelector('select').value !== '' : el.querySelector('input:checked') !== null;
	}

	// 답변하면 오류 표시 해제
	form.addEventListener('change', function (e) {
		const el = e.target.closest('[data-required]');
		if (el && isFilled(el)) el.classList.remove('invalid');
	});

	function showFailure(message) {
		failure.textContent = message;
		failure.hidden = false;
	}

	form.addEventListener('submit', function (e) {
		e.preventDefault();
		failure.hidden = true;

		let firstInvalid = null;
		form.querySelectorAll('[data-required]').forEach(function (el) {
			const filled = isFilled(el);
			el.classList.toggle('invalid', !filled);
			if (!filled && !firstInvalid) firstInvalid = el;
		});
		if (firstInvalid) return firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });

		button.disabled = true;
		fetch(location.pathname, {
			method: 'POST',
			body: new FormData(form),
			headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
		}).then(function (res) {
			if (res.status === 419) throw new Error(MESSAGE.expired);
			return res.json().catch(() => { throw new Error(MESSAGE.error); });
		}).then(function (res) {
			if (res.status !== 'success') throw new Error(res.message || MESSAGE.error);
			form.hidden = true;
			document.getElementById('done').hidden = false;
			window.scrollTo(0, 0);
		}).catch(function (err) {
			showFailure(err.message && !(err instanceof TypeError) ? err.message : MESSAGE.error);
			button.disabled = false;
		});
	});
	</script>
	@endif
</div>
</body>
</html>
