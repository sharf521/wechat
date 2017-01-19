<?php require 'header.php';?>
    <script type="text/javascript" src="/data/js/category.js?<?= rand(1, 100) ?>"></script>
    <div class="layui-main">
        <?php require 'left.php'; ?>
        <div class="warpright">
            <div class="box"><br>
                <span class="layui-breadcrumb">
                      <a href="<?= url('shipping') ?>">商品管理</a>
                      <a><cite><?=$this->func == 'add'?'新增':'编辑'; ?>商品</cite></cite></a>
                </span>
                <hr>
                <form method="post" class="layui-form">
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label">商品名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" value="<?=$goods->name?>"  placeholder="请填写名称" class="layui-input" value="" autocomplete="off"/>
                            </div>
                        </div>
                        <div id="specBox_no">
                            <div class="layui-form-item">
                                <label class="layui-form-label">价格</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="price" value="<?=$goods->price?>" onkeyup="value=value.replace(/[^0-9.]/g,'')" placeholder="￥" class="layui-input" value="" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">库存</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="stock_count" value="<?=$goods->stock_count?>" onkeyup="value=value.replace(/[^0-9]/g,'')" placeholder="请填写商品库存" class="layui-input" value="" autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label"></label>
                            <div class="layui-input-inline">
                                <input type="button" value="开启规格" class="layui-btn layui-btn-small">
                            </div>
                        </div>
                        <div id="specBox">
                            <? if($goods->is_have_spec) : ?>
                                <script>
                                    $(function() {
                                        $('#specBox_no').hide();
                                    });
                                </script>
                            <? foreach ($specs as $spec) : ?>
                                <div class="spec_item">
                                    <input type="hidden" name="spec_id[]" value="<?=$spec->id?>" />
                                    <div class="weui-cell">
                                        <label class="weui-label">规格</label>
                                        <input class="weui-input" type="text" name="spec_1[]" value="<?=$spec->spec_1?>" placeholder="输入商品规格，如颜色、尺寸"/>
                                    </div>
                                    <div class="weui-cell" style="position: relative">
                                        <label class="weui-label">价格</label>
                                        <input class="weui-input" type="number" name="price[]" value="<?=$spec->price?>" onkeyup="value=value.replace(/[^0-9.]/g,'')" placeholder="请输入价格"/>
                                        <i class="spec_del iconfont">&#xe642;</i>
                                    </div>
                                    <div class="weui-cell">
                                        <label class="weui-label">库存</label>
                                        <input class="weui-input" type="number" name="stock_count[]" value="<?=$spec->stock_count?>" onkeyup="value=value.replace(/[^0-9]/g,'')" placeholder="请输入库存数"/>
                                    </div>
                                </div>
                            <? endforeach;?>
                            <? endif;?>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">照片</label>
                            <div class="layui-input-block">
                                <ul id="uploaderFiles">
                                    <?
                                    $imgids=',';
                                    foreach ($images as $img) :
                                        $imgids.=$img->id.',';
                                        ?>
                                        <li>
                                            <img src="<?=$img->image_url?>">
                                            <i class='iconfont' onclick=delGoodsImg(this,'<?=$img->id?>')>&#xe642;</i></li>
                                    <? endforeach;?>
                                </ul>
                                <input type="hidden" name="imgids" id="imgids" value="<?=$imgids?>">
                                <input type="file" name="file" class="layui-upload-file" accept="image/*" />
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">介绍</label>
                            <div class="layui-input-block">
                                <? ueditor(array('value' => $GoodsData->content)); ?>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">店铺分类</label>
                            <div class="layui-input-inline">
                                <select name="shop_category" class="layui-select">
                                    <option value="" selected>请选择</option>
                                    <? foreach ($cates as $cate) :?>
                                        <option value="<?=$cate->id?>" <? if($cate->id==$goods->shop_cateid){echo 'selected';}?>><?=$cate->name?></option>
                                    <? endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">配送方式</label>
                            <div class="layui-input-inline">
                                <select name="shipping_id" class="layui-select">
                                    <option value="" selected>请选择</option>
                                    <? foreach ($shippings as $ship) :?>
                                        <option value="<?=$ship->id?>" <? if($ship->id==$goods->shipping_id){echo 'selected';}?>><?=$ship->name?></option>
                                    <? endforeach;?>
                                </select>
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
            </div>
        </div>
    </div>
    <script>
        goodsAdd_js();
        $(function () {
            layui.form('select').render();
            layui.form().on('submit(*)', function(data){
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
<?php require 'footer.php';?>