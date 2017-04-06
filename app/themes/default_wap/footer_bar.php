<div class="weui-tabbar" style="position: fixed">
    <a href="/" class="weui-tabbar__item <? if($this->control=='index'){echo 'weui-bar__item_on';}?>">
        <i class="iconfont weui-tabbar__icon">&#xe64f;</i>
        <p class="weui-tabbar__label">首页</p>
    </a>
    <a href="<?=url('goods/lists')?>" class="weui-tabbar__item <? if($this->func=='lists'){echo 'weui-bar__item_on';}?>">
        <i class="iconfont weui-tabbar__icon">&#xe600;</i>
        <p class="weui-tabbar__label">商品列表</p>
    </a>
    <a href="<?=url('/cart')?>" class="weui-tabbar__item <? if($this->control=='cart'){echo 'weui-bar__item_on';}?>">
        <i class="iconfont weui-tabbar__icon" style="position: relative">&#xe698;
            <span class="weui-badge" id="cart_num"></span></i>
        <p class="weui-tabbar__label">购物车</p>
    </a>
    <a href="<?=url('member')?>" class="weui-tabbar__item">
        <i class="iconfont weui-tabbar__icon">&#xe6fc;</i>
        <p class="weui-tabbar__label">我</p>
    </a>
</div>

