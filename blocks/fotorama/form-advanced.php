<?php
defined('C5_EXECUTE') or die('Access Denied.');
/**
 * Advanced Form Tab
 *
 * @package  FotoramaPackage
 * @author   Oliver Green <green2go@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     http://codeblog.co.uk
 */
?>
<div role="tabpanel" class="tab-pane" id="advanced">

    <h3>Advanced Options</h3>
    <table class="table table-striped">
        <tr>
            <td><?php echo $form->label('transitionduration', t('Transition duration'))?></td>
            <td class="col-xs-4">
            <div class="form-group">
                    <div class="input-group">
                        <?php echo $form->text('transitionduration', $transitionduration); ?>
                        <span class="input-group-addon"><?php echo t('seconds'); ?></span>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->label('allow_fullscreen', t('Show full screen toggle'))?></td>
            <td><?php echo $form->checkbox('allow_fullscreen', '1', $allow_fullscreen); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->label('lazy_loading', t('Lazy load images'))?></td>
            <td><?php echo $form->checkbox('lazy_loading', '1', $lazy_loading); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->label('hash_nav', t('Allow hash based URLs'))?></td>
            <td><?php echo $form->checkbox('hash_nav', '1', $hash_nav); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->label('loop_images', t('Loop image carousel'))?></td>
            <td><?php echo $form->checkbox('loop_images', '1', $loop_images); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->label('keyboard', t('Allow keyboard navigation'))?></td>
            <td><?php echo $form->checkbox('keyboard', '1', $keyboard); ?></td>
        </tr>  
        <tr>
            <td><?php echo $form->label('click', t('Allow click navigation'))?></td>
            <td><?php echo $form->checkbox('click', '1', $click); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->label('swipe', t('Allow swipe navigation'))?></td>
            <td><?php echo $form->checkbox('swipe', '1', $swipe); ?></td>
        </tr>
    </table>
    
</div>
