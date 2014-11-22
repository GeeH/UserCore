<?php
/**
 * Copyright (c) 2014 Roave, LLC.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright 2014 Roave, LLC
 * @license http://www.opensource.org/licenses/bsd-license.php  BSD License
 */

namespace RoaveTest\User\Core\Service;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use ReflectionClass;
use Roave\User\Core\Entity\UserEntityInterface;
use Roave\User\Core\Event\UserEvent;
use Roave\User\Core\Service\UserService;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ResponseCollection;

/**
 * Class UserServiceTest
 *
 * @coversDefaultClass \Roave\User\Core\Service\UserService
 * @covers ::<!public>
 *
 * @group service
 */
class UserServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager|PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManager;

    /**
     * @var EventManagerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $eventManager;

    /**
     * @var UserService
     */
    private $service;

    protected function setUp()
    {
        $this->eventManager = $this->getMock(EventManagerInterface::class);
        $this->objectManager = $this->getMock(ObjectManager::class);

        $this->service = new UserService($this->objectManager);

        $reflection = new ReflectionClass(UserService::class);
        $property = $reflection->getProperty('eventManager');
        $property->setAccessible(true);
        $property->setValue($this->service, $this->eventManager);
    }

    /**
     * @covers ::__construct
     * @covers ::getEventManager()
     */
    public function testEventManagerIsProperlyConfigured()
    {
        $service = new UserService($this->objectManager);
        $evm = $service->getEventManager();

        $reflection = new ReflectionClass($evm);

        $property = $reflection->getProperty('eventClass');
        $property->setAccessible(true);

        $this->assertSame(UserEvent::class, $property->getValue($evm));
        $this->assertSame([UserService::class], $evm->getIdentifiers());
    }

    /**
     * @covers ::update
     */
    public function testUpdateShortCircuitedFromEvmIfLastResultIsFalse()
    {
        $result = new ResponseCollection();
        $result->setStopped(true);
        $result->push(false);

        $this->eventManager
            ->expects($this->once())
            ->method('trigger')
            ->with(
                $this->callback(function(UserEvent $event) {
                    $this->assertInstanceOf(UserEntityInterface::class, $event->getUser());
                    return $event->getName() === UserEvent::EVENT_UPDATE_PRE;
                }),
                $this->callback(function($callback) {
                    if ($callback === null) {
                        return true;
                    }

                    $this->assertTrue($callback(false));
                    $this->assertFalse($callback(true));

                    return true;
                })
            )
            ->will($this->returnValue($result));

        $this->assertFalse($this->service->update($this->getMock(UserEntityInterface::class)));
    }

    /**
     * @covers ::update
     */
    public function testUpdateDiscardShortCircuitIfStoppedAnLastResultIsNotFalse()
    {
        $result = new ResponseCollection();
        $result->setStopped(true);
        $result->push(null);

        $this->eventManager
            ->expects($this->any())
            ->method('trigger')
            ->with(
                $this->logicalOr(
                    $this->callback(function(UserEvent $event) {
                        $this->assertInstanceOf(UserEntityInterface::class, $event->getUser());
                        return $event->getName() === UserEvent::EVENT_UPDATE_PRE;
                    }),
                    $this->callback(function(UserEvent $event) {
                        $this->assertInstanceOf(UserEntityInterface::class, $event->getUser());
                        return $event->getName() === UserEvent::EVENT_UPDATE_POST;
                    })
                ),
                $this->callback(function($callback) {
                    if ($callback === null) {
                        return true;
                    }

                    $this->assertTrue($callback(false));
                    $this->assertFalse($callback(true));

                    return true;
                })
            )
            ->will($this->returnValue($result));

        $this->assertTrue($this->service->update($this->getMock(UserEntityInterface::class)));
    }

    /**
     * @covers ::update
     */
    public function testUpdateFlushesObjectWithUpdatedTimestamp()
    {
        $user = $this->getMock(UserEntityInterface::class);
        $user
            ->expects($this->once())
            ->method('setUpdatedAt')
            ->with($this->isInstanceOf(DateTime::class));

        $this->objectManager
            ->expects($this->once())
            ->method('flush');

        $this->eventManager
            ->expects($this->any())
            ->method('trigger')
            ->will($this->returnValue($this->getMock(ResponseCollection::class)));

        $this->assertTrue($this->service->update($user));
    }

    /**
     * @covers ::create
     */
    public function testCreateShortCircuitedFromEvmIfLastResultIsFalse()
    {
        $result = new ResponseCollection();
        $result->setStopped(true);
        $result->push(false);

        $this->eventManager
            ->expects($this->once())
            ->method('trigger')
            ->with(
                $this->callback(function(UserEvent $event) {
                    $this->assertInstanceOf(UserEntityInterface::class, $event->getUser());
                    return $event->getName() === UserEvent::EVENT_CREATE_PRE;
                }),
                $this->callback(function($callback) {
                    if ($callback === null) {
                        return true;
                    }

                    $this->assertTrue($callback(false));
                    $this->assertFalse($callback(true));

                    return true;
                })
            )
            ->will($this->returnValue($result));

        $this->assertFalse($this->service->create($this->getMock(UserEntityInterface::class)));
    }

    /**
     * @covers ::create
     */
    public function testCreateDiscardShortCircuitIfStoppedAnLastResultIsNotFalse()
    {
        $result = new ResponseCollection();
        $result->setStopped(true);
        $result->push(null);

        $this->eventManager
            ->expects($this->any())
            ->method('trigger')
            ->with(
                $this->logicalOr(
                    $this->callback(function(UserEvent $event) {
                        $this->assertInstanceOf(UserEntityInterface::class, $event->getUser());
                        return $event->getName() === UserEvent::EVENT_CREATE_PRE;
                    }),
                    $this->callback(function(UserEvent $event) {
                        $this->assertInstanceOf(UserEntityInterface::class, $event->getUser());
                        return $event->getName() === UserEvent::EVENT_CREATE_POST;
                    })
                ),
                $this->callback(function($callback) {
                    if ($callback === null) {
                        return true;
                    }

                    $this->assertTrue($callback(false));
                    $this->assertFalse($callback(true));

                    return true;
                })
            )
            ->will($this->returnValue($result));

        $this->assertTrue($this->service->create($this->getMock(UserEntityInterface::class)));
    }

    /**
     * @covers ::create
     */
    public function testCreateFlushesObjectWithCreatedAndUpdatedTimestamp()
    {
        $user = $this->getMock(UserEntityInterface::class);
        $user
            ->expects($this->once())
            ->method('setCreatedAt')
            ->with($this->isInstanceOf(DateTime::class));
        $user
            ->expects($this->once())
            ->method('setUpdatedAt')
            ->with($this->isInstanceOf(DateTime::class));

        $this->objectManager
            ->expects($this->once())
            ->method('persist')
            ->with($user);

        $this->objectManager
            ->expects($this->once())
            ->method('flush');

        $this->eventManager
            ->expects($this->any())
            ->method('trigger')
            ->will($this->returnValue($this->getMock(ResponseCollection::class)));

        $this->assertTrue($this->service->create($user));
    }

    /**
     * @covers ::remove
     */
    public function testDeleteShortCircuitedFromEvmIfLastResultIsFalse()
    {
        $result = new ResponseCollection();
        $result->setStopped(true);
        $result->push(false);

        $this->eventManager
            ->expects($this->once())
            ->method('trigger')
            ->with(
                $this->callback(function(UserEvent $event) {
                    $this->assertInstanceOf(UserEntityInterface::class, $event->getUser());
                    return $event->getName() === UserEvent::EVENT_DELETE_PRE;
                }),
                $this->callback(function($callback) {
                    if ($callback === null) {
                        return true;
                    }

                    $this->assertTrue($callback(false));
                    $this->assertFalse($callback(true));

                    return true;
                })
            )
            ->will($this->returnValue($result));

        $this->assertFalse($this->service->remove($this->getMock(UserEntityInterface::class)));
    }

    /**
     * @covers ::remove
     */
    public function testDeleteDiscardShortCircuitIfStoppedAnLastResultIsNotFalse()
    {
        $result = new ResponseCollection();
        $result->setStopped(true);
        $result->push(null);

        $this->eventManager
            ->expects($this->any())
            ->method('trigger')
            ->with(
                $this->logicalOr(
                    $this->callback(function(UserEvent $event) {
                        $this->assertInstanceOf(UserEntityInterface::class, $event->getUser());
                        return $event->getName() === UserEvent::EVENT_DELETE_PRE;
                    }),
                    $this->callback(function(UserEvent $event) {
                        $this->assertInstanceOf(UserEntityInterface::class, $event->getUser());
                        return $event->getName() === UserEvent::EVENT_DELETE_POST;
                    })
                ),
                $this->callback(function($callback) {
                    if ($callback === null) {
                        return true;
                    }

                    $this->assertTrue($callback(false));
                    $this->assertFalse($callback(true));

                    return true;
                })
            )
            ->will($this->returnValue($result));

        $this->assertTrue($this->service->remove($this->getMock(UserEntityInterface::class)));
    }

    /**
     * @covers ::remove
     */
    public function testDeleteFlushesObjectWithDeletedTimestamp()
    {
        $user = $this->getMock(UserEntityInterface::class);

        $this->objectManager
            ->expects($this->once())
            ->method('remove')
            ->with($user);

        $this->objectManager
            ->expects($this->once())
            ->method('flush');

        $this->eventManager
            ->expects($this->any())
            ->method('trigger')
            ->will($this->returnValue($this->getMock(ResponseCollection::class)));

        $this->assertTrue($this->service->remove($user));
    }
}
