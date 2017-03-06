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
                <div class="layui-field-box">
                    <div class="layui-form-item">
                        <label class="layui-form-label">收货人</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" placeholder="请填写收货人" class="layui-input" value="" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">联系电话</label>
                        <div class="layui-input-inline">
                            <input type="text" name="phone" required placeholder="请填写联系电话" class="layui-input" value="" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">联系地址</label>
                        <div class="layui-input-inline">
                            <select name="province" lay-filter="province" required>
                                <option></option>
                            </select>
                        </div>
                        <div class="layui-input-inline">
                            <select name="city" lay-filter="city" required>
                                <option></option>
                            </select>
                        </div>
                        <div class="layui-input-inline">
                            <select name="county" lay-filter="county" required>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <input type="text" name="address" placeholder="小区或街道地址" required class="layui-input" value="" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">邮政编码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="zipcode" placeholder="邮政编码" class="layui-input" value="" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <input type="checkbox" value="1" name="is_default" title="设为默认收货地址" checked>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="*">确认提交</button>
                        </div>
                    </div>
                </div>
            </form>
            <script src="/plugin/js/layui_citys.js"></script>
            <script>
                address_js();
                function address_del(id){
                    layer.open({
                        content: '您确定要删除吗？'
                        ,btn: ['删除', '取消']
                        ,yes: function(index){
                            location.href='<?=url("address/del/?id=")?>'+id;
                            layer.close(index);
                        }
                    });
                }
            </script>
        </div>
    </div>
</div>

<?php require 'footer.php';?>
