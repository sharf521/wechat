<?php include 'header_top.php';?>
<div class="usernav">
    <div class="layui-main">
        <div class="logoleft">
            <a href="/"><img src="<?=$this->site->logo?>" height="60"></a>
        </div>
        <a class="avatar" href="/member/">
            <img src="<?=$this->user->headimgurl?>">
            <cite><?=$this->username?></cite>
            <i></i>
        </a>
        <div class="usermenu">
            <ul>
                <li>
                    <a href="<?=url('/member')?>">个人中心</a>
                </li>
                <li>
                    <a href="<?=$this->site->center_url?>" target="_blank">帐户中心</a>
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