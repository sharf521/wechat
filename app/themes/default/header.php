<!DOCTYPE html >
<html lang="zh-cmn-Hans">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <? if( $this->control=='index' && $this->func=='index') :  ?>
        <title><?=$this->site->title;?></title>
        <meta name="Keywords" content="<?=$this->site->keywords?>" />
        <meta name="Description" content="<?=$this->site->description?>" />
    <? else : ?>
        <title><?php if($this->title!=''){echo $this->title.'-';}?><?=$this->site->name;?></title>
    <? endif;?>
    <script language="javascript" src="/plugin/js/jquery.js"></script>
    <link rel="stylesheet" href="/plugin/Swiper/css/swiper.min.css"/>
    <script src="/plugin/Swiper/js/swiper.min.js"></script>
    <link rel="stylesheet" href="/plugin/layui.v2/css/layui.css" />
    <link rel="stylesheet" href="/themes/base.css"/>
    <link href="/themes/default/default.css" rel="stylesheet" type="text/css" />
    <script src="/themes/default/default.js"></script>
</head>
<body>
<div class="top-wrapper clearFix">
    <div class="layui-main">
        <div class="user-entry">
            <? if($this->user_id=='' || $this->user_id==0) : ?>
                您好，欢迎来到 <?=$this->site->name;?>
                <span>【<a href="<?=url('user/login')?>" target="_blank">登陆</a>】</span>
                <span>【<a href="<?=url('user/register')?>" target="_blank">注册</a>】</span>
                <? else : ?>
                您好，<a href="/member"><b><?=$this->username?></a></b> 欢迎来到 <?=$this->site->name;?>
                <span>【<a href="<?=url('/member/logout')?>" target="_blank">退出</a>】</span>
            <? endif;?>
        </div>
        <div class="quick-menu">
            <dl class="">
                <dt><a href="/cart">我的购物车</a><i></i></dt>
            </dl>
            <? if($this->user_id!='' && $this->user_id!=0) : ?>
            <dl class="">
                <dt><a href="/member">个人中心</a><i></i></dt>
            </dl>
            <? endif;?>
            <dl class="">
                <dt><a href="/member/order">我的订单</a><i></i></dt>
                <dd>
                    <ul>
                        <li><a href="/member/order/status1">待付款订单</a></li>
                        <li><a href="/member/order/status4">待确认收货</a></li>
                    </ul>
                </dd>
            </dl>
        </div>
    </div>
</div>
<div class="public-head-layout layui-main clearFix">
    <h1 class="site-logo"><a href="/"><img src="<?=$this->site->logo?>" class="pngFix"></a></h1>
    <div id="search" class="head-search-bar">
        <!--商品和店铺-->
        <ul class="tab">
            <li title="请输入您要搜索的商品关键字" act="search" class="current">商品</li>
            <!--<li title="请输入您要搜索的店铺关键字" act="store_list">店铺</li>-->
        </ul>
        <form class="search-form" method="get" action="/goods/lists/">
            <input type="hidden" value="search" id="search_act" name="act">
            <input placeholder="请输入关键字" name="keyword" id="keyword" type="text" class="input-text" value="<?=$_GET['keyword']?>" maxlength="60">
            <input type="submit" id="button" value="搜索" class="input-submit">
        </form>
        <!--搜索关键字-->
        <div class="keyword">热门搜索：        <ul>
                <li><a href="/goods/lists/?keyword=珠宝手表">珠宝手表</a></li>
                <li><a href="/goods/lists/?keyword=小家电">小家电</a></li>
                <li><a href="/goods/lists/?keyword=服装">服装</a></li>
                <li><a href="/goods/lists/?keyword=家纺">家纺</a></li>
            </ul>
        </div>
    </div>
    <a href="/cart" class="my_cart layui-btn  layui-btn-primary"><i class="iconfont">&#xe698;</i> 我的购物车</a>
</div>
<div class="header-wrapper">
    <div class="layui-main">
        <!--所有分类 Start-->
        <div class="category-title">
            <h2>全部商品分类</h2>
            <div class="category-list">
                <? foreach ($this->site->cates as $_cate) : ?>
                <div class="item">
                    <h3><a href="/goods/lists/<?=$_cate['id']?>"><i class="layui-icon">&#xe60a;</i><?=$_cate['name']?></a></h3>
                    <div class="item-list">
                        <div class="subitem">
                            <? if(isset($_cate['son']) && is_array($_cate['son'])) : ?>
                                <? foreach ($_cate['son'] as $child) : ?>
                                    <dl>
                                        <dt><a href="/goods/lists/<?=$child['id']?>"><?=$child['name']?></a></dt>
                                        <dd>
                                            <? if(isset($child['son']) && is_array($child['son'])) : ?>
                                                <? foreach ($child['son'] as $sun) : ?>
                                                    <em><a href="/goods/lists/<?=$sun['id']?>"><?=$sun['name']?></a></em>
                                                    <? endforeach;?>
                                            <? endif;?>
                                        </dd>
                                    </dl>
                                    <? endforeach;?>
                            <? endif;?>
                        </div>
                    </div>
                </div>
                <? endforeach;?>
            </div>
        </div>
        <!--所有分类 End-->
        <div class="nav-list clearFix">
            <ul>
                <?
                $headerHoverIndex='';
                if($_SERVER['PHP_SELF']=='/index.php'){
                    $headerHoverIndex=1;
                }
                if(strpos($_SERVER['PHP_SELF'],'/goods/')!==false){
                    $headerHoverIndex=2;
                }
                if(strpos($_SERVER['PHP_SELF'],'/purchase/')!==false){
                    $headerHoverIndex=3;
                }
                if(strpos($_SERVER['PHP_SELF'],'/goods/lists/13')!==false){
                    $headerHoverIndex=4;
                }
                if(strpos($_SERVER['PHP_SELF'],'/goods/lists/206')!==false){
                    $headerHoverIndex=5;
                }
                ?>
                <li <? if($headerHoverIndex==1){echo 'class="hover"';}?>><a href="/">首页</a></li>
                <li <? if($headerHoverIndex==2){echo 'class="hover"';}?>><a href="/goods/lists">商品列表</a></li>
                <li <? if($headerHoverIndex==3){echo 'class="hover"';}?>><a href="/purchase/lists">我要采购</a></li>
                <li <? if($headerHoverIndex==4){echo 'class="hover"';}?>><a href="/goods/lists/13">汽车专区</a></li>
                <li <? if($headerHoverIndex==5){echo 'class="hover"';}?>><a href="/goods/lists/206">苹果专区</a></li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
    header_js();
</script>