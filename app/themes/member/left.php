<div class="warpleft">
    <h3>会员中心</h3>
    <ul>
        <li><a href="<?=url('/member/address')?>"  <? if($this->control=='address'){echo 'class="whover"';}?>>地址管理</a></li>
        <li><a href="<?=url('/member/invite')?>"  <? if($this->control=='invite'){echo 'class="whover"';}?>>邀请链接</a></li>
        <li><a href="<?=url('/member/order')?>"  <? if(strpos($_SERVER['PHP_SELF'],'/member/order')!==false){echo 'class="whover"';}?>>我的订单</a></li>
        <li><a href="<?=url('/member/notice')?>"  <? if($this->control=='notice'){echo 'class="whover"';}?>>我的消息</a></li>
    </ul>
    <? if($this->user->is_shop==0) : ?>
        <a class="layui-btn" href="<?=url('/member/shop')?>">申请开店</a>
    <? else: ?>
        <h3>我是卖家</h3>
        <ul>
            <li><a href="<?=url('/sellManage/shop')?>"  <? if($this->control=='shop'){echo 'class="whover"';}?>>店铺设置</a></li>
            <li><a href="<?=url('/sellManage/category')?>"  <? if($this->control=='category'){echo 'class="whover"';}?>>分类管理</a></li>
            <li><a href="<?=url('/sellManage/shipping')?>"  <? if($this->control=='shipping'){echo 'class="whover"';}?>>配送方式管理</a></li>
            <li><a href="<?=url('/sellManage/goods')?>"  <? if($this->control=='goods'){echo 'class="whover"';}?>>商品管理</a></li>
            <li><a href="<?=url('/sellManage/order')?>"  <? if(strpos($_SERVER['PHP_SELF'],'/sellManage/order')!==false){echo 'class="whover"';}?>>订单管理</a></li>
<!--            <li><a href="<?/*=url('/sellManage/supply')*/?>"  <?/* if($this->control=='supply'){echo 'class="whover"';}*/?>>我要采购</a></li>-->
        </ul>
    <? endif;?>
</div>