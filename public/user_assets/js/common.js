
$(window).on('load', function(){
    if($('#wrap').length){
        window.lenis = new Lenis({
            duration: 1.2,
            smooth: true,
            smoothTouch: false,
            direction: 'vertical',
        });

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);

        AOS.init({
            duration: 1000,
            once: true,
        });

        lenis.on('scroll', () => {
            AOS.refresh();
        });
    }
});


// 모달
const modalOpen = (item)=>{
    $(`#${item}`).addClass('show')
    if(window.lenis){
        lenis.stop(); // 바디 스크롤 멈춤
    }
}
const modalClose = (item)=>{
    $(`#${item}`).removeClass('show')
    if(window.lenis){
        lenis.start(); // 다시 활성화
    }
}

// header
const hamToggle = (item)=>{
    $(item).toggleClass('on');  
    $('#all_menu').toggleClass('active')
}

// quick_box
const quickOpen = (item) =>{
    $(item).toggleClass('open')
    $(item).siblings('.fold_menu').slideToggle();
}
const topMove = () =>{
    if($('#fullpage').length){
        $.fn.fullpage.moveTo(1)
    }else{
        // lenis 활성화된 경우
        if (window.lenis) {
            window.lenis.scrollTo(0, {
                duration: 0.8,
                easing: (t) => t * (2 - t), // ease-out curve
            });
        } else {
            // lenis 없으면 기존 방식
            $('html, body').animate({ scrollTop: 0 }, 800);
        }
    }
}

// 수익계산기
/*const calculatorBtn = (item)=>{
    $(item).addClass('active');
    $(item).parents('.right_box').removeClass('active')
    $(item).parents('.top').siblings('.bot').hide();

    setTimeout(function(){
        $(item).parents('.right_box').addClass('active')
        $(item).parents('.top').siblings('.bot').fadeIn();
        $(item).removeClass('active');

        $(item).parents('.cal_box').find('.count').each(function(){
            let num = $(this).data('num')
            animateNumber($(this),num,5000)
        });
    },1500)
}*/

const calculatorBtn = (item) => {
    const $rightBox = $(item).closest('.right_box');
    const $calBox   = $rightBox.find('.cal_box');
    const $input    = $calBox.find('.input_box input');

    // 1) 입력값 가져오기 (콤마 제거 후 숫자로 변환)
    let val = $input.val().replace(/,/g, '').trim();
    let pyung = parseFloat(val);

    if (isNaN(pyung) || pyung <= 0) {
        alert('평수를 올바르게 입력해주세요.');
        $input.focus();
        return;
    }

    // 2) 기본값: (입력평 / 3, 소숫점 2자리 반올림)
    let base = Math.round((pyung / 3) * 100) / 100; // 소수 둘째 자리 반올림

    // 3) 각 수식 계산 (최종 결과는 모두 정수로 반올림)
    // 1. 건물 수입: (입력평 / 3) * 4 * 20
    const income = Math.round(base * 4 * 20); // 만원 단위 총수입

    // 2. 발전용량: (입력평 / 3)
    const capacity = Math.round(base); // kW / 년

    // 3. 임대료: (입력평 / 3) * 4
    const rent = Math.round(base * 4); // 만원

    // 4. 탄소저감: (입력평 / 3) * 92
    const carbon = Math.round(base * 3.6 * 365 * 0.45941 / 6.6); // 그루

    // 4) .count 요소들에 target 값 세팅 (순서대로)
    const $counts = $calBox.find('.count');
    $counts.eq(0).data('num', income).attr('data-num', income);
    $counts.eq(1).data('num', capacity).attr('data-num', capacity);
    $counts.eq(2).data('num', rent).attr('data-num', rent);
    $counts.eq(3).data('num', carbon).attr('data-num', carbon);

    // 5) 기존 애니메이션 로직 그대로 사용
    $(item).addClass('active');
    $rightBox.removeClass('active');
    $(item).parents('.top').siblings('.bot').hide();

    setTimeout(function () {
        $rightBox.addClass('active');
        $(item).parents('.top').siblings('.bot').fadeIn();
        $(item).removeClass('active');

        // 6) 숫자 애니메이션 실행
        $counts.each(function () {
            let num = $(this).data('num');
            animateNumber($(this), num, 5000);
        });
    }, 1500);
};

const calModalToggle = ()=>{
    $('.cal_modal').fadeToggle();
    var calSlide = new Swiper(".cal_modal .calculator_box .img_slide", {
        effect:'fade',
        speed:1000,
        observeParents:true,
        observeSlideChildren:true,
        loop:true,
        autoplay: {
            delay: 2000,
            disableOnInteraction: false,  
        },
    });
}

const popupModalToggle = ()=>{
    alert(1);
    $('.popup_modal').fadeToggle();
}

const animateNumber = ($element, endValue, duration)=>{
    var startValue = 0;
    $({ counter: startValue }).animate({ counter: endValue }, {
        duration: duration,
        easing: 'swing', // 부드럽게
        step: function(now) {
            $element.text(Math.floor(now).toLocaleString());
        },
        complete: function() {
            $element.text(endValue.toLocaleString());
        }
    });
}


// qna
const toggleQna = (item)=>{
    $(item).parent('dl').toggleClass('active').siblings().removeClass('active')
    $(item).parents('dl').siblings().find('dd').slideUp()
    $(item).siblings('dd').slideToggle()
}

// scrolldown 
$(document).on('ready',function(){
    if($('#scroll_down')){
        setTimeout(function(){
            $('#scroll_down').addClass('active')
        },800)
    }
})
