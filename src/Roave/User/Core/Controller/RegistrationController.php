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

namespace Roave\User\Core\Controller;

use Roave\User\Core\Options\RegistrationOptions;
use Roave\User\Core\Service\UserServiceInterface;
use Zend\Form\FormInterface;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class RegistrationController
 *
 * @method \Zend\Http\Request getRequest
 * @method \Zend\Http\Response getResponse
 */
class RegistrationController extends AbstractActionController
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var RegistrationOptions
     */
    private $options;

    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * @param RegistrationOptions  $options
     * @param FormInterface        $form
     * @param UserServiceInterface $userService
     */
    public function __construct(RegistrationOptions $options, FormInterface $form, UserServiceInterface $userService)
    {
        $this->form        = $form;
        $this->options     = $options;
        $this->userService = $userService;
    }

    /**
     * @return ViewModel|Response
     */
    public function indexAction()
    {
        $this->form->setAttribute('method', 'POST');
        $this->form->setAttribute('action', $this->getRequest()->getUriString());

        $prg = $this->prg($this->getRequest()->getUriString(), true);
        if ($prg instanceof Response) {
            return $prg;
        }

        $model = new ViewModel();
        $model->setTemplate($this->options->getFormTemplate());
        $model->setVariable('form', $this->form);

        if (!is_array($prg)) {
            return $model;
        }

        $this->form->setData($prg);
        if ($this->form->isValid()) {

            $user = $this->form->getObject();

            if (!$this->userService->create($user)) {
                // todo: not sure about this one...
                throw new \LogicException('An error occurred');
            }

            if ($this->options->getRedirectOnSuccessToRoute() !== null) {
                return $this->redirect()->toRoute($this->options->getRedirectOnSuccessToRoute());
            }

            $model->setTemplate($this->options->getSuccessTemplate());
            $model->setVariable('user', $user);
        }

        return $model;
    }
}
