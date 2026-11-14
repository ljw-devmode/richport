@php
	$ripsPartner = Auth::guard('rips')->user();
	$rid         = $ripsPartner->rips_id ?? '';
	$menu        = $menu ?? '';
@endphp
<!DOCTYPE html>

<html lang="ko" class="light">

<!-- BEGIN: Head -->
<head>
    <meta charset="utf-8">
    <link href="{{ asset('admin_assets/dist/images/favicon.svg') }}" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RICHPORT RIPS</title>

    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/pretendard.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('rips_assets/css/rips.css') }}?v={{ filemtime(public_path('rips_assets/css/rips.css')) }}" />
    <!-- END: CSS Assets-->
</head>
<!-- END: Head -->

<body>

<!-- ** 페이지 로딩 ** -->
<div id="loadingContainer">
    <svg width="20" viewBox="0 0 135 135" xmlns="http://www.w3.org/2000/svg" fill="#4C8498" class="w-20 h-20 opacity-80">
        <path d="M67.447 58c5.523 0 10-4.477 10-10s-4.477-10-10-10-10 4.477-10 10 4.477 10 10 10zm9.448 9.447c0 5.523 4.477 10 10 10 5.522 0 10-4.477 10-10s-4.478-10-10-10c-5.523 0-10 4.477-10 10zm-9.448 9.448c-5.523 0-10 4.477-10 10 0 5.522 4.477 10 10 10s10-4.478 10-10c0-5.523-4.477-10-10-10zM58 67.447c0-5.523-4.477-10-10-10s-10 4.477-10 10 4.477 10 10 10 10-4.477 10-10z">
            <animateTransform attributeName="transform" type="rotate" from="0 67 67" to="-360 67 67" dur="2.5s" repeatCount="indefinite"></animateTransform>
        </path>
    </svg>
</div>
<!-- ** 페이지 로딩 끝 ** -->
@if (session('alert'))
<script>
    alert(@json(session('alert')));
</script>
@endif

@if (empty($header_skip))
<div class="content_wrap">
	<!-- BEGIN: Side Menu -->
	<nav class="side-nav border-r border-slate-200 !px-0">
	    <a href="{{ route('rips.dashboard', ['rid' => $rid]) }}" class="intro-x justify-center items-center py-3.5 hidden logo_lg">
	        <img alt="로고" class="h-6" src="{{ asset('admin_assets/dist/images/logo.svg') }}" />
	    </a>
	    <a href="{{ route('rips.dashboard', ['rid' => $rid]) }}" class="intro-x items-center py-3.5 flex logo_sm">
	        <img alt="로고" class="h-6 mx-auto" src="{{ asset('admin_assets/dist/images/favicon.svg') }}" />
	    </a>
	    <div class="border-t border-slate-200 my-2.5"></div>

	    <ul class="px-3">
	        <li>
	            <a href="{{ route('rips.dashboard', ['rid' => $rid]) }}" class="side-menu {{ $menu == 'dashboard' ? 'side-menu--active' : '' }}">
	                <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="home"></i> </div>
	                <div class="side-menu__title font-semibold"> 대시보드 </div>
	            </a>
	        </li>
	        @php
	            // 메뉴 그룹 : [제목, 아이콘, [[메뉴키, 라우트, 아이콘, 이름], ...]]
	            $menuGroups = [
	                ['상품관리', 'package', [
	                    ['category', 'rips.category', 'folder-tree', '카테고리'],
	                    ['product',  'rips.product',  'package-open', '상품'],
	                ]],
	                ['판매/발주관리', 'chart-column', [
	                    ['sales', 'rips.sales', 'receipt', '판매관리'],
	                    ['order', 'rips.order', 'clipboard-list', '발주관리'],
	                ]],
	                ['리치포트 상품', 'shopping-bag', [
	                    ['richport', 'rips.richport', 'package-search', '리치포트 상품'],
	                    ['testbed',  'rips.testbed',  'flask-conical', '테스트베드 신청'],
	                    ['survey',   'rips.survey',   'clipboard-check', '설문조사 결과'],
	                ]],
	                ['설정', 'settings', [
	                    ['profile', 'rips.profile', 'user-cog', '내 정보 수정'],
	                ]],
	            ];
	        @endphp
	        @foreach ($menuGroups as [$groupTitle, $groupIcon, $groupItems])
	            @php $isOpen = in_array($menu, array_column($groupItems, 0)); @endphp
	            <li>
	                <a href="javascript:;" class="side-menu {{ $isOpen ? 'side-menu--active side-menu--open' : '' }}">
	                    <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="{{ $groupIcon }}"></i> </div>
	                    <div class="side-menu__title justify-between font-semibold"> {{ $groupTitle }} <i data-lucide="chevron-down" class="menu__sub-icon"></i></div>
	                </a>
	                <ul class="{{ $isOpen ? 'side-menu__sub-open' : '' }}">
	                    @foreach ($groupItems as [$itemKey, $itemRoute, $itemIcon, $itemTitle])
	                        <li>
	                            <a href="{{ route($itemRoute, ['rid' => $rid]) }}" class="side-menu {{ $menu == $itemKey ? 'side-menu--active' : '' }}">
	                                <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="{{ $itemIcon }}"></i> </div>
	                                <div class="side-menu__title font-semibold"> {{ $itemTitle }} </div>
	                            </a>
	                        </li>
	                    @endforeach
	                </ul>
	            </li>
	        @endforeach
	    </ul>
	</nav>
	<!-- END: Side Menu -->

	<div class="content">
		<div class="top-bar flex items-center py-3.5">
		    <div class="fold_btn intro-x">
		        <button class="btn btn-sm btn-dark rounded border-0 p-1.5">
		            <i data-lucide="menu" class="text-white"></i>
		        </button>
		    </div>
		    <div class="flex items-center gap-3">
		        <span class="intro-x font-semibold">{{ $ripsPartner->partner_name }}@if($ripsPartner->store_name) <span class="rp-muted font-normal">· {{ $ripsPartner->store_name }}</span>@endif</span>
		        <a href="javascript:;" class="btn btn-dark intro-x" onclick="modalOpen('logout-modal')">로그아웃</a>
		    </div>
		</div>
@endif
