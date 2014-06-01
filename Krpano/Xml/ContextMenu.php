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

class ContextMenu
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
     * All
     *
     * @return void
     */
    public function all()
    {
        $this->_writer->startElement('contextmenu');
        $items = array(
            array('caption' => 'FULLSCREEN',),
            array(
                'caption' => 'normal view',
                'onclick' => 'action(reset);',
            ),
            array(
                'caption' => 'fisheye view',
                'onclick' => 'action(view_fisheye);',
            ),
            array(
                'caption' => 'architectural view',
                'onclick' => 'action(view_architectural);',
            ),
            array(
                'caption' => 'little planet view',
                'onclick' => 'action(littleplanet);',
            ),
        );
        $this->_items($items);

        $this->_writer->endElement();
    }

    /**
     * Items
     *
     * @param array $items
     * @return void
     */
    protected function _items(array $items)
    {
        foreach ($items as $item) {
            $this->_item($item);
        }
    }

    /**
     * Item
     *
     * @param array $attributes
     * @return void
     */
    protected function _item(array $attributes)
    {
        $this->_writer->startElement('item');
        foreach ($attributes as $name => $value) {
            $this->_writer->writeAttribute($name, $value);
        }
        $this->_writer->endElement();
    }
}