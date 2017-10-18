<?php require 'header.php';?>

<div class="warpcon">
    <?php require 'left.php'; ?>
    <div class="warpright">
        <div class="box">
            <br>
            <fieldset class="layui-elem-field layui-field-title">
                <legend>收货地址管理</legend>
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
                $(function () {
                    address_js();
                });
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

        <fieldset class="layui-elem-field layui-field-title">
            <legend>我的收货地址</legend>
        </fieldset>
        <?
        if(count($result)==0) {
            echo '<blockquote class="layui-elem-quote">暂无收货地址</blockquote>';
        }else{?>
            <table class="layui-table"  lay-skin="line">
                <thead>
                    <tr>
                        <th>收货人</th><th>地址</th><th>电话</th><th>邮编</th><th>操作</th>
                    </tr>
                </thead>
                <tbody>
                <? foreach($result as $adds) : ?>
                    <tr>
                        <td><?=$adds->name?></td>
                        <td><?=$adds->region_name?><br><?=$adds->address?></td>
                        <td><?=$adds->phone?></td>
                        <td><?=$adds->zipcode?></td>
                        <td>
                            <? if($adds->is_default) : ?>
                                <span class="layui-btn layui-btn-mini layui-btn-primary">默认地址</span>
                            <? else : ?>
                                <a class="layui-btn layui-btn-mini" href="<?=url("address/setDefault/?id={$adds->id}")?>">设为默认</a>
                            <? endif;?>
                            <input type="button" class="layui-btn layui-btn-mini" onclick="address_del(<?=$adds->id?>)" value="删除"></td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
        <?php }?>
    </div>
</div>

<?php require 'footer.php';?>
