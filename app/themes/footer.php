<script src="/plugin/layui.v2/layui.all.js"></script>
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
    }
</script>
<?=app('system')->getCode('tongji');?>
</body>
</html>