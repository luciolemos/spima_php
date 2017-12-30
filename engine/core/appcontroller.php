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
 * Core Application controller class  
 *
 * File version: 1.1
 * Last update: 02/11/2017
 */

/**
 * Mother class of the application controllers to derive from 
 */
abstract class AppController {
    // Properties
    static private $setAllowedActionsMethodName = 'setAllowedActions';
    static private $requiredProfilesbyAction = array();
    static private $forbiddenProfilesByAction = array();
    
    // Methods
    /**
     * Executes the specified action of the controller
     * @param string $action Action name
     * @return boolean|Response Object of type \Response returned by the action
     * or FALSE if the action does not exist in the controller. 
     */
    static public function doAction($action) {
        if (self::isAction($action)) {
            if (self::isActionAllowed($action)) {
                $response = self::executeAction($action);
                return self::getValidatedResponse($response, $action);
            } else {
                // The user is not allowed to execute the action
                $response = new \Response(FALSE);
                $response->setFailedMessage(LC_MSG_ERR_FORBIDDEN_ACTION_SUMMARY,
                        LC_MSG_ERR_FORBIDDEN_ACTION_MESSAGE);
                return $response;
            }
        } else {
            return FALSE; // the action is not managed by the controller
        }
    }
    
    /**
     * Checks whether the specified action exists in the controller
     * @param string $action Action name
     * @return boolean TRUE if action exists, FALSE otherwise.
     */
    static public function isAction($action) {
        $method = self::getMethodName($action);
        if (method_exists(get_called_class(), $method)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Checks whether the specified action is allowed for the connected user
     * according to the profile definition of the controller set through the 
     * 'setAllowedActions' method.
     * @param string $action Name of the action
     * @return boolean Value TRUE if the connected user is allowed to execute
     * the specified action. Otherwise returns FALSE.
     */
    static public function isActionAllowed($action) {
        self::doErrorIfActionUnknown($action); // Unknown action !
        if (CFG_AUTHENT_REQUIRED === FALSE) {
            // No authentication required...
            return TRUE; // So the action is allowed
        }
        if (!self::doesAllowedActionMethodExist()) { //No profile access definition exists 
            return TRUE; // So the action is allowed
        }
        $profileName = NULL;
        $menuItem = NULL;
        $profileType = NULL;
        if (key_exists($action, self::$requiredProfilesbyAction)) {
            $profileName = self::$requiredProfilesbyAction[$action]['profileName'];
            $menuItem = self::$requiredProfilesbyAction[$action]['menuItem'];
            $profileType = 'required';
        } elseif (key_exists($action, self::$forbiddenProfilesByAction)) {
            $profileName = self::$forbiddenProfilesByAction[$action]['profileName'];
            $menuItem = self::$forbiddenProfilesByAction[$action]['menuItem'];
            $profileType = 'forbidden';
        }
        if (is_null($profileName)) {
            return TRUE; // No definition found for the action, so the action is allowed
        }
        $doesUserHasProfile = MainController::execute('users', 'hasProfile', $profileName);
        if ($profileType === 'required') {
            // The user must have the required profile
            if (is_null($menuItem)) {
                // No menu item restriction is set.
                return $doesUserHasProfile;
            } elseif ($doesUserHasProfile) { // The specified menu item must be set for the profile
                $isMenuItemSetForProfile = ProfileManager::isMenuItemSetForProfile($profileName, $menuItem);
                return $isMenuItemSetForProfile;
            } else {
                return FALSE; // The user does not have the required profile.
            }
        } else {
            // The user must not have the specified profile
            if (is_null($menuItem)) {
                // No menu item restriction is set.
                return $doesUserHasProfile === FALSE;
            } elseif ($doesUserHasProfile) {
                $isMenuItemSetForProfile = ProfileManager::isMenuItemSetForProfile($profileName, $menuItem);
                return $isMenuItemSetForProfile === FALSE;
            } else {
                return TRUE;
            }
        }

    }
    
    /**
     * Specifies the user profiles allowed to execute the controller's action.
     * This method must be called from the 'setAllowedActions' controller's 
     * method
     * @param string $action Name of the action
     * @param string $profileName Name of the user profile
     */
    static protected function setRequiredProfileForAction($action, $profileName, $menuItem = NULL) {
        self::doErrorIfActionUnknown($action);
        self::doErrorIfActionSetSeveralTimes($action);
        self::$requiredProfilesbyAction[$action] = array(
            'profileName' => $profileName,
            'menuItem' => $menuItem);
    }
    
    /**
     * Specifies the user profiles NOT allowed to execute the controller's action.
     * This method must be called from the 'setAllowedActions' controller's 
     * method
     * @param string $action Name of the action
     * @param string $profileName Name of the user profile
     */
    static protected function setForbiddenProfileForAction($action, $profileName, $menuItem = NULL) {
        self::doErrorIfActionUnknown($action);
        self::doErrorIfActionSetSeveralTimes($action);
        self::$forbiddenProfilesByAction[$action] = array(
            'profileName' => $profileName,
            'menuItem' => $menuItem);
    }
    
    static private function doesAllowedActionMethodExist() {
        $method = self::$setAllowedActionsMethodName;
        if (method_exists(get_called_class(), $method)) {
            // The static properties are reset
            self::$requiredProfilesbyAction = array();
            self::$forbiddenProfilesByAction = array();
            // The 'setAllowedActions' method is called
            static::$method();
            return TRUE;
        } else {
            return FALSE; // The 'setAllowedActions' method is not declared
        }
    }

    static private function getMethodName($action) {
        return 'action_'.$action;
    }

    static private function executeAction($action) {
        $method = self::getMethodName($action);
        return static::$method();
    }

    static private function getValidatedResponse($response, $action) {
        $objectType = '\Response';
        if ($response instanceof $objectType) {
            return $response;
        } else {
            $message = "CTL-006: the response returned by the action '".$action.
                    "' of the controller '".  get_called_class() .
                    "' is not an object of type ".$objectType."!";
            self::doHttpError($message);
        }
    }
    
    static private function doHttpError($message) {
        \General::writeErrorLog('ZNETDK ERROR', $message, TRUE);
        $response = new \Response(FALSE);
        $response->doHttpError(500,LC_MSG_CRI_ERR_SUMMARY,
                \General::getFilledMessage(LC_MSG_CRI_ERR_DETAIL, $message));
    }
    
    static private function doErrorIfActionUnknown($action) {
        if (!self::isAction($action)) {
            // EXCEPTION - the action definition does not exist within the controller
            self::doHttpError("CTL-008: the '$action' action specified within the "
                . "'setAllowedActions' method does not exist for the '"
                . get_called_class() . "' controller!");
        }
    }
    
    static private function doErrorIfActionSetSeveralTimes($action) {
        if (key_exists($action, self::$requiredProfilesbyAction)
                || key_exists($action, self::$forbiddenProfilesByAction)) {
            self::doHttpError("CTL-008: the '$action' action specified within the "
                . "'setAllowedActions' method is specified several times for the '"
                . get_called_class() . "' controller!");
        }
    }
}
