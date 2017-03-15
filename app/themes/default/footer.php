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