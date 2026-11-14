<!-- 헤더 -->
<header class="active">
    <h1 class="logo">
    	<a href="/en">
    		<img src="{{ asset('user_assets/img/logo.svg') }}" alt="">
    	</a>
    </h1>

    <nav id="gnb" onmouseenter="openGnb()" onmouseleave="closeGnb()">
        <ul class="main_menu">
            <!-- 1. 회사소개 -->
            <li class="menu_item">
                <a href="javascript:;" class="depth1_title">Company</a>
                <ul class="depth2_menu">
                    <li><a href="/en/company/about">About Us</a></li>
                    <li><a href="/en/company/contact">Location</a></li>
                    <li><a href="/en/company/crew">Crew</a></li>
                    <li><a href="/en/company/history">History</a></li>
                </ul>
            </li>
            <!-- 2. RIPS -->
            <li class="menu_item">
                <a href="javascript:;" class="depth1_title">RIPS</a>
                <ul class="depth2_menu">
                    <li><a href="/en/rips/about">Key Features</a></li>
                    <li><a href="/en/rips/inventory">Safety Stock Prediction</a></li>
                    <li><a href="/en/rips/order">Order Prediction</a></li>
                    <li><a href="/en/rips/why">Why RIPS</a></li>
                    <li><a href="/en/rips/portpolio">Case Studies</a></li>
                    <!-- <li><a href="/en/rips/price">Pricing</a></li> -->
                    <li><a href="/en/rips/check">FAQ</a></li>
                </ul>
            </li>
            <!-- 3. 파트너 -->
            <li class="menu_item">
                <a href="javascript:;" class="depth1_title">Partners</a>
                <ul class="depth2_menu">
                    <li><a href="/en/partner/vietnam">Vietnam</a></li>
                    <li><a href="/en/partner/korea">Korea</a></li>
                </ul>
            </li>
            <!-- 4. 상품 -->
            <li class="menu_item">
                <a href="javascript:;" class="depth1_title">Products</a>
                <ul class="depth2_menu">
                    <li><a href="/en/product/congbo">Product Registration</a></li>
                    <li><a href="/en/product/new">New Products</a></li>
                </ul>
            </li>
            <!-- 5. 테스트 베드 -->
            <li class="menu_item">
                <a href="javascript:;" class="depth1_title">Testbed</a>
                <ul class="depth2_menu">
                    <li><a href="/en/testbed/about">Service Overview</a></li>
                </ul>
            </li>
        </ul>
        
        <!-- 하위 메뉴들이 내려올 때 깔리는 전체 배경 레이어 -->
        <div class="gnb_bg"></div>
    </nav>
    
    <div class="right_btn">
        <a href="javascript:;" class="type02" onclick="modalOpen('rips_inquiry-modal')">
        	<img src="{{ asset('user_assets/img/icon/rips.svg') }}" alt="">
        	<span>Apply for RIPS</span>
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
                <li><a href="javascript:;" onclick="changeLang('vn')">Tiếng Việt</a></li>
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
                <li><a href="/en/company/about">About Us</a></li>
                <li><a href="/en/company/contact">Location</a></li>
                <li><a href="/en/company/crew">Crew</a></li>
                <li><a href="/en/company/history">History</a></li>
            </ul>
        </div>
    </div>
    <div class="menu_box">
        <div class="depth1">
            <p>RIPS</p>
            <ul class="depth2">
                <li><a href="/en/rips/about">Key Features</a></li>
                <li><a href="/en/rips/inventory">Safety Stock Prediction</a></li>
                <li><a href="/en/rips/order">Order Prediction</a></li>
                <li><a href="/en/rips/why">Why RIPS</a></li> <br/>
                <li><a href="/en/rips/portpolio">Case Studies</a></li>
                <li><a href="/en/rips/price">Pricing</a></li>
                <li><a href="/en/rips/check">FAQ</a></li>
            </ul>
        </div>
    </div>
    <div class="menu_box">
        <div class="depth1">
            <p>Partners</p>
            <ul class="depth2">
                <li><a href="/en/partner/vietnam">Vietnam</a></li>
                <li><a href="/en/partner/korea">Korea</a></li>
            </ul>
        </div>
    </div>
    <div class="menu_box">
        <div class="depth1">
            <p>Products</p>
            <ul class="depth2">
                <li><a href="/en/product/congbo">Product Registration</a></li>
                <li><a href="/en/product/new">New Products</a></li>
            </ul>
        </div>
    </div>
    <div class="menu_box">
        <div class="depth1">
            <p>Testbed</p>
            <ul class="depth2">
                <li><a href="/en/testbed/about">Service Overview</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- 문의하기모달 -->
