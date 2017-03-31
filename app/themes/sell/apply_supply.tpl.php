<?php require 'header.php';?>

<div class="warpcon">
    <?php require 'left.php'; ?>
    <div class="warpright">
        <div class="box">
            <br>
            <fieldset class="layui-elem-field layui-field-title">
                <legend><?=$this->title?></legend>
            </fieldset>
            <?php
            if($supply->is_exist){
                if($supply->status==0){
                    echo '<blockquote class="layui-elem-quote">待审核</blockquote>';
                }elseif($supply->status==2){
                    echo '<blockquote class="layui-elem-quote">未通过<br>原因：'.nl2br($supply->verify_remark).'</blockquote>';
                }
            }
            ?>
            <form method="post" class="layui-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">供货说明</label>
                        <div class="layui-input-block">
                            <? ueditor(array('value' => $supply->remark)); ?>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="*">确认保存</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require 'footer.php';?>