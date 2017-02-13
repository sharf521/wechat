<?php require 'header.php';?>
    <div class="layui-main wrapper">
        <div class="layui-tab layui-tab-brief">
            <ul class="layui-tab-title">
                <li class="layui-this">我的购物车</li>
            </ul>
        </div>
        <? if(count($result_carts)==0) : ?>
            <div class="cart_empty">
                购物车内还没有商品！<br>
                <a href="<?=url('goods/lists')?>" class="weui-btn weui-btn_plain-primary weui-btn_mini">去逛逛</a>
            </div>
        <? else : ?>
            <div class="cart_list">
                <div class="cart_title clearFix">
                    <div class="cell1">选择</div>
                    <div class="cell2">商品信息</div>
                    <div class="cell3">数量</div>
                    <div class="cell4">金额</div>
                    <div class="cell5">操作</div>
                </div>
                <?  foreach ($result_carts as $i=>$carts) : ?>
                    <div class="cart_box clearFix">
                        <a class="shopBar"><i class="iconfont">&#xe854;</i><em>我的小店<?=$cart->seller_id?></em></a>
                        <? foreach($carts as $cart): ?>
                            <div class="goods_item clearFix">
                                <input class="checkbox"  type="checkbox" checked name="cart_id[]" value="<?=$cart->id?>">
                                <div class="cell2">
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
                                            <span class="count money">¥<?=$cart->price?></span></p>
                                    </div>
                                </div>
                                <div class="cell3">
                                    <div class="wrap-input" style="margin-left: 80px;">
                                        <span class="btn-reduce">-</span>
                                        <input class="text" value="<?=$cart->quantity?>"  maxlength="5" type="text" name="quantity" onkeyup="value=value.replace(/[^0-9]/g,'')">
                                        <span class="btn-add">+</span>
                                    </div>
                                </div>
                                <div class="cell4 money" style="text-align: center">¥
                                    <em class="price"><?=math($cart->price,$cart->quantity,'*',2)?></em>
                                </div>
                                <i class="del layui-btn layui-btn-danger layui-btn-small" data-id="<?=$cart->id?>">移除</i>
                            </div>
                        <? endforeach;?>
                        <div class="cart_foot">小计：<em>¥<span class="shop_total" shop_id="<?=$i?>"></span></em></div>
                    </div>
                <? endforeach;?>
                <br><br>
                <div class="cart_bottom">
                    <label><input type="checkbox" class="checkall" checked><br>全选</label>
                    <div class="total">
                        <p>总计：<strong id="totalPrice">¥<span></span></strong><small>(不含运费)</small></p>
                        <a href="javascript:;" class="btn_pay">去结算<em id="totalNum">(<span></span>件)</em></a>
                    </div>
                </div>
            </div>
        <? endif;?>
    </div>





<script>
    $(function () {
        cart_js();
    });
</script>
<?php require 'footer.php';?>