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
 * Core API for retrieving HTTP request data
 *
 * File version: 1.4
 * Last update: 04/02/2017
 */

/**
 * Get the values from the HTTP request.
 */
class Request {

    private static $filter = FILTER_SANITIZE_STRING;
    public static $toolGetParamName = 'tool';
    public static $applGetParamName = 'appl';
    
    /**
     * When a \Request object is instanciated, the authentication is checked 
     * for the current user.
     * @param boolean $checkAuthentication Indicates whether the authentication
     * of the current user must be checked. By default, authentication is checked.
     */
    public function __construct($checkAuthentication = TRUE) {
        if ($checkAuthentication) {
            // HTTP error 401 sent if user is not authenticated 
            \UserSession::isAuthenticated();
        }
    }

    /**
     * Returns the filter options to apply to the values of the HTTP request
     * @return Integer Filter flag value
     */
    private static function getFilterOptions() {
        return FILTER_FLAG_NO_ENCODE_QUOTES;
    }

    /**
     * Returns the value for the POST parameter specified in parameter.
     * @param string $name Name of the parameter for which the value is 
     * to be returned.
     * @return mixed Value of the specified parameter.
     */
    public function __get($name) {
        if (isset($_REQUEST[$name])) {
            if (is_array($_REQUEST[$name])) {
                $paramTab = array();
                foreach ($_REQUEST[$name] as $value) {
                    $paramTab[] = filter_var(trim($value), self::$filter, self::getFilterOptions());
                }
                return $paramTab;
            } elseif ($_REQUEST[$name] !== '') {
                return filter_var(trim($_REQUEST[$name]), self::$filter, self::getFilterOptions());
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    /**
     * Returns the current HTTP request method 'POST' or 'GET'
     * @return string Sanitized request method name.
     */
    public static function getMethod() {
        return key_exists('REQUEST_METHOD', $_SERVER)
            && ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET')
            ? $_SERVER['REQUEST_METHOD'] : NULL;
    }

    /**
     * Returns the controller name sent as POST parameter in the HTTP request.
     * @return string Sanitized controller name
     */
    public static function getController() {
        $method = self::getMethod();
        if (is_null($method)) {
            \General::writeErrorLog('ZNETDK ERROR','REQ-001: the HTTP request method is not equal to GET or POST!', TRUE);
            self::setHttpError(500);
            return 'httperror';
        }
        $filterType = $method === 'POST' ? INPUT_POST : INPUT_GET;
        return filter_input($filterType, 'control', self::$filter, self::getFilterOptions());
    }

    /**
     * Returns the action name sent as POST parameter in the HTTP request.
     * @return string Sanitized action name
     */
    public static function getAction() {
        $filterType = self::getMethod() === 'POST' ? INPUT_POST : INPUT_GET;
        return filter_input($filterType, 'action', self::$filter, self::getFilterOptions());
    }
    
    /**
     * Returns the other application specified as GET or POST parameter
     * @return string Name of the other application. NULL is returned if no
     * other application is specified as POST or GET parameter or if the
     * ZDK_TOOLS_DISABLED constant is set to TRUE in 'globalconfig.php' 
     */
    public static function getOtherApplication($parameterNameOnly = FALSE) {
        $method = self::getMethod();
        if (is_null($method)) { // The request comes from the command line (CLI)
            return self::getApplicationFromArguments();
        }
        $filterType = $method === 'POST' ? INPUT_POST : INPUT_GET;
        $toolApp = filter_input($filterType, self::$toolGetParamName, self::$filter,
                self::getFilterOptions());
        if (!is_null($toolApp) && (!defined('ZDK_TOOLS_DISABLED') ||
                (defined('ZDK_TOOLS_DISABLED') && ZDK_TOOLS_DISABLED !== TRUE))) {
            return $parameterNameOnly ? self::$toolGetParamName : $toolApp;
        }
        $otherApp = filter_input($filterType, self::$applGetParamName, self::$filter,
                self::getFilterOptions());
        if (!is_null($otherApp)) {
            return $parameterNameOnly ? self::$applGetParamName :$otherApp;
        }
        return NULL;
    }

    /**
     * Returns the application ID set as an argument of the command line.
     * The command line arguments expected in the $_SERVER variable are :
     * 1) $argv[0]: the 'index.php' script name,
     * 2) $argv[1]: the value 'autoexec'
     * 3) $argv[2]: the application ID (for example 'default')
     * @return string The application ID passed to the script in command line
     * or NULL if no argument is set for the application ID.
     */
    private static function getApplicationFromArguments() {
        if (key_exists('argc', $_SERVER) && $_SERVER['argc'] === 3) {
                // No application set in the command line arguments at position  
            $applicationId = $_SERVER['argv'][2];
            return filter_var($applicationId, self::$filter, self::getFilterOptions());
        }
        return NULL;
    }
    
    /**
     * Sets an HTTP error 404 (not found) or 500 (internal error)
     * @param string $httpError Code of the HTTP error.
     */
    public static function setHttpError($httpError) {
        switch ($httpError) {
            case 404: 
                $header = "HTTP/1.0 404 Not Found";
                $_REQUEST['httperror'] = "404";
                break;
            case 500:
            default:
                $header = "HTTP/1.0 500 Internal Server Error";
                $_REQUEST['httperror'] = "500";
        }
        if (!headers_sent()) {
            header($header);
        }
    }

    /**
     * Returns the HTTP error code.
     * @return string value "403", "404" or "500"
     */
    static public function getHttpErrorCode() {
        $nativeErrorCode = self::getAction();
        if ($nativeErrorCode === "403" || $nativeErrorCode === "404") {
            return $nativeErrorCode;
        }
        $forcedErrorCode = $_REQUEST['httperror'];
        if ($forcedErrorCode === "404" || $forcedErrorCode === "500") {
            return $forcedErrorCode;
        }
        return "500";
    }
    
    /**
     * Returns the language code sent in the HTTP request for the GET parameter 'lang'
     * @return string Language code
     */
    public static function getLanguage() {
        return filter_input(INPUT_GET, 'lang', self::$filter, self::getFilterOptions());
    }

    /**
     * Returns the language code sent in the HTTP request header 'Accept-Language'.
     * This language is the one set for the web browser which has sent the request.
     * @return string Language code
     */
    public static function getAcceptLanguage() {
        return filter_input(INPUT_SERVER, 'HTTP_ACCEPT_LANGUAGE', self::$filter, self::getFilterOptions());
    }

    /**
     * Returns the IP address of the user sending the HTTP request
     * @return string IP address of the user
     */
    public static function getRemoteAddress() {
        return filter_input(INPUT_SERVER,'REMOTE_ADDR', FILTER_SANITIZE_URL);    
    }
    
    /**
     * Returns an array of the POST values matching the POST parameters specified in
     * input of the method. Each value returned in the array is indexed with the
     * name of the POST parameter.
     * @param string $postParameter1 First POST parameter name for which the value is
     * to be returned in the array.
     * @param string $postParameter2 Second POST parameter name for which the value is
     * to be returned in the array.
     * @param string $postParameterN Nth POST parameter name for which the value is
     * to be returned in the array.
     * @return array Values of the POST parameters specified in parameters
     */
    public function getValuesAsMap() {
        $map = array();
        foreach (func_get_args() as $key) {
            $map[$key] = $this->__get($key);
        }
        return $map;
    }

    /**
     * Returns an array of the POST values matching the POST parameters specified in
     * input of the method. Each value returned in the array is indexed with the
     * name of the POST parameter.
     * @param array $arrayOfKeys POST parameter names for which values have to be
     * returned.
     * @return array Values of the POST parameters specified in the array passed
     * in parameter.
     */
    public function getArrayAsMap($arrayOfKeys) {
        $map = array();
        foreach ($arrayOfKeys as $key) {
            $map[$key] = $this->__get($key);
        }
        return $map;
    }
    /**
     * Checks whether the specified file has been uploaded or not.
     * @param String $name Name of a POST parameter matching an uploaded file.
     * @return Boolean TRUE if the specified file has been uploaded.
     */
    public function isUploadedFile($name) {
        return (!empty($_FILES[$name]));
    }
    /**
     * Returns informations about the specified uploaded file
     * @param String $name Name of the POST parameter matching the uploaded file.
     * @return Array The file informations for the foloowing array keys: 'basename',
     * 'extension', 'filename', 'dirname', 'size', 'type', and 'tmp_name'.
     * @throws \Exception Triggered when an error is detected.
     */
    public function getUploadedFileInfos($name) {
        if (empty($_FILES[$name])) {
            $message = "UPL-001: the specified POST parameter named '$name' does not"
                    . " match any uploaded file!";
        } elseif (count($_FILES[$name]['name']) !== 1) {
            $message = "UPL-002: only one file can be uploaded at the same time!";
        } elseif ($_FILES[$name]['error'] !== UPLOAD_ERR_OK) {
            $errorNumber = $_FILES[$name]['error'];
            $message = "UPL-003: the error number '$errorNumber' occurred during the"
                . " upload process!";
        }
        if (!isset($message)) {
            $fileInfos = pathinfo($_FILES[$name]['name']);
            $fileInfos['size'] = $_FILES[$name]['size'];
            $fileInfos['type'] = $_FILES[$name]['type'];
            $fileInfos['tmp_name'] = $_FILES[$name]["tmp_name"];
            return $fileInfos;
        } else {
            \General::writeErrorLog('ZNETDK ERROR', $message, true);
            throw new \ZDKException($message);
        }
    }
    
    /**
     * Moves the specified uploaded image file into the target directory.
     * @param String $name Name of the POST parameter matching the uploaded file.
     * @param String $targetFileName Full path and name of the definitive file. 
     * @param Integer $fileMaxSize Maximum size in bytes of the image file.
     * @throws \Exception Triggered when an error is detected.
     */
    public function moveImageFile($name, $targetFileName, $fileMaxSize) {
        $message = NULL;
        
        $fileInfos = $this->getUploadedFileInfos($name);
        $uploadedFileName = $fileInfos['basename'];
                
        if (getimagesize($fileInfos["tmp_name"]) === FALSE) {
            $message = "UPL-004: the uploaded file '$uploadedFileName' is not a valid image!";
        } elseif (file_exists($targetFileName)) {
            $message = "UPL-005: the target file name '$targetFileName' already exists!";
        } elseif ($fileInfos['size'] > $fileMaxSize) {
            $message = "UPL-006: the size of the uploaded file '$uploadedFileName' is greater than the allowed"
                . " maximum size of '$fileMaxSize' bytes!";
        } elseif (move_uploaded_file($fileInfos["tmp_name"], $targetFileName) === FALSE) {
            $message = "UPL-007: unable to move the temporary uploaded file to the "
                . "specified target directory '$targetFileName'!";            
        } elseif (chmod($targetFileName, 0644) === FALSE) {
            $message = "UPL-008: unable to change the file mode for the uploaded file!";
        }
        
        if (!is_null($message)) {
            throw new \ZDKException($message);
        }
        
    }
}
