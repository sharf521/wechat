<div class="footer">
        <div class="foottop">
            <div class="footlogo"> <a href="/"><img src="<?=$this->site->logo?>" width="220"></a> </div>
            <div class="foottext">
                <?
                $articleModel=(new \App\Model\Article());
                foreach ($this->site->articleCates as $cate) :
                    $pic=$cate->picture;
                    if(empty($pic)){
                        $pic='/themes/images/nopic.gif';
                    }
                    ?>
                    <ul>
                        <li>
                            <h3><img src="<?=$pic?>" width="30" height="30"><?=$cate->name?></h3>
                            <?
                            $aList=$articleModel->where("status=1 and category_id={$cate->id}")->orderBy('id desc')->limit('0,5')->get();
                            foreach ($aList as $art) :
                            ?>
                                <p><a href="<?=url("article/detail/{$art->id}")?>"><?=$art->title?></a></p>
                                <? endforeach;?>
                        </li>
                    </ul>
                <? endforeach;?>
            </div>
        </div>
        <div class="footbot">
            <?=\App\Helper::getSystemParam('icp');?>
        </div>
</div>
<div class="floating_ck">
    <dl>
        <dd>
            <a href="/cart">
                <i class="iconfont" id="icon_cart">&#xe698;</i>
                <div class="floating_left">购物车</div>
                <em class="cart_tip" id="cart_num">0</em>
            </a>
        </dd>
        <dd>
            <a href="/member">
                <i class="iconfont">&#xe6fc;</i>
                <div class="floating_left">个人中心</div>
            </a>
        </dd>
        <dd>
            <i class="layui-icon" style="font-size: 32px;" onclick="gotoTop()">&#xe604;</i>
        </dd>
    </dl>
</div>
<script src="/plugin/layui.v2/layui.all.js"></script>
<script src="/themes/default/default.js"></script>
<?php
require __DIR__.'/../footer.php';