<?php
namespace Concrete\Package\FotoramaPackage;

use Core;
use AssetList;
use Asset;
use Package;
use BlockType;
 
defined('C5_EXECUTE') or die('Access Denied.');
 
class Controller extends Package 
{
    protected $pkgHandle = 'fotorama_package';
    protected $appVersionRequired = '5.7.1';
    protected $pkgVersion = '0.0.1';

    public function getPackageName() 
    {
        return t("Fotorama Block Components");
    }

    public function getPackageDescription() 
    {
        return t("Installs a fotorama block type capable of creating galleries from filesets.");
    }

    public function install()
    {
        $pkg = parent::install();

        // Install Block
        if(!BlockType::getByHandle('fotorama')) {
            BlockType::installBlockTypeFromPackage('fotorama', $pkg);
        }
    }

    public function upgrade()
    {
        $pkg = $this;

        parent::upgrade();
    }

    public function on_start()
    {
        // Register themes assets
        $al = AssetList::getInstance();
        $al->register(
                'css', 'fotorama', '3rd-party/fotorama-4.6.2/fotorama.css',
                array('version' => '4.6.2', 'position' => Asset::ASSET_POSITION_HEADER, 'minify' => true, 'combine' => true), $this
        );

        $al->register(
                'javascript', 'fotorama', '3rd-party/fotorama-4.6.2/fotorama.js',
                array('version' => '4.6.2', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => true, 'combine' => false), $this
        );
    }
}
