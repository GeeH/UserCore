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

namespace RoaveTest\User\Core\Authentication\Plugin;

use BaconAuthentication\Result\Result;
use BaconUser\Password\HandlerInterface;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Roave\User\Core\Authentication\Plugin\Exception\LogicException;
use Roave\User\Core\Authentication\Plugin\PasswordAuthentication;
use Roave\User\Core\Entity\UserEntityInterface;
use Roave\User\Core\Repository\UserRepositoryInterface;
use Zend\Stdlib\Parameters;

/**
 * Class PasswordAuthenticationTest
 *
 * @coversDefaultClass Roave\User\Core\Authentication\Plugin\PasswordAuthentication
 * @covers ::<!public>
 *
 * @group authentication
 */
class PasswordAuthenticationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var HandlerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $handler;

    /**
     * @var UserRepositoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var PasswordAuthentication
     */
    private $plugin;

    /**
     * @covers ::__construct
     */
    protected function setUp()
    {
        $this->handler    = $this->getMock(HandlerInterface::class);
        $this->repository = $this->getMock(UserRepositoryInterface::class);

        $this->plugin = new PasswordAuthentication(
            $this->repository,
            $this->handler
        );
    }

    /**
     * @return Parameters
     */
    private function getParameters()
    {
        return new Parameters([
            'identity' => 'identity',
            'password' => 'password'
        ]);
    }

    /**
     * @covers ::authenticateCredentials
     */
    public function testAuthenticateCredentialsWithMissingIdentity()
    {
        $this->repository
            ->expects($this->once())
            ->method('getByIdentity')
            ->with('identity');

        $result = $this->plugin->authenticateCredentials($this->getParameters());

        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isFailure());
    }

    /**
     * @covers ::authenticateCredentials
     */
    public function testAuthenticateCredentialsWithUnsupportedHash()
    {
        $this->setExpectedException(LogicException::class);

        $this->repository
            ->expects($this->once())
            ->method('getByIdentity')
            ->with('identity')
            ->will($this->returnValue($this->getMock(UserEntityInterface::class)));

        $this->handler
            ->expects($this->once())
            ->method('supports')
            ->will($this->returnValue(false));

        $this->plugin->authenticateCredentials($this->getParameters());
    }

    /**
     * @covers ::authenticateCredentials
     */
    public function testAuthenticateCredentialsWithInvalidPassword()
    {
        $user = $this->getMock(UserEntityInterface::class);
        $user
            ->expects($this->any())
            ->method('getPassword')
            ->will($this->returnValue('helloWorld'));

        $this->repository
            ->expects($this->once())
            ->method('getByIdentity')
            ->with('identity')
            ->will($this->returnValue($user));

        $this->handler
            ->expects($this->once())
            ->method('supports')
            ->with('helloWorld')
            ->will($this->returnValue(true));

        $this->handler
            ->expects($this->once())
            ->method('compare')
            ->with('password', 'helloWorld')
            ->will($this->returnValue(false));

        $result = $this->plugin->authenticateCredentials($this->getParameters());

        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isFailure());
    }

    /**
     * @covers ::authenticateCredentials
     */
    public function testAuthenticateCredentialsWithSuccessfulResponseWithUserIdPayload()
    {
        $user = $this->getMock(UserEntityInterface::class);
        $user
            ->expects($this->any())
            ->method('getPassword')
            ->will($this->returnValue('helloWorld'));

        $user
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));

        $this->repository
            ->expects($this->once())
            ->method('getByIdentity')
            ->with('identity')
            ->will($this->returnValue($user));

        $this->handler
            ->expects($this->once())
            ->method('supports')
            ->with('helloWorld')
            ->will($this->returnValue(true));

        $this->handler
            ->expects($this->once())
            ->method('compare')
            ->with('password', 'helloWorld')
            ->will($this->returnValue(true));

        $result = $this->plugin->authenticateCredentials($this->getParameters());

        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertSame(1, $result->getPayload());
    }
}
