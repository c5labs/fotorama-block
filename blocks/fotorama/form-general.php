<?php
defined('C5_EXECUTE') or die('Access Denied.');
/**
 * General Form Tab
 *
 * @package  FotoramaPackage
 * @author   Oliver Green <green2go@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL3
 * @link     http://codeblog.co.uk
 */
?>
<style>
    #files ul {
        padding: 0;
        margin: 0;
    }

    .img-holder {
        display: inline-block;
        background-color: #ddd;
        background-size: cover;
        background-position: center;
        margin-right: 10px;
        width: 76px;
        height: 80px;
        cursor: move;
        position: relative;
    }

    #addImageBtn {
        cursor: pointer;
    }

    #addImageBtn:before {

        font-family: 'FontAwesome';
        font-size: 3em;
        color: #fff;

        padding: 10px 20px;
        display: block;

        content: '\f0fe';
        position: absolute;
    }

    .img-holder .remove-btn:after {

        font-family: 'FontAwesome';
        font-size: 1em;
        color: #fff;
        text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.6);
        display: block;
        background: #000;
        border-radius: 2.5em;
        width: 1.5em;
        height: 1.5em;
        padding: 3px 0 0 5px;

        content: '\f00d';
        cursor: pointer;
        position: absolute;
        top: -8px;
        right: -8px;
        line-height: normal;
    }

    span.alert {
        display: block;
        margin-top: 15px;
    }
</style>
<div role="tabpanel" class="tab-pane active" id="general">

        <?php if (!isset($control)) { ?>
        <h3>Image Selection</h3>
        <hr>
        <?php } ?>

        <div class="form-group">
            <?php echo $form->label('image_source', t('Show images from')); ?>
            <?php echo $form->select(
                $view->field('image_source'),
                $image_sources,
                $image_source,
                array('style' => 'border: 1px solid #ccc')
            ); ?>

        </div>
        <div class="form-group" id="fileSets">
            <?php echo $form->label('selected_file_set_ids', t('File Set(s)')); ?>
            <?php echo $form->selectMultiple(
                $view->field('selected_file_set_ids'),
                $available_file_sets,
                (is_array($selected_file_set_ids))  ? $selected_file_set_ids : null,
                array('style' => 'border: 1px solid #ccc')
            ); ?>
            <script>
                $(function () {
                    $('select[name="<?php echo $view->field("selected_file_set_ids"); ?>[]"]').select2();
                });
            </script>
        </div>

        <div class="form-group" id="files">
            <?php echo $form->label('img', t('Selected Files')); ?>
            <ul>
                <li id="addImageBtn" class="img-holder"></li>
            </ul>
            <span class="alert alert-info">
                <strong>Ordering:</strong> You can sort the images by dragging them into the correct order.
            </span>
        </div>

    <?php if (!isset($control)) { ?>

    <!-- Autoplay !-->
    <div class="form-group">
        <h3>Autoplay</h3>
        <hr>
        <div class="form-group">
            <?php echo $form->label('autoplay_toggle', t('Autoplay carousel'))?>&nbsp;&nbsp;
            <?php echo $form->checkbox('autoplay_toggle', '1', ($autoplay > 0) ? '1' : '0'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('autoplay', t('Delay'))?>
            <div class="input-group col-xs-4">
                <?php echo $form->text('autoplay', ($autoplay > 0) ? $autoplay : ''); ?>
                <span class="input-group-addon"><?php echo t('seconds'); ?></span>
            </div>
        </div>
    </div>

    <?php } else { 
        /* Hidden composer controls that retain defaults from checkbox values */
        foreach ($defaults as $k => $v) {
            echo $form->hidden($view->field($k), $v);
        }
     } ?>

</div>

<script>
    $(function () {

        // Show or hide autoplay input
        $('input[name="autoplay_toggle"]').change(function () {
            var $ele = $('input[name="autoplay"]'),
                state = 'block';
            if (!$(this).prop('checked')) {
                $ele.val('');
                state = 'none';
            }
            $ele.parent().parent().css({ display: state });
        }).trigger('change');

        // Show or hide fileset / files selector
        $('select[name="<?php echo $view->field("image_source"); ?>"]').change(function () {
            var val = $(this).val();
            $('#fileSets, #files').css({ display: 'none' });

            if ('files' === val) {
                $('#files').css({ display: 'block' });
            } else {
                $('#fileSets').css({ display: 'block' });
            }
        }).trigger('change');

        // Make things sortable
        $( "#files ul" ).sortable({
            containment: "parent",
        });
        $( "#files ul" ).disableSelection();

        // Wire the add image button
        $('#addImageBtn').click(function () {
            ConcreteFileManager.launchDialog(function (data) {
                    ConcreteFileManager.getFileDetails(data.fID, function(r) {
                        jQuery.fn.dialog.hideLoader();
                        if ('Image' === r.files[0].genericTypeText) {
                            attachImage(r.files[0]);
                        } else {
                            alert('The file you selected was not an image file.');
                        }
                    });
                });
        });

        // Attach an image
        function attachImage(file)
        {
            var controlName = '<?php echo $view->field("img"); ?>[]',
            $ele = $('<li></li>').addClass('img-holder').css({ 'background-image': 'url(' + file.url + ')' }),
            $input = $('<input></input>').prop('type', 'hidden').prop('name', controlName).prop('id', controlName).val(file.fID);
            $removeBtn = $('<span></span>').addClass('remove-btn');
            $ele.append($removeBtn, $input);

            $('#addImageBtn').before($ele);
            $removeBtn.click(function () {
                $ele.remove(); 
            });
        }

        // Attach existing images on load
        <?php foreach ($selected_images as $file) { ?>
            attachImage({ fID: '<?php echo $file->getFileID(); ?>', url: '<?php echo $file->getRecentVersion()->getUrl(); ?>' });
        <?php } ?>
    });
</script>
