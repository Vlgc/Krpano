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
use Vlgc\Krpano\Persistence\Pano as Pano;
use \xmlWriter as Writer;

class Hotspot
{
    /**
     * @var Pano
     */
    protected $_pano;

    /**
     * @var Writer
     */
    protected $_writer;

    /**
     * @var array
     */
    protected $_points;

    /**
     * @param Pano $pano
     * @param Writer $writer
     */
    public function __construct(Pano $pano, Writer $writer)
    {
        $this->_pano   = $pano;
        $this->_writer = $writer;
    }

    /**
     * Tour
     *
     * @param array $hotspot
     * @param string $panoName
     * @return void
     */
    public function tour(array $hotspot, $panoName)
    {
        $name       = $hotspot["hotspot_name"];

        $this->_writer->startElement('hotspot');
        $attributes = array(
            'name'             => $name,
            'keep'             => 'false',
            'visible'          => 'true',
            'enabled'          => 'true',
            'handcursor'       => 'true',
            'capture'          => 'true',
            'children'         => 'true',
            'zorder'           => '0',
            'bordercolor'      => '0xff0000',
            'borderalpha'      => '0.20',
            'fillcolorhover'   => '0xff0000',
            'fillalphahover'   => '0.20',
            'borderwidthhover' => '4.0',
            'bordercolorhover' => '0xff0000',
            'borderalphahover' => '0.80',
            'fadeintime'       => '0.150',
            'fadeouttime'      => '0.300',
            'fadeincurve'      => '1.100',
            'fadeoutcurve'     => '0.700',
            'onhover'          => $hotspot["onhover"],
            'onout'            => '',
            'ondown'           => '',
            'onup'             => '',
            'onclick'          => $hotspot["onclick"],
            'details'          => '8',
            'effect'           => '',
            'flying'           => '0',
            'inverserotation'  => 'false',
            'usecontentsize'   => 'false',
        );
        if ($hotspot["highlight"]) {
            $attributes['fillcolor']   = '0xff0000';
            $attributes['fillalpha']   = '0.20';
            $attributes['borderwidth'] = '4.0';
        } else {
            $attributes['fillcolor']   = '0x0000ff';
            $attributes['fillalpha']   = '0.10';
            $attributes['borderwidth'] = '1.0';
        }
        $this->_attributes($attributes);

        foreach ($this->_points($panoName, $name) as $point) {
            $this->_writer->startElement('point');
            $this->_writer->writeAttribute('ath', $point["ath"]);
            $this->_writer->writeAttribute('atv', $point["atv"]);
            $this->_writer->endElement();
        }
        $this->_writer->endElement();
    }

    /**
     * Points
     *
     * @param string $panoName
     * @param string $name
     * @return array $points
     */
    protected function _points($panoName, $name)
    {
        if (null === $this->_points) {
            $points = $this->_pano->hotspotPoints($panoName);

            foreach ($points as $point) {
                if (!is_array($this->_points[$point['hotspot_name']])) {
                    $this->_points[$point['hotspot_name']] = array();
                }
                $this->_points[$point['hotspot_name']][]   = $point;
            }
        }

        return $this->_points[$name];
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