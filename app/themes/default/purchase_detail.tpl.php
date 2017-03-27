<?php require 'header.php';?>

    <div class="layui-main container">
        <div class="topnav">
            <span class="layui-breadcrumb"><?=$topnav_str?></span>
        </div>
        <div class="goods_info_box clearFix">
            <div class="goods_pics">
                <div class="pic_big">
                    <img src="/themes/images/blank.gif" data-echo="<?=$goods->image_url?>">
                </div>
                <ul class="clearFix">
                    <? foreach($images as $img) : ?>
                        <li>
                            <img src="/themes/images/blank.gif" data-echo="<?=$img->image_url?>">
                        </li>
                    <? endforeach;?>
                </ul>
            </div>
            <div class="goods_info">
                <div class="name"><h1><?=$goods->name?></h1></div>
                <input type="hidden" id="is_have_spec" value="<?=$goods->is_have_spec?>">
                <form method="post" name="form_order" class="layui-form">
                    <? if($goods->is_have_spec) : ?>
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
                            $specs=$goods->GoodsSpec();
                            foreach($specs as $spec) :?>
                                <tr>
                                    <td><?=$spec->spec_1?></td>
                                    <?php  if($goods->spec_name2!=''): ?>
                                        <td><?=$spec->spec_2?></td>
                                    <?php endif;?>
                                    <td><?=$spec->stock_count?></td>
                                    <td><?=$spec->price?></td>
                                    <td>
                                        <input type="text" class="layui-input" placeholder="￥" style="width: 100px; display: inline-block" name="retail_price<?=$spec->id?>" value="<?=$spec->retail_price?>" data_price="<?=$spec->price?>" onkeyup="value=value.replace(/[^0-9.]/g,'')"> 建议￥<?=$spec->retail_price?>
                                    </td>
                                </tr>
                            <? endforeach;?>
                            </tbody>
                        </table>
                    <? else:?>
                        <div class="layui-form-item">
                            <label class="layui-form-label">供货价</label>
                            <div class="layui-input-inline"><span class="money">￥<i id="goods_price"><?=$goods->price?></i></span></div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">建议零售价</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" name="retail_price" value="<?=$goods->retail_price?>" data_price="<?=$goods->price?>"onkeyup="value=value.replace(/[^0-9.]/g,'')">
                            </div>
                            <div class="layui-form-mid layui-word-aux">元</div>
                        </div>
                    <? endif;?>

                    <div class="layui-form-item">
                        <label class="layui-form-label">配送费用</label>
                        <div class="layui-input-block">
                            <table class="layui-table">
                                <tr bgcolor="#efefef"><td>地区</td><td>首件</td><td>首费</td><td>续件</td><td>续费</td></tr>
                                <? foreach ($areas as $area) :?>
                                    <tr><td><?=$area['areaname']?></td><td><?=$area['one']?></td><td>￥<?=$area['price']?></td><td><?=$area['next']?></td><td>￥<?=$area['nprice']?></td></tr>
                                <? endforeach;?>
                            </table>

                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">采购到分类</label>
                        <div class="layui-input-inline">
                            <select name="shop_category" class="layui-select">
                                <option value="" selected>请选择</option>
                                <? foreach ($cates as $cate) :?>
                                    <option value="<?=$cate['id']?>"><?=$cate['name_pre']?><?=$cate['name']?></option>
                                <? endforeach;?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-inline">
                            <? if($isPurchase!==true) : ?>
                                <a href="javascript:;" class="layui-btn purchase-btn" style="background-color: #f44;">采购到店铺</a>
                            <? else : ?>
                                <a href="javascript:;" class="layui-btn layui-btn-disabled">己采购</a>
                            <? endif;?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="goods_detail_box">
            <div class="sidebar">
                <ul>
                    
                </ul>
            </div>
            <div class="main">
                <div class="layui-tab layui-tab-brief">
                    <ul class="layui-tab-title">
                        <li class="layui-this">商品详情</li>
                        <li>交易记录</li>
                    </ul>
                    <div class="layui-tab-content" style="min-height: 400px;">
                        <div class="layui-tab-item layui-show">
                            <?=nl2br($GoodsData->content)?>
                        </div>
                        <div class="layui-tab-item">暂不显示</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var goods_id='<?=(int)$goods->id?>';
        purchase_detail_js();
    </script>
<?php require 'footer.php';?>