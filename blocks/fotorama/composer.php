<?php
    defined('C5_EXECUTE') or die('Access Denied.'); ?>
<div class="control-group composer-control-group">
    <label class="control-label"><?php echo $label?></label>
    <?php $this->inc('form-general.php', array('view' => $view, 'control' => $control)); ?>
<?php 
    /* Hidden composer controls that retain defaults from checkbox values */
    foreach ($defaults as $k => $v) {
        echo $form->hidden($view->field($k), $v);
    }
?>
</div>