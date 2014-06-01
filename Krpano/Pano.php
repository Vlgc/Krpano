<?php
/**
 * Copyright (c) 2014 Vlgc <vlgc@system-lords.de>
 * All rights reserved.
 *
 * @package   Krpano
 * @author    Vlgc <vlgc@system-lords.de>
 * @copyright Vlgc <vlgc@system-lords.de>, All rights reserved
 * @link      http://github.com/Vlgc/
 * @license   BSD License
 */
namespace Vlgc\Krpano;
use \xmlWriter as Writer;

class Pano
{
    /**
     * @var Xml\Body
     */
    protected $_body;

    /**
     * @var Xml\Action
     */
    protected $_action;

    /**
     * @var Xml\Plugin
     */
    protected $_plugin;

    /**
     * @var Xml\Hotspot
     */
    protected $_hotspot;

    /**
     * @var Xml\ContextMenu
     */
    protected $_contextMenu;

    /**
     * @var Persistence\Pano
     */
    protected $_pano;

    /**
     * @var Writer
     */
    protected $_writer;

    /**
     * @var array
     */
    protected $_available;

    /**
     * @var string
     */
    protected $_url;

    /**
     * @param Xml\Body $body
     * @param Xml\Action $action
     * @param Xml\Plugin $plugin
     * @param Xml\Hotspot $hotspot
     * @param Xml\ContextMenu $contextMenu
     * @param Persistence\Pano $pano
     * @param Writer $writer
     * @param string $url
     */
    public function __construct(
        Xml\Body $body,
        Xml\Action $action,
        Xml\Plugin $plugin,
        Xml\Hotspot $hotspot,
        Xml\ContextMenu $contextMenu,
        Persistence\Pano $pano,
        Writer $writer,
        $url
    ) {
        $this->_body        = $body;
        $this->_action      = $action;
        $this->_plugin      = $plugin;
        $this->_hotspot     = $hotspot;
        $this->_contextMenu = $contextMenu;
        $this->_pano        = $pano;
        $this->_writer      = $writer;
        $this->_url         = $url;
    }


    /**
     * Output available
     *
     * @return void
     */
    public function outputAvailable()
    {
?>
        <table width="100%" height="100%">
<?php
        foreach ($this->_getAvailable() as $name) {
            $href = $this->_url . '/swf.php?pano=' . $name;
?>
            <tr>
                <td>
                    <a href="<?php echo $href; ?>" target="_blank">
                        <?php echo $name; ?>
                    </a>
                </td>
            </tr>
<?php
        }
?>
        </table>
<?
    }

    /**
     * Is available $name
     *
     * @param string $name
     * @return boolean $isAvailable
     */
    public function isAvailable($name)
    {
        return in_array($name, $this->_getAvailable());
    }

    /**
     * Init $name
     *
     * @param string $name
     * @return void
     */
    public function init($name)
    {
        $this->_pano->init($name);
    }

    /**
     * render
     *
     * @param string $name
     * @return void
     */
    public function render($name)
    {
        $this->_writer->openMemory();
        $this->_writer->setIndent(TRUE);
        $this->_writer->setIndentString("\t");

        $this->_body($name);

        $this->_writer->endElement();

        header("Content-type:text/xml");
        echo("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n");
        print $this->_writer->outputMemory(TRUE);

        $this->_pano->incrementViewCount($name);
    }

    /**
     * body
     *
     * @param string $name
     * @return void
     */
    protected function _body($name)
    {
        $data     = $this->_pano->byName($name);
        $hotspots = $this->_pano->hotspots($name);

        $this->_body->render($data);

        // plugins
        $this->_plugin->control();

        if (1 == $data["plugin_editor"]) {
            $this->_plugin->editor();
        }
        if (1 == $data["plugin_options"]) {
            $this->_plugin->options();
        }
        if (0 != count($hotspots)) {
            $this->_plugin->introImage();
            $this->_action->hideIntroImage();
        }
        // actions
        $this->_action->reset();
        $this->_action->viewFisheye();
        $this->_action->viewArchitectural();
        $this->_action->littlePlanet();

        // context menu
        $this->_contextMenu->all();

        // hotspots
        foreach ($hotspots as $hotspot) {
            $this->_hotspot->tour($hotspot, $name);
        }
    }

    /**
     * Get $available
     *
     * @return array $available
     */
    protected function _getAvailable()
    {
        if (null === $this->_available) {
            $this->_available = $this->_pano->available();
        }

        return $this->_available;
    }
}