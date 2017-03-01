<?php require 'header_top.php';?>
<style>
    .item { margin-bottom: 2px;    }
</style>
<? if($this->func=='editMoney') : ?>
    <form method="post" class="layui-form">
        <div class="layui-field-box">
            <div class="layui-form-item item">
                <label class="layui-form-label">订单号</label>
                <div class="layui-form-mid layui-word-aux"><?=$order->order_sn?></div>
            </div>
            <div class="layui-form-item item">
                <label class="layui-form-label">商品价格</label>
                <div class="layui-form-mid layui-word-aux">¥<?=$order->goods_money?></div>
            </div>
            <div class="layui-form-item item">
                <label class="layui-form-label">物流费用</label>
                <div class="layui-form-mid layui-word-aux">¥<?=$order->shipping_fee?></div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">订单总金额</label>
                <div class="layui-input-inline">
                    <input class="layui-input" required type="text" name="money" onkeyup="value=value.replace(/[^0-9.]/g,'')" value="<?=$order->order_money?>"  placeholder="¥"/>
                </div>
                <div class="layui-form-mid layui-word-aux">元</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"></label>
                <input class="layui-btn" type="submit" value="保存">
                <input class="layui-btn" type="button" value="取消" onclick="close1()">
            </div>
        </div>
    </form>
<? elseif ($this->func=='editShipping') : ?>
    <form method="post" class="layui-form">
        <div class="layui-field-box">
            <div class="layui-form-item item">
                <label class="layui-form-label">订单号</label>
                <div class="layui-form-mid layui-word-aux"><?=$order->order_sn?></div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">快递名称</label>
                <div class="layui-input-inline">
                    <input class="layui-input" required type="text" name="money" value=""  placeholder="请输入快递名称"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">快递单号</label>
                <div class="layui-input-inline">
                    <input class="layui-input" required type="text" name="shipping_no" value=""  placeholder="请输入快递单号"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"></label>
                <input class="layui-btn" type="submit" value="保存">
                <input class="layui-btn" type="button" value="取消" onclick="close1()">
            </div>
        </div>
    </form>
<? endif;?>
    <script>
        function close1() {
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        }
    </script>
<?php require 'footer.php';?>