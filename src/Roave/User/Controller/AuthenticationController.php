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

namespace Roave\User\Controller;

use BaconAuthentication\AuthenticationServiceInterface;
use Roave\User\Options\AuthenticationOptions;
use Zend\Form\FormInterface;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class AuthenticationController
 *
 * @method \Zend\Http\Request getRequest
 * @method \Zend\Http\Response getResponse
 */
class AuthenticationController extends AbstractActionController
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var AuthenticationOptions
     */
    private $options;

    /**
     * @var AuthenticationServiceInterface
     */
    private $authenticationService;

    /**
     * @param AuthenticationOptions          $options
     * @param FormInterface                  $form
     * @param AuthenticationServiceInterface $authenticationService
     */
    public function __construct(
        AuthenticationOptions $options,
        FormInterface $form,
        AuthenticationServiceInterface $authenticationService
    ) {
        $this->form                  = $form;
        $this->options               = $options;
        $this->authenticationService = $authenticationService;
    }

    /**
     * Attempt to authenticate the user
     *
     * @return ViewModel|Response
     */
    public function loginAction()
    {
        $result = $this->authenticationService->authenticate($this->getRequest(), $this->getResponse());

        if ($result->isChallenge()) {
            return $this->getResponse();
        }

        $model = new ViewModel();
        $model->setTemplate($this->options->getLoginTemplate());
        $model->setVariable('form', $this->form);

        return $model;
    }

    public function logoutAction()
    {
    }
}
