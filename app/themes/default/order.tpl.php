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
        <? foreach ($addressList as $add) : ?>
            <div data_id="<?=$add->id?>" class="addr-cur <? if($add->id==$address->id){echo 'addselect';}?>">
                <div class="addrinner">
                    <h3><strong><?=$address->name?></strong> <?=$address->phone?></h3>
                    <p><?=$address->region_name?><br><?=$address->address?></p>
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
        <?  foreach ($result_carts as $i=>$carts) : ?>
            <div class="order_box clearFix">
                <a class="order_shopBar"><i class="iconfont">&#xe854;</i><em>我的小店<?=$i?></em></a>
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
                <div class="remark clearFix">
                    <div class="span">备注留言：</div>
                    <textarea name="buyer_remark" placeholder="订单备注,选填" rows="3"></textarea>

                    <div class="order_foot">小计：<em>¥<span class="shop_total" shop_id="<?=$i?>"></span></em></div>
                </div>


            </div>
        <? endforeach;?>

    </form><br><br><br>

    <div class="order_bottom">
        <div class="total">
            <p>总计：<strong id="totalPrice">¥<span></span></strong><small>(不含运费)</small></p>
            <a href="javascript:;" class="btn">提交订单<em id="totalNum">(<span></span>件)</em></a>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('.addr-cur').on('click',function () {
            var aId=$(this).attr('data_id');
            alert(aId);
           $(this).addClass('addselect').siblings().removeClass('addselect');
        });
        order_js('<?=$cart_id?>');
    })
</script>
<?php require 'footer.php';?>