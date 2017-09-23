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