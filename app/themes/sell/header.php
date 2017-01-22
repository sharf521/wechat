<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if(!empty($title_herder)){echo $title_herder.'-';}?><?=app('\App\Model\System')->getCode('webname');?></title>
    <link rel="stylesheet" href="/themes/base.css"/>
    <link href="/themes/sell/sell.css" rel="stylesheet" type="text/css" />
    <script language="javascript" src="/plugin/js/jquery.js"></script>
    <link rel="stylesheet" href="/plugin/layui/css/layui.css" />
    <script src="/plugin/layui/lay/dest/layui.all.js"></script>
    <script src="/themes/sell/sell.js"></script>
</head>
<body>
<div class="usernav">
    <div class="layui-main">
        <div class="logoleft">
            <a href="/"><img src="<?=$this->site->logo?>" height="60"></a>
        </div>
        <div class="usermenu">
            <ul>
                <li>
                    <a href="<?=url('/member')?>">个人中心</a>
                </li>
                <li>
                    <a href="<?=$this->site->center_url?>" target="_blank">支付中心</a>
                </li>
                <li>
                    <a href="<?=url('/member/logout')?>">退出</a>
                </li>
                <div class="clear"></div>
            </ul>
        </div>
    </div>
</div>
<!--.usernav .layui-nav{position: absolute; right: 0; top: 0; padding: 0; background: none;}
.usernav .layui-nav .layui-nav-item{margin: 0 10px; line-height: 85px;}
.usernav .layui-nav .layui-this{background:#ebc5c5;}
.usernav .layui-nav .layui-nav-item a:hover,.usernav .layui-nav .layui-this a{color: #000;}
.usernav .layui-nav .layui-nav-bar{background-color: #393D49;}
<ul class="layui-nav" lay-filter="">
    <li class="layui-nav-item layui-this"><a href="<?/*=url('/member')*/?>">个人中心</a></li>
    <li class="layui-nav-item"><a href="<?/*=$this->site->center_url*/?>" target="_blank">支付中心</a></li>
    <li class="layui-nav-item"><a href="<?/*=url('/member/logout')*/?>">退出</a></li>
</ul>-->