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
 * Core User session API
 *
 * File version: 1.3
 * Last update: 04/09/2017
 */
Class UserSession {
    static private $customVarPrefix = "zdkcust-";
    
    /**
     * Sets the specified value in user session for the current application
     * @param string $variable Name of the variable in the user session
     * @param string $value Value to set in session
     */
    static private function setValue($variable, $value) {
        if (!isset($_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME])) {
            $_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME] = array();
        }
        $_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME][$variable] = $value;
    }
    
    /**
     * Gets from the user session and the current application, the value for the
     * specified variable name.
     * @param string $variable Name of the variable that contains the requested
     * value
     * @return mixed NULL is the requested variable does not exist in the user 
     * session, otherwise the value found.
     */
    static private function getValue($variable) {
        if (!isset($_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME])
                || !isset($_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME][$variable])) {
            return NULL;
        } else {
            return $_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME][$variable];
        }
    }
    
    /**
     * Returns the access mode stored in session for the current user 
     * @return string Value 'public' or 'private'
     */
    static private function getAccessMode() {
        if (!is_null(self::getValue('last_time_access'))) {
            return 'public';
        } else {
            return 'private';
        }
    }

    /**
     * Indicated whether the user session has timed out
     * @return boolean TRUE is session has timed out
     */
    static private function hasSessionTimedOut() {
        if (self::getAccessMode() === 'public') {
            $current_time = new \DateTime('now');
            $interval = self::getValue('last_time_access')->diff($current_time);
            $minutes = $interval->days * 24 * 60;
            $minutes += $interval->h * 60;
            $minutes += $interval->i;
            if ($minutes <= CFG_SESSION_TIMEOUT) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * Returns the number of times the current user failed to authenticate.
     * @return int Number of failed authentications
     */
    static private function getNbrOfFailedAuthent() {
        if (!is_null(self::getValue('nbr_of_failed_authent'))) {
            return self::getValue('nbr_of_failed_authent');
        } else {
            return 0;
        }
    }

    /**
     * Reset the last time HTTP requests sent by the current user.
     */
    static private function renewSessionTime() {
        self::setValue('last_time_access', new \DateTime("now"));
    }

    /**
     * Returns the IP address stored in session for the connected user
     * @return string User IP address stored in session
     */
    static private function getRemoteAddress() {
        if (!is_null(self::getValue('ip_address'))) {
            return self::getValue('ip_address');
        } else {
            return NULL;
        }
    }
    
    static private function getAbosulteURI() {
        if (!is_null(self::getValue('application_uri'))) {
            return self::getValue('application_uri');
        } else {
            return NULL;
        }
    }
    
    /* Public methods */

    /**
     * Indicates whether the user is authenticated or not.
     * @param boolean $silent if TRUE, no HTTP error 401 is returned (set to 
     * FALSE by default).
     * @return boolean TRUE if user is authenticated else FALSE
     */
    static public function isAuthenticated($silent = FALSE) {
        if (!CFG_AUTHENT_REQUIRED) {
            return TRUE;
        } elseif (self::getLoginName()) { // User is authenticated
            if (self::getAccessMode() === 'public') {
                if (!self::hasSessionTimedOut()) {
                    self::renewSessionTime();
                    return TRUE;
                } else {
                    $message = LC_MSG_WARN_SESS_TIMOUT;
                }
            } else { // Private acess : user session never times out
                return TRUE;
            }
        } else { // User not authenticated
            $message = LC_MSG_WARN_NO_AUTH;
        }
        if ($silent) {
            return FALSE;
        } else {
            $response = new \Response(FALSE);
            $response->doHttpError(401,LC_FORM_TITLE_LOGIN,$message);
        }
    }

    /**
     * Returns the login name stored in session for the user only if the URI
     * and the IP address have not changed since the previous call
     * If the application is executed in command line, the 'autoexec' value 
     * is returned if set as parameter 
     * @return string The login name of the connected user, 'autoexec' in
     * command line execution, NULL otherwise.
     */
    static public function getLoginName() {
        if (!is_null(self::getValue('login_name')) && 
                \Request::getRemoteAddress() === self::getRemoteAddress() &&
                \General::getAbsoluteURI() === self::getAbosulteURI()) {
            return self::getValue('login_name');
        } else {
            return AutoExec::getLoginName();
        }
    }
    
    /**
     * Returns the language code stored in the user session
     * @return string Language code
     */
    static public function getLanguage() {
        if (!is_null(self::getValue('lang'))) {
            return self::getValue('lang');
        } else {
            return NULL;
        }
    }
    
    /**
     * Store in the user session his prefered language 
     * @param string $language Language code to set
     */
    static public function setLanguage($language) {
        self::setValue('lang', $language);
    }

    /**
     * Stored in session the fact that the specified user failed to authenticate
     * @param type $loginName Login of the user
     */
    static public function setAuthentHasFailed($loginName) {
        if (!is_null(self::getValue('nbr_of_failed_authent')) 
                && !is_null(self::getValue('user_failed_authent'))
                && self::getValue('user_failed_authent') === $loginName) {
            self::setValue('nbr_of_failed_authent', self::getValue('nbr_of_failed_authent') + 1);
        } else {
            self::setValue('nbr_of_failed_authent', 1);
            self::setValue('user_failed_authent', $loginName);
        }
    }

    /**
     * Indicates whether the number of allowed authentication has been reached
     * @return boolean TRUE if the user failed to authenticate 3 times or more
     */
    static public function isMaxNbrOfFailedAuthentReached() {
        if (self::getNbrOfFailedAuthent() >= CFG_NBR_FAILED_AUTHENT) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Resets in session the information concerning the authentication failure
     * of the current user.
     */
    static public function resetAuthentHasFailed() {
        unset($_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME]['nbr_of_failed_authent']);
        unset($_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME]['user_failed_authent']);
    }

    /**
     * Stores in session the login name of the current user and its associated
     * information(IP adddress and URI of the application accessed).
     * @param string $loginName
     */
    static public function setLoginName($loginName) {
        self::setValue('login_name', $loginName);
        self::setValue('ip_address', \Request::getRemoteAddress());
        self::setValue('application_uri', \General::getAbsoluteURI());
    }

    /**
     * Stores in session the last time HTTP request when 'public' mode is set, 
     * in order to calculate when the next request will be received, if the max
     * time without activity has been exceeded or not.
     * @param string $accessMode Value 'public' or 'private' allowed.
     */
    static public function setAccessMode($accessMode) {
        if (!isset($accessMode)) {
            $accessMode = CFG_SESSION_DEFAULT_MODE;
        }
        if ($accessMode === 'public') {
            self::setValue('last_time_access', new \DateTime("now"));
        } elseif (!is_null(self::getValue ('last_time_access'))) {
            unset($_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME]['last_time_access']);
        }
    }

    /**
     * Clears all the current user information stored in session
     */
    static public function clearUserSession() {
        unset($_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME]['login_name']);
        unset($_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME]['last_time_access']);
        unset($_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME]['ip_address']);
        unset($_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME]['application_uri']);
        self::clearCustomValues();
        self::resetAuthentHasFailed();
    }
    
    /**
     * Clears the custom variables added to the user session
     */
    static private function clearCustomValues() {
        $prefixLength = strlen(self::$customVarPrefix);
        $sessionKeys = array_keys($_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME]);
        foreach($sessionKeys as $key) {
           $prefix = substr($key,0,$prefixLength);
           if ($prefix === self::$customVarPrefix) {
               unset($_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME][$key]);
           }
        }
    }

    /**
     * Stores a custom value in the user session.
     * The variable name is prefixed with the string "zdkcust-" to be sure that 
     * all existing variable in session with the same name will not be overwritten.
     * @param string $variableName Name of the variable stored in session
     * @param mixed $value Value to store in session
     * @return boolean TRUE if value has been set properly in session,
     * otherwise returns FALSE.
     */
    static public function setCustomValue($variableName, $value) {
        if (isset($variableName) && isset($value)) {
            $sessionVar = 'zdkcust-'.$variableName;
            
            self::setValue($sessionVar, self::getCleanedValue($value));
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * Returns the value of the specified variable stored in session
     * @param string $variableName Name of the variable stored in session
     * @return mixed Value read in session for the specified variable
     */
    static public function getCustomValue($variableName) {
        $sessionVar = 'zdkcust-'.$variableName;
        if (!is_null(self::getValue($sessionVar))) {
            return self::getCleanedValue(self::getValue($sessionVar));
        } else { 
            return NULL;
        }
    }
    
    /**
     * Removes the specified custom variable stored in session.
     * @param string $variableName Name of the variable to remove from session
     * @return boolean TRUE if the variable exists and has been removed, FALSE 
     * otherwise
     */
    static public function removeCustomValue($variableName) {
        $sessionVar = 'zdkcust-'.$variableName;
        if (!is_null(self::getValue($sessionVar))) {
            unset($_SESSION[\General::getAbsoluteURI() . ZNETDK_APP_NAME][$sessionVar]);
            return TRUE;
        } else { 
            return FALSE;
        }
    }

    /**
     * Returns the sanitized value specified in parameter 
     * @param mixed $value Value to sanitize
     * @return mixed Sanitized value
     */
    static private function getCleanedValue($value) {
        if (is_array($value)) {
            self::cleanArrayValues($value);
            return $value;
        } elseif (is_numeric($value)) {
            return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
        } elseif (is_bool($value)) {
            return $value;
        } else {
            return filter_var($value, FILTER_SANITIZE_STRING,
                    FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW);
        }
    }
    /**
     * Sanitizes the values of the array preserving without encoding the codes
     * @param array $array Array containing the values to sanitize
     */
    static private function cleanArrayValues(&$array) {
        foreach ($array as &$value) {
            if (!is_array($value)) {
                filter_var($value, FILTER_SANITIZE_STRING,
                    FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW);
            } else {
                self::cleanArrayValues($value);
            }            
        }
    }
    
}

