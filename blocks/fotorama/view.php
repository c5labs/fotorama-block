<?php 
defined('C5_EXECUTE') or die('Access Denied.');

if (! function_exists('filter_attr_name')) {
    function filter_attr_name($name)
    {
        switch ($name) {
            case 'loop_images':
                return 'loop';
            case 'hash_nav':
                return 'hash';
            case 'nav_position':
                return 'navposition';
            case 'allow_fullscreen':
                return 'allowfullscreen';
            default:
                return $name;
        }
    }
}

$data_attrs = array(
    'width',
    'height',
    'fit',
    'transition',
    'nav',
    'nav_position',
    'arrows',
    'allow_fullscreen',
    'hash_nav',
    'loop_images',
    'autoplay',
    'keyboard',
    'click',
    'swipe',
    'transitionduration',
);

$attr_str = '';
foreach ($data_attrs as $v) {   
    if (in_array($v, array('autoplay', 'transitionduration'))) {
        $$v = $$v * 1000;
    }

    if (!empty($$v) || 0 === $$v) {
        $attr_str .= ' data-' . filter_attr_name($v) . '="' . $$v . '"';
    }
}

if (!Page::getCurrentPage()->isEditMode()) { 

if (count($images) > 0) { ?>

<div class="fotorama"<?php echo $attr_str; ?>>
<?php foreach ($images as $image) { ?>
    <<?php if (!$lazy_loading) { ?>img src<?php } else { ?>a href<?php } ?>="<?php echo $image->getRelativePath(); ?>" alt="<?php echo $image->getTitle(); ?>"<?php if ($captions) { echo 'data-caption="' . $image->getDescription() . '"'; } ?>><?php if ($lazy_loading) {  ?></a><?php } ?>
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