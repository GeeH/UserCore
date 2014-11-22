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

use BaconAuthentication\PluggableAuthenticationService;
use Roave\User\Core\Authentication\Plugin\PasswordAuthentication;
use Roave\User\Core\Authentication\Plugin\ResolveUserIdentifier;
use Roave\User\Core\Controller\AuthenticationController;
use Roave\User\Core\Controller\RegistrationController;
use Roave\User\Core\Factory\AbstractOptionsFactory;
use Roave\User\Core\Factory\Authentication\Plugin\PasswordAuthenticationFactory;
use Roave\User\Core\Factory\Authentication\Plugin\ResolveUserIdentifierFactory;
use Roave\User\Core\Factory\Controller\AuthenticationControllerFactory;
use Roave\User\Core\Factory\Controller\RegistrationControllerFactory;
use Roave\User\Core\Factory\Hydrator\RegistrationHydratorFactory;
use Roave\User\Core\Factory\PluggableAuthenticationServiceFactory;
use Roave\User\Core\Factory\Repository\UserRepositoryFactory;
use Roave\User\Core\Factory\Service\UserServiceFactory;
use Roave\User\Core\Factory\Stdlib\Hydrator\Strategy\PasswordStrategyFactory;
use Roave\User\Core\Factory\Validator\NoUserObjectExistsFactory;
use Roave\User\Core\Factory\Validator\UserObjectExistsFactory;
use Roave\User\Core\Hydrator\RegistrationHydrator;
use Roave\User\Core\Repository\UserRepository;
use Roave\User\Core\Service\UserService;
use Roave\User\Core\Stdlib\Hydrator\Strategy\PasswordStrategy;
use Roave\User\Core\Validator\NoUserObjectExists;
use Roave\User\Core\Validator\UserObjectExists;

return [
    'controllers' => [
        'factories' => [
            RegistrationController::class  => RegistrationControllerFactory::class,
            AuthenticationController::class => AuthenticationControllerFactory::class
        ]
    ],

    'validators' => [
        'factories' => [
            UserObjectExists::class   => UserObjectExistsFactory::class,
            NoUserObjectExists::class => NoUserObjectExistsFactory::class,
        ]
    ],

    'hydrators' => [
        'factories' => [
            RegistrationHydrator::class => RegistrationHydratorFactory::class
        ]
    ],

    'service_manager' => [
        'abstract_factories' => [
            AbstractOptionsFactory::class => AbstractOptionsFactory::class
        ],

        'factories' => [
            // Authentication
            ResolveUserIdentifier::class          => ResolveUserIdentifierFactory::class,
            PasswordAuthentication::class         => PasswordAuthenticationFactory::class,
            PluggableAuthenticationService::class => PluggableAuthenticationServiceFactory::class,

            // Services
            UserService::class => UserServiceFactory::class,

            // Repository
            UserRepository::class => UserRepositoryFactory::class,

            // Misc
            PasswordStrategy::class => PasswordStrategyFactory::class
        ]
    ],

    'view_manager' => [
        'template_map' => [

            // Authentication views
            'roave/user/authentication/login'  => __DIR__ . '/../view/authentication/login.phtml',
            'roave/user/authentication/logout' => __DIR__ . '/../view/authentication/logout.phtml',

            // Registration views
            'roave/user/registration/form'    => __DIR__ . '/../view/registration/form.phtml',
            'roave/user/registration/success' => __DIR__ . '/../view/registration/success.phtml'
        ]
    ],

    'roave' => [
        'options' => include __DIR__ . '/roave.options.config.php'
    ],

    'doctrine' => include __DIR__ . '/doctrine.config.php'
];
