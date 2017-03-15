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
                <form method="post" name="form_order">
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
                        <div class="price clearFix">
                            <span class="label">价格: </span><span class="money">￥<i id="goods_price"><?=$goods->price?></i></span>
                        </div>
                        <div class="price clearFix">
                            <span class="label">建议零售价: </span>
                            <input type="text" class="layui-input" style="width: 150px; display: inline-block" name="retail_price" value="<?=$goods->retail_price?>" data_price="<?=$goods->price?>"onkeyup="value=value.replace(/[^0-9.]/g,'')"> 元
                        </div>
                    <? endif;?>
                </form>
                <? if($isPurchase!==true) : ?>
                <a href="javascript:;" class="layui-btn purchase-btn" style="background-color: #f44; margin:10px 100px;">立即上架到店铺</a>
                <? else : ?>
                    <a href="javascript:;" class="layui-btn layui-btn-disabled" style="margin:10px 100px;">己采购</a>
                <? endif;?>
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