<?php

/**
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website http://www.znetdk.fr 
 * Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
 * License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
 * --------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------
 * Core application controller for authentication
 *
 * File version: 1.2
 * Last update: 05/20/2017
 */

namespace controller;

class Security extends \AppController {

    /**
     * Action called to cancel the login process of the user trying to connect
     * @return \Response Response returned to the main controller
     */
    static protected function action_cancellogin() {
        $response = new \Response(FALSE);
        \UserSession::clearUserSession();
        $response->success = TRUE;
        $response->msg = LC_MSG_INF_CANCEL_LOGIN;
        return $response;
    }

    /**
     * Action called to authenticate the user attempting to connect
     * @return \Response Response returned to the main controller
     */
    static protected function action_login() {
        $loginOk = TRUE;
        $errorMsg = '';
        $response = new \Response(FALSE);
        $validator = new \validator\Authentication(FALSE);
        $changePasswordRequested = !($validator->getValue('login_password') === null && $validator->getValue('login_password2') === null);
        if (!$validator->validate()) {
            //Data validation failed...
            $response->setFailedMessage(LC_FORM_TITLE_LOGIN, $validator->getErrorMessage(), $validator->getErrorVariable());
            $loginOk = FALSE;
            $errorMsg = $validator->getErrorMessage();
        } else { // Data validation is OK
            // Get user infos from the DB security tables 
            $user = \UserManager::getUserInfos($validator->getValue('login_name'));
            // Check user credentials
            if ($user && \MainController::execute('security', 'isPasswordValid', $validator->getValue('password'), $user['login_password'])) {
                // Authentication has succeed
                \UserSession::resetAuthentHasFailed();
                if (!$user['user_enabled']) {
                    // But user account is disabled
                    $response->setFailedMessage(LC_FORM_TITLE_LOGIN, LC_MSG_ERR_LOGIN_DISABLED, 'login_name');
                    $loginOk = FALSE;
                    $errorMsg = LC_MSG_ERR_LOGIN_DISABLED;
                } elseif (new \DateTime($user['expiration_date']) < new \DateTime('now') && !$changePasswordRequested) {
                    // But password has expired
                    $response->setFailedMessage(LC_FORM_TITLE_LOGIN, LC_MSG_ERR_LOGIN_EXPIRATION, 'login_name');
                    $response->newpasswordrequired = TRUE;
                } else { // Authentication has succeeded
                    $result = TRUE;
                    if ($changePasswordRequested) {
                        $response->setSuccessMessage(LC_FORM_TITLE_CHANGE_PASSWORD, LC_MSG_INF_PWDCHANGED);
                        $result = \MainController::execute('users', 'changePassword');
                    } else {
                        $response->setSuccessMessage(LC_FORM_TITLE_LOGIN, LC_MSG_INF_LOGIN);
                    }
                    if ($result === TRUE) {
                        \UserSession::setLoginName($validator->getValue('login_name'));
                        \UserSession::setAccessMode($validator->getValue('access'));
                    } else {
                        $response->setFailedMessage(LC_FORM_TITLE_LOGIN, $result, 'login_password');
                        $loginOk = FALSE;
                        $errorMsg = $result;
                    }
                }
            } elseif ($user && $user['user_enabled']) { // Password is invalid but user exists and its account is enabled...
                // The counter of allowed login attempts is incremented
                \UserSession::setAuthentHasFailed($validator->getValue('login_name'));
                if (\UserSession::isMaxNbrOfFailedAuthentReached() && \MainController::execute('users', 'disableUser', $validator->getValue('login_name'))) {
                    // The max number of authentications allowed has been reached
                    // User account has been disabled
                    $response->setFailedMessage(LC_FORM_TITLE_LOGIN, LC_MSG_ERR_LOGIN_TOO_MUCH_ATTEMPTS,'login_name');
                    $response->toomuchattempts = TRUE;
                    $loginOk = FALSE;
                    $errorMsg = LC_MSG_ERR_LOGIN_TOO_MUCH_ATTEMPTS;
                } else { // Number of login attempts not yet exceeded
                    $response->setFailedMessage(LC_FORM_TITLE_LOGIN, LC_MSG_ERR_LOGIN, $changePasswordRequested ? 'password' : 'login_name');
                    $loginOk = FALSE;
                    $errorMsg = LC_MSG_ERR_LOGIN;
                }
            } else { // User unknown or user exists but he's disabled and his password is invalid.
                $response->setFailedMessage(LC_FORM_TITLE_LOGIN, LC_MSG_ERR_LOGIN, $changePasswordRequested ? 'password' : 'login_name');
                $loginOk = FALSE;
                $errorMsg = LC_MSG_ERR_LOGIN;
            }
        }
        // For tracking connections purpose (just declare a 'Security::loginResult' in your application)
        \MainController::execute('security', 'loginResult', array(
            'login_date' => \General::getCurrentW3CDate(TRUE),
            'login_name' => $validator->getValue('login_name'),
            'ip_address' => \Request::getRemoteAddress(),
            'status' => $loginOk,
            'message' => $errorMsg
        ));
        return $response;
    }

