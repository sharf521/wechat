<?php require 'header.php';?>

    <div class="swiper-container car_mes">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><a href="#"><img src="http://img.yuantuwang.com//uploads/wapCar/201701/14841090309547.jpg"></a></div>
            <div class="swiper-slide"><a href="#"><img src="http://img.yuantuwang.com//uploads/wapCar/201701/14841086807535.jpg"></a></div>
            <div class="swiper-slide"><a href="#"><img src="http://img.yuantuwang.com//uploads/wapCar/201701/14841090436174.jpg"></a></div>

        </div>
        <div class="swiper-pagination"></div>
    </div>
    <!--ad end-->
    <div class="m_regtilinde">推荐品牌<span><a href="/wap/home/buy/brandList">查看更多</a></span></div>
    <div class="br_box clearFix">
        <ul class="clearFix">

            <li><a href="/wap/home/buy/searchBrand?name=大众"><div><img src="http://img.yuantuwang.com//uploads/wapCar/201701/14841042467238.jpg" /></div><span>大众</span></a></li>
            <li><a href="/wap/home/buy/searchBrand?name=起亚"><div><img src="http://img.yuantuwang.com//uploads/wapCar/201701/14841044286525.jpg" /></div><span>起亚</span></a></li>
            <li><a href="/wap/home/buy/searchBrand?name=宝马"><div><img src="http://img.yuantuwang.com//uploads/wapCar/201701/14841044418476.jpg" /></div><span>宝马</span></a></li>
            <li><a href="/wap/home/buy/searchBrand?name=本田"><div><img src="http://img.yuantuwang.com//uploads/wapCar/201701/14841044552374.jpg" /></div><span>本田</span></a></li>
            <li><a href="/wap/home/buy/searchBrand?name=标致"><div><img src="http://img.yuantuwang.com//uploads/wapCar/201701/14841044871437.jpg" /></div><span>标致</span></a></li>
            <li><a href="/wap/home/buy/searchBrand?name=日产"><div><img src="http://img.yuantuwang.com//uploads/wapCar/201701/14841052003146.jpg" /></div><span>日产</span></a></li>

        </ul>
    </div>

    <div class="m_regtilinde" style="margin-top: -1px">推荐汽车<span><a href="/wap/home/buy/carList">查看更多</a></span></div>
    <div class="clearFix">
        <ul class="commoditylist_content">
            <li>
                <a href="/goods/detail/?id=12">
                <span class="imgspan">
                    <img src="/data/upload/1/2/goods/201701/14848997437683.png">
                </span>
                    <div class="info">
                        <p class="cd_title">正宗焦作铁棍山药盒装70cm、80cm 80cm 正宗焦作铁棍山药盒装</p>
                        <p class="cd_money">
                            <span>￥</span>
                            <var>112.00</var>
                        </p>
                        <p class="cd_sales">库存：3051</p>
                    </div>
                    <i class="iconfont"></i>
                </a>
            </li>
            <li>
                <a href="/goods/detail/?id=11">
                <span class="imgspan">
                    <img src="/data/upload/1/2/goods/201701/14849016676929.png">
                </span>
                    <div class="info">
                        <p class="cd_title">12</p>
                        <p class="cd_money">
                            <span>￥</span>
                            <var>12.00</var>
                        </p>
                        <p class="cd_sales">库存：12</p>
                    </div>
                    <i class="iconfont"></i>
                </a>
            </li>
            <li>
                <a href="/goods/detail/?id=9">
                <span class="imgspan">
                    <img src="/data/upload/1/406/goods/201612/14814491001594.jpg">
                </span>
                    <div class="info">
                        <p class="cd_title">测试</p>
                        <p class="cd_money">
                            <span>￥</span>
                            <var>10.00</var>
                        </p>
                        <p class="cd_sales">库存：11</p>
                    </div>
                    <i class="iconfont"></i>
                </a>
            </li>
            <li>
                <a href="/goods/detail/?id=2">
                <span class="imgspan">
                    <img src="/data/upload/1/2/goods/201701/14849020572643.png">
                </span>
                    <div class="info">
                        <p class="cd_title">asdf</p>
                        <p class="cd_money">
                            <span>￥</span>
                            <var>234.00</var>
                        </p>
                        <p class="cd_sales">库存：10</p>
                    </div>
                    <i class="iconfont"></i>
                </a>
            </li>
        </ul>
    </div>
    <script>
        //banner Swiper
        $(function () {
            var mySwiper = new Swiper('.car_mes', {
                loop: true,
                autoplay: 4800,
                autoplayDisableOnInteraction: false,
                pagination: '.swiper-pagination',
                paginationClickable: true,
            });
        });

    </script>
<?php require 'footer.php';?>