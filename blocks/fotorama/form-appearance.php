<?php
defined('C5_EXECUTE') or die('Access Denied.');
/**
 * Appearance Form Tab
 *
 * @package  FotoramaPackage
 * @author   Oliver Green <green2go@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     http://codeblog.co.uk
 */
?>
<div role="tabpanel" class="tab-pane" id="appearance">

    <!-- General Appearance !-->
    <div class="form-group">
        <h3>General Appearance</h3>
        <hr>
        <div class="row">
            <div class="col-xs-3">
                <?php echo $form->label('width', t('Width')); ?>
                <?php echo $form->text('width', $width); ?>
            </div>
            <div class="col-xs-3">
                <?php echo $form->label('height', t('Height')); ?>
                <?php echo $form->text('height', $height, array('placeholder' => 'auto')); ?>
            </div>
            <div class="col-xs-6">
                <?php echo $form->label('fit', t('Image Scaling')); ?>
                <?php echo $form->select('fit', array('contain' => 'Contain', 'cover' => 'Cover', 'scaledown' => 'Scaledown', 'none' => 'None'), $fit); ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->label('transition', t('Transition Style')); ?>
        <?php echo $form->select('transition', array('slide' => 'Slide', 'crossfade' => 'Crossfade', 'dissolve' => 'Dissolve'), $transition); ?>
    </div>
    <div class="form-group">
        <?php echo $form->label('captions', t('Show Captions')); ?>&nbsp;&nbsp;
        <?php echo $form->checkbox('captions', '1', $captions); ?>
        <p class="help-block">Captions are set by editing the file description within file manager.</p>
    </div>

    <!-- Navigation Style !-->
    <div class="form-group">
        <h3>Navigation Style</h3>
        <hr>
        <div class="row">
            <div class="col-xs-6">
                <?php echo $form->label('nav', t('Navigation Display')); ?>
                <?php echo $form->select('nav', array('false' => 'Hidden', 'dots' => 'Dots', 'thumbs' => 'Thumbnails'), $nav); ?>
                <script>
                $(function () {
                    $('.form-group select[name="nav"]').change(function () {
                        var state = 'block';
                        if ('false' === $(this).val()) {
                            state = 'none';
                        }
                        $('.form-group select[name="nav_position"]').parent().css({ display: state });
                    }).trigger('change');
                });
                </script>
            </div>
            <div class="col-xs-6">
                <?php echo $form->label('nav_position', t('Navigation Display')); ?>
                <?php echo $form->select('nav_position', array('top' => 'Top', 'bottom' => 'Bottom'), $nav_position); ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->label('arrows', t('Show Navigation Arrows')); ?>&nbsp;&nbsp;
        <?php echo $form->checkbox('arrows', '1', $arrows); ?>
    </div>

</div>