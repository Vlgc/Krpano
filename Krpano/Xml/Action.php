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

class Action
{
    /**
     * @var Writer
     */
    protected $_writer;

    /**
     * @var string
     */
    protected $_indention = "\t\t";

    /**
     * @param Writer $writer
     */
    public function __construct(Writer $writer)
    {
        $this->_writer = $writer;
    }

    /**
     * little Planet
     *
     * @return void
     */
    public function littlePlanet()
    {
        $content = array(
            "set(display.flash10,off);\n",
            "tween(view.architectural, 0.0, 0.25);\n",
            "tween(view.vlookat,  90, distance(179, 1.50), easeoutquad);\n",
            "set(backtonormalfirst,false);\n",
            "if(view.pannini       == true,  set(backtonormalfirst,true) );\n",
            "if(view.stereographic == false, set(backtonormalfirst,true) );\n",
            "if(backtonormalfirst, tween(view.fisheye, 0.0 ,distance(1.0, 0.25), easeoutquad, WAIT); );\n",
            "set(view.pannini, false);\n",
            "set(view.stereographic, true);\n",
            "set(view.fovmax, 160);\n",
            "tween(view.fisheye, 1.0, distance(1.0, 0.75), easeoutquad);\n",
            "tween(view.fov,     155, distance(179, 0.75), easeoutquad);\n",
        );

        $this->_action('littleplanet', $content);
    }

    /**
     * view Architectural
     *
     * @return void
     */
    public function viewArchitectural()
    {
        $content = array(
            "tween(view.fovmax,       150.0, distance(179, 1.00), easeoutquad);\n",
            "tween(view.architectural,  1.0, distance(1.0, 0.45), easeoutquad);\n",
            "tween(view.fisheye,        0.0, distance(1.0, 0.45), easeoutquad,"
            . " set(view.stereographic,false);set(view.pannini,false);set(display.flash10,on); );\n",
        );
        $this->_action('view_architectural', $content);
    }

    /**
     * reset
     *
     * @return void
     */
    public function reset()
    {
        $content = array(
            "tween(view.fovmax,       150.0, distance(179,1.00), easeoutquad);\n",
            "tween(view.architectural,  0.0, distance(1.0, 0.45), easeoutquad);\n",
            "tween(view.fisheye,        0.0, distance(1.0, 0.45), easeoutquad,"
            . " set(view.stereographic,false); set(view.pannini,false); set(display.flash10,on); );\n",
        );
        $this->_action('reset', $content);
    }

    /**
     * hide intro image
     *
     * @return void
     */
    public function hideIntroImage()
    {
        $content = array(
            "if(plugin[introimage].enabled,set(plugin[introimage].enabled,false);\n",
            "tween(plugin[introimage].alpha, 0.0, 0.5, default, removeplugin(introimage));\n",
            ");\n",
        );
        $this->_action('hideintroimage', $content);
    }

    /**
     * view Fisheye
     *
     * @return void
     */
    public function viewFisheye()
    {
        $content = array(
            "set(display.flash10,off);\n",
            "tween("
            . "view.architectural, 0.0, distance(1.0, 0.30), easeoutquad"
            . ");\n",
            "tween("
            . "view.fisheye,       0.0 ,distance(1.0, 0.30), easeoutquad,"
            . "set(view.stereographic,false); "
            . "set(view.pannini,false); "
            . "set(view.fovmax,179);"
            . "tween(view.fisheye, 0.35, distance(1.0,1.25)); "
            . ");\n",
        );
        $this->_action('view_fisheye', $content);
    }

    /**
     * Action
     *
     * @param string $name
     * @param array $content
     * @return void
     */
    protected function _action($name, array $content)
    {
        $this->_writer->startElement('action');
        $this->_writer->writeAttribute('name', $name);
        $action = "\n" . implode($this->_indention, $content);
        $this->_writer->text($action);
        $this->_writer->endElement();
    }
}