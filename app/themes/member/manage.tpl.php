<?php require 'header.php';?>

<div class="warpcon">
    <?php require 'left.php'; ?>
    <div class="warpright">
        <div class="jiben">
            <div class="jbtx">
                <div class="touxiang">
                    <img src="<?= $this->user->headimgurl; ?>">
                </div>
                <div class="toutext">
                    <h2><?= $this->username ?></h2>
                    <p><?= $this->user->name ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php';?>
