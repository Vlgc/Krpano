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
use \Vlgc\Persistence\Persistence as Database;
use \xmlWriter as Writer;

class Factory
{
    /**
     * @var array
     */
    protected $_config;

    /**
     * @param array $config
     */
     public function __construct(array $config)
    {
        $this->_config = $config;
    }

    /**
     * Get a new instance of Persistence\Pano
     *
     * @return Persistence\Pano
     */
    public function createPersistence()
    {
        return new Persistence\Pano(
            new Database($this->_createPdo($this->_config['database']))
        );
    }

    /**
     * Get a new instance of Pano
     *
     * @return Pano
     */
    public function createBasic()
    {
        $writer      = new Writer();
        $database    = new Database(
            $this->_createPdo($this->_config['database'])
        );
        //$database->attach(new Debug());
        $persistence = new Persistence\Pano($database);

        return new Pano(
            new Xml\Body($writer),
            new Xml\Action($writer),
            new Xml\Plugin($writer),
            new Xml\Hotspot($persistence, $writer),
            new Xml\ContextMenu($writer),
            $persistence,
            $writer,
            $this->_config['pano']['swf-url']
        );
    }

    /**
     * Get a new instance of \PDO
     *
     * @return \PDO $pdo
     */
    protected function _createPdo(array $config)
    {
        $pdo = new \PDO(
            "mysql:host="
            . $config['params']['host']
            . ";dbname="
            . $config['params']['name'],
            $config['params']['user'],
            $config['params']['pass'],
            array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')
        );
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}