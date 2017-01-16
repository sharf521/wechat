<?php require 'header.php';?>
<?
echo $this->control;
echo $this->func;
?>

    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="<?=url('logout')?>">
            安全退出
        </a>
    </div>

<?php require 'footer.php';?>