<?php require 'header.php';?>
<div class="layui-main wrapper">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>选择收货地址</legend>
    </fieldset>
    <? if(empty($addressList)) : ?>
        <blockquote class="layui-elem-quote">
            暂无收货地址
            <a class="layui-btn layui-btn-small" href="<?=url('/member/address')?>">添加收货地址</a>
        </blockquote>
    <? else : ?>
        <? foreach ($addressList as $add) :
            $arr=explode('-',$add->region_name);
            ?>
            <div data_id="<?=$add->id?>" data_city="<?=$arr[1]?>" class="addr-cur <? if($add->id==$address->id){echo 'addselect';}?>">
                <div class="addrinner">
                    <h3><strong><?=$add->name?></strong> <?=$add->phone?></h3>
                    <p><?=$add->region_name?><br><?=$add->address?></p>
                </div>
            </div>
        <? endforeach;?>
        <div class="clearFix"></div>
        <a class="layui-btn layui-btn-small" href="<?=url('/member/address')?>">管理收货地址</a>
    <? endif;?>




    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
        <legend>确认订单信息</legend>
    </fieldset>
    <form method="post" id="form_order">
        <input type="hidden" name="address_id" value="<?=$address->id?>" id="address_id">
        <?  foreach ($result_carts as $i=>$carts) : ?>
            <div class="order_box clearFix">
                <div class="order_shopBar">
                    <?php
                    $shop=(new \App\Model\Shop())->find($i);
                    ?>
                    <i class="iconfont">&#xe854;</i><em><?=$shop->name?></em> <?=\App\Helper::getQqLink($shop->qq)?>
                </div>
                <div class="order_titleBar">
                    <ul>
                        <li class="cell1">商品信息</li>
                        <li class="cell2">单价（元）</li>
                        <li class="cell3">数量</li>
                        <li class="cell4">小计(元)</li>
                    </ul>
                </div>
                <? foreach($carts as $cart): ?>
                    <div class="goods_item clearFix">
                        <div class="cell1">
                            <img class="image" src="<?=$cart->goods_image?>">
                            <div class="oi_content">
                                <a href="<?=url("goods/detail/?id={$cart->goods_id}")?>"><?=$cart->goods_name?></a>
                                <p><?
                                    if($cart->spec_1!=''){
                                        echo "<span class='spec'>{$cart->spec_1}</span>";
                                    }
                                    if($cart->spec_2!=''){
                                        echo "<span class='spec'>{$cart->spec_2}</span>";
                                    }
                                    ?>
                                    <? if($cart->is_exist==true) : ?>

                                        <span class="count">剩余：<?=$cart->stock_count?></span>
                                    <? else :?>
                                        <span class="money">己失效,请重新添加</span>
                                    <? endif;?>

                                </p>
                            </div>
                        </div>
                        <div class="cell2">
                            <span class="money">¥<?=$cart->price?></span>
                        </div>
                        <div class="cell3">
                            <?=$cart->quantity?>
                        </div>
                        <div class="cell4 money" style="text-align: center">¥
                            <em class="price"><?=math($cart->price,$cart->quantity,'*',2)?></em>
                        </div>
                    </div>
                <? endforeach;?>
                <div class="order_foot clearFix">
                    <div class="span">备注留言：</div>
                    <textarea class="buyer_remark" name="buyer_remark" placeholder="订单备注,选填" rows="3"></textarea>

                    <div class="foot_money">
                        送费：<em>¥<span class="shop_shopping_fee" id="shop<?=$i?>_shipping_fee"></span></em><br>
                        小计：<em>¥<span class="shop_total" id="shop<?=$i?>_money" shop_id="<?=$i?>"></span></em>
                    </div>
                </div>
            </div>
        <? endforeach;?>

    </form>
    <div class="order_bottom">
        <div class="total">
            <strong id="totalPrice">共计：¥<span></span></strong>
            <a href="javascript:;" class="layui-btn layui-btn-danger btn">提交订单<em id="totalNum">(<span></span>件)</em></a>
        </div>
    </div>
</div>

<script>
    var cart_ids='<?=$cart_id?>';
    $(function () {
        order_js();
    })
</script>
<?php require 'footer.php';?>