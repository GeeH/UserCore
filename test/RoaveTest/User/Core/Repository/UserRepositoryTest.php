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

namespace RoaveTest\User\Core\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Roave\User\Core\Entity\UserEntityInterface;
use Roave\User\Core\Options\RepositoryOptions;
use Roave\User\Core\Repository\UserRepository;

/**
 * Class UserRepositoryTest
 *
 * @coversDefaultClass \Roave\User\Core\Repository\UserRepository
 * @covers ::<!public>
 *
 * @group repository
 */
class UserRepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RepositoryOptions|PHPUnit_Framework_MockObject_MockObject
     */
    private $options;

    /**
     * @var ObjectRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $objectRepository;

    /**
     * @var UserRepository
     */
    private $repository;

    protected function setUp()
    {
        $this->options          = $this->getMock(RepositoryOptions::class);
        $this->objectRepository = $this->getMock(ObjectRepository::class);

        $this->repository = new UserRepository(
            $this->objectRepository,
            $this->options
        );
    }

    /**
     * @covers ::getByIdentity
     */
    public function testGetByIdentity()
    {
        $this->options
            ->expects($this->once())
            ->method('getIdentifierProperty')
            ->will($this->returnValue('fooBarString'));

        $this->objectRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['fooBarString' => 1]);

        $this->repository->getByIdentity(1);
    }

    /**
     * @covers ::find
     */
    public function testFind()
    {
        $id     = 1337;
        $result = $this->getMock(UserEntityInterface::class);

        $this->objectRepository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->will($this->returnValue($result));

        $this->assertSame($result, $this->repository->find($id));
    }

    /**
     * @covers ::findAll
     */
    public function testFindAll()
    {
        $this->objectRepository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([]));

        $this->assertSame([], $this->repository->findAll());
    }

    /**
     * @covers ::findBy
     */
    public function testFindBy()
    {
        $limit    = 10;
        $offset   = 10;
        $orderBy  = ['foo' => 'asc'];
        $criteria = ['hello' => 'world'];

        $this->objectRepository
            ->expects($this->once())
            ->method('findBy')
            ->with($criteria, $orderBy, $limit, $offset)
            ->will($this->returnValue([]));

        $this->assertSame([], $this->repository->findBy($criteria, $orderBy, $limit, $offset));
    }

    /**
     * @covers ::findOneBy
     */
    public function testFindOneBy()
    {
        $user     = $this->getMock(UserEntityInterface::class);
        $criteria = ['world' => 'hello'];

        $this->objectRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($criteria)
            ->will($this->returnValue($user));

        $this->assertSame($user, $this->repository->findOneBy($criteria));
    }

    /**
     * @covers ::getClassName
     */
    public function testGetClassName()
    {
        $className = UserEntityInterface::class;

        $this->objectRepository
            ->expects($this->once())
            ->method('getClassName')
            ->will($this->returnValue($className));

        $this->assertSame($className, $this->repository->getClassName());
    }
}
