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
 * App Wizard starter application generator   
 *
 * File version: 1.1
 * Last update: 10/25/2015
 */

namespace app;

/**
 * Generate the starter application and database from the settings entered
 * from the Application Wizard  
 */
class AppGenerator {
    private $applicationName;
    private $report = array();
    private $nbStatusFailed = 0;

    /**
     * Constructor of the AppGenerator class
     * @param string $applicationName Identifier of the application
     */
    public function __construct($applicationName = 'default') {
        $this->applicationName = $applicationName;
    }
    
    /**
     * Generates the preview application
     * @param array $locale Localization parameters
     * @param array $config Configuration parameters
     */
    public function preview($locale, $config) {
        $this->updateLocaleParameters($locale, $config);
        $this->updateConfigParameters($config);
    }
    
    /**
     * Generates the starter application 
     * @param array $locale Localization parameters
     * @param array $config Configuration parameters
     * @param array $dbParams Database parameters
     * @param array $uploadError Upload errors
     */
    public function generate($locale, $config, $dbParams, $uploadError) {
        // Create the application
        $this->createDirectories();
        $this->copyStandardFiles();
        $this->copyUploadedLogo($locale, $uploadError);
        $this->updateLocaleParameters($locale, $config);
        $this->updateConfigParameters($config);
        // Connect to MySQL
        $dbConnection = $this->getDbConnection($config, $dbParams);
        // Create database
        $isDbCreated = $this->createDatabase($dbConnection, $config, $dbParams);
        // Create user
        $this->createUserAccount($isDbCreated, $dbConnection, $config, $dbParams);
        // Create tables
        $this->createSecurityTables($dbConnection, $config, $dbParams);
        // Terminated
        $summaryMsg = $this->getNbErrors() === 0 ? LA_RPT_RESULT_SUMMARY_OK 
                : \General::getFilledMessage(LA_RPT_RESULT_SUMMARY_ERRORS,
                            $this->getNbErrors());
        $this->addReportEntry(LA_RPT_ACTION_SUMMARY, $summaryMsg);
    }
    
    /**
     * Returns the detailed report of the generated application 
     * @return array lines of the report
     */
    public function getReport() {
        return $this->report;
    }

    /**
     * Returns the URL of the generated application
     * @return string URL of the generated application
     */
    public function getApplicationUrl() {
        $url = \General::getMainScript();
        if (!\General::isDefaultApplication($this->applicationName)) {
            $GETparamName = \General::isToolApplication($this->applicationName)
                    ? \Request::$toolGetParamName : \Request::$applGetParamName;
            $url = \General::addGetParameterToURI($url, $GETparamName,
                $this->applicationName);
        }
        return $url;
    }
    
    /**
     * Returns the number of errors detected during the generation of the application
     * @return int Number of errors detected during the generation of the application
     */
    public function getNbErrors() {
        return $this->nbStatusFailed;
    }
    
    /**
     * Adds a new entry in the generation report 
     * @param string $action Short description of the action
     * @param string $result Detailed result of the action
     */
    private function addReportEntry($action, $result) {
        $this->report[] = array('step'=>count($this->report)+1,'action'=>$action,'result'=>$result);
    }

    /**
     * Returns the status of an action executed during the generation
     * @param boolean $state TRUE if action has succeeded, FALSE otherwise.
     * @return string Localized label of the action's status
     */
    private function getStatus($state) {
        if (!$state) {
            $this->nbStatusFailed += 1;
        }
        return $state ? LA_RPT_STATUS_OK : LA_RPT_STATUS_FAILED;
    }
    
    /**
     * Returns the OS directory of the specified application
     * @param string $applicationID Identifier of the application
     * @return string OS path of the application directory
     */
    private function getOSappDirectory($applicationID) {
        return ZNETDK_ROOT
            . \General::getApplicationRelativePath($applicationID)
            . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR;
    }
    
