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
namespace Vlgc\Krpano\Xml;
use \xmlWriter as Writer;

class Plugin
{
    /**
     * @var Writer
     */
    protected $_writer;

    /**
     * @param Writer $writer
     */
    public function __construct(Writer $writer)
    {
        $this->_writer = $writer;
    }

    /**
     * editor
     *
     * @return void
     */
    public function editor()
    {
        $attributes = array(
            'name' => 'editor',
            'url'  => 'plugins/editor.swf',
        );
        $this->_plugin($attributes);
    }

    /**
     * options
     *
     * @return void
     */
    public function options()
    {
        $attributes = array(
            'name' => 'options',
            'url'  => 'plugins/options.swf',
        );
        $this->_plugin($attributes);
    }

    /**
     * control
     *
     * @return void
     */
    public function control()
    {
        $attributes = array(
            'name'  => 'controlpanel',
            'url'   => 'plugins/controlmenuV3notext.swf',
            'align' => 'bottom',
        );
        $this->_plugin($attributes);
    }

    /**
     * intro image
     *
     * @return void
     */
    public function introImage()
    {
        $attributes = array(
            'name'    => 'introimage',
            'url'     => 'images/introimage.png',
            'align'   => 'center',
            'onload'  => 'set(alpha,0); tween(alpha,1.0);',
            'onclick' => 'hideintroimage();',
        );
        $this->_plugin($attributes);
    }

    /**
     * plugin
     *
     * @param array $attributes
     * @return void
     */
    protected function _plugin(array $attributes)
    {
        $this->_writer->startElement('plugin');
        $this->_attributes($attributes);
        $this->_writer->endElement();
    }

    /**
     * Attributes
     *
     * @param array $attributes
     * @return void
     */
    protected function _attributes(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->_writer->writeAttribute($name, $value);
        }
    }
}