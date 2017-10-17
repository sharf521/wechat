<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if($this->title!=''){echo $this->title.'-';}?><?=$this->site->name;?></title>
    <script language="javascript" src="/plugin/js/jquery.js"></script>
    <link rel="stylesheet" href="/plugin/layui.v2/css/layui.css" />
    <script src="/plugin/layui.v2/layui.all.js"></script>
    <script src="/themes/member/member.js"></script>
    <link rel="stylesheet" href="/themes/base.css"/>
    <link href="/themes/member/member.css" rel="stylesheet" type="text/css" />
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
<div class="top_header">
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
    <!--<a href="/cart" class="my_cart layui-btn  layui-btn-primary"><i class="iconfont">&#xe698;</i> 我的购物车</a>-->
    <a class="userCenter layui-btn  layui-btn-primary" href="<?=$this->site->center_url?>" target="_blank">帐户中心</a>

</div>
</div>
<!--<div class="usernav">
    <div class="userlogo">
        <div class="logoleft">
            <a href="/"><img src="<?/*=$this->site->logo*/?>" height="60"></a>
        </div>

        <a class="avatar" href="/member/">
            <img src="<?/*=$this->user->headimgurl*/?>">
            <cite><?/*=$this->username*/?></cite>
            <i></i>
        </a>

        <div class="usermenu">
            <ul>
                <li>
                    <a href="<?/*=url('')*/?>">个人中心</a>
                </li>
                <li>
                    <a href="<?/*=$this->site->center_url*/?>" target="_blank">帐户中心</a>
                </li>
                <li>
                    <a href="<?/*=url('logout')*/?>">退出</a>
                </li>
                <div class="clear"></div>
            </ul>
        </div>
    </div>
</div>-->