<?php require 'header.php';?>
    <div class="layui-main wrapper" style="padding: 20px 0px;">
        <div class="articleLeft">
            <?
            $articleModel=(new \App\Model\Article());
            foreach ($this->site->articleCates as $cate) :
            ?>
            <dl>
                <dt><?=$cate->name?></dt>
                <dd>
                    <ul>
                        <?
                        $aList=$articleModel->where("status=1 and category_id={$cate->id}")->orderBy('id desc')->limit('0,5')->get();
                        foreach ($aList as $art) :
                            ?>
                            <li><a class="<?php if($article->id==$art->id){echo 'active';}?>"  href="<?=url("article/detail/{$art->id}")?>"><?=$art->title?></a></li>
                        <? endforeach;?>
                    </ul>
                </dd>
            </dl>
            <? endforeach;?>
        </div>
        <div class="articleRight">
            <h2><?=$article->title?></h2>
            <div class="articleContent">
                <?=$article->content?>
            </div>
        </div>
    </div>


<?php require 'footer.php';?>