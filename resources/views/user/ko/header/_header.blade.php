<!-- 헤더 -->
<header class="active">
    <h1 class="logo">
    	<a href="/ko">
    		<img src="{{ asset('user_assets/img/logo.svg') }}" alt="">
    	</a>
    </h1>
    <div class="right_btn">
        <a href="javascript:;" onclick="calModalToggle()">
        	<img src="{{ asset('user_assets/img/icon/calculator.svg') }}" alt="">
        	<img class="mo_ver" src="{{ asset('user_assets/img/icon/calculator_mo.svg') }}" alt="">
        	<span>수익계산기</span>
        </a>
        <a href="javascript:;" class="type02" onclick="modalOpen('inquiry-modal')">
        	<img src="{{ asset('user_assets/img/icon/inquiry.svg') }}" alt="">
        	<span>문의하기</span>
        </a>
        <div class="language_selector">
            <a href="javascript:;" onclick="toggleLangMenu(this)">
                <img src="{{ asset('user_assets/img/icon/language.svg') }}" alt="">
                <span class="mo_ver">KO</span>
                <span class="lang_text">한국어</span>
            </a>
            <ul class="lang_menu" style="display: none;">
                <li><a href="javascript:;" onclick="changeLang('ko')">한국어</a></li>
                <li><a href="javascript:;" onclick="changeLang('en')">English</a></li>
                <li><a href="javascript:;" onclick="changeLang('zh-cn')">中文</a></li>
            </ul>
        </div>
        <button class="ham_btn" onclick="hamToggle(this)"><i></i><i></i><i></i></button>
    </div>
</header>

<!-- 전체메뉴 -->
<div id="all_menu">
    <div class="menu_box">
        <div class="depth1">
            <p>회사소개</p>
            <ul class="depth2">
                <li><a href="/ko/company/about">솔라테크 소개</a></li>
                <li><a href="/ko/company/history">연혁</a></li>
                <li><a href="/ko/company/patent">인증·특허</a></li>
            </ul>
        </div>
    </div>
    <div class="menu_box">
        <div class="depth1">
            <p>사업소개</p>
            <ul class="depth2">
                <li>
                    <a href="javascript:;">신재생에너지</a>
                    <ul class="depth3">
                        <li><a href="/ko/business/index">왜 솔라테크인가?</a></li>
						<li><a href="/ko/business/solar">지붕임대 태양광</a></li>
                        <li><a href="/ko/business/lease">RE100 리스</a></li>
                        <li><a href="/ko/business/rps">RPS 전력판매</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">정부지원사업</a>
                    <ul class="depth3">
                        <li><a href="/ko/gov/building">건물지원</a></li>
						<li><a href="/ko/gov/finance">금융지원</a></li>
                        <li><a href="/ko/gov/house">주택지원</a></li>
                        <li><a href="/ko/gov/carbon">탄소중립설비지원</a></li>
                    </ul>
                </li>                
                <li>
                    <a href="javascript:;">스마트팩토리</a>
                    <ul class="depth3">
                        <li><a href="javascript:alert('준비중입니다')">AUTOMATION</a></li>
                        <li><a href="javascript:alert('준비중입니다')">MES</a></li>
                        <li><a href="javascript:alert('준비중입니다')">FEMS</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="menu_box">
        <div class="depth1">
            <p>솔라테크 소식</p>
            <ul class="depth2">
                <li><a href="/ko/portfolio">포트폴리오</a></li>
                <li><a href="/ko/news">회사소식</a></li>
                <li><a href="/ko/culture">조직문화</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- 퀵메뉴 -->
<div id="quick_box">
    <a href="javascript:;" onclick="topMove()"><img src="{{ asset('user_assets/img/icon/quick_top.svg') }}" alt=""></a>
    <a href="https://www.youtube.com/channel/UC09-6cslYKyVAiH86JmuvrA" target="_blank"><img src="{{ asset('user_assets/img/icon/quick_youtube.svg') }}" alt=""></a>
    <a href="https://blog.naver.com/solarteq"  target="_blank"><img src="{{ asset('user_assets/img/icon/quick_blog.svg') }}" alt=""></a>
</div>

<!-- 수익계산기 모달 -->
<div class="cal_modal">
    <div class="close_btn" onclick="calModalToggle()"><i></i><i></i></div>
    @include('user.ko.header._calculator')
</div>