    /**
     * Action called to logout the currently connected user
     * @return \Response Response returned to the main controller
     */
    static protected function action_logout() {
        $response = new \Response(FALSE);
        \UserSession::clearUserSession();
        $response->success = TRUE;
        $response->msg = LC_MSG_INF_LOGOUT;
        return $response;
    }

    /**
     * Returns the translated labels displayed on the connection dialog box
     * @return \Response Response returned to the main controller
     */
    static protected function action_getlogindialoglabels() {
        $response = new \Response(FALSE); // FALSE --> no authentication required
        $response->title = LC_FORM_TITLE_LOGIN;
        $response->loginFieldLabel = LC_FORM_LBL_LOGIN_ID;
        $response->passwordFieldLabel = LC_FORM_LBL_PASSWORD;
        $response->loginButtonLabel = LC_BTN_LOGIN;
        $response->cancelButtonLabel = LC_BTN_CANCEL;
        $response->accessLabel = LC_FORM_LBL_ACCESS;
        $response->publicAccessLabel = LC_FORM_LBL_PUBL_ACC;
        $response->privateAccessLabel = LC_FORM_LBL_PRIV_ACC;
        $response->fieldMandatory = LC_MSG_ERR_MISSING_VALUE;
        $response->defaultAccess = CFG_SESSION_DEFAULT_MODE;
        $response->selectAccess = CFG_SESSION_SELECT_MODE;
        $response->changePasswordTitle = LC_FORM_TITLE_CHANGE_PASSWORD;
        $response->changePasswordButton = LC_BTN_SAVE;
        $response->changePasswordOriginal = LC_FORM_LBL_ORIG_PASSWORD;
        $response->changePasswordNew = LC_FORM_LBL_NEW_PASSWORD;
        $response->changePasswordConfirm = LC_FORM_LBL_PASSWORD_CONFIRM;
        return $response;
    }

    // Other security methods that can be overiden by the class named 'security' 
    // in the /app/controller directory.
    
    /**
     * Indicates which are the granted menu items to the currently connected user 
     * @return string|array Value "ALL" if the connected user has a full access
     *  to the navigation menu, otherwise the menu items which are granted to him
     *  thru his assigned profiles
     */
    static public function getAllowedMenuItems() {
        $loginName = \UserSession::getLoginName();
        // Has user full access to the menu items?
        if (\UserManager::hasUserFullMenuAccess($loginName)) {
            // String "ALL" is returned because all menu items are allowed 
            return "ALL";
        } else {
            // Get menu items authorized for the authenticated user
            return \UserManager::getGrantedMenuItemsToUser($loginName);
        }
    }

    /**
     * Checks whether the user password is valid 
     * @param type $inputPassword Password typed in by the user
     * @param type $storedPassword Password stored in the database
     * @return boolean TRUE if the password is valid else FALSE.
     */
    static public function isPasswordValid($inputPassword, $storedPassword) {
        return password_verify($inputPassword, $storedPassword);
    }

}