    /**
     * Returns the list of directories matching the specified root directory
     * @param type $directory Root directory ('app' or 'public')
     * @return array List of directories
     */
    private function getSubDirectories($directory) {
        if ($directory === 'app') {
            return array(
              'app'. DIRECTORY_SEPARATOR . 'controller'
            , 'app'. DIRECTORY_SEPARATOR . 'help'
            , 'app'. DIRECTORY_SEPARATOR . 'lang'
            , 'app'. DIRECTORY_SEPARATOR . 'layout'
            , 'app'. DIRECTORY_SEPARATOR . 'model'
            , 'app'. DIRECTORY_SEPARATOR . 'validator'
            , 'app'. DIRECTORY_SEPARATOR . 'view');
        } else {
            return array(
              'public' . DIRECTORY_SEPARATOR . 'css'
            , 'public' . DIRECTORY_SEPARATOR . 'images'
            , 'public' . DIRECTORY_SEPARATOR . 'js'
            , 'public' . DIRECTORY_SEPARATOR . 'themes');
        }
    }

    /**
     * Generates the directories tree of the application 
     */
    private function createDirectories() {
        $directories = array_merge(array('','app'), $this->getSubDirectories('app')
                , array('public'),$this->getSubDirectories('public')
                , array('documents'));
        foreach($directories as $directory) {
            $fullDirectory = ZNETDK_ROOT . 'applications' . DIRECTORY_SEPARATOR
                    . $this->applicationName . DIRECTORY_SEPARATOR . $directory;
            $isOK = mkdir($fullDirectory);
            $this->addReportEntry(LA_RPT_ACTION_CREATE_DIR,
                    \General::getFilledMessage(LA_RPT_RESULT_CREATE_DIR,
                            $fullDirectory, $this->getStatus($isOK)));
        }
    }
    
    /**
     * Copies the files required for the application
     */
    private function copyStandardFiles() {
        $sourcePath = ZNETDK_ROOT . \General::getApplicationRelativePath('appwiz') . DIRECTORY_SEPARATOR;
        $targetPath = ZNETDK_ROOT . \General::getApplicationRelativePath($this->applicationName) . DIRECTORY_SEPARATOR;
        
        // Copy README.TXT files...
        $directories = array_merge($this->getSubDirectories('app'),$this->getSubDirectories('public'),
                array('documents'));
        $readmeFile = 'README.TXT';
        foreach ($directories as $directory) {
            $sourceFile = $sourcePath . $directory . DIRECTORY_SEPARATOR . $readmeFile;
            $targetFile = $targetPath . $directory . DIRECTORY_SEPARATOR . $readmeFile;
            $this->addReportEntry(LA_RPT_ACTION_COPY_FILE, \General::getFilledMessage(LA_RPT_RESULT_COPY_FILE,
                $readmeFile, $targetFile, $this->getStatus(copy($sourceFile, $targetFile))));

        }
        // Copy 'menu.php' file
        $sourceFile = $sourcePath . 'app' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'menu.template';
        $targetFile = $targetPath . 'app' . DIRECTORY_SEPARATOR . 'menu.php';
        $this->addReportEntry(LA_RPT_ACTION_COPY_FILE, \General::getFilledMessage(LA_RPT_RESULT_COPY_FILE,
                'menu.php', $targetFile, $this->getStatus(copy($sourceFile, $targetFile))));

        // Copy 'app/.htaccess' file
        $sourceFile = $sourcePath . 'app' . DIRECTORY_SEPARATOR . '.htaccess';
        $targetFile = $targetPath . 'app' . DIRECTORY_SEPARATOR . '.htaccess';
        $this->addReportEntry(LA_RPT_ACTION_COPY_FILE, \General::getFilledMessage(LA_RPT_RESULT_COPY_FILE,
                '.htaccess', $targetFile, $this->getStatus(copy($sourceFile, $targetFile))));

        // Copy 'documents/.htaccess' file
        $sourceFile = $sourcePath . 'documents' . DIRECTORY_SEPARATOR . '.htaccess';
        $targetFile = $targetPath . 'documents' . DIRECTORY_SEPARATOR . '.htaccess';
        $this->addReportEntry(LA_RPT_ACTION_COPY_FILE, \General::getFilledMessage(LA_RPT_RESULT_COPY_FILE,
                '.htaccess', $targetFile, $this->getStatus(copy($sourceFile, $targetFile))));

        // Copy 'default.css' file
        $sourceFile = $sourcePath . 'app' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'custom.css';
        $targetFile = $targetPath . 'public' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR  . 'custom.css';
        $this->addReportEntry(LA_RPT_ACTION_COPY_FILE,
            \General::getFilledMessage(LA_RPT_RESULT_COPY_FILE, 'custom.css'
            , $targetFile, $this->getStatus(copy($sourceFile, $targetFile))));
    }
    
