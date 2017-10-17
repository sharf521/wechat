<style>
    body {background-color: #efefef;}

    /* 公用顶部区域
    -------------------------------------- */
    .top-wrapper { font-family: Arial, "宋体"; height: 30px; color: #777; background-color: #FAFAFA; border-bottom: solid 1px #F0F0F0;font-size: 12px;}
    .top-wrapper .user-entry { width: 45%; float: left; color: #777; line-height: 30px;}
    .top-wrapper .user-entry a { color: #777}
    .top-wrapper .quick-menu { font-size: 0; *word-spacing:-1px/*IE6、7*/; text-align: right; width: 55%; height: 30px; float: right;}
    .top-wrapper .quick-menu a { color: #777 !important;}
    .top-wrapper .quick-menu dl { text-align: left; vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; *display:inline/*IE6、7*/; width: 80px; height: 30px; position: relative; z-index: 999; *zoom:1/*IE6、7*/;}
    .top-wrapper .quick-menu dl dt { font-size: 12px; line-height: 20px; height: 20px; padding: 5px 0; position: absolute; z-index: 2; top: 0px; left: 12px;}
    .top-wrapper .quick-menu dl dt a:hover { text-decoration: none; color: #F30;}
    .top-wrapper .quick-menu dl dt i { }
    .top-wrapper .quick-menu dl.hover dt i { }
    .top-wrapper .quick-menu dl dd { background-color: #FFF; display: none; width: 78px; border: solid 1px #F0F0F0; position: absolute; z-index: 1; top: 0; left: 0;}
    .top-wrapper .quick-menu dl.hover dd { display: block;}
    .top-wrapper .quick-menu dl dd ul { width: 78px; margin: 30px 0 0 0; }
    .top-wrapper .quick-menu dl dd ul li a { font-size: 12px; line-height: 24px; display: block; clear: both; padding: 2px 0 2px 11px;}
    .top-wrapper .quick-menu dl dd ul li a:hover { text-decoration: none; background-color: #F7F7F7;}

    .top_header{border-bottom: 2px solid #c00; background-color: #fff;}

    /* 站点logo */
    .public-head-layout{padding-bottom: 5px;}
    .public-head-layout .site-logo {  height: 80px; float: left; margin: 10px 50px auto 0;}
    .public-head-layout .site-logo img { max-height: 80px;}
    /* 头部搜索 */
    .head-search-bar {position: relative;}
    .head-search-bar { width: 440px; float: left; padding-top:28px; overflow: hidden;}
    #search ul.tab { width: 200px; height: 23px; display:block; position: absolute; z-index: 99; top: 8px; left: 0px; overflow: hidden;}
    #search ul.tab li { font-weight: bold; line-height: 20px; color: #555; white-space: nowrap; float: left; height: 20px; float: left; padding: 0 14px 3px 14px; margin-right: 8px; cursor: pointer;}
    #search ul.tab li.current:hover {background: #D93600;}
    #search ul.tab li.current { line-height: 22px; color: #FFF; background: #D93600; height: 20px; float: left; padding: 0 14px 3px 14px; margin: 0;}
    .head-search-bar .search-form { background-color: #D93600; height: 36px; padding: 3px;}
    .head-search-bar .input-text { line-height: 24px; color: #555; width: 82%; height: 24px; float: left; padding: 6px 1%; border: none 0;}
    .head-search-bar .input-submit{ font-size: 14px; color: #FFF; font-weight: 600; background-color: transparent; width: 15%; height: 35px; float: right; border: none; cursor: pointer;}
    .head-search-bar .keyword { line-height: 20px; color: #999; white-space: nowrap; width: 500px; height: 20px; margin-top: 4px; overflow: hidden;}
    .head-search-bar .keyword ul { font-size: 0; *word-spacing:-1px/*IE6、7*/; vertical-align: top; display: inline-block; *display:inline/*IE6、7*/;}
    .head-search-bar .keyword ul li { font-size: 12px; vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; *display:inline/*IE6、7*/; margin-right: 12px;}
    .head-search-bar .keyword ul li a { color: #777;}

    .public-head-layout .userCenter {margin: 30px 0px 0px 150px;}
    .public-head-layout .userCenter:hover{border: 1px solid #ccc;}

    .header-wrapper{    background: #f50;    position: relative;    z-index: 10;    width: 100%;}
    .header-wrapper  .nav-list{}
    .header-wrapper  .nav-list li{float:left;line-height: 45px;}
    .header-wrapper .nav-list li a{display:block;color:#fff;padding: 0 30px; font-family:"宋体"   ;  text-decoration: none;    font-size: 16px;    white-space: nowrap}
    .header-wrapper .nav-list li.hover a,.header-wrapper .nav-list li.hover2 a{color:#fff; background-color: #f30;}

    /*warpcon*/
    .warpcon{ width:1140px; margin:0 auto; clear:both; overflow:hidden; min-height: 700px;}
    .warpleft{ width:150px; overflow: hidden; height:auto; display:inline-block; float:left; background:#fff; padding: 0px 0px 20px 20px;}
    .warpleft h3{font-size:14px;font-weight:bold;color:#666;margin: 25px 0 10px;cursor: default;}
    .warpleft h3 img { width: 20px;height:20px;display: inline-block;margin-right: 5px;}
    .warpleft ul li a {display: block;color: #666;text-decoration: none;line-height: 34px;margin-left: 25px; }
    .warpleft ul li a:hover { color: #c00;}
    .warpleft ul li a.whover{color: #c00}
    /*右侧内容*/
    .warpright{ float:right; width: 940px; background-color: #fff;padding:0px 10px 20px 10px;}

    .footbot{line-height: 30px; background-color: #fff; padding: 10px 0px; text-align: center; margin-top: 10px;}

    .box{ background-color: #fff; margin-bottom: 10px;}
    .box > h3 {    font-size: 15px;    color: #c00;    padding-left: 20px;    line-height: 50px;}
</style>
<div class="warpleft">
    <h3><img src="/themes/member/images/user.png" alt="">会员中心</h3>
    <ul>
        <li><a href="<?=url('/member/address')?>"  <? if($this->control=='address'){echo 'class="whover"';}?>>地址管理</a></li>
        <li><a href="<?=url('/member/invite')?>"  <? if($this->control=='invite'){echo 'class="whover"';}?>>邀请链接</a></li>
        <li><a href="<?=url('/member/order')?>"  <? if(strpos($_SERVER['PHP_SELF'],'/member/order')!==false){echo 'class="whover"';}?>>我的订单</a></li>
        <li><a href="<?=url('/member/preSaleOrder')?>"  <? if(strpos($_SERVER['PHP_SELF'],'/member/preSaleOrder')!==false){echo 'class="whover"';}?>>我的预订</a></li>
        <li><a href="<?=url('/member/notice')?>"  <? if($this->control=='notice'){echo 'class="whover"';}?>>我的消息</a></li>
    </ul>
    <? if($this->user->is_shop==0) : ?>
        <a class="layui-btn" href="<?=url('/member/shop')?>">申请开店</a>
    <? else: ?>
        <h3><img src="/themes/member/images/shop.png" alt="">我是卖家</h3>
        <ul>
            <li><a href="<?=url('/sellManage/shop')?>"  <? if($this->control=='shop'){echo 'class="whover"';}?>>店铺设置</a></li>
            <li><a href="<?=url('/sellManage/advert')?>"  <? if($this->control=='advert'){echo 'class="whover"';}?>>广告位设置</a></li>
            <li><a href="<?=url('/sellManage/category')?>"  <? if($this->control=='category'){echo 'class="whover"';}?>>分类管理</a></li>
            <li><a href="<?=url('/sellManage/shipping')?>"  <? if($this->control=='shipping'){echo 'class="whover"';}?>>配送方式管理</a></li>
            <li><a href="<?=url('/sellManage/goods')?>"  <? if(strpos($_SERVER['PHP_SELF'],'/sellManage/goods')!==false){echo 'class="whover"';}?>>商品管理</a></li>
            <li><a href="<?=url('/sellManage/order')?>"  <? if(strpos($_SERVER['PHP_SELF'],'/sellManage/order')!==false){echo 'class="whover"';}?>>订单管理</a></li>
            <li><a href="<?=url('/sellManage/preSaleOrder')?>"  <? if(strpos($_SERVER['PHP_SELF'],'/sellManage/preSaleOrder')!==false){echo 'class="whover"';}?>>预订管理</a></li>
            <li><a href="<?=url('/sellManage/commitment')?>"  <? if(strpos($_SERVER['PHP_SELF'],'/sellManage/commitment')!==false){echo 'class="whover"';}?>>奖励承诺</a></li>
            <li><a href="<?=url('/purchase')?>">我要采购</a></li>
        </ul>
    <? endif;?>

    <? if($this->user->is_shop==1) : ?>
        <? if($this->user->is_supply==0) : ?>
            <a class="layui-btn" href="<?=url('/sellManage/applySupply')?>">申请成为供应商</a>
        <? else: ?>
            <h3><img src="/themes/member/images/gongys.png" alt="">我是供应商</h3>
            <ul>
                <li><a href="<?=url('/supplyManage/goods')?>"  <? if(strpos($_SERVER['PHP_SELF'],'/supplyManage/goods')!==false){echo 'class="whover"';}?>>商品管理</a></li>
                <li><a href="<?=url('/supplyManage/order')?>"  <? if(strpos($_SERVER['PHP_SELF'],'/supplyManage/order')!==false){echo 'class="whover"';}?>>订单管理</a></li>
                <li><a href="<?=url('/supplyManage/commitment')?>"  <? if(strpos($_SERVER['PHP_SELF'],'/supplyManage/commitment')!==false){echo 'class="whover"';}?>>奖励承诺</a></li>
            </ul>
        <? endif;?>
    <? endif;?>
</div>