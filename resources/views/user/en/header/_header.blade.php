<!-- 헤더 -->
<header class="active">
    <h1 class="logo">
    	<a href="/en">
    		<img src="{{ asset('user_assets/img/logo.svg') }}" alt="">
    	</a>
    </h1>
    <div class="right_btn">
        <a href="javascript:;" onclick="calModalToggle()">
        	<img src="{{ asset('user_assets/img/icon/calculator.svg') }}" alt="">
        	<img class="mo_ver" src="{{ asset('user_assets/img/icon/calculator_mo.svg') }}" alt="">
        	<span>Profit Calculator</span>
        </a>
        <a href="javascript:;" class="type02" onclick="modalOpen('inquiry-modal')">
        	<img src="{{ asset('user_assets/img/icon/inquiry.svg') }}" alt="">
        	<span>Contact Us</span>
        </a>
        <div class="language_selector">
            <a href="javascript:;" onclick="toggleLangMenu(this)">
                <img src="{{ asset('user_assets/img/icon/language.svg') }}" alt="">
                <span class="mo_ver">EN</span>
                <span class="lang_text">English</span>
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
            <p>Company</p>
            <ul class="depth2">
                <li><a href="/en/company/about">About SOLARTEQ</a></li>
                <li><a href="/en/company/history">History</a></li>
                <li><a href="/en/company/patent">Certifications·Patents</a></li>
            </ul>
        </div>
    </div>
    <div class="menu_box">
        <div class="depth1">
            <p>Business</p>
            <ul class="depth2">
                <li class="liwidth">
                    <a href="javascript:;">Renewable Energy</a>
                    <ul class="depth3">
                        <li><a href="/en/business/index">Why SOLARTEQ</a></li>
                        <li><a href="/en/business/solar">Rooftop Solar Leasing</a></li>
                        <li><a href="/en/business/lease">RE100 Leasing</a></li>
                        <li><a href="/en/business/rps">RPS (Electricity Sales)</a></li>
                    </ul>
                </li>
                <li class="liwidth">
                    <a href="javascript:;">Smart Factory</a>
                    <ul class="depth3">
                        <li><a href="javascript:alert('Coming Soon')">AUTOMATION</a></li>
                        <li><a href="javascript:alert('Coming Soon')">MES</a></li>
                        <li><a href="javascript:alert('Coming Soon')">FEMS</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="menu_box">
        <div class="depth1">
            <p>News</p>
            <ul class="depth2">
                <li><a href="/en/portfolio">Portfolio</a></li>
                <li><a href="/en/news">Company News</a></li>
                <li><a href="/en/culture">Company Culture</a></li>
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
    @include('user.en.header._calculator')
</div>

<!-- 문의하기모달 -->
<div class="modal" id="inquiry-modal">
    <div class="modal_wrap">
        <div class="modal_header">
            <h3>Contact Us</h3>
            <p>Please write your inquiry below. Our representative will review it and contact you promptly.</p>
            <button onclick="modalClose('inquiry-modal')"><img src="{{ asset('user_assets/img/icon/x.svg') }}" alt=""></button>
        </div>
        <div class="modal_body in_scroll" data-lenis-prevent>
            <div class="inquiry_box">
                <dl>
                    <dt>Inquiry Type</dt>
                    <dd class="check_form">
                        <input type="radio" class="input-check" id="cate_1" name="types_idx" value="1"><label for="cate_1">Rooftop Solar Leasing</label>
                        <input type="radio" class="input-check" id="cate_2" name="types_idx" value="2"><label for="cate_2">RE100 Leasing</label>
                        <input type="radio" class="input-check" id="cate_3" name="types_idx" value="3"><label for="cate_3">RPS(Electricity Sales)</label>
                        <input type="radio" class="input-check" id="cate_4" name="types_idx" value="4"><label for="cate_4">Government-funded Energy Programs</label>
                        <input type="radio" class="input-check" id="cate_5" name="types_idx" value="5"><label for="cate_5">Request Company Profile</label>
                    </dd>
                </dl>
                <dl>
                    <dt>Company Name / Name<span>*</span></dt>
                    <dd><input type="text" id="inquire_company" name="inquire_company" class="form-input"></dd>
                </dl>
                <dl>
                    <dt>Contact Number<span>*</span></dt>
                    <dd><input type="text" id="inquire_hp" name="inquire_hp" class="form-input"></dd>
                </dl>
                <dl id="address_field" style="">
                    <dt>Installation Address<span>*</span></dt>
                    <dd><input type="text" id="inquire_address" name="inquire_address" class="form-input"></dd>
                </dl>
                <dl id="email_field" style="display:none;">
                    <dt>Email Address<span>*</span></dt>
                    <dd><input type="text" id="inquire_email" name="inquire_email" class="form-input"></dd>
                </dl>
                <dl>
                    <dt>Inquiry</dt>
                    <dd><textarea id="inquire_contents" name="inquire_contents" class="form-textarea"></textarea></dd>
                </dl>
                <div class="agree_area">
                    <input type="checkbox" class="input-check" id="agree_policy" ><label for="agree_policy">Agree to Privacy Policy</label>
                </div>
                <div class="agree_policy">
                	@include('user.en.header._policy_text')
                </div>
            </div>
        </div>
        <div class="modal_footer">
            <a href="tel:0269124900" class="btn btn-black pc_none">Call Now</a>
            <button class="btn btn-primary" onclick="inquire()">Contact Us</button>
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

	if (!types_idx)           return alert('Please select an inquiry type.');
	if (!inquire_company)     return alert('Please enter your name and company name.');
	if (!inquire_hp)     	  return alert('Please enter your contact number.');
	if (!inquire_address)     return alert('Please enter the installation address.');
	if (!inquire_contents)    return alert('Please enter your inquiry details.');
	if (!agree)               return alert('Please agree to the Privacy Policy.');

	
	if (!confirm('Would you like to submit this inquiry?')) return;

	  
	const fd = new FormData();
	fd.append('types_idx', types_idx);
	fd.append('inquire_company', inquire_company);
	fd.append('inquire_hp', inquire_hp);
	fd.append('inquire_address', inquire_address);
	fd.append('inquire_contents', inquire_contents);

	$btn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');

    $.ajax({
    	url: '/en/inquire',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	alert('Your inquiry has been submitted successfully.');

            	const $modal = $('#inquiry-modal');
            	$modal.find('input[name="types_idx"]').prop('checked', false); // 라디오 해제
            	$modal.find('#inquire_company').val('');
            	$modal.find('#inquire_hp').val('');
            	$modal.find('#inquire_address').val('');
            	$modal.find('#inquire_contents').val('');
            	$modal.find('#agree_policy').prop('checked', false); // 체크박스 해제

            	modalClose('inquiry-modal');
            	
            } else {
            	alert(res.message || 'Failed to submit your inquiry. Please try again.');
            }
        },
        error: function (xhr) {
        	alert('Error occurred', 'error');
            console.error(xhr.responseText);
        },
        complete: function () {
            $btn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
		}
    });
}
</script>