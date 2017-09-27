<?php require 'header.php';?>

    <div class="layui-main container">
        <div class="topnav">
            <span class="layui-breadcrumb"><?=$topnav_str?></span>
        </div>
        <div class="goods_info_box clearFix">
            <div class="goods_pics">
                <div class="pic_big">
                    <img lay-src="<?=$goods->image_url?>">
                </div>
                <ul class="clearFix">
                    <? foreach($images as $img) : ?>
                        <li>
                            <img lay-src="<?=\App\Helper::smallPic($img->image_url)?>">
                        </li>
                    <? endforeach;?>
                </ul>
            </div>
            <div class="goods_info">
                <div class="name"><h1><?=$goods->name?></h1></div>
                <div class="price clearFix">
                    <span class="label">价格: </span><span class="money">￥<i id="goods_price"><?=$goods->price?></i></span>
                </div>
                <div class="clearFix" style="display: none">
                    <span class="label">运费: </span><span class="shipping_fee">¥<?=$goods->shipping_fee?></span>
                </div>
                <div class="bottom_buy_box">
                    <form method="post" name="form_order" class="layui-form">
                        <? if($goods->is_have_spec) : ?>
                            <? $specs=$goods->GoodsSpec();?>
                            <script>
                                $(function(){
                                    var specs = new Array();
                                    <? foreach($specs as $spec) :?>
                                    specs.push(new spec(<?=$spec->id?>, '<?=$spec->spec_1?>', '<?=$spec->spec_2?>', <?=$spec->price?>, <?=$spec->stock_count?>));
                                    <? endforeach;?>
                                    goodsSpec=new GoodsSpec(specs);
                                });
                            </script>
                            <input type="hidden" name="spec_id" id="spec_id">
                            <div class="clearFix">
                                <span class="label"><?=$goods->spec_name1?>: </span><div id="specBox_1" class="spec_choose clearFix"></div>
                            </div>
                            <?php  if($goods->spec_name2!=''): ?>
                            <div class="clearFix">
                                <span class="label"><?=$goods->spec_name2?>: </span><div id="specBox_2" class="spec_choose clearFix"></div>
                            </div>
                            <?php endif;?>
                        <? endif;?>
                        <div class="clearFix choose">
                            <span class="label">购买数量: </span>
                            <div class="wrap-input">
                                <span class="btn-reduce">-</span>
                                <input class="text" value="1"  maxlength="5" type="text" id="buy_quantity" name="quantity" onkeyup="value=value.replace(/[^0-9]/g,'')">
                                <span class="btn-add">+</span>
                            </div>
                            <div class="stock_count">库存 <span id="goods_stock_count" class="goods_stock_count"><?=$goods->stock_count?></span> 件</div>
                        </div>
                        <div class="goods_prompt"></div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">首付比例</label>
                            <div class="layui-input-inline">
                                <select name="shop_category" class="layui-select">
                                    <option value="0">零首付</option>
                                    <option value="0.1">10%</option>
                                    <option value="0.2">20%</option>
                                    <option value="0.3">30%</option>
                                    <option value="0.5">50%</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">分期月数</label>
                            <div class="layui-input-block">
                                <ul class="selectLimit">
                                    <li class="active">不分期</li>
                                    <li>3个月</li>
                                    <li>6个月</li>
                                    <li>9个月</li>
                                    <li>12个月</li>
                                </ul>
                            </div>
                        </div>

                        <div class="buy_box_opts clearFix">
                            <? if($goods->stock_count>0):?>
                                <? if($goods->is_presale==1): ?>
                                        <a href="javascript:;" class="layui-btn btn-presale">申请预订</a>
                                <? else : ?>
                                    <a href="javascript:;" class="layui-btn opt1">加入购物车</a>
                                    <a href="javascript:;" class="layui-btn opt2">分期购买</a>
                                <? endif;?>
                            <? else: ?>
                                <span class="layui-btn layui-btn-primary">库存己不足</span>
                            <? endif;?>
                        </div>
                    </form>
                </div>

            </div>
            <div class="about_store">
                <p><strong><?=$shop->name?></strong></p>
                <p><span>联系店家：</span><?=\App\Helper::getQqLink($shop->qq)?><br><br>
                    <a class="layui-btn layui-btn-mini" href="<?=$shop->getLink()?>" target="_blank"> 进 入 店 铺 </a>
                </p>
            </div>
        </div>
        <div class="goods_detail_box">
            <div class="sidebar">
                <div class="sidebar_title">商品二维码</div>
                <div class="sidebar_content">
                    <img src="<?=$QRcode_url?>">
                </div>
            </div>
            <div class="main">
                <div class="layui-tab layui-tab-brief" lay-filter="demo">
                    <ul class="layui-tab-title">
                        <li class="layui-this">商品详情</li>
                        <li>交易记录</li>
                    </ul>
                    <div class="layui-tab-content" style="min-height: 400px;">
                        <div class="layui-tab-item layui-show goods_detail_txt">
                            <?=nl2br($content)?>
                        </div>
                        <div class="layui-tab-item" id="orderRecord">暂不显示</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/plugin/js/fly.min.js"></script>
    <script>
        var goods_id='<?=(int)$goods->id?>';
        $(function(){
            goods_detail_js();
        });
    </script>
<?php require 'footer.php';?>