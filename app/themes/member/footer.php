<div class="clearFix"></div>
<div class="footbot">
    Copyright 2012-2020 Inc.,All rights reserved. 豫ICP备110xxxx2号-1
</div>
<script src="/plugin/js/echo.min.js"></script>
<script>
    window.onload=function(){
        <?php if (session('msg')){?>
        layer.msg('<?= addslashes(session('msg')) ?>', {
            offset: '200px',
            icon: 1,
            time: 2000
        });
        <? }
        if (session('error')){?>
        layer.msg('<?= addslashes(session('error')) ?>', {
            offset: '200px',
            icon: 2,
            time: 3000
        });
        <?php } ?>
        echo.init();
    }
</script>
</body>
</html>