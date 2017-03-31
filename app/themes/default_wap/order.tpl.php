<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="javascript:history.go(-1)"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r" href=""></a>
        <h1>我的订单</h1>
    </div>
    <div class="order_address margin_header">
        <h4>收货地址</h4>
        <? if($address->is_exist) : ?>
            <a href="<?=url("/member/address/?redirect_url={$this->self_url}")?>">
                <p><?=$address->region_name?> <?=$address->address?></p>
                <p><strong><?=$address->name?></strong><?=$address->phone?></p>
            </a>
        <? else : ?>
            <div class="noadres">
                <a href="<?=url("/member/address/add/?redirect_url={$this->self_url}")?>" class="weui-btn weui-btn_plain-primary weui-btn_mini">添加收货地址</a>
            </div>
        <? endif;?>
    </div>

<form method="post" id="form_order">
    <input type="hidden" name="address_id" value="<?=$address->id?>" id="address_id">
    <?
    foreach ($result_carts as $i=>$carts) :
        $shop=(new \App\Model\Shop())->find($i);
        ?>
        <div class="order_box">
            <a class="order_shopBar"><i class="iconfont">&#xe854;</i><em><?=$shop->name?></em></a>
            <? foreach($carts as $cart): ?>
                <div class="order_item clearFix">
                    <img class="image" src="<?=$cart->goods_image?>">
                    <div class="oi_content">
                        <a href="<?=url("goods/detail/{$cart->goods_id}")?>"><?=$cart->goods_name?></a>
                        <p><?
                            if($cart->spec_1!=''){
                                echo "<span class='spec'>{$cart->spec_1}</span>";
                            }
                            if($cart->spec_2!=''){
                                echo "<span class='spec'>{$cart->spec_2}</span>";
                            }
                            ?>
                            <span class="count price">¥<?=$cart->price?> x<?=$cart->quantity?></span></p>
                    </div>
                </div>
            <? endforeach;?>
            <div class="order_foot clearFix">
                <textarea name="buyer_remark" class="buyer_remark" placeholder="订单备注,选填" rows="2"></textarea>
                <div class="foot_money">
                    运费：<em>¥<span class="shop_shopping_fee" id="shop<?=$i?>_shipping_fee"></span></em><br>
                    小计：<em>¥<span class="shop_total" id="shop<?=$i?>_money" shop_id="<?=$i?>"></span></em>
                </div>
            </div>
        </div>
    <? endforeach;?>
</form>
    <br><br><br>

    <div class="order_bottom">
        <div class="total">
            <p>总计：<strong id="totalPrice">¥<span></span></strong></p>
            <a href="javascript:;" class="btn">提交订单<em id="totalNum">(<span></span>件)</em></a>
        </div>
    </div>
<script>
    <?
    $arr=explode('-',$address->region_name);
    ?>
    var cityName='<?=$arr[1]?>';
    var cart_ids='<?=$cart_id?>';
    $(function () {
        order_js();
    })
</script>
<?php require 'footer.php';?>