<?php if (!Page::getCurrentPage()->isEditMode()) { ?>

<?php if (count($images) > 0) { ?>

<div class="fotorama" data-fit="cover" data-height="400" data-nav="thumbs" data-allowfullscreen="true">
<?php foreach ($images as $image) { ?>
    <img src="<?php echo $image->getRelativePath(); ?>" alt="<?php echo $image->getTitle(); ?>">
<?php } ?>
</div>

<?php } else { ?>
    <p><?php echo t('There are no files in the selected set(s).'); ?></p>
<?php } ?>

<?php } else { ?>
    <div class="ccm-edit-mode-disabled-item">
        <div style="padding:8px 0px;"><?php echo t('Fotorama disabled in edit mode.'); ?></div>
    </div>
<?php } ?>