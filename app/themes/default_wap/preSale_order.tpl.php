<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="javascript:history.go(-1)"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r" href=""></a>
        <h1>我的预订</h1>
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
        $shop=(new \App\Model\Shop())->find($goods->user_id);
        ?>
        <div class="order_box">
            <a class="order_shopBar"><i class="iconfont">&#xe854;</i><em><?=$shop->name?></em></a>
            <div class="order_item clearFix">
                <img class="image" src="<?=\App\Helper::smallPic($goods->image_url)?>">
                <div class="oi_content">
                    <a href="<?=url("goods/detail/{$goods->goods_id}")?>"><?=$goods->goods_name?></a>
                    <p>
                        <?
                        if($goods->spec_1!=''){
                            echo "<span class='spec'>{$goods->spec_1}</span>";
                        }
                        if($goods->spec_2!=''){
                            echo "<span class='spec'>{$goods->spec_2}</span>";
                        }
                        ?>
                        <span class="count price">¥<?=$goods->price?> x<?=$order->quantity?></span></p>
                </div>
            </div>
            <div class="order_foot clearFix">
                <textarea name="buyer_remark" class="buyer_remark" placeholder="订单备注,选填" rows="2"></textarea>
            </div>
        </div>
    </form>
</div>
    <br><br><br>
    <div class="order_bottom">
        <div class="total">
            <p>预付定金：<strong id="totalPrice">¥<span><?=$order->pre_money?></span></strong></p>
            <a href="javascript:;" class="btn">提交订单<em id="totalNum">(<span></span>件)</em></a>
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