<div class="modal" id="inquiry-modal">
    <div class="modal_wrap">
        <div class="modal_header">
            <h3>Contact Us</h3>
            <p>Please fill out your request below and our team will review it and get back to you shortly.</p>
            <button onclick="modalClose('inquiry-modal')"><img src="{{ asset('user_assets/img/icon/x.svg') }}" alt=""></button>
        </div>
        <div class="modal_body in_scroll" data-lenis-prevent>
            <div class="inquiry_box">
                <dl>
                    <dt>Inquiry Type</dt>
                    <dd class="check_form">
                        <input type="radio" class="input-check" id="cate_1" name="types_idx" value="1"><label for="cate_1">RIPS</label>
                        <input type="radio" class="input-check" id="cate_2" name="types_idx" value="2"><label for="cate_2">Partners</label>
                        <input type="radio" class="input-check" id="cate_3" name="types_idx" value="3"><label for="cate_3">Products</label>
                        <input type="radio" class="input-check" id="cate_4" name="types_idx" value="4"><label for="cate_4">Testbed</label>
                        <input type="radio" class="input-check" id="cate_5" name="types_idx" value="5"><label for="cate_5">Other</label>
                    </dd>
                </dl>
                <dl>
                    <dt>Company Name<span>*</span></dt>
                    <dd><input type="text" id="inquire_company" name="inquire_company" class="form-input"></dd>
                </dl>
                <dl id="address_field" style="">
                    <dt>Address<span>*</span></dt>
                    <dd><input type="text" id="inquire_address" name="inquire_address" class="form-input"></dd>
                </dl>
                <dl>
                    <dt>Phone Number<span>*</span></dt>
                    <dd><input type="text" id="inquire_hp" name="inquire_hp" class="form-input"></dd>
                </dl>
                <dl id="email_field" style="display:none;">
                    <dt>Email Address<span>*</span></dt>
                    <dd><input type="text" id="inquire_email" name="inquire_email" class="form-input"></dd>
                </dl>
                <dl>
                    <dt>Inquiry Details</dt>
                    <dd><textarea id="inquire_contents" name="inquire_contents" class="form-textarea"></textarea></dd>
                </dl>
                <div class="agree_area">
                    <input type="checkbox" class="input-check" id="agree_policy" ><label for="agree_policy">I agree to the Privacy Policy</label>
                </div>
                <div class="agree_policy">
                	@include('user.en.header._policy_text')
                </div>
            </div>
        </div>
        <div class="modal_footer">
            <!-- <a href="tel:0269124900" class="btn btn-black pc_none">전화걸기</a> -->
            <button class="btn btn-primary" onclick="inquire()">Submit Inquiry</button>
        </div>
    </div>
</div>


