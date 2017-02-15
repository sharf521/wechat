<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="javascript:history.go(-1)"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r"></a>
        <h1>产品详情</h1>
    </div>

    <div class="clearFix margin_header">

        <div class="car-wrapper clearFix">
            <div class="car-photo"><img src="<?=$product->picture?>">
            </div>
            <div class="car-info">
                <div class="car-name"><?=$product->name?></div>
                <div class="car-price">厂商指导价：<?=$product->price?>万</div>
            </div>
        </div>
        <div class="lease">
            <div class="product">
                <? foreach ($product->specs as $spec) : ?>
                    <? if($spec->time_limit!=0) : ?>
                        <label data_id="<?=$spec->id?>">
                            <span><strong><?=$spec->time_limit?>期</strong></span>
                            <span>首付: <?=$spec->first_payment/10000	?>万元</span>
                            <span>月租: <?=$spec->month_payment?>元</span>
                            <span>尾付: <?=(float)$spec->last_payment?>元</span>
                        </label>
                    <? endif;?>
                <? endforeach;?>
            </div>
        </div>
        <div class="product_detail">
            <div class="detail_nav">详情介绍</div>
            <div class="detail_content">
                <?=$product->content?>
            </div>
        </div>
    </div>
<form>
    <input type="hidden" name="spec_id" id="spec_id" value="0">
    <input type="submit" value="wkdasdfa">
</form>
<script>
    product_detail();
</script>
<?php require 'footer.php';?>