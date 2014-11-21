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
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 */

namespace Roave\User\Service\Listener;

use Roave\User\Event\UserEvent;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use ZfcRbac\Exception\UnauthorizedException;
use ZfcRbac\Service\AuthorizationServiceInterface;

class ModificationProtectionListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    const LISTENER_PRIORITY = 500;
    const PERMISSION_UPDATE = 'roave:user.update';
    const PERMISSION_DELETE = 'roave.user.delete';

    /**
     * @var AuthorizationServiceInterface
     */
    private $authorizationService;

    /**
     * @param AuthorizationServiceInterface $authorizationService
     */
    public function __construct(AuthorizationServiceInterface $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            UserEvent::EVENT_UPDATE_PRE,
            [$this, 'authorizeUpdate'],
            static::LISTENER_PRIORITY
        );

        $this->listeners[] = $events->attach(
            UserEvent::EVENT_DELETE_PRE,
            [$this, 'authorizeDelete'],
            static::LISTENER_PRIORITY
        );
    }

    /**
     * Callback to authorize updating the user
     *
     * @throws UnauthorizedException
     *
     * @param UserEvent $user
     *
     * @return void
     */
    public function authorizeUpdate(UserEvent $user)
    {
        if (!$this->authorizationService->isGranted(static::PERMISSION_UPDATE, $user->getUser())) {
            throw new UnauthorizedException();
        }
    }

    /**
     * Callback to authorize deleting the user
     *
     * @throws UnauthorizedException
     *
     * @param UserEvent $user
     *
     * @return void
     */
    public function authorizeDelete(UserEvent $user)
    {
        if (!$this->authorizationService->isGranted(static::PERMISSION_DELETE, $user->getUser())) {
            throw new UnauthorizedException();
        }
    }
}
