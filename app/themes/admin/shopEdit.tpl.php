<?php require 'header.php';?>
    <blockquote class="layui-elem-quote"><span>审核</span>
        <a href="<?= url('shop') ?>" class="layui-btn layui-btn-small">返回列表</a></blockquote>
    <div class="main_content">
        <form method="post" class="layui-form">
            <div class="layui-field-box">
                <div class="layui-form-item">
                    <label class="layui-form-label">店铺名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" placeholder="请填写店铺名称" class="layui-input" value="<?=$shop->name?>" autocomplete="off"/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">联系人</label>
                    <div class="layui-input-inline">
                        <input type="text" name="contacts" required placeholder="请填写姓名" class="layui-input" value="<?=$shop->contacts?>" autocomplete="off"/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">联系电话</label>
                    <div class="layui-input-inline">
                        <input type="text" name="tel" required placeholder="请填写联系电话" class="layui-input" value="<?=$shop->tel?>" autocomplete="off"/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">联系QQ</label>
                    <div class="layui-input-inline">
                        <input type="text" name="qq" required placeholder="请填写QQ号" class="layui-input" value="<?=$shop->qq?>" autocomplete="off" onkeyup="value=value.replace(/[^0-9]/g,'')"/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">开启预售</label>
                    <div class="layui-input-inline">
                        <input type="checkbox" value="1" name="is_presale" lay-skin="switch" <? if($shop->is_presale==1){echo 'checked';}?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit="" lay-filter="*">确认保存</button>
                        <button class="layui-btn" onclick="history.go(-1)">返回</button>
                    </div>
                </div>
            </div>
        </form>
        <script>
            $(function () {
                layui.form.on('submit(*)', function(data){
                    var form=data.form;
                    var fields=data.field;
                    var name=$(form).find('input[name=name]');
                    if(name.val()==''){
                        layer.tips('不能为空！', name);
                        name.focus();
                        return false;
                    }
                });
            });
        </script>
    </div>
<?php require 'footer.php'; ?>