    /**
     * Copies the uploaded logo into the application 'images' directory
     * @param array $locale Loacalization parameters
     * @param array $uploadError Error occurred during upload of the logo
     */
    private function copyUploadedLogo($locale, $uploadError) {
        $relativePath = DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
        $sourcePath = ZNETDK_ROOT . \General::getApplicationRelativePath('appwiz-preview');
        $targetPath = ZNETDK_ROOT . \General::getApplicationRelativePath($this->applicationName);
        if (is_null($uploadError)) { // No upload error
            $fileInfo = pathinfo($locale['logo']);
            $fileExtension = strtolower($fileInfo['extension']);
            $displayedSourceFile = $fileInfo['basename'];
            $sourceFile = $sourcePath . $relativePath . "logo.$fileExtension";
            $targetFile = $targetPath . $relativePath . "logo.$fileExtension";
        } else { // Default logo is copied
            $displayedSourceFile = 'mylogo_en.png';
            $sourceFile = $sourcePath . $relativePath . $displayedSourceFile;
            $targetFile = $targetPath . $relativePath . $displayedSourceFile;
            $this->addReportEntry(LA_RPT_ACTION_COPY_FILE,\General::getFilledMessage(
                    LA_RPT_RESULT_COPY_LOGO_ERROR, $uploadError, $displayedSourceFile));
            $this->getStatus(FALSE);
        }
        $this->addReportEntry(LA_RPT_ACTION_COPY_FILE,
            \General::getFilledMessage(LA_RPT_RESULT_COPY_FILE, $displayedSourceFile
            , $targetFile, $this->getStatus(copy($sourceFile, $targetFile))));
    }
    
    /**
     * Generates the config.php file of the generated application
     * @param array $parameters Parameters to set into the config.php script
     */
    private function updateConfigParameters($parameters) {
        $configParams = array('def_lang'=>'CFG_DEFAULT_LANGUAGE'
            ,'layout'=>'CFG_PAGE_LAYOUT','theme'=>'CFG_THEME',
            'host'=>'CFG_SQL_HOST', 'database'=>'CFG_SQL_APPL_DB',
            'user'=>'CFG_SQL_APPL_USR', 'user_pwd'=>'CFG_SQL_APPL_PWD');
        $subDirectory = $this->applicationName === 'appwiz-preview'
            ? 'preview' . DIRECTORY_SEPARATOR : NULL;
        $templateFile = self::getOSappDirectory('appwiz') . 'template'
            . DIRECTORY_SEPARATOR . $subDirectory . 'config.template';
        $configFile = self::getOSappDirectory($this->applicationName).'config.php';
        foreach ($configParams as $paramKey => $paramName) {
            $paramValue = $parameters[$paramKey];
            $storedValue = isset($paramValue) ? $paramValue : 'NULL';
            $this->updateFileParameter($templateFile, $configFile, $paramName,
                    $storedValue, isset($paramValue));
            $templateFile = $configFile;
        }
    }
    
