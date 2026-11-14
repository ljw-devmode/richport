{{--
	카테고리 1~4차 단계별 선택 (스크립트 : 푸터의 RipsCategorySelect)
	- id          : 감싸는 div id
	- name        : 검색 폼 등에서 전송할 hidden input 이름 (선택)
	- value       : 초기 선택 카테고리 idx (선택)
	- placeholder : 각 select 의 빈 값 문구 접미사 (기본 '선택', 검색 필터는 '전체')
--}}
<div class="rp-cat-select {{ $class ?? '' }}" id="{{ $id }}" data-value="{{ $value ?? '' }}" data-placeholder="{{ $placeholder ?? '선택' }}">
	@foreach ([1, 2, 3, 4] as $depth)
		<select class="form-select" data-depth="{{ $depth }}"></select>
	@endforeach
	@if (!empty($name))
		<input type="hidden" name="{{ $name }}" value="{{ $value ?? '' }}">
	@endif
</div>
