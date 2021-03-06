<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lurker\StateChecker;

use Lurker\Resource\FileResource;
use Lurker\Event\FilesystemEvent;

/**
 * File state checker.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class FileStateChecker extends ResourceStateChecker
{
    /**
     * Initializes checker.
     *
     * @param FileResource $resource
     * @param integer      $eventsMask event types bitmask
     */
    public function __construct(FileResource $resource, $eventsMask = FilesystemEvent::ALL)
    {
        parent::__construct($resource, $eventsMask);
    }
}
