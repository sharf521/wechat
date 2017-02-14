<?php require 'header.php';?>
    <script type="text/javascript" src="/data/js/category.js?<?= rand(1, 100) ?>"></script>
    <div class="layui-main">
        <?php require 'left.php'; ?>
        <div class="warpright">
            <div class="box"><br>
                <span class="layui-breadcrumb">
                      <a href="<?= url('goods') ?>">商品管理</a>
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
                        <input type="hidden" name="is_have_spec" id="is_have_spec" value="<?=(int)$goods->is_have_spec?>">
                        <div id="specBox_no" <? if((int)$goods->is_have_spec==1){echo 'class="hide"';}?>>
                            <div class="layui-form-item">
                                <label class="layui-form-label">价格</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="g_price" value="<?=$goods->price?>" onkeyup="value=value.replace(/[^0-9.]/g,'')" placeholder="￥" class="layui-input" value="" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">库存</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="g_stock_count" value="<?=$goods->stock_count?>" onkeyup="value=value.replace(/[^0-9]/g,'')" placeholder="请填写商品库存" class="layui-input" value="" autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label"></label>
                            <div class="layui-input-inline">
                                <input type="button" value="<?=($goods->is_have_spec==1)?'关闭':'开启';?>规格" id="addSpecBtn" class="layui-btn layui-btn-small">
                            </div>
                        </div>
                        <div id="specBox" <? if((int)$goods->is_have_spec==0){echo 'class="hide"';}?>>
                            <div class="layui-form-item">
                                <label class="layui-form-label">规格</label>
                                <div class="layui-input-block">
                                    <table class="layui-table" lay-skin="row" lay-even="" id="MySpecTB">
                                        <thead>
                                        <tr>
                                            <th><input name="spec_name1" type="text" class="layui-input" size="4" value="<?=$goods->spec_name1?>" placeholder="颜色"></th>
                                            <th><input name="spec_name2" type="text" class="layui-input" size="4" value="<?=$goods->spec_name2?>" placeholder="大小"></th>
                                            <th>价格</th>
                                            <th>库存</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <? foreach ($specs as $spec) : ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="spec_id[]" value="<?=$spec->id?>">
                                                <input name="spec_1[]" type="text" size="6" class="layui-input" placeholder="具体规格" value="<?=$spec->spec_1?>"></td>
                                            <td><input name="spec_2[]" type="text" size="6" class="layui-input" placeholder="具体规格" value="<?=$spec->spec_2?>"></td>
                                            <td><input name="price[]" type="text" size="6"  placeholder="￥" class="layui-input" value="<?=$spec->price?>" onkeyup="value=value.replace(/[^0-9.]/g,'')"></td>
                                            <td><input name="stock_count[]" type="text" size="6"  placeholder="库存数量" value="<?=$spec->stock_count?>" class="layui-input" onkeyup="value=value.replace(/[^0-9]/g,'')"></td>
                                            <td>
                                                <span class="up_btn layui-btn layui-btn-mini">上移</span>
                                                <span class="down_btn layui-btn layui-btn-mini">下移</span>
                                                <span class="delete_btn layui-btn layui-btn-mini">删除</span>
                                            </td>
                                        </tr>
                                        <? endforeach;?>
                                        </tbody>
                                    </table>
                                    <input type="button" class="layui-btn layui-btn-mini add_btn" value=" + 添 加 一 个 规 格 ">
                                </div>
                            </div>
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
                                        <option value="<?=$cate['id']?>" <? if($cate['id']==$goods->shop_cateid){echo 'selected';}?>><?=$cate['name']?></option>
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
    </script>
<?php require 'footer.php';?>