    /**
     * Generates the locale.php file from the specified translations
     * @param array $locale Translation labels
     * @param array $config Configuration parameters
     */
    private function updateLocaleParameters($locale, $config) {
        $logoFileName = !is_null($locale['logo'])
                ? 'logo.' . strtolower(pathinfo($locale['logo'],PATHINFO_EXTENSION))
                : 'mylogo_en.png';
        $locale['logo'] = "ZNETDK_APP_URI.'images/$logoFileName'";
        
        $localeParams = array('appl_name'=>'LC_PAGE_TITLE','banner_title'=>'LC_HEAD_TITLE',
            'banner_subtitle'=>'LC_HEAD_SUBTITLE','footer_left'=>'LC_FOOTER_LEFT',
            'footer_mid'=>'LC_FOOTER_CENTER','footer_right'=>'LC_FOOTER_RIGHT',
            'logo'=>'LC_HEAD_IMG_LOGO');
        $standardLanguages = array('fr','en','es');
        $templateLang = in_array($config['def_lang'], $standardLanguages)
                ? $config['def_lang'] : 'en';
        $subDirectory = $this->applicationName === 'appwiz-preview'
                ? 'preview' . DIRECTORY_SEPARATOR : NULL;
        $templateFile = self::getOSappDirectory('appwiz').'template'.DIRECTORY_SEPARATOR
                . $subDirectory . "locale_{$templateLang}.template";
        $localeFile = self::getOSappDirectory($this->applicationName).'lang'.DIRECTORY_SEPARATOR.'locale.php';
        foreach ($localeParams as $paramKey => $paramName) {
            $paramValue = $locale[$paramKey];
            $storedValue = isset($paramValue) ? $paramValue : '!NO VALUE!';
            $this->updateFileParameter($templateFile, $localeFile, $paramName,
                    $storedValue, $paramKey!=='logo');
            $templateFile = $localeFile;
        }
    }
    
    /**
     * Neutralizes the characters ' (quote), \ (backslash) and " (quatation marks)
     * found into the specified string 
     * @param string $text
     * @return string Sanitized string
     */
    private function escapeCharacters($text) {
        return str_replace("'", '&apos;',
               str_replace("\\", '&bsol;',
               str_replace('"', '&quot;', $text)));
    }
    
    /**
     * Updates a parameter value in the target file from a template file 
     * @param string $templateFileName Template file name
     * @param string $targetFileName Target file name
     * @param string $parameter Parameter name to set 
     * @param mixed $value Value of the parameter
     * @param boolean $isString Specifies if the value is a string
     * @throws \ZDKException Thrown when the template file can't be opened, 
     * when the specified parameter does not exist, when the target file name
     * can't be updated 
     */
    private function updateFileParameter($templateFileName, $targetFileName, $parameter,
            $value, $isString = TRUE) {
        
        $templateContent = file_get_contents($templateFileName);
        if ($templateContent === FALSE) {
            throw new \ZDKException("AWZ-001: Unable to open the template file"
                    . " '$templateFileName'!");
        }
        $nbReplacements = 0;
        $storedValue = $isString ? "'".$this->escapeCharacters($value)."'" : $value;
        $targetContent = str_replace('%' .$parameter . '%', $storedValue
                , $templateContent, $nbReplacements);
        if ($nbReplacements!==1) {
            throw new \ZDKException("AWZ-002: The '$parameter' parameter was not found"
                . " in the template file '$templateFileName'!");
        }
        if (file_put_contents($targetFileName, $targetContent) === FALSE) {
            throw new \ZDKException("AWZ-003: Unable to save the value '$storedValue' for the parameter"
                    . " '$parameter' in the file '$targetFileName'!");
        }
        $this->addReportEntry(LA_RPT_ACTION_UPDATE_PARAM, \General::getFilledMessage(
            LA_RPT_RESULT_UPDATE_PARAM, $value, $parameter, $this->getStatus(TRUE)));
    }

