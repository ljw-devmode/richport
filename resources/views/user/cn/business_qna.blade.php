@include('user.cn.header._head')
<body>
	@include('user.cn.header._header')
	<main id="wrap">

        <div class="qna_box">
            <div class="sub_tit">
                <span data-aos="fade-up">问答</span>
                <h3 data-aos="fade-up" data-aos-delay="150">您想了解什么？</h3>
            </div>

            <div class="tab_box" data-aos="fade-up" data-aos-delay="300">
                <a href="javascript:;" class="active">屋顶租赁式太阳能</a>
                <a href="javascript:;">RE100 租赁</a>
                <a href="javascript:;">RPS 发电项目</a>
            </div>

            <div class="tab_content" data-aos="fade-up" data-aos-delay="450">
                <!-- 지붕임대태양광 -->
                <div class="active">
                    <h4>屋顶租赁式太阳能</h4>
                    <div class="qna_con">
                        <div class="qna_list">
                            <dl>
                                <dt onclick="toggleQna(this)">投资费用真的为 0 吗？</dt>
                                <dd><div class="answer">太阳能电站安装所需的全部投资费用均由 SOLARTEQ 承担。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">租金如何计算及支付？</dt>
                                <dd><div class="answer">租金以装机容量为基准计算。首次租金从商业运营开始日至当年年底按日计算支付。之后每年 1 月预付全年租金。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">可以提前支付租金吗？</dt>
                                <dd><div class="answer">可以提前支付租金，但提前履约保证保险的费用需由出租方承担。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">合同期满后怎么办？</dt>
                                <dd><div class="answer">根据出租方意愿，可选择：(1) 续签租赁合同，(2) 无偿接收电站，(3) 无偿拆除电站。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">如果出售建筑怎么办？</dt>
                                <dd><div class="answer">只需将合同办理至新的产权人名下即可。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">需要哪些必备资料？</dt>
                                <dd><div class="answer">营业执照、法人印鉴证明、建筑图纸、建筑物现状图、法人印章。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">具体流程是怎样的？</dt>
                                <dd><div class="answer">流程分为五个阶段：咨询（收益性、安全性评估）→ 租赁合同 → 屋顶修缮/防水 → 太阳能安装 → 竣工前检查 → 商业运营。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">合同签订后多久可以开始发电？</dt>
                                <dd><div class="answer">一般施工约 3 个月，若包含审批流程约需 6 个月，具体时间会根据装机容量有所调整。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">电站安装后如何管理？</dt>
                                <dd><div class="answer">由 SOLARTEQ 内部运维（O&M）团队进行定期检查和管理。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">安装电站时会修缮屋顶吗？</dt>
                                <dd><div class="answer">在安装前会先进行屋顶修缮及防水施工，相关费用均由 SOLARTEQ 承担。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">20 年合同期限可以变更吗？</dt>
                                <dd><div class="answer">合同期可通过双方协商进行调整，但合同条件可能随之变更。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">太阳能施工后发生漏水怎么办？</dt>
                                <dd><div class="answer">如运营过程中发生漏水，SOLARTEQ 将进行再次修缮和防水施工。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">施工会影响日常工作吗？</dt>
                                <dd><div class="answer">施工将尽量避免影响正常业务运营。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">屋顶荷载增加会有危险吗？</dt>
                                <dd><div class="answer">SOLARTEQ 依据建筑结构标准对恒载、活载、雪载、风载等进行评估，仅在确认安全的建筑上施工，不存在荷载风险。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">最小需要多少面积？</dt>
                                <dd><div class="answer">屋顶租赁式太阳能项目最少需要 1,200 坪（约 400kW）。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">屋顶租金也需要缴纳所得税吗？</dt>
                                <dd><div class="answer">屋顶租赁属于租赁经营行为，会产生收入，因此需要缴纳所得税。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">如果缴纳所得税，适用的税率是多少？</dt>
                                <dd><div class="answer">屋顶租赁式太阳能收入适用综合所得税累进税率。如除租赁收入外还有其他收入，将合并计算总收入后确定税率，具体税率请参考韩国国税厅官网。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">屋顶租赁式太阳能与自营发电项目哪个更好？</dt>
                                <dd><div class="answer">屋顶租赁式太阳能更接近“租赁业务”而非“发电投资”，最大优势在于无需初期投资、可免费进行屋顶防水施工，并获得固定租金收益。由于“租赁业务”和“投资业务”目的不同，建议根据自身目标进行选择。</div></dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- RE100 리스 -->
                <div>
                    <h4>RE100 租赁</h4>
                    <div class="qna_con">
                        <div class="qna_list">
                            <dl>
                                <dt onclick="toggleQna(this)">投资费用真的为 0 吗？</dt>
                                <dd><div class="answer">太阳能电站安装所需的全部投资费用均由 SOLARTEQ 承担。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">屋顶修缮费用是多少？</dt>
                                <dd><div class="answer">在安装太阳能电站前会先进行屋顶修缮及防水施工，相关费用均由 SOLARTEQ 承担。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">电价是否 20 年固定？</dt>
                                <dd><div class="answer">可通过 20 年固定的 RE100 租赁订阅电价，使用太阳能电站所生产的电力。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">电价是如何计算的？</dt>
                                <dd><div class="answer">电价根据预计发电量及商业运营开始时的电价进行测算。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">自发自用后剩余的电力（余电）如何处理？</dt>
                                <dd><div class="answer">余电将通过 KEPCO 或 KPX 按电力市场价格出售，余电可按权重 1.0 获得 REC 证书。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">如何确认收益性？</dt>
                                <dd><div class="answer">通过商业运营后 1 年的用电数据及发电数据进行收益性分析并提供结果。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">除了节省电费还有其他效果吗？</dt>
                                <dd><div class="answer">通过在 K-RE100 系统中登记设备，可获得 RE100 认证。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">什么是租赁法人 SPC？</dt>
                                <dd><div class="answer">这是 SOLARTEQ 为提供太阳能租赁而设立的特殊目的法人，RE100 租赁通过与该 SPC 签订租赁合同进行。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">如果出售工厂怎么办？</dt>
                                <dd><div class="answer">只需将合同办理至新的产权人名下即可。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">具体流程是怎样的？</dt>
                                <dd><div class="answer">流程共分为七个阶段：现场调查（安全性、收益性、能效）→ 方案提案/协商 → RE100 租赁合同 → 屋顶修缮/防水 → 太阳能安装 → 竣工前检查 → 电力供应。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">合同签订后多久可以开始发电？</dt>
                                <dd><div class="answer">一般施工约 3 个月，若包含审批流程约需 6 个月，具体时间会根据装机容量有所调整。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">电站安装后如何管理？</dt>
                                <dd><div class="answer">由 SOLARTEQ 内部运维（O&M）团队通过定期检查对太阳能电站进行管理。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">如果屋顶看起来比较薄弱，还能安装电站吗？</dt>
                                <dd><div class="answer">在进行安全性检测后，会通过屋顶修缮（如加装加固支架等）确保结构安全，因此即使是较弱的屋顶也可以正常施工并发电。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">20 年的合同期限可以变更吗？</dt>
                                <dd><div class="answer">合同期可通过双方协商进行调整，但合同条件可能随之变更。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">租赁合同到期后怎么办？</dt>
                                <dd><div class="answer">根据出租方意愿，可选择：(1) 续签合同，(2) 无偿接收电站，(3) 无偿拆除电站。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">最少需要多少面积？</dt>
                                <dd><div class="answer">项目可在至少 1,000 坪以上的建筑屋顶上进行。</div></dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- RPS 발전사업 -->
                <div>
                    <h4>RPS 发电项目</h4>
                    <div class="qna_con">
                        <div class="qna_list">
                            <dl>
                                <dt onclick="toggleQna(this)">施工费用如何计算？</dt>
                                <dd><div class="answer">施工费用根据电站面积及发电量计算，可通过与负责人咨询及项目可行性评估后明确确定。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">从签约到施工大约需要多久？</dt>
                                <dd><div class="answer">一般施工约需 3 个月，如包含审批流程约需 6 个月，具体时间会根据装机容量有所调整。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">太阳能设备产生的电磁波安全吗？</dt>
                                <dd><div class="answer">太阳能设备产生的电磁波水平远低于家用吸尘器、电饭煲等家电，非常安全。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">安装太阳能会对周边环境造成影响吗？</dt>
                                <dd><div class="answer">太阳能设备对周边温度影响极小，且组件可通过雨水自然清洁，不会造成环境污染。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">对电站用地有条件要求吗？</dt>
                                <dd><div class="answer">电站用地需满足出入口、电网接入、通知条件等多项要求，且地方政府可能通过条例附加其他条件，需通过咨询进行确认。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">20 年后需要更换组件吗？</dt>
                                <dd><div class="answer">通过 SOLARTEQ 运维（O&M）团队的定期管理，组件最长可使用至 30 年。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">发电效率每年大约下降多少？</dt>
                                <dd><div class="answer">发电效率年衰减率约为 0.5–1%，10 年后仍可保持约 90%，20 年后约 80%，可持续发电至 30 年。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">发电项目的收益如何计算？</dt>
                                <dd><div class="answer">RPS 发电项目通过向韩国电力出售电力（SMP）以及向大型发电企业出售可再生能源证书（REC）获取收益，因此收益可按装机容量的 SMP + REC 进行计算。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">电站建成后的维护如何进行？</dt>
                                <dd><div class="answer">通过与 SOLARTEQ 签订运维（O&M）合同，可通过定期检查对太阳能电站进行管理。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">运行中发生故障怎么办？</dt>
                                <dd><div class="answer">运维（O&M）团队通过定期检查尽量减少故障发生，如遇不可避免的故障，请联系 SOLARTEQ（02-6912-4900），我们将尽快处理。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">最少需要多少面积？</dt>
                                <dd><div class="answer">RPS 发电项目至少需要 000 坪以上的土地或建筑屋顶，规模越大收益越高，建议尽可能在较大的空间内进行。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">发电项目收益涉及哪些税费？</dt>
                                <dd><div class="answer">发电收益需缴纳增值税和所得税（个人为综合所得税，法人为法人税），此外还有取得税（如取得建筑、农地时）及许可税等。</div></dd>
                            </dl>
                            <dl>
                                <dt onclick="toggleQna(this)">缴税时适用的税率是多少？</dt>
                                <dd><div class="answer">对于式太阳能，个人需缴纳综合所得税，法人需缴纳法人税。由于税率会根据总收入变化，建议通过税务师进行准确的税务规划。</div></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @include('user.cn.footer._footer')
    <script>

        $(window).on('load', function(){

            $('.tab_box a').on('click',function(){
                let liN = $(this).index();
                $(this).addClass('active').siblings().removeClass('active');
                $('.tab_content > div').eq(liN).addClass('active').siblings().removeClass('active');
            })
        });

    </script>
</body>
@include('user.cn.footer._gtm')
</html>