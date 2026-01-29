
<div class="calculator_box">
    <div class="img_box">
        <div class="img_slide">
            <ul class="swiper-wrapper">
                <li class="swiper-slide">
                    <img class="pc_img" src="{{ asset('user_assets/img/main/1.청주 한청판지_수익계산기_PC.png') }}" alt="">
                    <img class="fold_img" src="{{ asset('user_assets/img/main/1.청주 한청판지_수익계산기_F.png') }}" alt="">
                    <img class="mo_img" src="{{ asset('user_assets/img/main/1.청주 한청판지_수익계산기_M.png') }}" alt="">
                </li>
                <li class="swiper-slide">
                    <img class="pc_img" src="{{ asset('user_assets/img/main/2. 영주 재경_수익계산기_PC.png') }}" alt="">
                    <img class="fold_img" src="{{ asset('user_assets/img/main/2. 영주 재경_수익계산기_F.png') }}" alt="">
                    <img class="mo_img" src="{{ asset('user_assets/img/main/2. 영주 재경_수익계산기_M.png') }}" alt="">
                </li>
                <li class="swiper-slide">
                    <img class="pc_img" src="{{ asset('user_assets/img/main/3. 평택 매일유업_수익계산기_PC.png') }}" alt="">
                    <img class="fold_img" src="{{ asset('user_assets/img/main/3. 평택 매일유업_수익계산기_F.png') }}" alt="">
                    <img class="mo_img" src="{{ asset('user_assets/img/main/3. 평택 매일유업_수익계산기_F.png') }}" alt="">
                </li>
            </ul>
        </div>
    </div>
    <div class="right_box type02">
        <div class="cal_box">
            <div class="top">
                <h4>건물 지붕 임대 수익은</h4>
                <p>평수를 입력하고 <br/>'수익 계산하기' 를 눌러보세요</p>
                <div class="input_box">
                    <input type="text">
                    <b>평</b>
                </div>
                <button onclick="calculatorBtn(this)"><span>계산하기</span><i><img src="{{ asset('user_assets/img/icon/loading.svg') }}" alt=""></i></button>
            </div>
            <div class="bot">
                <h5>20년 총 예상 수익은</h5>
                <b><span class="count" data-num="23000000">0</span>만원</b>
                <p>• 발전용량 : <span class="count">0</span> kW</p>
                <p>• 임대료 : <span class="count">0</span>만원/년</p>
                <p>• 탄소저감 : 연간 나무 <span class="count">0</span> 그루를 심은 효과</p>
            </div>
        </div>
    </div>
</div>
