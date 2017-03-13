<?php require 'header.php';?>
    <div class="m_header">
        <a class="m_header_l" href="javascript:history.go(-1)"><i class="iconfont">&#xe604;</i></a>
        <a class="m_header_r"></a>
        <h1><?=$this->title?></h1>
    </div>

    <div class="clearFix margin_header">
        <div class="m_regtilinde">品牌列表<span><a href="<?=url('brand')?>"></a></span></div>
        <div class="br_box clearFix">
            <ul class="clearFix">
                <? foreach ($brands as $brand) : ?>
                    <li><a href="<?=url("product/lists/?brand_name={$brand->name}")?>"><div><img src="<?=$brand->picture?>" /></div><span><?=$brand->name?></span></a></li>
                <? endforeach;?>
            </ul>
        </div>
    </div>
<?php require 'footer.php';?>