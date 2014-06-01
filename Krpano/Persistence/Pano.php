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
namespace Vlgc\Krpano\Persistence;
use \Vlgc\Persistence\Persistence as Sql;

class Pano
{
    /**
     * @var Sql
     */
    protected $_sql;

    /**
     * @param Sql $sql
     */
    public function __construct($sql)
    {
        $this->_sql = $sql;
    }

    /**
     * Available
     *
     * @return array $available
     */
    public function available()
    {
        $sql       = "SELECT a.pano_name FROM panoramen a";

        $this->_sql->prepare($sql);
        $this->_sql->execute();
        $result    = $this->_sql->fetchAssoc();

        $available = array();

        foreach ($result as $panorama) {
            array_push($available, $panorama["pano_name"]);
        }

        return $available;
    }

    /**
     * Init $name
     *
     * @param string $name
     * @return void
     */
    public function init($name)
    {
        $sql = 'INSERT INTO panoramen ('
        . 'pano_name, '
        . 'pano_title, '
        . 'context_menu_name, '
        . 'hlookat, '
        . 'vlookat, '
        . 'fovtype, '
        . 'fov, '
        . 'maxpixelzoom, '
        . 'fovmax, '
        . 'plugin_editor, '
        . 'plugin_options, '
        . 'show_on_pano_gallery, '
        . 'timestamp '
        . ') VALUES ('
        . '?, '
        . '"new pano", '
        . '"standart", '
        . '0, '
        . '0, '
        . '"MFOV", '
        . '90, '
        . '1, '
        . '160, '
        . '0, '
        . '0, '
        . '0, '
        . '?'
        . ');';

        $this->_sql->prepare($sql);
        $this->_sql->bindValue(1, $name, \PDO::PARAM_STR);
        $this->_sql->bindValue(2, $this->_getTime(), \PDO::PARAM_INT);
        $this->_sql->execute();
    }

    /**
     * LoadGallery by $galleryId
     *
     * @return array $result
     */
    public function loadGallery($galleryId)
    {
        $sql = 'SELECT a.* FROM panoramen a WHERE '
        . 'a.show_on_pano_gallery=? '
        .'AND tabgroup!=? '
        . 'ORDER BY a.timestamp DESC';

        $this->_sql->prepare($sql);
        $this->_sql->bindValue(1, $galleryId, \PDO::PARAM_INT);
        $this->_sql->bindValue(2, '', \PDO::PARAM_STR);
        $this->_sql->execute();

        return $this->_sql->fetchAssoc();
    }

    /**
     * by $name
     *
     * @param string $name
     * @return array $pano
     */
    public function byName($name)
    {
        $sql    = "SELECT a.* FROM panoramen a WHERE a.pano_name=?";

        $this->_sql->prepare($sql);
        $this->_sql->bindValue(1, $name, \PDO::PARAM_STR);
        $this->_sql->execute();
        $result = $this->_sql->fetchAssoc();

        if (is_array($result[0])) {
            return $result[0];
        }

        return array();
    }

    /**
     * $hotspots by $name
     *
     * @param string $name
     * @return array $hotspots
     */
    public function hotspots($name)
    {
        $sql = "SELECT a.* FROM hotspots a WHERE a.pano_name=?";

        $this->_sql->prepare($sql);
        $this->_sql->bindValue(1, $name, \PDO::PARAM_STR);
        $this->_sql->execute();

        return $this->_sql->fetchAssoc();
    }

    /**
     * $hotspotPoints by $name
     *
     * @param string $name
     * @return array $hotspotPoints
     */
    public function hotspotPoints($name)
    {
        $sql = "SELECT a.* FROM hotspot_points a WHERE a.pano_name=?"
        . " ORDER BY a.point_no asc";

        $this->_sql->prepare($sql);
        $this->_sql->bindValue(1, $name, \PDO::PARAM_STR);
        $this->_sql->execute();

        return $this->_sql->fetchAssoc();
    }

    /**
     * Increment view counter of $name
     *
     * @param string $name
     * @return void
     */
    public function incrementViewCount($name)
    {
        $sql = "UPDATE panoramen SET view_counter=view_counter+1"
        . " WHERE pano_name=?";

        $this->_sql->prepare($sql);
        $this->_sql->bindValue(1, $name, \PDO::PARAM_STR);
        $this->_sql->execute();
    }

    /**
     * Get time()
     *
     * @codeCoverageIgnore will be mocked for unit testing
     * @return int $time
     */
    protected function _getTime()
    {
        return time();
    }
}