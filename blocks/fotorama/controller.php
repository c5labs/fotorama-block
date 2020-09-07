<?php
namespace Concrete\Package\FotoramaBlock\Block\Fotorama;

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
use Events;

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
    protected $defaults = array(
        'width' => '100%',
        'height' => '',
        'fit' => 'contain',
        'transition' => 'slide',
        'nav_position' => 'bottom',
        'allow_fullscreen' => '1',
        'arrows' => '1',
        'swipe' => '1',
        'transitiondelay' => '1',
        'nav' => 'thumbs'
    );

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

    public function registerViewAssets($outputContent = '')
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
            foreach ($this->defaults as $k => $v) {
                $this->set($k, $v);
            }
        }
        $this->set('defaults', $this->defaults);
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
        $event = new \Symfony\Component\EventDispatcher\GenericEvent();
        $event->setArgument('args', $args);
        Events::dispatch('on_fotorama_save', $event);

        if ('file_sets' === $args['image_source'] && is_array($args['selected_file_set_ids'])) {
            $this->saveIds($args['selected_file_set_ids'], 'FS', $this->bID);
        } elseif ('files' === $args['image_source'] && is_array($args['img'])) {
            $this->saveIds($args['img'], 'F', $this->bID);
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
        );

        foreach ($checkboxes as $checkbox) {
            if (empty($args[$checkbox])) {
                $args[$checkbox] = 0;
            } else {
                $args[$checkbox] = 1;
            }
        }

        $args['transitionduration'] = 
            empty($args['transitionduration']) ? 1 : intval($args['transitionduration']);

        parent::save($args);
    }

    public function duplicate($newBID)
    {
        if ('file_sets' === $this->image_source) {
            $ids = array_map(function($item) { return $item['object_id']; }, $this->getFileSets());
            $this->saveIds($ids, 'FS', $newBID);
        } elseif ('files' === $this->image_source) {
            $ids = array_map(function($item) { return $item->getFileId(); }, $this->getFiles());
            $this->saveIds($ids, 'F', $newBID);
        }

        parent::duplicate($newBID);
    }

    protected function saveIds(array $ids, $type = '', $bID)
    {
        $db = Database::get();
        $db->exec('DELETE FROM btFotoramaEntries WHERE bID = ' . $bID);

        foreach ($ids as $k => $v) {
            $db->insert(
                'btFotoramaEntries',
                array(
                    'bID' => $bID,
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
            $files = array();

            if ('FS' === $entry['object_type']) {
                $fs = FileSet::getById($entry['object_id']);
                if ($fs) {
                    $files = $fs->getFiles();
                }
            } elseif ('F' === $entry['object_type']) {
                $files = array(File::getById($entry['object_id']));
            }

            foreach ($files as $file) {
                if (!empty($file)) {
                    $images[] = $file;
                }
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