<!-- 문의하기모달 -->
<div class="modal" id="inquiry-modal">
    <div class="modal_wrap">
        <div class="modal_header">
            <h3>문의하기</h3>
            <p>요청사항을 아래에 작성해주시면 담당자가 확인 후 신속하게 연락드리도록 하겠습니다.</p>
            <button onclick="modalClose('inquiry-modal')"><img src="{{ asset('user_assets/img/icon/x.svg') }}" alt=""></button>
        </div>
        <div class="modal_body in_scroll" data-lenis-prevent>
            <div class="inquiry_box">
                <dl>
                    <dt>문의구분</dt>
                    <dd class="check_form">
                        <input type="radio" class="input-check" id="cate_1" name="types_idx" value="1"><label for="cate_1">지붕임대 태양광</label>
                        <input type="radio" class="input-check" id="cate_2" name="types_idx" value="2"><label for="cate_2">RE100 리스</label>
                        <input type="radio" class="input-check" id="cate_3" name="types_idx" value="3"><label for="cate_3">RPS 전력판매</label>
                        <input type="radio" class="input-check" id="cate_4" name="types_idx" value="4"><label for="cate_4">지원사업(건물·금융·주택·탄소중립)</label>
                        <input type="radio" class="input-check" id="cate_5" name="types_idx" value="5"><label for="cate_5">회사소개서·지명원 요청</label>
                    </dd>
                </dl>
                <dl>
                    <dt>회사명/성명<span>*</span></dt>
                    <dd><input type="text" id="inquire_company" name="inquire_company" class="form-input"></dd>
                </dl>
                <dl>
                    <dt>연락처<span>*</span></dt>
                    <dd><input type="text" id="inquire_hp" name="inquire_hp" class="form-input"></dd>
                </dl>
                <dl id="address_field" style="">
                    <dt>설치주소<span>*</span></dt>
                    <dd><input type="text" id="inquire_address" name="inquire_address" class="form-input"></dd>
                </dl>
                <dl id="email_field" style="display:none;">
                    <dt>이메일주소<span>*</span></dt>
                    <dd><input type="text" id="inquire_email" name="inquire_email" class="form-input"></dd>
                </dl>
                <dl>
                    <dt>문의사항</dt>
                    <dd><textarea id="inquire_contents" name="inquire_contents" class="form-textarea"></textarea></dd>
                </dl>
                <div class="agree_area">
                    <input type="checkbox" class="input-check" id="agree_policy" ><label for="agree_policy">개인정보 처리방침 동의</label>
                </div>
                <div class="agree_policy">
                	@include('user.ko.header._policy_text')
                </div>
            </div>
        </div>
        <div class="modal_footer">
            <a href="tel:0269124900" class="btn btn-black pc_none">전화걸기</a>
            <button class="btn btn-primary" onclick="inquire()">문의하기</button>
        </div>
    </div>
</div>


<script>
/* ===============================
 *  다국어 선택
 * =============================== */

// 쿠키 설정 함수
function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    // 브라우저 개발자 도구에서 쿠키 확인 가능하도록 설정
    document.cookie = name + "=" + encodeURIComponent(value) + ";" + expires + ";path=/;SameSite=Lax";
}

// 쿠키 읽기 함수
function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i].trim();
        if (c.indexOf(nameEQ) === 0) {
            const value = c.substring(nameEQ.length);
            return decodeURIComponent(value);
        }
    }
    return null;
}

// 페이지 로드 시 저장된 언어 복원 및 자동 이동 (미들웨어 처리 후 실행)
document.addEventListener('DOMContentLoaded', function() {
    // 현재 URL의 언어 코드 추출
    const pathParts = window.location.pathname.split('/').filter(p => p);
    const currentUrlLang = pathParts[0]; // 첫 번째 세그먼트가 언어 코드
    
    // 유효한 언어 코드 확인
    const validLangs = ['ko', 'en', 'cn'];
    
    // URL 코드 → 저장값 매핑
    const urlToStorageMap = {
        'ko': 'ko',
        'en': 'en',
        'cn': 'zh-cn'
    };
    
    // 현재 URL이 유효한 언어 코드를 가지고 있는지 확인
    if (validLangs.includes(currentUrlLang)) {
        // 현재 URL 언어를 저장값으로 변환하여 로컬 스토리지에 저장
        const storageLang = urlToStorageMap[currentUrlLang];
        localStorage.setItem('selectedLang', storageLang);
        // 현재 URL 언어로 헤더 표시 업데이트
        updateLanguageDisplay(storageLang);
    } else {
        // 첫 방문 또는 잘못된 경로의 경우 로컬 스토리지에서 복원
        const savedLang = localStorage.getItem('selectedLang') || 'ko';
        localStorage.setItem('selectedLang', savedLang);
        updateLanguageDisplay(savedLang);
    }
});

// 다국어 메뉴 토글
function toggleLangMenu(button) {
    const langMenu = button.nextElementSibling;
    const isVisible = langMenu.style.display === 'block';
    
    // 다른 메뉴들 닫기
    document.querySelectorAll('.lang_menu').forEach(m => {
        if (m !== langMenu) m.style.display = 'none';
    });
    
    // 현재 메뉴 토글
    langMenu.style.display = isVisible ? 'none' : 'block';
}