<!-- RIPS 문의하기모달 -->
<div class="modal" id="rips_inquiry-modal">
    <div class="modal_wrap">
        <div class="modal_header">
            <h3>Apply for RIPS</h3>
            <p>To start using RIPS, please fill out the form below and our team will review it and get back to you shortly.</p>
            <button onclick="modalClose('rips_inquiry-modal')"><img src="{{ asset('user_assets/img/icon/x.svg') }}" alt=""></button>
        </div>
        <div class="modal_body in_scroll" data-lenis-prevent>
            <div class="inquiry_box">
                <dl>
                    <dt>Company Type</dt>
                    <dd class="check_form">
                        <input type="radio" class="input-check" id="rips_cate_1" name="rips_types_idx" value="1"><label for="rips_cate_1">Korean Manufacturer</label>
                        <input type="radio" class="input-check" id="rips_cate_2" name="rips_types_idx" value="2"><label for="rips_cate_2">Korean Exporter</label>
                        <input type="radio" class="input-check" id="rips_cate_3" name="rips_types_idx" value="3"><label for="rips_cate_3">Vietnamese Distributor</label>
                        <input type="radio" class="input-check" id="rips_cate_4" name="rips_types_idx" value="4"><label for="rips_cate_4">Vietnamese Importer</label>
                        <input type="radio" class="input-check" id="rips_cate_5" name="rips_types_idx" value="5"><label for="rips_cate_5">Other</label>
                    </dd>
                </dl>
                <dl>
                    <dt>Company Name<span>*</span></dt>
                    <dd><input type="text" id="rips_inquire_company" name="rips_inquire_company" class="form-input"></dd>
                </dl>
                <dl id="address_field" style="">
                    <dt>Contact Person<span>*</span></dt>
                    <dd><input type="text" id="rips_inquire_address" name="rips_inquire_address" class="form-input"></dd>
                </dl>
                <dl>
                    <dt>Phone Number<span>*</span></dt>
                    <dd><input type="text" id="rips_inquire_hp" name="rips_inquire_hp" class="form-input"></dd>
                </dl>
                <dl id="email_field" style="display:none;">
                    <dt>Email Address<span>*</span></dt>
                    <dd><input type="text" id="rips_inquire_email" name="rips_inquire_email" class="form-input"></dd>
                </dl>
                <dl>
                    <dt>Inquiry Details</dt>
                    <dd><textarea id="rips_inquire_contents" name="rips_inquire_contents" class="form-textarea"></textarea></dd>
                </dl>
                <div class="agree_area">
                    <input type="checkbox" class="input-check" id="rips_agree_policy" ><label for="rips_agree_policy">I agree to the Privacy Policy</label>
                </div>
                <div class="agree_policy">
                	@include('user.en.header._policy_text')
                </div>
            </div>
        </div>
        <div class="modal_footer">
            <!-- <a href="tel:0269124900" class="btn btn-black pc_none">전화걸기</a> -->
            <button class="btn btn-primary rips" onclick="rips_inquire()">Submit Application</button>
        </div>
    </div>
</div>


<!-- TESTBEB 문의하기모달 -->
<div class="modal" id="testbed_inquiry-modal">
    <div class="modal_wrap">
        <div class="modal_header">
            <h3>Apply for Testbed</h3>
            <p>To use the Testbed service, please fill out the form below and our team will review it and get back to you shortly.</p>
            <button onclick="modalClose('testbed_inquiry-modal')"><img src="{{ asset('user_assets/img/icon/x.svg') }}" alt=""></button>
        </div>
        <div class="modal_body in_scroll" data-lenis-prevent>
            <div class="inquiry_box">    
                <input type="hidden" id="testbed_types_idx" name="testbed_types_idx"/>
                <dl>
                    <dt>Product Name<span></span></dt>
                    <dd><input type="text" id="testbed_product_name" name="testbed_product_name" class="form-input" readonly></dd>
                </dl>                
                <dl>
                    <dt>Company Name<span>*</span></dt>
                    <dd><input type="text" id="testbed_inquire_company" name="testbed_inquire_company" class="form-input"></dd>
                </dl>
                <dl id="address_field" style="">
                    <dt>Address<span>*</span></dt>
                    <dd><input type="text" id="testbed_inquire_address" name="testbed_inquire_address" class="form-input"></dd>
                </dl>
                <dl>
                    <dt>Phone Number<span>*</span></dt>
                    <dd><input type="text" id="testbed_inquire_hp" name="testbed_inquire_hp" class="form-input"></dd>
                </dl>
                <dl id="email_field" style="display:none;">
                    <dt>Email Address<span>*</span></dt>
                    <dd><input type="text" id="testbed_inquire_email" name="testbed_inquire_email" class="form-input"></dd>
                </dl>
                <dl>
                    <dt>Request</dt>
                    <dd><textarea id="testbed_inquire_contents" name="testbed_inquire_contents" class="form-textarea"></textarea></dd>
                </dl>
                <div class="agree_area">
                    <input type="checkbox" class="input-check" id="testbed_agree_policy" ><label for="testbed_agree_policy">I agree to the Privacy Policy</label>
                </div>
                <div class="agree_policy">
                	@include('user.en.header._policy_text')
                </div>
            </div>
        </div>
        <div class="modal_footer">
            <!-- <a href="tel:0269124900" class="btn btn-black pc_none">전화걸기</a> -->
            <button class="btn btn-primary" onclick="testbed_inquire()">Apply for Testbed</button>
        </div>
    </div>
