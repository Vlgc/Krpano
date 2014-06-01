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
namespace Vlgc\KrPano;

class Debug implements \SplObserver
{
    /**
     * Update
     *
     * @param \SplSubject $subject
     * @return void
     */
    public function update(\SplSubject $subject)
    {
        var_dump($subject->get());
    }
}
