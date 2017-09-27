<?php require 'header.php';?>
<div class="layui-main wrapper">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>选择收货地址</legend>
    </fieldset>
    <? if(empty($addressList)) : ?>
        <blockquote class="layui-elem-quote">
            暂无收货地址
            <a class="layui-btn layui-btn-small" href="<?=url("/member/address/?redirect_url={$this->self_url}")?>">添加收货地址</a>
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
        <div class="order_box clearFix">
            <div class="order_shopBar">
                <?php
                $shop=(new \App\Model\Shop())->find($goods->user_id);
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
            <div class="goods_item clearFix">
                <div class="cell1">
                    <img class="image" src="<?=\App\Helper::smallPic($goods->image_url)?>">
                    <div class="oi_content">
                        <a href="<?=url("goods/detail/{$goods->goods_id}")?>"><?=$goods->goods_name?></a>
                        <p><?
                            if($goods->spec_1!=''){
                                echo "<span class='spec'>{$goods->spec_1}</span>";
                            }
                            if($goods->spec_2!=''){
                                echo "<span class='spec'>{$goods->spec_2}</span>";
                            }
                            ?>
                            <? if($goods->is_exist==true) : ?>

                                <span class="count">剩余：<?=$goods->stock_count?></span>
                            <? else :?>
                                <span class="money">己失效,请重新添加</span>
                            <? endif;?>

                        </p>
                    </div>
                </div>
                <div class="cell2">
                    <span class="money">¥<?=$goods->price?></span>
                </div>
                <div class="cell3">
                    <?=$order->quantity?>
                </div>
                <div class="cell4 money" style="text-align: center">¥
                    <em class="price"><?=$order->order_money?></em>
                </div>
            </div>
            <div class="order_foot clearFix">
                <div class="span">备注留言：</div>
                <textarea class="buyer_remark" name="buyer_remark" placeholder="订单备注,选填" rows="3"></textarea>
            </div>
        </div>

    </form>
    <div class="order_bottom">
        <div class="total">
            <strong id="totalPrice">预付定金：¥<span><?=$order->pre_money?></span></strong>
            <a href="javascript:;" class="layui-btn layui-btn-danger btn">提交订单</a>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('.addr-cur').on('click',function () {
            var aId=$(this).attr('data_id');
            $('#address_id').val(aId);
            $(this).addClass('addselect').siblings().removeClass('addselect');
        });
        $('.order_bottom .btn').on('click',function () {
            if($('#address_id').val()==''){
                layer.open({
                    content: '收货地址不能为空！',
                    skin: 'msg'
                });
            }else{
                $('#form_order').submit();
            }
        });
    })
</script>
<?php require 'footer.php';?>