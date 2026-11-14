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

        @if(auth()->user()->hasPerm('history.view')
        || auth()->user()->hasPerm('notice.view'))        
        <li>
            <a href="javascript:;" class="side-menu  <?=$depth1_num=="2"?"side-menu--active side-menu--open":"" ?> ">
                <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="building"></i> </div>
                <div class="side-menu__title justify-between font-semibold"> 회사소개 <i data-lucide="chevron-down" class="menu__sub-icon "></i></div>
            </a>
            <ul class="<?=$depth1_num=="2" ?"side-menu__sub-open":"" ?>">
                @if(auth()->user()->hasPerm('history.view'))
                <li>
                    <a href="/admin/history" class="side-menu <?=$depth1_num=="2" && $depth2_num=="1"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="history"></i> </div>
                        <div class="side-menu__title font-semibold"> 연혁 </div>
                    </a>
                </li>
                @endif
          
            </ul>
        </li>
        @endif

        @if(auth()->user()->hasPerm('portfolio.view')
        || auth()->user()->hasPerm('partner.view')
        || auth()->user()->hasPerm('product.view')
        || auth()->user()->hasPerm('formCategory.view'))
        <li>
            <a href="javascript:;" class="side-menu  <?=$depth1_num=="3"?"side-menu--active side-menu--open":"" ?> ">
                <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="briefcase-business"></i> </div>
                <div class="side-menu__title justify-between font-semibold"> 사업관리 <i data-lucide="chevron-down" class="menu__sub-icon "></i></div>
            </a>
            <ul class="<?=$depth1_num=="3" ?"side-menu__sub-open":"" ?>">
                @if(auth()->user()->hasPerm('portfolio.view'))
                <li>
                    <a href="/admin/portfolio" class="side-menu  <?=$depth1_num=="3" && $depth2_num=="1"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="images"></i> </div>
                        <div class="side-menu__title font-semibold"> 도입현황 </div>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPerm('partner.view'))
                <li>
                    <a href="/admin/partner" class="side-menu  <?=$depth1_num=="3" && $depth2_num=="2"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="building-2"></i> </div>
                        <div class="side-menu__title font-semibold"> 파트너 </div>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPerm('product.view'))
                <li>
                    <a href="/admin/product" class="side-menu  <?=$depth1_num=="3" && $depth2_num=="3"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="package-search"></i> </div>
                        <div class="side-menu__title font-semibold"> 제품 </div>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPerm('formCategory.view'))
                <li>
                    <a href="/admin/formCategory" class="side-menu  <?=$depth1_num=="3" && $depth2_num=="4"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="folder-tree"></i> </div>
                        <div class="side-menu__title font-semibold"> 카테고리 관리 </div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if(auth()->user()->hasPerm('rips.view')
        || auth()->user()->hasPerm('ripsOrder.view')
        || auth()->user()->hasPerm('ripsSales.view')
        || auth()->user()->hasPerm('aiApi.view')
        || auth()->user()->hasPerm('inquireRips.view')
        || auth()->user()->hasPerm('customsIssue.view'))
        <li>
            <a href="javascript:;" class="side-menu  <?=$depth1_num=="7"?"side-menu--active side-menu--open":"" ?> ">
                <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="store"></i> </div>
                <div class="side-menu__title justify-between font-semibold"> RIPS 관리 <i data-lucide="chevron-down" class="menu__sub-icon "></i></div>
            </a>
            <ul class="<?=$depth1_num=="7" ?"side-menu__sub-open":"" ?>">
                @if(auth()->user()->hasPerm('rips.view'))
                <li>
                    <a href="/admin/ripsPartner" class="side-menu  <?=$depth1_num=="7" && $depth2_num=="1"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="link"></i> </div>
                        <div class="side-menu__title font-semibold"> RIPS 업체 </div>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPerm('rips.create'))
                <li>
                    <a href="/admin/ripsPartner/create" class="side-menu  <?=$depth1_num=="7" && $depth2_num=="6"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="building-2"></i> </div>
                        <div class="side-menu__title font-semibold"> RIPS 업체 등록 </div>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPerm('ripsSales.view'))
                <li>
                    <a href="/admin/ripsSales" class="side-menu  <?=$depth1_num=="7" && $depth2_num=="5"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="chart-column"></i> </div>
                        <div class="side-menu__title font-semibold"> 판매 관리 </div>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPerm('aiApi.view'))
                <li>
                    <a href="/admin/aiApi" class="side-menu  <?=$depth1_num=="7" && $depth2_num=="8"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="bot"></i> </div>
                        <div class="side-menu__title font-semibold"> AI 연동 </div>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPerm('ripsOrder.view'))
                <li>
                    <a href="/admin/ripsOrder" class="side-menu  <?=$depth1_num=="7" && $depth2_num=="3"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="truck"></i> </div>
                        <div class="side-menu__title font-semibold"> 발주 관리 </div>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPerm('inquireRips.view'))
                <li>
                    <a href="/admin/inquireRips" class="side-menu  <?=$depth1_num=="7" && $depth2_num=="2"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="square-pen"></i> </div>
                        <div class="side-menu__title font-semibold"> RIPS 이용신청 </div>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPerm('customsIssue.view'))
                <li>
                    <a href="/admin/customsIssue" class="side-menu  <?=$depth1_num=="7" && $depth2_num=="4"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="triangle-alert"></i> </div>
                        <div class="side-menu__title font-semibold"> 통관 이슈 </div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if(auth()->user()->hasPerm('checklist.view')
        || auth()->user()->hasPerm('checkresult.view'))
        <li>
            <a href="javascript:;" class="side-menu  <?=$depth1_num=="4"?"side-menu--active side-menu--open":"" ?> ">
                <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="briefcase-business"></i> </div>
                <div class="side-menu__title justify-between font-semibold"> 설문조사 <i data-lucide="chevron-down" class="menu__sub-icon "></i></div>
            </a>
            <ul class="<?=$depth1_num=="4" ?"side-menu__sub-open":"" ?>">
                @if(auth()->user()->hasPerm('checklist.view'))
                <li>
                    <a href="/admin/checklist" class="side-menu  <?=$depth1_num=="4" && $depth2_num=="1"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="square-check-big"></i> </div>
                        <div class="side-menu__title font-semibold">설문조사 관리 </div>
                    </a>
                </li>
                @endif                                
                @if(auth()->user()->hasPerm('checkresult.view'))
                <li>
                    <a href="/admin/checkResultList" class="side-menu  <?=$depth1_num=="4" && $depth2_num=="2"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="user-round-check"></i> </div>
                        <div class="side-menu__title font-semibold">설문조사 결과 관리</div>
                    </a>
                </li>
                @endif                 
            </ul>
        </li>
        @endif        

        @if(auth()->user()->hasPerm('inquire.view')
        || auth()->user()->hasPerm('inquireTestbed.view')
        || auth()->user()->hasPerm('faq.view'))
        <li>
            <a href="javascript:;" class="side-menu  <?=$depth1_num=="5"?"side-menu--active side-menu--open":"" ?> ">
                <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="mail"></i> </div>
                <div class="side-menu__title justify-between font-semibold"> 고객문의 <i data-lucide="chevron-down" class="menu__sub-icon "></i></div>
            </a>
            <ul class="<?=$depth1_num=="5" ?"side-menu__sub-open":"" ?>">
                @if(auth()->user()->hasPerm('inquire.view'))
                <li>
                    <a href="/admin/inquire" class="side-menu  <?=$depth1_num=="5" && $depth2_num=="1"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="mail"></i> </div>
                        <div class="side-menu__title font-semibold"> 고객문의 </div>
                    </a>
                </li>
                @endif 
                @if(auth()->user()->hasPerm('inquireTestbed.view'))
                <li>
                    <a href="/admin/inquireTestbed" class="side-menu  <?=$depth1_num=="5" && $depth2_num=="3"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="book-open-check"></i> </div>
                        <div class="side-menu__title font-semibold"> 테스트베드 문의 </div>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPerm('faq.view'))
                <li>
                    <a href="/admin/faq" class="side-menu <?=$depth1_num=="5" && $depth2_num=="4"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-4 h-4" data-lucide="message-circle-question"></i> </div>
                        <div class="side-menu__title font-semibold"> FAQ </div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if(auth()->user()->hasPerm('user.view') || auth()->user()->hasPerm('adminLog.view'))
        <li>
            <a href="javascript:;" class="side-menu  <?=$depth1_num=="6"?"side-menu--active side-menu--open":"" ?> ">
                <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="monitor-cloud"></i> </div>
                <div class="side-menu__title justify-between font-semibold"> 서비스 관리 <i data-lucide="chevron-down" class="menu__sub-icon "></i></div>
            </a>
            <ul class="<?=$depth1_num=="6" ?"side-menu__sub-open":"" ?>">
                @if(auth()->user()->hasPerm('user.view'))
                <li>
                    <a href="/admin/user" class="side-menu  <?=$depth1_num=="6" && $depth2_num=="1"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="user"></i> </div>
                        <div class="side-menu__title font-semibold"> 사용자 관리 </div>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPerm('adminLog.view'))
                <li>
                    <a href="/admin/adminLog" class="side-menu  <?=$depth1_num=="6" && $depth2_num=="2"?"side-menu--active":"" ?> ">
                        <div class="side-menu__icon"> <i class="w-5 h-5" data-lucide="history"></i> </div>
                        <div class="side-menu__title font-semibold"> 관리자 로그 </div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif   
    </ul>
</nav>