<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lurker\Tracker;

use Lurker\Resource\DirectoryResource;
use Lurker\Event\FilesystemEvent;
use Lurker\Resource\TrackedResource;
use Lurker\StateChecker\DirectoryStateChecker;
use Lurker\StateChecker\FileStateChecker;

/**
 * Recursive iterator resources tracker.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class RecursiveIteratorTracker implements TrackerInterface
{
    private $checkers = array();

    /**
     * {@inheritdoc}
     */
    public function track(TrackedResource $resource, $eventsMask = FilesystemEvent::ALL)
    {
        $trackingId = $resource->getTrackingId();
        $checker    = $resource->getOriginalResource() instanceof DirectoryResource
            ? new DirectoryStateChecker($resource->getOriginalResource(), $eventsMask)
            : new FileStateChecker($resource->getOriginalResource(), $eventsMask);

        $this->checkers[$trackingId] = array(
            'tracked' => $resource,
            'checker' => $checker
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getEvents()
    {
        $events = array();
        foreach ($this->checkers as $trackingId => $meta) {
            $tracked = $meta['tracked'];
            $checker = $meta['checker'];

            foreach ($checker->getChangeset() as $change) {
                $events[] = new FilesystemEvent($tracked, $change['resource'], $change['event']);
            }
        }

        return $events;
    }
}