// 언어 변경
function changeLang(lang) {
    // 언어 코드 매핑 (저장값 → URL 경로)
    const langMap = {
        'ko': 'ko',
        'en': 'en',
        'zh-cn': 'cn'
    };
    
    // 새로운 URL 언어 코드
    const newUrlLang = langMap[lang];
    
    // 현재 URL의 언어 추출
    const pathParts = window.location.pathname.split('/').filter(p => p);
    const currentUrlLang = pathParts[0]; // 첫 번째 세그먼트
    
    // 동일한 언어면 return
    if (currentUrlLang === newUrlLang) {
        return;
    }
    
    // localStorage 저장 및 표시 업데이트
    localStorage.setItem('selectedLang', lang);
    setCookie('selectedLang', lang, 30);
    updateLanguageDisplay(lang);
    
    // 메뉴 닫기
    const langMenu = document.querySelector('.lang_menu');
    if (langMenu) langMenu.style.display = 'none';
    
    // URL 경로에서 언어 코드 치환 (첫 번째 세그먼트만)
    const pathArray = window.location.pathname.split('/').filter(p => p);
    if (pathArray.length > 0) {
        pathArray[0] = newUrlLang;
        const newUrl = '/' + pathArray.join('/');
        window.location.href = newUrl;
    }
}

// 언어 표시 업데이트 (통합)
function updateLanguageDisplay(lang) {
    const langNames = {
        'ko': '한국어',
        'en': 'English',
        'zh-cn': '中文'
    };
    const codeMap = {
        'ko': 'KO',
        'en': 'EN',
        'zh-cn': 'CN'
    };

    const langText = document.querySelector('.lang_text');
    if (langText) langText.textContent = langNames[lang] || '한국어';

    const codeEl = document.querySelector('.language_selector .mo_ver');
    if (codeEl) codeEl.textContent = codeMap[lang] || 'KO';
}

// 문의하기 라디오 상태 처리
const radioButtons = document.querySelectorAll('input[name="types_idx"]');
var requireCheck = false;

radioButtons.forEach(radio => {
    radio.addEventListener('change', function() {
        const addressField = document.querySelector('#address_field');
        const emailField = document.querySelector('#email_field');

        if (this.value === '5') {
            addressField.style.display = 'none';
            emailField.style.display = 'flex';
            requireCheck = false;
        } else {
            addressField.style.display = 'flex';
            emailField.style.display = 'none';
            requireCheck = true;
        }
    });
});


function inquire() {
	const $btn = $('.modal_footer .btn.btn-primary');
	if ($btn.prop('disabled')) return; // 중복 클릭 방지
	
	const types_idx        = $('input[name="types_idx"]:checked').val();
	const inquire_company  = $('#inquire_company').val().trim();
	const inquire_hp   	   = $('#inquire_hp').val().trim();
	const inquire_address  = $('#inquire_address').val().trim();
	const inquire_contents = $('#inquire_contents').val().trim();
	const agree            = $('#agree_policy').is(':checked');

	if (!types_idx)           return alert('문의구분을 선택해주세요.');
	if (!inquire_company)     return alert('회사명/성명을 입력해주세요.');
	if (!inquire_hp)     	  return alert('연락처를 입력해주세요.');
	if (!inquire_address)     return alert('설치주소를 입력해주세요.');
	if (!inquire_contents)    return alert('문의사항을 입력해주세요.');
	if (!agree)               return alert('개인정보 처리방침에 동의해주세요.');
	
	
	if (!confirm('문의를 등록하시겠습니까?')) return;

	  
	const fd = new FormData();
	fd.append('types_idx', types_idx);
	fd.append('inquire_company', inquire_company);
	fd.append('inquire_hp', inquire_hp);
	fd.append('inquire_address', inquire_address);
	fd.append('inquire_contents', inquire_contents);

	$btn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');

    $.ajax({
    	url: '/ko/inquire',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	alert('문의가 등록되었습니다.');

            	const $modal = $('#inquiry-modal');
            	$modal.find('input[name="types_idx"]').prop('checked', false); // 라디오 해제
            	$modal.find('#inquire_company').val('');
            	$modal.find('#inquire_hp').val('');
            	$modal.find('#inquire_address').val('');
            	$modal.find('#inquire_contents').val('');
            	$modal.find('#agree_policy').prop('checked', false); // 체크박스 해제

            	modalClose('inquiry-modal');
            	
            } else {
            	alert(res.message || '문의 등록이 실패하였습니다. 다시 시도해주세요.');
            }
        },
        error: function (xhr) {
        	alert('오류 발생','error');
            console.error(xhr.responseText);
        },
        complete: function () {
            $btn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
		}
    });
}
</script>