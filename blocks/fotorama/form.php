<?php
    defined('C5_EXECUTE') or die('Access Denied.');
?>
<style>
    .tab-content {
        margin-top: 10px;
    }
    .help-block {
        font-size: 12px;
        font-style: italic;
    }
    #advanced table td {
        vertical-align: middle;
    }
    #advanced table td .form-group,
    #advanced table td label {
        margin-bottom: 0;
    }
</style>
<div id="fotoramaEditor" role="tabpanel">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab"><?php echo t('General'); ?></a></li>
        <li role="presentation"><a href="#appearance" aria-controls="appearance" role="tab" data-toggle="tab"><?php echo t('Appearance'); ?></a></li>
        <li role="presentation"><a href="#advanced" aria-controls="advanced" role="tab" data-toggle="tab"><?php echo t('Advanced'); ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <?php $this->inc('form-general.php'); ?>
        <?php $this->inc('form-appearance.php'); ?>
        <?php $this->inc('form-advanced.php'); ?>
    </div>
</div>
    
<script>
    $(function () {
        setTimeout(function () {
            $('#fotoramaEditor input[type="checkbox"]').each(function () {
                var init = new Switchery(this, { size: 'small' });
            });
        }, 1000);
    });
</script>