<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="javascript:history.go(-1)"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r" href="/member/?st_uid=<?=$this->st_uid?>"><i class="iconfont">&#xe6fc;</i></a>
        <h1>我的购物车</h1>
    </div>
    <div class="margin_header"></div>
    <?
    if(count($result_carts)==0) : ?>
        <div class="cart_empty">
            <div class="cart-logo">
                <i class="weui-icon-warn weui-icon_msg-primary"></i>
            </div>
            <div class="cart-text">
                你的购物车内还没有商品
            </div>
            <?
            if($this->st_uid==0){
                $goodsList_url=url('goods/lists');
            }else{
                $goodsList_url=$this->store_url.'/goods/lists/';
            }
            ?>
            <a href="<?=$goodsList_url?>" class="cart-btn">立即逛逛</a>
        </div>

    <?php require 'footer_bar.php';?>
    <? else : ?>
    <div style="margin-bottom: 60px">
        <?  foreach ($result_carts as $i=>$carts) :
            $shop=(new \App\Model\Shop())->find($i);
            ?>
            <div class="cart_box">
                <a class="shopBar"><i class="iconfont">&#xe854;</i><em><?=$shop->name?></em></a>
                <? foreach($carts as $cart): ?>
                    <div class="goods_item clearFix">
                        <input class="checkbox"  type="checkbox" <?=($cart->is_exist==false)? 'disabled':'checked'?> name="cart_id[]" value="<?=$cart->id?>">
                        <a href="<?=url("goods/detail/{$cart->goods_id}/?st_uid={$this->st_uid}")?>">
                            <img class="image" src="<?=\App\Helper::smallPic($cart->goods_image)?>">
                            <div class="oi_content" style="float: left">
                                <?=$cart->goods_name?>
                                <p><?
                                    if($cart->spec_1!=''){
                                        echo "<span class='spec'>{$cart->spec_1}</span>";
                                    }
                                    if($cart->spec_2!=''){
                                        echo "<span class='spec'>{$cart->spec_2}</span>";
                                    }
                                    ?>

                                    <? if($cart->is_exist==true) : ?>
                                        <span class="count money">¥<?=$cart->price?></span>
                                        <span class="count">剩余：<?=$cart->stock_count?></span>
                                    <? else :?>
                                        <span class="money">己失效,请重新添加</span>
                                    <? endif;?>
                                </p>
                            </div></a>
                        <div class="wrap-input">
                            <span class="btn-reduce">-</span>
                            <input class="text" value="<?=$cart->quantity?>" readonly  maxlength="5" type="text" name="quantity">
                            <span class="btn-add">+</span>
                        </div>
                        <i class="iconfont del" data-id="<?=$cart->id?>">&#xe69d;</i>
                    </div>
                <? endforeach;?>
                <div class="cart_foot">小计：<em>¥<span class="shop_total" shop_id="<?=$i?>"></span></em></div>
            </div>
        <? endforeach;?>
        <div class="cart_bottom">
            <label><input type="checkbox" class="checkall"><br>全选</label>
            <div class="total">
                <p>总计：<strong id="totalPrice">¥<span></span></strong><small>(不含运费)</small></p>
                <a href="javascript:;" class="btn_pay">去结算<em id="totalNum">(<span></span>件)</em></a>
            </div>
        </div>
    </div>
    <? endif;?>
<script>
    $(function () {
        cart_js();
    });
</script>
<?php require 'footer.php';?>