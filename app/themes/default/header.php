<!DOCTYPE html >
<html lang="zh-cmn-Hans">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if($this->title!=''){echo $this->title.'-';}?><?=$this->site->name;?></title>
    <script language="javascript" src="/plugin/js/jquery.js"></script>
    <link rel="stylesheet" href="/plugin/Swiper/css/swiper.min.css"/>
    <script src="/plugin/Swiper/js/swiper.min.js"></script>
    <link rel="stylesheet" href="/plugin/layui/css/layui.css" />
    <script src="/plugin/layui/lay/dest/layui.all.js"></script>
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
            <dl class="">
                <dt><a href="http://www.mogo100.com/shop/index.php?act=show_joinin&amp;op=index" title="免费开店">免费开店</a><i></i></dt>
                <dd>
                    <ul>
                        <li><a href="http://www.mogo100.com/shop/index.php?act=show_joinin&amp;op=index" title="招商入驻">招商入驻</a></li>
                        <li><a href="http://www.mogo100.com/shop/index.php?act=seller_login&amp;op=show_login" target="_blank" title="登录商家管理中心">商家登录</a></li>
                    </ul>
                </dd>
            </dl>
            <dl class="">
                <dt><a href="http://www.mogo100.com/shop/index.php?act=member_order">我的订单</a><i></i></dt>
                <dd>
                    <ul>
                        <li><a href="http://www.mogo100.com/shop/index.php?act=member_order&amp;state_type=state_new">待付款订单</a></li>
                        <li><a href="http://www.mogo100.com/shop/index.php?act=member_order&amp;state_type=state_send">待确认收货</a></li>
                        <li><a href="http://www.mogo100.com/shop/index.php?act=member_order&amp;state_type=state_noeval">待评价交易</a></li>
                    </ul>
                </dd>
            </dl>
            <dl class="">
                <dt>客户服务<i></i></dt>
                <dd>
                    <ul>
                        <li><a href="http://www.mogo100.com/shop/index.php?act=article&amp;op=article&amp;ac_id=2">帮助中心</a></li>
                        <li><a href="http://www.mogo100.com/shop/index.php?act=article&amp;op=article&amp;ac_id=5">售后服务</a></li>
                        <li><a href="http://www.mogo100.com/shop/index.php?act=article&amp;op=article&amp;ac_id=6">客服中心</a></li>
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
            <li title="请输入您要搜索的店铺关键字" act="store_list">店铺</li>
        </ul>
        <form class="search-form" method="get">
            <input type="hidden" value="search" id="search_act" name="act">
            <input placeholder="请输入您要搜索的商品关键字" name="keyword" id="keyword" type="text" class="input-text" value="" maxlength="60">
            <input type="submit" id="button" value="搜索" class="input-submit">
        </form>
        <!--搜索关键字-->
        <div class="keyword">热门搜索：        <ul>
                <li><a href="http://www.mogo100.com/shop/index.php?act=search&amp;op=index&amp;keyword=%E7%8F%A0%E5%AE%9D%E6%89%8B%E8%A1%A8">珠宝手表</a></li>
                <li><a href="http://www.mogo100.com/shop/index.php?act=search&amp;op=index&amp;keyword=%E5%B0%8F%E5%AE%B6%E7%94%B5">小家电</a></li>
                <li><a href="http://www.mogo100.com/shop/index.php?act=search&amp;op=index&amp;keyword=%E6%9C%8D%E8%A3%85">服装</a></li>
                <li><a href="http://www.mogo100.com/shop/index.php?act=search&amp;op=index&amp;keyword=%E5%AE%B6%E7%BA%BA">家纺</a></li>
            </ul>
        </div>
    </div>
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
                <li  class="hover" ><a href="/">首页</a></li>
                <li  class="" ><a href="/goods/lists">商品列表</a></li>
                <li class=""><a href="/user/login">登陆</a></li>
            </ul>
        </div>
    </div>
</div>


<script type="text/javascript">
    header_js();
</script>