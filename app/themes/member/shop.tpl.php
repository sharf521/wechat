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
                if($shop->is_exist){
                    if($shop->status==0){
                        echo '<blockquote class="layui-elem-quote">待审核</blockquote>';
                    }elseif($shop->status==2){
                        echo '<blockquote class="layui-elem-quote">未通过<br>原因：'.nl2br($shop->verify_remark).'</blockquote>';
                    }
                }
                ?>
                <form method="post" class="layui-form">
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label">店铺名称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="name" placeholder="请填写店铺名称" class="layui-input" value="<?=$shop->name?>" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">联系电话</label>
                            <div class="layui-input-inline">
                                <input type="text" name="tel" required placeholder="请填写联系电话" class="layui-input" value="<?=$shop->tel?>" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">联系人</label>
                            <div class="layui-input-inline">
                                <input type="text" name="contacts" required placeholder="请填写姓名" class="layui-input" value="<?=$shop->contacts?>" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">联系QQ</label>
                            <div class="layui-input-inline">
                                <input type="text" name="qq" required placeholder="请填写QQ号" class="layui-input" value="<?=$shop->qq?>" autocomplete="off" onkeyup="value=value.replace(/[^0-9]/g,'')"/>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">详细地址</label>
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
                                <input type="text" name="address" placeholder="小区或街道地址" required class="layui-input" value="<?=$shop->address?>" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">店铺介绍</label>
                            <div class="layui-input-block">
                                <? ueditor(array('value' => $shop->remark)); ?>
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
                <script src="/plugin/js/layui_citys.js"></script>
                <script>
                    $(function () {
                        pca.init('select[name=province]', 'select[name=city]', 'select[name=county]', '<?=$shop->province?>', '<?=$shop->city?>', '<?=$shop->province?>');
                    });
                    shop_js();
                </script>
            </div>
        </div>
    </div>

<?php require 'footer.php';?>