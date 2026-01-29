
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
                <h4>Your rooftop leasing income</h4>
                <p>Enter the area <br/>and click “Calculate Income”</p>
                <div class="input_box">
                    <input type="text">
                    <b>pyeong (㎡)</b>
                </div>
                <button onclick="calculatorBtn(this)"><span>Calculate</span><i><img src="{{ asset('user_assets/img/icon/loading.svg') }}" alt=""></i></button>
            </div>
            <div class="bot">
                <h5>Estimated Total Income over 20 Years</h5>
                <b><span class="count" data-num="23000000">0</span>0,000 KRW</b>
                <p>• Power Generation Capacity : <span class="count">0</span> kW</p>
                <p>• Lease Fee : <span class="count">0</span>0,000 KRW/Year</p>
                <p>• Carbon Reduction : Trees <span class="count">0</span> Equivalent to planting trees</p>
            </div>
        </div>
    </div>
</div>
