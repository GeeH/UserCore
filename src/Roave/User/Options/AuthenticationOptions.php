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

use Roave\User\Form\AuthenticationForm;
use Zend\Stdlib\AbstractOptions;

class AuthenticationOptions extends AbstractOptions
{
    /**
     * The field that is the users identity
     *
     * @var string
     */
    private $identityField = 'email';

    /**
     * The route to use that actually executes the authentication process
     *
     * @var string
     */
    private $authenticationRoute = 'authenticate';

    /**
     * The route used for the login page
     *
     * @var string
     */
    private $loginRoute = 'login';

    /**
     * The template that contains the login form
     *
     * @var string
     */
    private $loginTemplate = 'roave/user/authentication/login';

    /**
     * The url to redirect the user to on a successful authentication
     *
     * @var string|null
     */
    private $loginRedirectToRoute;

    /**
     * The query parameter to user to redirect the user
     *
     * @var string
     */
    private $redirectField = 'next';

    /**
     * The authentication form to be used
     *
     * @var string
     */
    private $authenticationForm = AuthenticationForm::class;

    /**
     * The route used to logout
     *
     * @var string
     */
    private $logoutRoute = 'logout';

    /**
     * The template user when the user has logged out
     *
     * @var string
     */
    private $logoutTemplate = 'roave/user/authentication/logout';

    /**
     * If not null, this route is used instead of displaying a logout template
     *
     * @var string|null
     */
    private $logoutRedirectToRoute;

    /**
     * @return string
     */
    public function getLoginTemplate()
    {
        return $this->loginTemplate;
    }

    /**
     * @param string $loginTemplate
     */
    public function setLoginTemplate($loginTemplate)
    {
        $this->loginTemplate = (string) $loginTemplate;
    }

    /**
     * @return null|string
     */
    public function getLoginRedirectToRoute()
    {
        return $this->loginRedirectToRoute;
    }

    /**
     * @param null|string $loginRedirectToRoute
     */
    public function setLoginRedirectToRoute($loginRedirectToRoute)
    {
        $this->loginRedirectToRoute = $loginRedirectToRoute ? (string) $loginRedirectToRoute : null;
    }

    /**
     * @return string
     */
    public function getRedirectField()
    {
        return $this->redirectField;
    }

    /**
     * @param string $redirectField
     */
    public function setRedirectField($redirectField)
    {
        $this->redirectField = (string)$redirectField;
    }

    /**
     * @return string
     */
    public function getAuthenticationForm()
    {
        return $this->authenticationForm;
    }

    /**
     * @param string $authenticationForm
     */
    public function setAuthenticationForm($authenticationForm)
    {
        $this->authenticationForm = (string) $authenticationForm;
    }

    /**
     * @return string
     */
    public function getLogoutTemplate()
    {
        return $this->logoutTemplate;
    }

    /**
     * @param string $logoutTemplate
     */
    public function setLogoutTemplate($logoutTemplate)
    {
        $this->logoutTemplate = (string) $logoutTemplate;
    }

    /**
     * @return null|string
     */
    public function getLogoutRedirectToRoute()
    {
        return $this->logoutRedirectToRoute;
    }

    /**
     * @param null|string $logoutRedirectToRoute
     */
    public function setLogoutRedirectToRoute($logoutRedirectToRoute)
    {
        $this->logoutRedirectToRoute = $logoutRedirectToRoute ? (string) $logoutRedirectToRoute : null;
    }

    /**
     * @return string
     */
    public function getAuthenticationRoute()
    {
        return $this->authenticationRoute;
    }

    /**
     * @param string $authenticationRoute
     */
    public function setAuthenticationRoute($authenticationRoute)
    {
        $this->authenticationRoute = (string) $authenticationRoute;
    }

    /**
     * @return string
     */
    public function getLoginRoute()
    {
        return $this->loginRoute;
    }

    /**
     * @param string $loginRoute
     */
    public function setLoginRoute($loginRoute)
    {
        $this->loginRoute = (string) $loginRoute;
    }

    /**
     * @return string
     */
    public function getLogoutRoute()
    {
        return $this->logoutRoute;
    }

    /**
     * @param string $logoutRoute
     */
    public function setLogoutRoute($logoutRoute)
    {
        $this->logoutRoute = (string) $logoutRoute;
    }

    /**
     * @return string
     */
    public function getIdentityField()
    {
        return $this->identityField;
    }

    /**
     * @param string $identityField
     */
    public function setIdentityField($identityField)
    {
        $this->identityField = (string) $identityField;
    }
}
