@php
	$currentPage = $data->currentPage();
	$lastPage    = $data->lastPage();

	$blockSize    = 10;
	$currentBlock = (int) ceil($currentPage / $blockSize);

	$start = ($currentBlock - 1) * $blockSize + 1;
	$end   = min($start + $blockSize - 1, $lastPage);
@endphp
@if ($lastPage > 1)
<nav class="w-full mt-5">
	<ul class="pagination justify-center">
		<li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
			<a class="page-link" href="{{ $data->url(1) }}"> <i data-lucide="chevrons-left" class="w-4 h-4"></i></a>
		</li>
		<li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
			<a class="page-link" href="{{ $data->previousPageUrl() }}"> <i data-lucide="chevron-left" class="w-4 h-4"></i></a>
		</li>
		@for ($i = $start; $i <= $end; $i++)
			<li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
				<a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
			</li>
		@endfor
		<li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
			<a class="page-link" href="{{ $data->nextPageUrl() }}"> <i data-lucide="chevron-right" class="w-4 h-4"></i></a>
		</li>
		<li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
			<a class="page-link" href="{{ $data->url($lastPage) }}"> <i data-lucide="chevrons-right" class="w-4 h-4"></i></a>
		</li>
	</ul>
</nav>
@endif
