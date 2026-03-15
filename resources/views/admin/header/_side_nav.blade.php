<!-- BEGIN: Side Menu -->
<nav class="side-nav border-r border-slate-200 !px-0">
    <!-- 펼쳤을 때 메뉴 로고 -->
    <a href="/admin/dashboard" class="intro-x justify-center items-center py-3.5 hidden logo_lg">
        <img alt="로고" class="h-6" src="{{ asset('admin_assets/dist/images/logo.svg') }}" />
    </a>
    <!-- 닫았을 때 메뉴 로고 -->
    <a href="/admin/dashboard" class="intro-x items-center py-3.5 flex logo_sm">
        <img alt="로고" class="h-6 mx-auto" src="{{ asset('admin_assets/dist/images/favicon.svg') }}" />
    </a>
    <div class="border-t border-slate-200 my-2.5"></div>
  
    <ul class="px-3">
        <li>
            <a href="/admin/dashboard" class="side-menu <?=$depth1_num=="1"?"side-menu--active":"" ?>  "> 
                 <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="home"></i> </div>
                 <div class="side-menu__title font-semibold"> 대시보드 </div>
             </a>
        </li>
        <li>
            <a href="javascript:;" class="side-menu  <?=$depth1_num=="2"?"side-menu--active side-menu--open":"" ?> ">
                <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="building"></i> </div>
                <div class="side-menu__title justify-between font-semibold"> 회사소개 <i data-lucide="chevron-down" class="menu__sub-icon "></i></div>
            </a>
            <ul class="<?=$depth1_num=="2" ?"side-menu__sub-open":"" ?>">
                <li>
                    <a href="/admin/history" class="side-menu <?=$depth1_num=="2" && $depth2_num=="1"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="history"></i> </div>
                        <div class="side-menu__title font-semibold"> 연혁 </div>
                    </a>
                </li>
                <li>
                    <a href="/admin/notice" class="side-menu  <?=$depth1_num=="2" && $depth2_num=="2"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="building-2"></i> </div>
                        <div class="side-menu__title font-semibold"> 회사소식 </div>
                    </a>
                </li>                
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="side-menu  <?=$depth1_num=="3"?"side-menu--active side-menu--open":"" ?> ">
                <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="briefcase-business"></i> </div>
                <div class="side-menu__title justify-between font-semibold"> 사업관리 <i data-lucide="chevron-down" class="menu__sub-icon "></i></div>
            </a>
            <ul class="<?=$depth1_num=="3" ?"side-menu__sub-open":"" ?>">
                <li>
                    <a href="/admin/portfolio" class="side-menu  <?=$depth1_num=="3" && $depth2_num=="1"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="images"></i> </div>
                        <div class="side-menu__title font-semibold"> 포트폴리오 </div>
                    </a>
                </li>
                <li>
                    <a href="/admin/company" class="side-menu  <?=$depth1_num=="3" && $depth2_num=="21"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="solar-panel"></i> </div>
                        <div class="side-menu__title font-semibold"> SPC 리포트 </div>
                    </a>
                </li>                 
                <li>
                    <a href="/admin/company" class="side-menu  <?=$depth1_num=="3" && $depth2_num=="22"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="solar-panel"></i> </div>
                        <div class="side-menu__title font-semibold"> 발전소 리포트 </div>
                    </a>
                </li>                 
                <li>
                    <a href="/admin/company" class="side-menu  <?=$depth1_num=="3" && $depth2_num=="31"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="solar-panel"></i> </div>
                        <div class="side-menu__title font-semibold"> 발전소 관리 </div>
                    </a>
                </li>                 
                <li>
                    <a href="/admin/spc" class="side-menu  <?=$depth1_num=="3" && $depth2_num=="32"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="factory"></i> </div>
                        <div class="side-menu__title font-semibold"> SPC 관리 </div>
                    </a>
                </li>
                <li>
                    <a href="/admin/kma" class="side-menu  <?=$depth1_num=="3" && $depth2_num=="33"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="sun"></i> </div>
                        <div class="side-menu__title font-semibold"> 기상청 일사량 </div>
                    </a>
                </li>                
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="side-menu  <?=$depth1_num=="4"?"side-menu--active side-menu--open":"" ?> ">
                <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="mail"></i> </div>
                <div class="side-menu__title justify-between font-semibold"> 고객문의 <i data-lucide="chevron-down" class="menu__sub-icon "></i></div>
            </a>
            <ul class="<?=$depth1_num=="5" ?"side-menu__sub-open":"" ?>">
                <li>
                    <a href="/admin/inquire" class="side-menu  <?=$depth1_num=="5" && $depth2_num=="1"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="mail"></i> </div>
                        <div class="side-menu__title font-semibold"> 고객문의 </div>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="side-menu  <?=$depth1_num=="5"?"side-menu--active side-menu--open":"" ?> ">
                <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="user"></i> </div>
                <div class="side-menu__title justify-between font-semibold"> 사용자 관리 <i data-lucide="chevron-down" class="menu__sub-icon "></i></div>
            </a>
            <ul class="<?=$depth1_num=="6" ?"side-menu__sub-open":"" ?>">
                <li>
                    <a href="/admin/user" class="side-menu  <?=$depth1_num=="6" && $depth2_num=="1"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="user"></i> </div>
                        <div class="side-menu__title font-semibold"> 사용자 관리 </div>
                    </a>
                </li>
            </ul>
        </li>        
    </ul>
</nav>