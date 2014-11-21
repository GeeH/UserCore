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

namespace Roave\User\Hydrator;

use Roave\User\Entity\UserEntityInterface;
use Zend\Stdlib\Hydrator\AbstractHydrator;

class RegistrationHydrator extends AbstractHydrator
{
    /**
     * {@inheritDoc}
     */
    public function extract($object)
    {
        if (! $object instanceof UserEntityInterface) {
            throw Exception\InvalidObjectException::fromObject($object, UserEntityInterface::class);
        }

        return [
            'id'        => $this->extractValue('id', $object->getId(), $object),
            'email'     => $this->extractValue('email', $object->getEmail(), $object),
            'username'  => $this->extractValue('username', $object->getUsername(), $object),
            'firstName' => $this->extractValue('firstName', $object->getFirstName(), $object),
            'lastName'  => $this->extractValue('lastName', $object->getLastName(), $object),
            'createdAt' => $this->extractValue('createdAt', $object->getCreatedAt(), $object),
            'updatedAt' => $this->extractValue('updatedAt', $object->getUpdatedAt(), $object),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate(array $data, $object)
    {
        if (! $object instanceof UserEntityInterface) {
            throw Exception\InvalidObjectException::fromObject($object, UserEntityInterface::class);
        }

        $object->setEmail($this->hydrateValue('email', $data['email'], $data));
        $object->setUsername($this->hydrateValue('username', $data['username'], $data));
        $object->setFirstName($this->hydrateValue('firstName', $data['firstName'], $data));
        $object->setLastName($this->hydrateValue('lastName', $data['lastName'], $data));
        $object->setPassword($this->hydrateValue('password', $data['password'], $data));

        return $object;
    }
}
