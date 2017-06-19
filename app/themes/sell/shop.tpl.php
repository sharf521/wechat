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
                        <label class="layui-form-label">详细地址</label>
                        <div class="layui-input-inline">
                            <select name="province" lay-filter="province" required>
                                <option></option>
                            </select>
                        </div>
                        <div class="layui-input-inline">
                            <select name="city" lay-filter="city" id="city" required>
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
                            <input type="text" name="address" id="address" placeholder="小区或街道地址" required class="layui-input" value="<?=$shop->address?>" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">开启满减</label>
                        <div class="layui-input-block">
                            <input type="checkbox" value="1" <? if($shop->is_fulldown){echo 'checked';}?> name="is_fulldown" lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF">
                        </div>
                        <div class="layui-form-mid layui-word-aux">开启后：用户订单满100元直减10元。</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">店铺介绍</label>
                        <div class="layui-input-block">
                            <? ueditor(array('value' => $shop->remark)); ?>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">所在位置</label>
                        <div class="layui-input-block">
                            （先点击地图，然后拖动图标到你的位置上）
                            <input type="hidden" name="gps" id="gps" value="<?=$shop->gps?>">
                            <div id="map" style="width: 100%; height: 500px;"></div>
                            <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=FD277acba8a70dc3bd90b1790787d332"></script>
                            <script type="text/javascript">
                                map('map',$('#gps').val());
                            </script>
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