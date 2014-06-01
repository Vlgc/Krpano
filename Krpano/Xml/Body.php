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

class Body
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
     * Render
     *
     * @param array $data
     * @return void
     */
    public function render(array $data)
    {
        $this->_writer->startElement('krpano');
        $this->_writer->writeAttribute('version', '1.0.8');

        $this->_view($data);
        $this->_preview($data);
        $this->_cube($this->_tileSize($data));
    }

    /**
     * Cube
     *
     * @param int $tileSize
     * @return void
     */
    protected function _cube($tileSize)
    {
        $this->_writer->startElement('image');
        $this->_writer->writeAttribute('type', 'CUBE');
        $this->_writer->writeAttribute('multires', 'true');
        $this->_writer->writeAttribute('tilesize', '4000');

        $this->_writer->startElement('level');
        $this->_writer->writeAttribute('tiledimagewidth', $tileSize);
        $this->_writer->writeAttribute('tiledimageheight', $tileSize);

        $this->_image('left', 'pano_l_%0v%0h.jpg');
        $this->_image('front', 'pano_f_%0v%0h.jpg');
        $this->_image('right', 'pano_r_%0v%0h.jpg');
        $this->_image('back', 'pano_b_%0v%0h.jpg');
        $this->_image('up', 'pano_u_%0v%0h.jpg');
        $this->_image('down', 'pano_d_%0v%0h.jpg');

        $this->_writer->endElement();
        $this->_writer->endElement();
    }

    /**
     * Preview
     *
     * @return void
     */
    protected function _preview()
    {
        $this->_writer->startElement('preview');
        $this->_writer->writeAttribute('type', 'CUBESTRIP');
        $this->_writer->writeAttribute('url', 'preview.jpg');
        $this->_writer->endElement();
    }

    /**
     * View
     *
     * @param array $data
     * @return void
     */
    protected function _view(array $data)
    {
        $this->_writer->startElement('view');
        $this->_writer->writeAttribute('hlookat', $data["hlookat"]);
        $this->_writer->writeAttribute('vlookat', $data["vlookat"]);
        $this->_writer->writeAttribute('fovtype', $data["fovtype"]);
        $this->_writer->writeAttribute('fov', $data["fov"]);
        $this->_writer->writeAttribute('maxpixelzoom', $data["maxpixelzoom"]);
        $this->_writer->writeAttribute('fovmax', $data["fovmax"]);
        $this->_writer->endElement();
    }

    /**
     * Image
     *
     * @param string $name
     * @param string $url
     * @return void
     */
    protected function _image($name, $url)
    {
        $this->_writer->startElement($name);
        $this->_writer->writeAttribute('url', $url);
        $this->_writer->endElement();
    }

    /**
     * $tileSize
     *
     * @param string $tileSize
     * @return string $tileSize
     */
    protected function _tileSize(array $data)
    {
          if ('' != $data["tile_size"]) {
              return $data["tile_size"];
          }

          return '1910';
    }
}