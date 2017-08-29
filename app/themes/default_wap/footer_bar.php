<br><br>
<div class="weui-tabbar" style="position: fixed">
    <a href="<?=$this->home_url?>" class="weui-tabbar__item <? if($this->control=='index'){echo 'weui-bar__item_on';}?>">
        <i class="iconfont weui-tabbar__icon">&#xe64f;</i>
        <p class="weui-tabbar__label">首页</p>
    </a>
    <? if($this->st_uid==0) : ?>
        <a href="/category" class="weui-tabbar__item <? if($this->control=='category'){echo 'weui-bar__item_on';}?>">
            <i class="iconfont weui-tabbar__icon">&#xe600;</i>
            <p class="weui-tabbar__label">分类</p>
        </a>
    <? else : ?>
        <a href="<? if($this->st_uid!=0){echo $this->home_url;}?>/goods/lists/" class="weui-tabbar__item <? if($this->func=='lists'){echo 'weui-bar__item_on';}?>">
            <i class="iconfont weui-tabbar__icon">&#xe600;</i>
            <p class="weui-tabbar__label">商品列表</p>
        </a>
    <? endif;?>
    <a href="<?="/cart/?st_uid={$this->st_uid}"?>" class="weui-tabbar__item <? if($this->control=='cart'){echo 'weui-bar__item_on';}?>">
        <i class="iconfont weui-tabbar__icon" style="position: relative">&#xe698;
            <span class="weui-badge" id="cart_num"></span></i>
        <p class="weui-tabbar__label">购物车</p>
    </a>
    <a href="<?=url("member/?st_uid={$this->st_uid}")?>" class="weui-tabbar__item">
        <i class="iconfont weui-tabbar__icon">&#xe6fc;</i>
        <p class="weui-tabbar__label">我</p>
    </a>
</div>