</div>


<script>
function openGnb() {
    document.getElementById('gnb').classList.add('open');
}

function closeGnb() {
    document.getElementById('gnb').classList.remove('open');
}


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
    const validLangs = ['ko', 'en', 'vn'];
    
    // URL 코드 → 저장값 매핑
    const urlToStorageMap = {
        'ko': 'ko',
        'en': 'en',
        'vn': 'vn'
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
        'vn': 'vn'
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
        'vn': 'Tiếng Việt'
    };
    const codeMap = {
        'ko': 'KO',
        'en': 'EN',
        'vn': 'VN'
    };

    const langText = document.querySelector('.lang_text');
    if (langText) langText.textContent = langNames[lang] || '한국어';

    const codeEl = document.querySelector('.language_selector .mo_ver');
    if (codeEl) codeEl.textContent = codeMap[lang] || 'KO';
}

// 문의하기
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
	if (!inquire_company)     return alert('Please enter your company name or full name.');
	if (!inquire_hp)     	  return alert('Please enter your phone number.');
	if (!inquire_address)     return alert('Please enter your address.');
	if (!inquire_contents)    return alert('Please enter your inquiry details.');
	if (!agree)               return alert('Please agree to the Privacy Policy.');
	
	
	if (!confirm('Would you like to submit your inquiry?')) return;

	  
	const fd = new FormData();
	fd.append('types_idx', types_idx);
	fd.append('inquire_company', inquire_company);
	fd.append('inquire_hp', inquire_hp);
	fd.append('inquire_address', inquire_address);
	fd.append('inquire_contents', inquire_contents);

	$btn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');

    $.ajax({
    	url: '/en/setInquireRegist',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	alert('Your inquiry has been submitted.');

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
        	alert('An error occurred.','error');
            console.error(xhr.responseText);
        },
        complete: function () {
            $btn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
		}
    });
}

