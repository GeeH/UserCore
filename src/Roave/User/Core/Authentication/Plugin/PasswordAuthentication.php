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

namespace Roave\User\Core\Authentication\Plugin;

use BaconAuthentication\Plugin\AuthenticationPluginInterface;
use BaconAuthentication\Result\Error;
use BaconAuthentication\Result\Result;
use BaconUser\Password\HandlerInterface;
use Roave\User\Core\Repository\UserRepositoryInterface;
use Zend\Stdlib\ParametersInterface;

class PasswordAuthentication implements AuthenticationPluginInterface
{
    /**
     * @var HandlerInterface
     */
    private $passwordHandler;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param HandlerInterface        $passwordHandler
     */
    public function __construct(UserRepositoryInterface $userRepository, HandlerInterface $passwordHandler)
    {
        $this->userRepository  = $userRepository;
        $this->passwordHandler = $passwordHandler;
    }

    /**
     * Return a failed authentication result with a nice error payload
     *
     * @return Result
     */
    private function getFailedAuthenticationResult()
    {
        return new Result(
            Result::STATE_FAILURE,
            new Error('Identity not found', 'No user matches the given credentials')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function authenticateCredentials(ParametersInterface $credentials)
    {
        $user = $this->userRepository->getByIdentity($credentials->get('identity'));
        if (! $user) {
            return $this->getFailedAuthenticationResult();
        }

        if (! $this->passwordHandler->supports($user->getPassword())) {
            throw new Exception\LogicException('A unsupported password hash was found.');
        }

        if (!$this->passwordHandler->compare($credentials->get('password'), $user->getPassword())) {
            return $this->getFailedAuthenticationResult();
        }

        return new Result(Result::STATE_SUCCESS, $user->getId());
    }
}
