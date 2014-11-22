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

namespace RoaveTest\User\Core\Service\Listener;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Roave\User\Core\Entity\UserEntityInterface;
use Roave\User\Core\Event\UserEvent;
use Roave\User\Core\Service\Listener\ModificationProtectionListener;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use ZfcRbac\Exception\UnauthorizedException;
use ZfcRbac\Service\AuthorizationServiceInterface;

/**
 * Class ModificationProtectionListenerTest
 *
 * @coversDefaultClass \Roave\User\Core\Service\Listener\ModificationProtectionListener
 * @covers ::<!public>
 *
 * @group service
 */
class ModificationProtectionListenerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AuthorizationServiceInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $authorizationService;

    /**
     * @var ModificationProtectionListener
     */
    private $listener;

    protected function setUp()
    {
        $this->authorizationService = $this->getMock(AuthorizationServiceInterface::class);
        $this->listener = new ModificationProtectionListener($this->authorizationService);
    }

    /**
     * @covers ::attach
     */
    public function testAttach()
    {
        $eventManager = $this->getMock(EventManagerInterface::class);
        $eventManager
            ->expects($this->at(0))
            ->method('attach')
            ->with(
                UserEvent::EVENT_UPDATE_PRE,
                [$this->listener, 'authorizeUpdate'],
                ModificationProtectionListener::LISTENER_PRIORITY
            );

        $eventManager
            ->expects($this->at(1))
            ->method('attach')
            ->with(
                UserEvent::EVENT_DELETE_PRE,
                [$this->listener, 'authorizeDelete'],
                ModificationProtectionListener::LISTENER_PRIORITY
            );

        $this->listener->attach($eventManager);
    }

    /**
     * @covers ::authorizeUpdate
     */
    public function testAuthorizeUpdateWithDeniedAuthorization()
    {
        $this->setExpectedException(UnauthorizedException::class);

        $user = $this->getMock(UserEntityInterface::class);

        $event = new UserEvent(UserEvent::EVENT_UPDATE_PRE);
        $event->setUser($user);

        $this->authorizationService
            ->expects($this->once())
            ->method('isGranted')
            ->with(ModificationProtectionListener::PERMISSION_UPDATE, $user)
            ->will($this->returnValue(false));

        $this->listener->authorizeUpdate($event);
    }

    /**
     * @covers ::authorizeUpdate
     */
    public function testAuthorizeUpdateWithApprovedAuthorization()
    {
        $user = $this->getMock(UserEntityInterface::class);

        $event = new UserEvent(UserEvent::EVENT_UPDATE_PRE);
        $event->setUser($user);

        $this->authorizationService
            ->expects($this->once())
            ->method('isGranted')
            ->with(ModificationProtectionListener::PERMISSION_UPDATE, $user)
            ->will($this->returnValue(true));

        $this->listener->authorizeUpdate($event);
    }

    /**
     * @covers ::authorizeDelete
     */
    public function testAuthorizeDeleteWithDeniedAuthorization()
    {
        $this->setExpectedException(UnauthorizedException::class);

        $user = $this->getMock(UserEntityInterface::class);

        $event = new UserEvent(UserEvent::EVENT_DELETE_PRE);
        $event->setUser($user);

        $this->authorizationService
            ->expects($this->once())
            ->method('isGranted')
            ->with(ModificationProtectionListener::PERMISSION_DELETE, $user)
            ->will($this->returnValue(false));

        $this->listener->authorizeDelete($event);
    }

    /**
     * @covers ::authorizeDelete
     */
    public function testAuthorizeDeleteWithApprovedAuthorization()
    {
        $user = $this->getMock(UserEntityInterface::class);

        $event = new UserEvent(UserEvent::EVENT_DELETE_PRE);
        $event->setUser($user);

        $this->authorizationService
            ->expects($this->once())
            ->method('isGranted')
            ->with(ModificationProtectionListener::PERMISSION_DELETE, $user)
            ->will($this->returnValue(true));

        $this->listener->authorizeDelete($event);
    }
}