// RIPS 문의하기
function rips_inquire() {

	const $btn = $('.modal_footer .btn.btn-primary.rips');
	if ($btn.prop('disabled')) return; // 중복 클릭 방지
	
	const rips_types_idx        = $('input[name="rips_types_idx"]:checked').val();
	const rips_inquire_company  = $('#rips_inquire_company').val().trim();
	const rips_inquire_hp   	= $('#rips_inquire_hp').val().trim();
	const rips_inquire_address  = $('#rips_inquire_address').val().trim();
	const rips_inquire_contents = $('#rips_inquire_contents').val().trim();
	const rips_agree            = $('#rips_agree_policy').is(':checked');

	if (!rips_types_idx)           return alert('Please select an inquiry type.');
	if (!rips_inquire_company)     return alert('Please enter your company name or full name.');
	if (!rips_inquire_hp)     	  return alert('Please enter your phone number.');
	if (!rips_inquire_address)     return alert('Please enter the contact person\'s name.');
	if (!rips_inquire_contents)    return alert('Please enter your inquiry details.');
	if (!rips_agree)               return alert('Please agree to the Privacy Policy.');
	
	if (!confirm('Would you like to submit your inquiry?')) return;

	  
	const fd = new FormData();
	fd.append('types_idx', rips_types_idx);
	fd.append('inquire_company', rips_inquire_company);
	fd.append('inquire_hp', rips_inquire_hp);
	fd.append('inquire_address', rips_inquire_address);
	fd.append('inquire_contents', rips_inquire_contents);

	$btn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');

    $.ajax({
    	url: '/en/setRipsInquireRegist',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	alert('Your RIPS application has been submitted.');

            	const $modal = $('#rips_inquiry-modal');
            	$modal.find('input[name="rips_types_idx"]').prop('checked', false); // 라디오 해제
            	$modal.find('#rips_inquire_company').val('');
            	$modal.find('#rips_inquire_hp').val('');
            	$modal.find('#rips_inquire_address').val('');
            	$modal.find('#rips_inquire_contents').val('');
            	$modal.find('#rips_agree_policy').prop('checked', false); // 체크박스 해제

            	modalClose('rips_inquiry-modal');
            	
            } else {
            	alert(res.message || 'Failed to submit your inquiry. Please try again.');
            }
        },
        error: function (xhr) {
        	alert('An error occurred.','error');
            console.error(xhr.responseText);
        },
        complete: function () {
            $btn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
		}
    });
}


// TESTBED 신청하기
function testbed_inquire() {

	const $btn = $('.modal_footer .btn.btn-primary.testbed');
	if ($btn.prop('disabled')) return; // 중복 클릭 방지
	
	const testbed_types_idx        = $('#testbed_types_idx').val();
	const testbed_inquire_company  = $('#testbed_inquire_company').val().trim();
	const testbed_inquire_hp   	= $('#testbed_inquire_hp').val().trim();
	const testbed_inquire_address  = $('#testbed_inquire_address').val().trim();
	const testbed_inquire_contents = $('#testbed_inquire_contents').val().trim();
	const testbed_agree            = $('#testbed_agree_policy').is(':checked');

	if (!testbed_inquire_company)     return alert('Please enter your company name or full name.');
	if (!testbed_inquire_hp)     	  return alert('Please enter your phone number.');
	if (!testbed_inquire_address)     return alert('Please enter your address.');
	if (!testbed_inquire_contents)    return alert('Please enter your inquiry details.');
	if (!testbed_agree)               return alert('Please agree to the Privacy Policy.');
	
	if (!confirm('Would you like to submit your Testbed request?')) return;

	  
	const fd = new FormData();
	fd.append('types_idx', testbed_types_idx);
	fd.append('inquire_company', testbed_inquire_company);
	fd.append('inquire_hp', testbed_inquire_hp);
	fd.append('inquire_address', testbed_inquire_address);
	fd.append('inquire_contents', testbed_inquire_contents);

	$btn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');

    $.ajax({
    	url: '/en/setTestbedInquireRegist',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.status === 'success') {
            	alert('Your Testbed request has been submitted.');

            	const $modal = $('#testbed_inquiry-modal');
            	$modal.find('input[name="testbed_types_idx"]').prop('checked', false); // 라디오 해제
            	$modal.find('#testbed_inquire_company').val('');
            	$modal.find('#testbed_inquire_hp').val('');
            	$modal.find('#testbed_inquire_address').val('');
            	$modal.find('#testbed_inquire_contents').val('');
            	$modal.find('#testbed_agree_policy').prop('checked', false); // 체크박스 해제

            	modalClose('testbed_inquiry-modal');
            	$('#product_modal_body').html("");
            } else {
            	alert(res.message || 'Failed to submit your Testbed request. Please try again.');
            }
        },
        error: function (xhr) {
        	alert('An error occurred.','error');
            console.error(xhr.responseText);
        },
        complete: function () {
            $btn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
		}
    });
}
</script>
