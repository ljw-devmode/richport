
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
                <h4>您的屋顶租赁收益</h4>
                <p>输入面积并点击“计算收益”</p>
                <div class="input_box">
                    <input type="text">
                    <b>平方米</b>
                </div>
                <button onclick="calculatorBtn(this)"><span>计算</span><i><img src="{{ asset('user_assets/img/icon/loading.svg') }}" alt=""></i></button>
            </div>
            <div class="bot">
                <h5>20 年预计总收益</h5>
                <b><span class="count" data-num="23000000">0</span>万韩元</b>
                <p>• 发电容量 : <span class="count">0</span>kW</p>
                <p>• 租赁费用 : <span class="count">0</span>万韩元/年</p>
                <p>• 减碳量 : 树木<span class="count">0</span>相当于种植树木的效果</p>
            </div>
        </div>
    </div>
</div>
