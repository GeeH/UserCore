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

namespace Roave\User\InputFilter;

use Roave\User\Validator\NoUserObjectExists;
use Zend\Filter\StringTrim;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

class RegistrationInputFilter extends InputFilter
{
    /**
     * {@inheritDoc}
     */
    public function init()
    {
        $this->add([
            'name'    => 'username',
            'filters' => [
                [
                    'name' => StringTrim::class
                ]
            ],

            'validators' => [
                [
                    'name'    => NoUserObjectExists::class,
                    'options' => [
                        'fields' => 'username'
                    ]
                ]
            ]
        ]);

        $this->add([
            'name'    => 'email',
            'filters' => [
                [
                    'name' => StringTrim::class
                ]
            ],

            'validators' => [
                [
                    'name'    => NoUserObjectExists::class,
                    'options' => [
                        'fields' => 'email'
                    ]
                ]
            ]
        ]);

        $this->add([
            'name'       => 'password',
            'validators' => [
                [
                    'name'    => StringLength::class,
                    'options' => [
                        'min' => 4
                    ]
                ]
            ]
        ]);

        $this->add([
            'name'    => 'firstName',
            'filters' => [
                [
                    'name' => StringTrim::class
                ]
            ],

            'validators' => [
                [
                    'name'    => StringLength::class,
                    'options' => [
                        'max' => 30
                    ]
                ]
            ]
        ]);

        $this->add([
            'name'    => 'lastName',
            'filters' => [
                [
                    'name' => StringTrim::class
                ]
            ],

            'validators' => [
                [
                    'name'    => StringLength::class,
                    'options' => [
                        'max' => 30
                    ]
                ]
            ]
        ]);
    }
}
