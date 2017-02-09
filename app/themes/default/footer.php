<div class="footer">
        <div class="foottop">
            <div class="footlogo"> <a href="/"><img src="<?=$this->site->logo?>"></a> </div>
            <div class="foottext">


                <ul>
                    <li>
                        <h3><img src="/themes/images/nopic.gif" width="30" height="30">关于我们</h3>
                        <p><a href="http://www.xijia520.com/news/detail/10">合作企业</a></p>
                        <p><a href="http://www.xijia520.com/news/detail/9">门店效果</a></p>
                        <p ><a href="http://www.xijia520.com/news/detail/8">品牌优势</a></p>
                        <p ><a href="http://www.xijia520.com/news/detail/2">关于我们</a></p>
                    </li>
                </ul>

                <ul>
                    <li>
                        <h3><img src="/themes/images/nopic.gif" width="30" height="30">关于我们</h3>
                        <p><a href="http://www.xijia520.com/news/detail/10">合作企业</a></p>
                        <p><a href="http://www.xijia520.com/news/detail/9">门店效果</a></p>
                        <p ><a href="http://www.xijia520.com/news/detail/8">品牌优势</a></p>
                        <p ><a href="http://www.xijia520.com/news/detail/2">关于我们</a></p>
                    </li>
                </ul>

                <ul>
                    <li>
                        <h3><img src="/themes/images/nopic.gif" width="30" height="30">关于我们</h3>
                        <p><a href="http://www.xijia520.com/news/detail/10">合作企业</a></p>
                        <p ><a href="http://www.xijia520.com/news/detail/2">关于我们</a></p>
                    </li>
                </ul>

                <ul>
                    <li>
                        <h3><img src="/themes/images/nopic.gif" width="30" height="30">关于我们</h3>
                        <p><a href="http://www.xijia520.com/news/detail/10">合作企业</a></p>
                        <p ><a href="http://www.xijia520.com/news/detail/2">关于我们</a></p>
                    </li>
                </ul>

                <ul>
                    <li>
                        <h3><img src="/themes/images/nopic.gif" width="30" height="30">关于我们</h3>
                        <p><a href="http://www.xijia520.com/news/detail/10">合作企业</a></p>
                        <p><a href="http://www.xijia520.com/news/detail/9">门店效果</a></p>
                    </li>
                </ul>
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
            time: 2000
        });
        <?php } ?>
        echo.init();
    }
</script>
</body>
</html>