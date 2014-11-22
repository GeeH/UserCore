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

use Roave\User\Core\Controller\AuthenticationController;
use Roave\User\Core\Controller\RegistrationController;

return [
    'router' => [
        'routes' => [
            'login' => [
                'type'    => 'literal',
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => AuthenticationController::class,
                        'action'     => 'index'
                    ]
                ]
            ],

            'logout' => [
                'type'    => 'literal',
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => AuthenticationController::class,
                        'action'     => 'logout'
                    ]
                ]
            ],

            'authenticate' => [
                'type'    => 'literal',
                'options' => [
                    'route'    => '/authenticate',
                    'defaults' => [
                        'controller' => AuthenticationController::class,
                        'action'     => 'authenticate'
                    ]
                ]
            ],

            'register' => [
                'type'    => 'literal',
                'options' => [
                    'route'    => '/register',
                    'defaults' => [
                        'controller' => RegistrationController::class,
                        'action'     => 'index'
                    ]
                ]
            ],
        ]
    ]
];
