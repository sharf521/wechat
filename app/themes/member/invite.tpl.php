<?php require 'header.php';?>

<div class="warpcon">
    <?php require 'left.php'; ?>
    <div class="warpright">
        <div class="box">
            <br>
            <fieldset class="layui-elem-field layui-field-title">
                <legend>我的邀请链接</legend>
            </fieldset>
            <blockquote class="layui-elem-quote">将下面链接或二维码复制并发送给好友，该好友成功注册后您即可成为邀请人</blockquote>

            <form method="post" class="layui-form">
                <div class="layui-field-box">
                    <div class="layui-form-item">
                        <label class="layui-form-label">邀请链接</label>
                        <div class="layui-input-block">
                            <textarea class="layui-textarea" readonly><?=$invite_url?></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">邀请二维码</label>
                        <div class="layui-input-inline">
                            <img src="<?=$invite_img?>">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <fieldset class="layui-elem-field layui-field-title">
            <legend>我的邀请列表</legend>
        </fieldset>
        <?
        if(count($result)==0) {
            echo '<blockquote class="layui-elem-quote">暂无邀请</blockquote>';
        }else{?>
            <table class="layui-table"  lay-skin="line">
                <thead>
                <tr>
                    <th>用户名</th><th>注册时间</th>
                </tr>
                </thead>
                <tbody>
                <? foreach($result as $user) : ?>
                    <tr>
                        <td><?=$user->username?></td>
                        <td><?=$user->created_at?></td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
        <?php }?>
    </div>
</div>

<?php require 'footer.php';?>