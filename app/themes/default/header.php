<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if(!empty($title_herder)){echo $title_herder.'-';}?><?=$this->site->name;?></title>
    <link rel="stylesheet" href="/themes/base.css"/>
    <link href="/themes/default/default.css" rel="stylesheet" type="text/css" />
    <script language="javascript" src="/plugin/js/jquery.js"></script>
    <link rel="stylesheet" href="/plugin/layui/css/layui.css" />
    <script src="/plugin/layui/lay/dest/layui.all.js"></script>
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
                <div class="item">
                    <h3><span>·</span><a href="/goods/lists/32">酒</a></h3>
                    <div class="item-list clearfix">
                        <div class="subitem">
                            <dl>
                                <dt><a href="/goods/lists/33">尊王</a></dt>
                                <dd>
                                </dd>
                            </dl>
                            <dl>
                                <dt><a href="/goods/lists/34">一品坊</a></dt>
                                <dd>
                                </dd>
                            </dl>
                            <dl>
                                <dt><a href="/goods/lists/70">贵州御酒</a></dt>
                                <dd>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <h3><span>·</span><a href="/goods/lists/38">POS机</a></h3>
                    <div class="item-list clearfix">
                        <div class="subitem">
                            <dl>
                                <dt><a href="/goods/lists/48">快刷</a></dt>
                                <dd>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <h3><span>·</span><a href="/goods/lists/39">汽车</a></h3>
                    <div class="item-list clearfix">
                        <div class="subitem">
                            <dl>
                                <dt><a href="/goods/lists/49">MG</a></dt>
                                <dd>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <h3><span>·</span><a href="/goods/lists/40">化妆品</a></h3>
                    <div class="item-list clearfix">
                        <div class="subitem">
                            <dl>
                                <dt><a href="/goods/lists/41">遇见香芬</a></dt>
                                <dd>
                                </dd>
                            </dl>
                            <dl>
                                <dt><a href="/goods/lists/42">皓乐齿</a></dt>
                                <dd>
                                    <em><a href="/goods/lists/50">净澈气息系列</a></em>
                                    <em><a href="/goods/lists/51">口腔清洁系列</a></em>
                                    <em><a href="/goods/lists/52">亮白净色系列</a></em>
                                </dd>
                            </dl>
                            <dl>
                                <dt><a href="/goods/lists/43">双莲</a></dt>
                                <dd>
                                    <em><a href="/goods/lists/53">儿童系列</a></em>
                                    <em><a href="/goods/lists/54">口腔系列</a></em>
                                    <em><a href="/goods/lists/55">身体系列</a></em>
                                    <em><a href="/goods/lists/56">秀发系列</a></em>
                                </dd>
                            </dl>
                            <dl>
                                <dt><a href="/goods/lists/45">京润珍珠</a></dt>
                                <dd>
                                    <em><a href="/goods/lists/64">多肽赋颜系列</a></em>
                                    <em><a href="/goods/lists/65">活体靓采系列</a></em>
                                    <em><a href="/goods/lists/66">裸妆透白系列</a></em>
                                    <em><a href="/goods/lists/67">男士俊逸系列</a></em>
                                    <em><a href="/goods/lists/68">天然精粹系列</a></em>
                                    <em><a href="/goods/lists/69">天然盈润系列</a></em>
                                </dd>
                            </dl>
                            <dl>
                                <dt><a href="/goods/lists/46">名门闺秀</a></dt>
                                <dd>
                                    <em><a href="/goods/lists/57">白.珍珠美白淡斑系列</a></em>
                                    <em><a href="/goods/lists/58">紧.玉容紧致弹润系列</a></em>
                                    <em><a href="/goods/lists/59">润.玫瑰水嫩保湿系列</a></em>
                                    <em><a href="/goods/lists/60">生.幼颜抗衰老系列</a></em>
                                    <em><a href="/goods/lists/61">透.清荷补水平油系列</a></em>
                                    <em><a href="/goods/lists/62">修.童颜百搭增效系列</a></em>
                                    <em><a href="/goods/lists/63">御.琉金御养抗皱系列</a></em>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--所有分类 End-->
        <div class="nav-list clearFix">
            <ul>
                <li  class="hover" ><a href="/">首页</a></li>
                <li  class="" ><a href="/shuoshuo">商品列表</a></li>
                <li class=""><a href="/health">登陆</a></li>
            </ul>
        </div>
    </div>
</div>


<script type="text/javascript">
    header_js();
</script>