    /**
     * Provides a MySQL connection
     * @param array $config Configuration parameters
     * @param array $dbParams Connection parameters
     * @return mixed The MySQL connection or FALSE if the connection has failed
     */
    private function getDbConnection($config, $dbParams) {
        if ($dbParams['create_db'] === 'no_database') {
            return FALSE;
        }
        if ($dbParams['create_db'] || $dbParams['create_tables']) {
            $host = $config['host'];
            $database = NULL; $user = NULL; $password = NULL;
            \app\controller\Wizard::getConnectionParameters($database, $user, $password);
            try {
                $dbConnection = \Database::getCustomDbConnection($host, $database, $user['value'],
                    $password['value']);
            } catch (\PDOException $ex) {
                $this->addReportEntry(LA_RPT_ACTION_CONNECT_SQL, \General::getFilledMessage(
                    LA_RPT_STATUS_ERROR_MSG, $ex->getMessage()));
                $dbConnection = FALSE;
            }
            $this->addReportEntry(LA_RPT_ACTION_CONNECT_SQL, \General::getFilledMessage(
                    LA_RPT_RESULT_CONNECT_SQL, $user['value'] . '@' . $host,
                    $this->getStatus($dbConnection !== FALSE)));
            return $dbConnection;
        }
        return FALSE;
    }
    
