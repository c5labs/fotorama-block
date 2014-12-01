<?php
    $fsl = new Concrete\Core\File\Set\SetList();
    $fsl->filterByType(FileSet::TYPE_PUBLIC);
    $sets = $fsl->get();

    if (0 === count($sets)) {
        $sets_array = array('No file sets found.');
    } else {
        $sets_array = array();
        foreach ($sets as $set) {
            $sets_array[$set->getFileSetID()] = $set->getFileSetName();
        }
    }
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-image"></i> <?php  echo t('Images to display');?></h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <?php echo $form->label('fileSetIds', t('File Set(s)')); ?>
            <?php echo $form->selectMultiple(
                'fileSetIds', 
                $sets_array, 
                (is_array($selected_ids))  ? $selected_ids : null, 
                array('style' => 'border: 1px solid #ccc')
            ); ?>
            <script>
                $(function () {
                    $("#fileSetIds").select2();
                });
            </script>
        </div>
    </div>
</div>