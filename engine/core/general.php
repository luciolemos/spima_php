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
 * Core General purpose API
 *
 * File version: 1.5
 * Last update: 04/02/2017
 */

/**
 * ZnetDK general purpose API 
 */
Class General {

    static public $defaultApp = 'default';
    static private $applicationsDir = 'applications';
    static private $toolApp = array('appwiz','appwiz-preview');
    static private $ZnetDKToolsDir = 'engine/tools';

    /**
     * Returns the absolute URI where ZnetDK is installed.
     * @param boolean $includeGetParameter Specifies whether the GET parameter
     * with the application name should be included in the URI when the 
     * application is not the default application
     * @return string Absolute URI of ZnetDK
     */
    static public function getAbsoluteURI($includeGetParameter = FALSE) {
        $script = dirname(self::getMainScript());
        $uri = $script === DIRECTORY_SEPARATOR ? '/' : str_replace('\\', '/', $script) . '/';
        if ($includeGetParameter && !self::isDefaultApplication()) {
            $uri = self::addGetParameterToURI($uri, \Request::getOtherApplication(TRUE), ZNETDK_APP_NAME);
        }
        return $uri;
    }

    /**
     * Returns the main PHP script of ZnetDK (index.php)
     * @param boolean $includeGetParameter When set to TRUE, the GET parameter
     * for selecting another application is also returned.
     * @return string Main PHP script of ZnetDK
     */
    static public function getMainScript($includeGetParameter = FALSE) {
        $script = filter_var($_SERVER['SCRIPT_NAME'], FILTER_SANITIZE_URL);
        if ($includeGetParameter && !self::isDefaultApplication()) {
            $script = self::addGetParameterToURI($script, \Request::getOtherApplication(TRUE), ZNETDK_APP_NAME);
        }
        return $script;
    }

    /**
     * Returns date and time in W3C format('Y-m-d').
     * @return DateTime Date and time. 
     */
    static public function getCurrentW3CDate($withTime = FALSE) {
        $today = new \DateTime('now');
        $format = $withTime ? 'Y-m-d H:i:s' : 'Y-m-d';
        return $today->format($format);
    }

    /**
     * Replace in the text of the message specified for the first parameter, the
     * placeholders %1, %2, ... by the text values specified in the same order 
     * for the next paramaters.  
     * @param string $message Original message in which the placeholders %1, %2,
     * ... are to be replaced by the values specified as other parameters.
     * @param string $text1 Text which replaces the placeholder %1.
     * @param string $text2 Text which replaces the placeholder %2 if exists.
     * @param string $textN Text which replaces the placeholder %N if exists.
     * @return string Message filled with the pieces of text specified in input
     * parameters.  
     */
    static public function getFilledMessage() {
        $nbArgs = func_num_args();
        if ($nbArgs === 0) {
            return null;
        } elseif ($nbArgs === 1) {
            return func_get_arg(0);
        } else {
            $message = func_get_arg(0);
            $arg_list = func_get_args();
            for ($i = 1; $i < $nbArgs; $i++) {
                $placeHolder = "%" . $i;
                $newValue = $arg_list[$i];
                $message = str_replace($placeHolder, $newValue, $message);
            }
            return $message;
        }
    }

    /**
     * Adds a GET parameter to the specified URI and returned the filled version
     * @param string $URI Originale URI
     * @param string $parameter GET parameter name
     * @param string $value Value of the GET parameter
     * @return string Specified URI filled with the GET parameter
     */
    static public function addGetParameterToURI($URI,$parameter,$value) {
        $paramAndValue = $parameter . '=' . $value;
        if (strpos($URI,'?') === FALSE) {
            $filledURI = $URI . '?' . $paramAndValue;
        } else {
            $filledURI = $URI . '&' . $paramAndValue;
        }
        return $filledURI;
    }
    
    /**
     * Returns the GET URI for downloading a file 
     * @param string $controller Name of the controller taking in charge the
     * file download
     * @param string $parameters Extra parameters to send to the 'download' 
     * controller action (NULL by default)
     * @return string Full URI for downloading a file
     */
    static public function getURIforDownload($controller, $parameters = NULL) {
        $baseURI = self::getAbsoluteURI(TRUE);
        $URIwithController = self::addGetParameterToURI($baseURI, 'control', $controller);
        $URIwithAction = self::addGetParameterToURI($URIwithController, 'action', 'download');
        return is_null($parameters) ? $URIwithAction : $URIwithAction . '&' . $parameters;
    }
    
    /**
     * Add an error entry in the ZnetDK error log
     * @param string $origin Text specifying the origin of the error.
     * @param string $textError Text of the error.
     * @param boolean $isCore Specify whether the error to write is a CORE error
     * or an application error.
     */
    static public function writeErrorLog($origin, $textError, $isCore = FALSE) {
        $level = $isCore ? 'CORE' : 'APPL';
        $logFile = ZNETDK_ROOT . CFG_ZNETDK_ERRLOG;
        $currentDate = '[' . date("Y-m-d H:i:s") . '] ';
        $logEntry = $currentDate . $level . ' - ' . $origin . ' - ' . $textError . PHP_EOL;
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Add an information entry in the ZnetDK system log
     * @param string $origin Text specifying the origin of the error.
     * @param string $information Informations to trace.
     * @param boolean $isCore Specify whether the information to write is a CORE error
     * or an application error.
     */
    static public function writeSystemLog($origin, $information, $isCore = FALSE) {
        $level = $isCore ? 'CORE' : 'APPL';
        $logFile = ZNETDK_ROOT . CFG_ZNETDK_SYSLOG;
        $currentDate = '[' . date("Y-m-d H:i:s") . '] ';
        $logEntry = $currentDate . $level . ' - ' . $origin . ' - ' . $information . PHP_EOL;
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Returns the current application identifier.
     * @return string Identifier of the current application.
     */
    static public function getApplicationID() {
        $otherAppl = \Request::getOtherApplication();
        $applicationID = is_null($otherAppl) ? self::$defaultApp : $otherAppl;
        if (defined('ZDK_REDIRECT_APPL_UNKNOWN') && ZDK_REDIRECT_APPL_UNKNOWN !== NULL
            && !file_exists(ZNETDK_ROOT . \General::getApplicationRelativePath($applicationID))) {
            header('Location: '. ZDK_REDIRECT_APPL_UNKNOWN);
            exit;
        }
        return $applicationID;
    }
    
    /**
     * Checks if the current application is the default application
     * @return boolean TRUE if the current application is the default application
     */
    static public function isDefaultApplication($applicationID = NULL) {
        return is_null($applicationID) ? ZNETDK_APP_NAME === self::$defaultApp
                : $applicationID === self::$defaultApp;
    }
    
    /**
     * Specifies whether an application is a tool application
     * @param string $applicationID Identifier of the application
     * @return boolean TRUE if the application is a ZnetDK tool
     */
    static public function isToolApplication($applicationID = NULL) {
        if (in_array(is_null($applicationID) ? ZNETDK_APP_NAME : $applicationID
                , self::$toolApp)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Returns the relative path of the applications's files
     * @param string $applicationID Identifier of the current application
     * @return string Relative path from the installation root path of ZnetDK 
     */
    static public function getApplicationRelativePath($applicationID) {
        if (in_array($applicationID, self::$toolApp)) {
            $directory = str_replace('/', DIRECTORY_SEPARATOR, self::$ZnetDKToolsDir);
        } else {
            $directory = self::$applicationsDir;
        }
        return $directory . DIRECTORY_SEPARATOR . $applicationID;
    }
    
    /**
     * Returns the relative URI of the application public directory 
     * @param string $applicationID Identifier of the application
     * @return string Relative URI of the application public directory
     */
    static public function getApplicationPublicDirRelativeURI($applicationID) {
        if (in_array($applicationID, self::$toolApp)) {
            $directory = self::$ZnetDKToolsDir;
        } else {
            $directory = self::$applicationsDir;
        }
        return $directory . '/' . $applicationID . '/public/';
    }

    /**
     * Returns URI of the current application
     * @return string URI of the application like
     * 'https://www.mydomain/index.php?appl=myapp'
     */
    static public function getApplicationURI() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'
                ? 'https' : 'http';
        $server = filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL);
        return $protocol . '://' . $server . self::getMainScript(TRUE);
    }
    
    /**
     * Returns an array of the modules installed in ZnetDK
     * @param string $filter Filter limiting the returned modules to those
     * matching the specifed controller name or view name.
     * @return mixed An array of module names, the module name matching
     * the filter or FALSE if no module is found
     */
    static public function getModules($filter = NULL) {
        $modules = scandir(ZNETDK_MOD_ROOT, SCANDIR_SORT_ASCENDING);
        if (!$modules) {
            return FALSE;
        }
        foreach ($modules as $index => $moduleName) {
            if ($moduleName === '.' || $moduleName === '..') {
                unset($modules[$index]);
            } elseif (!is_null($filter) && file_exists(ZNETDK_MOD_ROOT
                    . DIRECTORY_SEPARATOR . $moduleName
                    . DIRECTORY_SEPARATOR . $filter)) {
                return $moduleName;
            }
        }
        if (!is_null($filter)) {
            return FALSE;
        }
        if (count($modules) > 0) {
            return $modules;
        }
        return FALSE;
    }
    
    /**
     * Inits the modules' parameters
     */   
    static public function initModuleParameters() {
        $modules = self::getModules();
        if ($modules) {
            foreach ($modules as $moduleName) {
                @include($moduleName . '/mod/config.php');
            }
        }
    }
    
    /**
     * Checks whether the specified module is installed or not
     * @param string $moduleName Name of the module
     * @return boolean TRUE if the module exists, FALSE otherwise 
     */
    static public function isModule($moduleName) {
        $modules = self::getModules();
        if ($modules) {
            foreach ($modules as $foundModuleName) {
                if ($foundModuleName === $moduleName) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    
    /**
     * Returns a dummy password for security purpose. 
     * @return string Dummy password.
     */
    static public function getDummyPassword() {
        return str_repeat("_", 20);
    }
    
    /**
     * Returns the mime type of the specified file
     * @param string $filename Full file path and name for which the mime type
     * is to evaluate
     * @return string Mime type of the specified file (for example 'image/gif')
     */
    static public function getMimeType($filename) {
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimeType;
        } else {
            throw new \ZDKException("GEN-001: unable to determine the mime type of the '$filename' file!"
                        . " Uncomment the line ';extension=php_fileinfo.dll' into the 'php.ini' file.");
        }
    }

}
