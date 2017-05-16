<?php require 'header.php';?>
    <script type="text/javascript" src="/data/js/category.js?<?= rand(1, 100) ?>"></script>
    <div class="layui-main">
        <?php require 'left.php'; ?>
        <div class="warpright">
            <div class="box"><br>
                <span class="layui-breadcrumb">
                      <a href="<?= url('goods') ?>">商品管理</a>
                      <a><cite>编辑采购商品</cite></a>
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

                        <? if($goods->is_have_spec) : ?>
                            <div class="layui-form-item">
                                <label class="layui-form-label">规格</label>
                                <div class="layui-input-block">
                                    <table class="layui-table">
                                        <thead>
                                        <tr>
                                            <th><?=$goods->spec_name1?></th>
                                            <?php  if($goods->spec_name2!=''): ?>
                                                <th><?=$goods->spec_name2?></th>
                                            <?php endif;?>
                                            <th>库存</th><th>供货价</th><th>零售价</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $arrSpecs=array();
                                        foreach ($goodsSpecs as $spec){
                                            $arrSpecs[$spec->supply_spec_id]=$spec->retail_float_money;
                                        }
                                        $specs=$supplyGoods->GoodsSpec();
                                        foreach($specs as $spec) :
                                            if(isset($arrSpecs[$spec->id])){
                                                $retail_price=math($spec->price,$arrSpecs[$spec->id],'+',2);
                                            }else{
                                                $retail_price=$spec->retail_price;
                                            }
                                            ?>
                                            <tr>
                                                <td><?=$spec->spec_1?></td>
                                                <?php  if($goods->spec_name2!=''): ?>
                                                    <td><?=$spec->spec_2?></td>
                                                <?php endif;?>
                                                <td><?=$spec->stock_count?></td>
                                                <td><?=$spec->price?></td>
                                                <td>
                                                    <?=$spec->retail_price?>
                                                    <!--<input type="text" class="layui-input" placeholder="￥" style="width: 100px; display: inline-block" name="retail_price<?/*=$spec->id*/?>" value="<?/*=$retail_price*/?>" data_price="<?/*=$spec->price*/?>" onkeyup="value=value.replace(/[^0-9.]/g,'')"> 建议￥--><?/*=$spec->retail_price*/?>
                                                </td>
                                            </tr>
                                        <? endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        <? else:?>
                            <div class="layui-form-item">
                                <label class="layui-form-label">零售价</label>
                                <div class="layui-input-inline">
                                    <?=$goods->price?>
                                    <!--<input type="text" name="retail_price" value="<?/*=$goods->price*/?>" onkeyup="value=value.replace(/[^0-9.]/g,'')" placeholder="￥" class="layui-input" value="" autocomplete="off"/>-->
                                </div>
                                <div class="layui-form-mid layui-word-aux">供货价:<?=$supplyGoods->price?>元<!--，建议零售价：<?=$supplyGoods->retail_price?>元--></div>
                            </div>
                        <? endif;?>
                        <div class="layui-form-item">
                            <label class="layui-form-label">图片</label>
                            <div class="layui-input-block">
                                <span id="upload_span_goods"><img src="<?=$goods->image_url?>" height="80"></span>
                                <input type="hidden" name="image_url" value="<?=$goods->image_url?>" id="goods">
                                <input type="file" name="file" class="layui-upload-file" accept="image/*" upload_id="goods" upload_type="goods">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">店铺分类</label>
                            <div class="layui-input-inline">
                                <select name="shop_category" class="layui-select">
                                    <option value="" selected>请选择</option>
                                    <? foreach ($cates as $cate) :?>
                                        <option value="<?=$cate['id']?>" <? if($cate['id']==$goods->shop_cateid){echo 'selected';}?>><?=$cate['name_pre']?><?=$cate['name']?></option>
                                    <? endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label"></label>
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="*">确认保存</button>
                                <button class="layui-btn" onclick="history.go(-1);">取消</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        purchaseGoodsEdit_js();
    </script>
<?php require 'footer.php';?>