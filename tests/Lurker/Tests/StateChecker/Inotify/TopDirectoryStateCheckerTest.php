<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lurker\Tests\StateChecker\Inotify;

use Lurker\Tests\StateChecker\Inotify\Fixtures\TopDirectoryStateCheckerForTest;

class TopDirectoryStateCheckerTest extends StateCheckerTest
{
    public function testResourceMovedAndReturnedDifferentWatchId()
    {
        $this->setAddWatchReturns(1);
        $checker = $this->getChecker();
        $checker->setEvent(IN_MOVE_SELF);

        $this->setAddWatchReturns(2);
        $events = $checker->getChangeset();

        $this->assertCount(0, $events);
        $this->assertCount(0, $this->bag->get(1));
        $this->assertCount(1, $this->bag->get(2));
        $this->assertContains($checker, $this->bag->get(2));
    }

    protected function setAddWatchReturns($id)
    {
        TopDirectoryStateCheckerForTest::setAddWatchReturns($id);
    }

    protected function getChecker()
    {
        return new TopDirectoryStateCheckerForTest($this->bag, $this->resource);
    }

    protected function getResource()
    {
        $resource = $this
            ->getMockBuilder('Lurker\Resource\DirectoryResource')
            ->disableOriginalConstructor()
            ->getMock();
        $resource
            ->expects($this->any())
            ->method('exists')
            ->will($this->returnCallback(array($this, 'isResourceExists')));
        $resource
            ->expects($this->any())
            ->method('getFilteredResources')
            ->will($this->returnValue(array()));

        return $resource;
    }
}
