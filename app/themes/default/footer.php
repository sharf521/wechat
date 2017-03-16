<div class="footer">
        <div class="foottop">
            <div class="footlogo"> <a href="/"><img src="<?=$this->site->logo?>"></a> </div>
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
            Copyright 2012-2020 Inc.,All rights reserved. 豫ICP备110xxxx2号-1
        </div>
</div>
<div class="floating_ck">
    <dl>
        <dd>
            <a href="/cart">
                <i class="iconfont">&#xe698;</i>
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
<script src="/plugin/js/echo.min.js"></script>
<script>
    window.onload=function(){
        <?php if (session('msg')){?>
        layer.msg('<?= addslashes(session('msg')) ?>', {
            offset: '200px',
            icon: 1,
            time: 1000
        });
        <? }
        if (session('error')){?>
        layer.msg('<?= addslashes(session('error')) ?>', {
            offset: '200px',
            icon: 2,
            time: 5000
        });
        <?php } ?>
        echo.init();
    }
</script>
</body>
</html>