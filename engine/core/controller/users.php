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
 * Core application controller for user management
 *
 * File version: 1.4
 * Last update: 05/20/2017 
 */

namespace controller;

/**
 * ZnetDK Core controller for user management
 */
class Users extends \AppController {

    // Action methods
    
    /**
     * Returns the list of the users defined for the application
     * @return \Response Response returned to the main controller
     */
    static protected function action_all() {
        $response = new \Response();
        // Get order specifications from request
        $request = new \Request();
        $sortfield = $request->sortfield;
        $sortorder = $request->sortorder;
        $sortCriteria = isset($sortfield) && isset($sortorder) ? $sortfield . ($sortorder == -1 ? ' DESC' : '') : 'login_name';
        $users = array();
        // JSON Response
        $response->total = \UserManager::getAllUsers($sortCriteria, $users);
        $response->rows = $users;
        $response->success = TRUE;
        return $response;
    }

    /**
     * Returns the list of user profile defined for the application
     * @return \Response Response returned to the main controller
     */
    static protected function action_profiles() {
        $response = new \Response();
        // Get profiles from DB
        $response->rows = \ProfileManager::getProfiles();
        $response->success = TRUE;
        return $response;
    }

    /**
     * Saves the user data sent thru the HTTP request
     * @return \Response Response returned to the main controller
     */
    static protected function action_save() {
        $response = new \Response();
        $request = new \Request();
        $isNewUser = $request->user_id ? FALSE : TRUE;
        $summary = $isNewUser ? LC_FORM_TITLE_USER_NEW : LC_FORM_TITLE_USER_MODIFY;
        $validator = new \validator\User();
        if ($validator->validate()) { // Data validation is OK
            $userRow = $validator->getValues();
            // Convert string number to boolean
            $userRow['full_menu_access'] = ($userRow['full_menu_access'] == 1);
            // Password is stored only if is new or has been changed
            if ($userRow['login_password'] === \General::getDummyPassword()) {
                unset($userRow['login_password']);
                $doesPasswordChanged = FALSE;
            } else { // Hashed password is stored in the database
                $passwordInClear = $userRow['login_password'];
                $doesPasswordChanged = TRUE;
                $userRow['login_password'] = \MainController::execute('users', 'hashPassword', $passwordInClear);
            }
            // Password 2 is always removed (not stored)
            unset($userRow['login_password2']);
            // Storing data into the database
            \UserManager::storeUser($userRow, $request->profiles);
            $response->setSuccessMessage($summary, LC_MSG_INF_SAVE_RECORD);
            $loginName = \UserSession::getLoginName();
            if (isset($loginName) && $loginName !== $userRow['login_name'] && $doesPasswordChanged === TRUE) {
                try { // Notifying user for his account creation or his password change 
                    \MainController::execute('users', 'notify', $isNewUser, $passwordInClear, $userRow);
                } catch (\Exception $e) {
                    $response->setWarningMessage($summary, $e->getMessage());
                }
            }
        } else { //Data validation failed...
            $response->setFailedMessage($summary, $validator->getErrorMessage(),
                    $validator->getErrorVariable());
        }
        return $response; // JSON response sent back to the main controller
    }

    /**
     * Removes the user specified in the HTTP request 
     * @return \Response Response returned to the main controller
     */
    static protected function action_remove() {
        /* Reading POST values of the HTTP request... */
        $request = new \Request();
        $userID = $request->user_id;
        \UserManager::removeUser($userID);
        /* Réponse retournée au contrôleur principal */
        $response = new \Response();
        $response->setSuccessMessage(LC_FORM_TITLE_USER_REMOVE, LC_MSG_INF_REMOVE_RECORD);
        return $response;
    }

    // Public methods that can be optionnaly overidden by the application controller 
    // This application controller must be named \app\controller\Users.
    
    /**
     * Generates a hashed version of the password specified in clear. 
     * @param type $password Password in clear
     * @return string Hashed password
     */
    static public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Notifies the user that her or his account has been created or modified
     * with her or his new credentials. 
     * @param boolean $isNewUser TRUE if the user account has been created,
     * otherwise FALSE.
     * @param string $passwordInClear User's password in clear
     * @param array $otherUserData Extra informations about the user
     */
    static public function notify($isNewUser, $passwordInClear, $otherUserData)
    {
        if (\General::isModule('zdkmail')) { // 'zdkmail' module available...
            $appURI = \General::getApplicationURI();
            $connectedUserName = \MainController::execute('users', 'getUserName');
            $connectedUserEmail = \MainController::execute('users', 'getUserEmail');
            $emailTemplate = $isNewUser ? 'new-user' : 'password-change';
            $email = new \zdkmail\mod\Email($emailTemplate);
            $email->addAddress('FROM', $connectedUserEmail, $connectedUserName);
            $email->addAddress('TO', $otherUserData['user_email'], $otherUserData['user_name']);
            $email->setSubjectValues($otherUserData['login_name']);
            $email->setMessageValues($otherUserData['user_name'], $appURI,
                $otherUserData['login_name'], $passwordInClear, $connectedUserName);
            if (!$email->send()) {
                throw new \Exception(LM_MSG_WARN_EMAIL_NOT_SENT_TO_USER);
            }
        }
    }
    
    /**
     * Returns the user name of the currently connected user
     * @return string User name or NULL if the user name can't be read in the 
     * database or if no user is authenticated.
     */
    static public function getUserName() {
        $loginName = \UserSession::getLoginName();
        if (isset($loginName)) {
            return \UserManager::getUserName($loginName);
        } else {
            return NULL;
        }
    }

    /**
     * Returns the user email of the currently connected user
     * @return string User email or NULL if the user email can't be read in the 
     * database or if no user is authenticated.
     */
    static public function getUserEmail() {
        $loginName = \UserSession::getLoginName();
        if (isset($loginName)) {
            return \UserManager::getUserEmail($loginName);
        } else {
            return null;
        }
    }

    /**
     * Changes the user password from the HTTP parameters
     * @return boolean|string TRUE if the password has been changed successfully
     * else the error message returned by the password validator
     */
    static public function changePassword() {
        $validator = new \validator\Password(FALSE);
        if ($validator->validate()) { // Password validation is OK
            $hashedPassword = \MainController::execute('users', 'hashPassword', $validator->getValue('login_password'));
            \UserManager::changeUserPassword($validator->getValue('login_name'), $hashedPassword);
            return TRUE;
        } else {
            return $validator->getErrorMessage();
        }
    }

    /**
     * Disables the user account for the specified user
     * @param type $loginName Login name of the user account to disable
     * @return boolean TRUE if user account has been disabled successfully
     */
    static public function disableUser($loginName) {
        \UserManager::disableUser($loginName);
            return TRUE;
    }

    /**
     * Indicates whether the user has the specified profile or not
     * @param type $profileName Profile name
     * @return boolean TRUE if user has the specified profile, otherwise FALSE 
     */
    static public function hasProfile($profileName) {
        $loginName = \UserSession::getLoginName();
        if (is_null($loginName)) {
            return FALSE;
        } else {
            return \UserManager::hasUserProfile($loginName, $profileName);
        }
    }
    
    /**
     * Indicates whether the connected user has the specified menu item 
     * @param string $menuItem The menu item 
     * @return boolean TRUE if the user has access to the specified menu item 
     */
    static public function hasMenuItem($menuItem) {
        $loginName = \UserSession::getLoginName();
        if (is_null($loginName)) {
            return FALSE;
        } else {
            return \UserManager::hasUserMenuItem($loginName, $menuItem);
        }
    }
}
