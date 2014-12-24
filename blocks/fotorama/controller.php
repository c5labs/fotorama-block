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
use Database;
use File;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController
{
    protected $btTable = 'btFotorama';
    protected $btInterfaceWidth = "538";
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

    public function view()
    {
        $this->set('images', $this->getImages());
    }

    public function form()
    {
        // Assets
        $this->requireAsset('core/file-manager');
        $this->requireAsset('select2');
        $this->requireAsset('css', 'font-awesome');
        $this->requireAsset('switchery');
        $this->requireAsset('javascript', 'bootstrap/tab');

        // Source Object Lists
        $this->set('image_sources', $this->getImageSources());
        $this->set('available_file_sets', $this->getPublicFileSets());

        // Selected Source Objects
        $selected_file_set_ids = array();
        foreach ($this->getFileSets() as $r) {
            $selected_file_set_ids[] = $r['object_id'];
        }
        $this->set('selected_file_set_ids', $selected_file_set_ids);
        $this->set('selected_images', $this->getFiles());

        // Defaults
        if ($this->bID <= 0) {
            $this->set('image_source', 'files');
            $this->set('width', '100%');
            $this->set('height', '');
            $this->set('fit', 'contain');
            $this->set('transition', 'slide');
            $this->set('nav_position', 'bottom');
            $this->set('allow_fullscreen', '1');
            $this->set('arrows', '1');
            $this->set('swipe', '1');
            $this->set('transitiondelay', '1');
        }
    }

    public function add()
    {
        $this->form();
    }

    public function edit()
    {
        $this->form();
    }

    public function composer() {
        $this->form();
    }

    public function save($args)
    {
        if ('file_sets' === $args['image_source'] && is_array($args['selected_file_set_ids'])) {
            $this->saveIds($args['selected_file_set_ids'], 'FS');
        } elseif ('files' === $args['image_source'] && is_array($args['img'])) {
            $this->saveIds($args['img'], 'F');
        }

        // Checkboxes
        $checkboxes = array(
            'allow_fullscreen',
            'lazy_loading',
            'captions',
            'hash_nav',
            'loop_images',
            'autoplay',
            'keyboard',
            'arrows',
            'click',
            'swipe',
            'nav',
        );

        foreach ($checkboxes as $checkbox) {
            if (!isset($args[$checkbox])) {
                $args[$checkbox] = 0;
            }
        }
        parent::save($args);
    }

    protected function saveIds(array $ids, $type = '')
    {
        $db = Database::get();
        $db->exec('DELETE FROM btFotoramaEntries WHERE bID = ' . $this->bID);

        foreach ($ids as $k => $v) {
            $db->insert(
                'btFotoramaEntries',
                array(
                    'bID' => $this->bID,
                    'object_id' => $v,
                    'object_type' => $type,
                    'entry_order' => $k
                )
            );
        }
    }

    public function getImages()
    {
        $db = Database::get();
        $q = 'SELECT * FROM btFotoramaEntries WHERE bID = ? ORDER BY entry_order ASC';
        $r = $db->query($q, array($this->bID));

        $images = array();
        foreach ($r as $entry) {

            if ('FS' === $entry['object_type']) {
                $fs = FileSet::getById($entry['object_id']);
                $files = $fs->getFiles();
            } elseif ('F' === $entry['object_type']) {
                $files = array(File::getById($entry['object_id']));
            }

            foreach ($files as $file) {
                $images[] = $file;
            }
        }

        return $images;
    }

    public function getImageSources()
    {
        return array(
            'files' => t('Selected Files'),
            'file_sets' => t('File Set'),
        );
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
        $db = Database::get();
        $q = 'SELECT * FROM btFotoramaEntries WHERE bID = ? AND object_type = "FS" ORDER BY entry_order ASC';
        return $db->fetchAll($q, array($this->bID));
    }

    public function getFiles()
    {
        $db = Database::get();
        $q = 'SELECT * FROM btFotoramaEntries WHERE bID = ? AND object_type = "F" ORDER BY entry_order ASC';
        return $db->project($q, array($this->bID), function ($r) {
            return File::getById($r['object_id']);
        });
    }
}
