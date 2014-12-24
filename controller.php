<?php
namespace Concrete\Package\FotoramaPackage;

use Core;
use AssetList;
use Asset;
use Package;
use BlockType;
use Database;
 
defined('C5_EXECUTE') or die('Access Denied.');
 
class Controller extends Package 
{
    protected $pkgHandle = 'fotorama_package';
    protected $appVersionRequired = '5.7.1';
    protected $pkgVersion = '0.9.3';

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

    public function uninstall()
    {
        parent::uninstall();
        $db = Database::get();
        $db->exec('DROP TABLE btFotorama;');
        $db->exec('DROP TABLE btFotoramaEntries;');
    }


    public function upgrade()
    {
        $pkg = $this;

        parent::upgrade();
    }

    public function on_start()
    {
        $this->registerAssets();
    }

    protected function registerAssets()
    {
        $al = AssetList::getInstance();

        // Fotorama
        $al->register(
                'css', 'fotorama/css', 'assets/fotorama-4.6.2/fotorama.css',
                array('version' => '4.6.2', 'position' => Asset::ASSET_POSITION_HEADER, 'minify' => true, 'combine' => false), $this
        );

        $al->register(
                'javascript', 'fotorama/js', 'assets/fotorama-4.6.2/fotorama.js',
                array('version' => '4.6.2', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => true, 'combine' => false), $this
        );

        $al->registerGroup(
            'fotorama',
            array(
                array('css', 'fotorama/css'), 
                array('javascript', 'fotorama/js')
            )
        );
        // Bootstrap Tabs
        $al->register(
            'javascript',
            'bootstrap/tab',
            'assets/bootstrap.tab.js',
            array(
                'version' => '3.3.1',
                'position' => Asset::ASSET_POSITION_FOOTER,
                'minify' => true,
                'combine' => true
            ),
            $this
        );

        // Switchery
        $al->register(
            'javascript',
            'switchery/js',
            'assets/switchery.js',
            array(
                'version' => '0.7.0',
                'position' => Asset::ASSET_POSITION_FOOTER,
                'minify' => true,
                'combine' => true
            ),
            $this
        );

        $al->register(
            'css',
            'switchery/css',
            'assets/switchery.css',
            array(
                'version' => '0.7.0',
                'position' => Asset::ASSET_POSITION_HEADER,
                'minify' => true,
                'combine' => true
            ),
            $this
        );

        $al->registerGroup(
            'switchery',
            array(
                array('css', 'switchery/css'),
                array('javascript', 'switchery/js')
            )
        );
    }
}
