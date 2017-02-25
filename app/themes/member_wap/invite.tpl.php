<?php require 'header.php';?>
<? if($this->func=='index') : ?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('/car')?>"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r"></a>
        <h1>我的邀请链接</h1>
    </div>
    <div class="margin_header"></div>
    <div class="weui-cells__title">将下面链接或二维码复制并发送给好友，该好友成功注册后您即可成为邀请人</div>
    <div class="weui-cells__title">邀请链接：<?=$invite_url?></div>

    <div style="text-align: center">
        <img src="<?=$invite_img?>" width="80%">
    </div>


    <?
    if(count($result)==0){
        echo '<div class="weui-cells__title"><p>暂无邀请！</p></div>';
    }else{
        ?>
        <div class="weui-cells__title">我的邀请列表</div>
        <div class="weui-cells">
            <? foreach($result as $user) : ?>
                <div class="weui-cell">
                    <div class="weui-cell__hd"></div>
                    <div class="weui-cell__bd">
                        <p><?=$user->username?></p>
                    </div>
                    <div class="weui-cell__ft"><?=$user->created_at?></div>
                </div>
            <? endforeach; ?>
        </div>
        <?
    }
    ?>
<? endif;?>
<?php require 'footer.php';?>