    /**
     * Creates the specified database
     * @param type $dbConnection Connection to MySQL
     * @param type $config Configuration parameters
     * @param type $dbParams Connection parameters
     * @return boolean TRUE if database creation succeeded, FALSE otherwise
     */
    private function createDatabase($dbConnection, $config, $dbParams) {
        if ($dbParams['create_db'] === 'yes') {
            if ($dbConnection === FALSE) {
                $this->addReportEntry(LA_RPT_ACTION_CREATE_DB, LA_RPT_RESULT_CONNECT_SQL_ERROR);
                $this->addReportEntry(LA_RPT_ACTION_CREATE_DB, \General::getFilledMessage(
                        LA_RPT_RESULT_CREATE_DB, $config['database'] ,$this->getStatus(FALSE)));
                return FALSE;
            }
            try {
                $databaseName = $config['database'];
                $dbConnection->exec("CREATE DATABASE `$databaseName`"
                        . " DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
                $isOK = TRUE;
            } catch (\PDOException $ex) {
                $this->addReportEntry(LA_RPT_ACTION_CREATE_DB, \General::getFilledMessage(
                    LA_RPT_STATUS_ERROR_MSG, $ex->getMessage()));
                $isOK = FALSE;
            }
            $this->addReportEntry(LA_RPT_ACTION_CREATE_DB, \General::getFilledMessage(
                    LA_RPT_RESULT_CREATE_DB, $databaseName ,$this->getStatus($isOK)));
            return $isOK;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Creates the specified user account
     * @param boolean $isDbCreated Specifies whether the database has been created
     * or not
     * @param \PDO $dbConnection Connection to MySQL
     * @param array $config Configuration parameters
     * @param array $dbParams Connection parameters
     * @return boolean TRUE if user's account has been created, FALSE otherwise
     */
    private function createUserAccount($isDbCreated, $dbConnection, $config, $dbParams) {
        if ($dbParams['create_db'] === 'yes') {
            if (!$isDbCreated) {
                $this->addReportEntry(LA_RPT_ACTION_CREATE_USR, LA_RPT_RESULT_CREATE_DB_ERROR);
                $this->addReportEntry(LA_RPT_ACTION_CREATE_USR, \General::getFilledMessage(
                    LA_RPT_RESULT_CREATE_USR, $config['user'], $this->getStatus(FALSE)));
                return FALSE;
            }
            try {
                $hostname = $config['host'];
                $databaseName = $config['database'];
                $userName = $config['user'];
                $password = $config['user_pwd'];
                $dbConnection->exec("CREATE USER '$userName'@'$hostname' IDENTIFIED BY '$password';");
                $dbConnection->exec("GRANT SELECT, INSERT, UPDATE, DELETE ON `$databaseName`.* "
                        . "TO '$userName'@'$hostname';");
                $isOK = TRUE;
            } catch (\PDOException $ex) {
                $this->addReportEntry(LA_RPT_ACTION_CREATE_USR, \General::getFilledMessage(
                    LA_RPT_STATUS_ERROR_MSG, $ex->getMessage()));
                $isOK = FALSE;
            }
            $this->addReportEntry(LA_RPT_ACTION_CREATE_USR, \General::getFilledMessage(
                    LA_RPT_RESULT_CREATE_USR, $userName, $this->getStatus($isOK)));
            return $isOK;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Creates the security tables of ZnetDK
     * @param \PDO $dbConnection Connection to MySQL
     * @param array $config Configuration parameters
     * @param array $dbParams Connection parameters
     * @return boolean TRUE if the tables has been created successfully, FALSE otherwise 
     */
    private function createSecurityTables($dbConnection, $config, $dbParams) {
        if ($dbParams['create_tables'] === 'yes') {
            if ($dbConnection === FALSE) {
                $this->addReportEntry(LA_RPT_ACTION_CREATE_DB, LA_RPT_RESULT_CONNECT_SQL_ERROR);
                return FALSE;
            } else {
                try {
                    $databaseName = $config['database'];
                    $dbConnection->exec("USE `$databaseName`");
                } catch (\PDOException $ex) {
                    $this->addReportEntry(LA_RPT_ACTION_CREATE_TABLE, \General::getFilledMessage(
                        LA_RPT_STATUS_ERROR_MSG, $ex->getMessage()));
                    return FALSE;
                }
            }
            $statements = $this->getDDLstatements();
            foreach ($statements as $statement) {
                try {
                    $dbConnection->exec($statement['sql']);
                    $isOK = TRUE;
                } catch (\PDOException $ex) {
                    $this->addReportEntry(LA_RPT_ACTION_CREATE_TABLE, \General::getFilledMessage(
                        LA_RPT_STATUS_ERROR_MSG, $ex->getMessage()));
                    $isOK = FALSE;
                }
                $this->addReportEntry(LA_RPT_ACTION_CREATE_TABLE, \General::getFilledMessage(
                    $statement['type'] === 'CREATE' ? LA_RPT_RESULT_CREATE_TABLE : LA_RPT_RESULT_ALTER_TABLE,
                    $statement['table'], $this->getStatus($isOK)));
            }
            return $isOK;
        } else {
            return TRUE;
        }
    }
    
    /**
     * Provides the list of SQL statements found in the 'znetdk-security.sql' script
     * @return array SQL statements found
     * @throws \ZDKException Thrown when the 'znetdk-security.sql' file can't be 
     * opened and when a SQL statement can't be read.
     */
    private function getDDLstatements() {
        $scriptSQL = $this->getOSappDirectory('appwiz') . 'template'
                . DIRECTORY_SEPARATOR . 'znetdk-security.sql';
        $sqlContent = file_get_contents($scriptSQL);        
        if ($sqlContent === FALSE) {
            throw new \ZDKException("AWZ-004: Unable to open the template file"
                    . " '$scriptSQL'!");
        }
        $returnedStatements = array();
        $statements = explode(';',$sqlContent);
        foreach ($statements as $statement) {
            $positionBegin = strpos($statement, '`');
            $positionEnd = strpos($statement, '`', $positionBegin + 1);
            if ($positionBegin > 0 && $positionBegin < $positionEnd) {
                $table = substr($statement, $positionBegin + 1, $positionEnd - $positionBegin -1);
                $statementType = strpos($statement,'CREATE') === FALSE
                        ? strpos($statement,'ALTER') === FALSE ? 'UNKNOWN' : 'ALTER'
                        : 'CREATE';
                $returnedStatements[] = array('type'=>$statementType,'table'=>$table,
                    'sql'=>$statement);
            } elseif ($positionBegin > 0) {
                throw new \ZDKException("AWZ-005: Unable to read SQL statement into the template file"
                    . " '$scriptSQL'!");
            }
        }
        return $returnedStatements;
    }
    
}
