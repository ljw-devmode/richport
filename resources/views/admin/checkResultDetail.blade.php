@php
	$header_skip = false;
	$depth1_num = '4';
	$depth2_num = '2';
@endphp
@include('admin.header._header')


<div class="mt-6 px-5 pb-10">
	<div class="flex items-center justify-between gap-3">
		<div>
			<h3 class="intro-y text-xl font-bold">{{ $product->product_name }}</h3>
			<p class="mt-1 text-slate-500">테스트베드 설문조사 결과 · {{ $product->partners->partner_name ?? '-' }}</p>
		</div>
		<a href="/admin/checkResultList" class="btn btn-outline-secondary shrink-0">목록으로</a>
	</div>

	<div class="mt-6">
		@include('survey._result', [
			'surveyUrl'   => '/admin/checkResult/'.$product->idx,
			'responseUrl' => $survey ? '/admin/checkResultResponse/'.$survey->idx : '',
		])
	</div>
</div>

<!-- END: Content -->
</div>
</div>
@include('admin.footer._footer')
