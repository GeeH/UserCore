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

namespace Roave\User\Service;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Roave\User\Entity\UserEntityInterface;
use Roave\User\Event\UserEvent;
use Zend\EventManager\EventManager;

class UserService implements UserServiceInterface
{
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventManager()
    {
        if ($this->eventManager === null) {
            $this->eventManager = new EventManager();
            $this->eventManager->setEventClass(UserEvent::class);
            $this->eventManager->setIdentifiers([static::class, __CLASS__]);
        }

        return $this->eventManager;
    }

    /**
     * Create a new user event
     *
     * @param string              $name
     * @param UserEntityInterface $user
     * @param array|object|null   $params
     *
     * @return UserEvent
     */
    private function createUserEvent($name, UserEntityInterface $user, $params = null)
    {
        $event = new UserEvent($name, $this, $params);
        $event->setUser($user);

        return $event;
    }

    /**
     * {@inheritDoc}
     */
    public function update(UserEntityInterface $user)
    {
        $result = $this->getEventManager()->trigger($this->createUserEvent(UserEvent::EVENT_UPDATE_PRE, $user));

        if ($result->stopped() && $result->last() === false) {
            return false;
        }

        $user->setUpdatedAt(new DateTime());
        $this->objectManager->flush();

        $this
            ->getEventManager()
            ->trigger($this->createUserEvent(UserEvent::EVENT_UPDATE_POST, $user));

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function create(UserEntityInterface $user)
    {
        $result = $this->getEventManager()->trigger($this->createUserEvent(UserEvent::EVENT_CREATE_PRE, $user));

        if ($result->stopped() && $result->last() === false) {
            return false;
        }

        $user->setCreatedAt(new DateTime());
        $user->setUpdatedAt(new DateTime());

        $this->objectManager->persist($user);
        $this->objectManager->flush();

        $this
            ->getEventManager()
            ->trigger($this->createUserEvent(UserEvent::EVENT_UPDATE_POST, $user));

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function remove(UserEntityInterface $user)
    {
        $result = $this->getEventManager()->trigger($this->createUserEvent(UserEvent::EVENT_DELETE_PRE, $user));

        if ($result->stopped() && $result->last() === false) {
            return false;
        }

        $this->objectManager->remove($user);
        $this->objectManager->flush();

        $this
            ->getEventManager()
            ->trigger($this->createUserEvent(UserEvent::EVENT_DELETE_POST, $user));

        return true;
    }
}
