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

namespace Roave\User\Options;

use Roave\User\Form\RegistrationForm;
use Zend\Stdlib\AbstractOptions;

class RegistrationOptions extends AbstractOptions
{
    /**
     * The template used for the registration
     *
     * @var string
     */
    private $formTemplate = 'roave/user/registration/form';

    /**
     * The template used when the user successfully registers
     *
     * @var string
     */
    private $successTemplate = 'roave/user/registration/success';

    /**
     * Redirect to this route on a successful registration
     *
     * @var string|null
     */
    private $redirectOnSuccessToRoute;

    /**
     * The form to use
     *
     * @var string
     */
    private $form = RegistrationForm::class;

    /**
     * @return string
     */
    public function getSuccessTemplate()
    {
        return $this->successTemplate;
    }

    /**
     * @param string $successTemplate
     */
    public function setSuccessTemplate($successTemplate)
    {
        $this->successTemplate = (string) $successTemplate;
    }

    /**
     * @return null|string
     */
    public function getRedirectOnSuccessToRoute()
    {
        return $this->redirectOnSuccessToRoute;
    }

    /**
     * @param null|string $redirectOnSuccessToRoute
     */
    public function setRedirectOnSuccessToRoute($redirectOnSuccessToRoute)
    {
        $this->redirectOnSuccessToRoute = (string) $redirectOnSuccessToRoute;
    }

    /**
     * @return string
     */
    public function getFormTemplate()
    {
        return $this->formTemplate;
    }

    /**
     * @param string $formTemplate
     */
    public function setFormTemplate($formTemplate)
    {
        $this->formTemplate = (string) $formTemplate;
    }

    /**
     * @return string
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param string $form
     */
    public function setForm($form)
    {
        $this->form = (string) $form;
    }
}
