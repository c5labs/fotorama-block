<?php
namespace Concrete\Package\FotoramaPackage\Block\Fotorama;

use Concrete\Core\Block\BlockController;
use Loader;
use Less_Parser;
use Less_Tree_Rule;
use Core;
use Page;
use AssetList;
use Asset;
use FileSet;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController
{
    protected $btTable = 'btFotorama';
    protected $btInterfaceWidth = "700";
    protected $btInterfaceHeight = "500";
    protected $btWrapperClass = 'ccm-ui';
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btDefaultSet = 'multimedia';

    public function getBlockTypeName()
    {
        return t("Fotorama");
    }
    public function getBlockTypeDescription()
    {
        return t("Add Fotorama galleries to your website.");
    }

    public function getBlockTypeHelp() {
        return t('For support please go to the marketplace page.');
    }

    public function registerViewAssets()
    {
        $this->requireAsset('fotorama');
        $this->requireAsset('javascript', 'jquery');
    }

    public function add()
    {
        $this->edit();
    }

    public function getImages()
    {
        $files = array();
        $fileSets = $this->getFileSets();
        foreach ($fileSets as $id) {
            $fs = FileSet::getById($id);
            $files = array_merge($files, $fs->getFiles());
        }

        return $files;
    }

    public function view()
    {
        $this->set('images', $this->getImages());
    }

    public function edit()
    {
        $this->set('selected_ids', $this->getFileSets());
        //$this->requireAsset('core/file-manager');
        $this->requireAsset('select2');
    }

    public function getPublicFileSets()
    {
        $fsl = new \Concrete\Core\File\Set\SetList();
        $fsl->filterByType(FileSet::TYPE_PUBLIC);
        $sets = $fsl->get();

        $sets_array = array();
        if (count($sets) > 0) {
            $sets_array = array();
            foreach ($sets as $set) {
                $sets_array[$set->getFileSetID()] = $set->getFileSetName();
            }
        }
        return $sets_array;
    }

    public function getFileSets()
    {
        $fs = json_decode($this->fileSetIds);
        return (is_array($fs)) ? $fs : array();
    }

    public function save($args)
    {
        $args['fileSetIds'] = json_encode($args['fileSetIds']);
        parent::save($args);
    }
}
