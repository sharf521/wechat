<?php require 'header.php';?>

    <div class="warpcon">
        <?php require 'left.php'; ?>
        <div class="warpright">
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend><?=$this->title?></legend>
                </fieldset>
                <form method="post" class="layui-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">奖励承诺</label>
                        <div class="layui-input-block">
                            <? ueditor(array('value' => $content)); ?>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="*">确认保存</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        commitment();
    </script>
<?php require 'footer.php';?>