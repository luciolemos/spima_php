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
 * ZnetDK Application Wizard Tool: controller
 *
 * File version: 1.2
 * Last update: 10/14/2016
 */

namespace app\controller;

/**
 * ZnetDK Core controller for profile management
 */
class Wizard extends \AppController {
        
    /**
     * Checks if the default application already exists
     */
    static protected function action_doesappexist() {
        $response = new \Response();
        $defaultAppDir = ZNETDK_ROOT . \General::getApplicationRelativePath(
            \General::$defaultApp);
        if (is_dir($defaultAppDir)) {
            $globalConfigPath = ZNETDK_ROOT. 'applications' . DIRECTORY_SEPARATOR .'globalconfig.php';
            $response->setFailedMessage(LA_ERR_APP_EXISTS_SUMMARY,
                \General::getFilledMessage(LA_ERR_APP_EXISTS_MSG
                    , $defaultAppDir, $globalConfigPath));
        } else {
            $response->success = TRUE;
        }
        return $response;
    }
    
    /**
     * Returns a full list of the ISO639-1 languages
     */
    static protected function action_languages() {
        $request = new \Request();
        $keyword = $request->query;
        $suggestions = \app\model\Languages::getLanguages($keyword);
        $response = new \Response();
        $response->setResponse($suggestions);
        return $response;
    }

    /**
     * Returns a list of widget's themes supported by ZnetDK 
     */
    static protected function action_themes() {
        $response = new \Response();
        $response->success = TRUE;
        $response->rows = \ThemeManager::getAllThemes();
        return $response;
    }
    
    /**
     * Upload the banner logo of the application
     */
    static protected function action_upload() {
        $response = new \Response();
        $uploadedFile = new \app\UploadedFile('logo', 'logo', 'appwiz-preview', 102400);
        try {
            $uploadedFile->upload();
            $response->success = TRUE;
        } catch (\ZDKException $ex) {
            switch ($ex->getCode()) {
                case 'UPL-004': 
                    $errMsg = \General::getFilledMessage(LA_MSG_UPLOAD_NO_IMAGE,
                            $uploadedFile->getSourceFileName());
                    break;
                case 'UPL-006': 
                    $errMsg = \General::getFilledMessage(LA_MSG_UPLOAD_TOO_LARGE_IMAGE,
                            $uploadedFile->getSourceFileName(), $uploadedFile->getMaxFileSize());
                    break;
                default:
                    $errMsg = \General::getFilledMessage(LA_MSG_UPLOAD_ERROR,
                            $uploadedFile->getSourceFileName(), $ex->getMessage());
            }
            $response->setFailedMessage(LA_MSG_UPLOAD_SUMMARY, $errMsg, 'logo');
        }
        return $response;
    }
    
    /**
     * Generates a preview of the application
     */
    static protected function action_preview() {
        $response = new \Response();
        $appGenerator = new \app\AppGenerator('appwiz-preview');
        $locale = NULL; $config = NULL;
        self::getPreviewParameters($locale, $config);
        try {
            $appGenerator->preview($locale, $config);
            $response->success = TRUE;
            $response->url = $appGenerator->getApplicationUrl();
        } catch (Exception $ex) {
            $response->setFailedMessage("Show preview", $ex->getMessage());
        }
        return $response;
    }
    
    /**
     * Tests the connection from the database parameters entered for the application
     */
    static protected function action_connect() {
        $response = new \Response();
        $validator = new \app\validator\DBMSAccess();
        if (!$validator->validate()) {
            $response->setFailedMessage(LA_MSG_STEP3_CONNECT_SUMMARY,
                    $validator->getErrorMessage(),
                    $validator->getErrorVariable());
        } else {
            $response->success = TRUE;
        }
        return $response;
    }
    
    /**
     * Generates the default application from its characteristics
     */
    static protected function action_generate() {
        $generator = new \app\AppGenerator(\General::$defaultApp);
        $locale = NULL; $config = NULL; $dbParams = NULL; $uploadError = NULL;
        self::getGenerateParameters($locale, $config, $dbParams, $uploadError);
        $generator->generate($locale, $config, $dbParams, $uploadError);
        $response = new \Response();
        $response->success = TRUE;
        $response->report = $generator->getReport();
        $response->nberrors = $generator->getNbErrors();
        $response->url = $generator->getApplicationUrl();
        return $response;
    }
    
    /**
     * Provides the connection parameters to the database which are sent thru
     * POST parameters
     * @param string $database Name of the database
     * @param string $user User name of the MySQL account 
     * @param string $password Password's user
     */
    static public function getConnectionParameters(&$database,&$user,&$password) {
        $request = new \Request();
        $createDB = $request->create_db;
        $createTables = $request->create_tables;
        if ($createDB === 'no' && $createTables !== 'yes') {
            $database = $request->database;
            $user['value'] = $request->user;
            $user['field'] = 'user';
            $password['value'] = $request->user_pwd;
            $password['field'] = 'user_pwd';
        } else {
            $database = NULL;
            $user['value'] = $request->admin;
            $user['field'] = 'admin';
            $password['value'] = $request->admin_pwd;
            $password['field'] = 'admin_pwd';
        }
    }
    
    /**
     * Provides the POST parameters values used to generate the preview application
     * @param array $locale Parameters relative to the localization 
     * @param array $config Parameters relative to the global configuration
     */
    static private function getPreviewParameters(&$locale, &$config) {
        $request = new \Request();
        $locale = $request->getValuesAsMap('logo','appl_name','banner_title',
            'banner_subtitle','footer_left','footer_mid','footer_right');
        $config = $request->getValuesAsMap('def_lang','theme','layout','host',
                'database','user','user_pwd');
        // Remove file extension
        if (!is_null($config['theme'])) {
            $config['theme'] = pathinfo($config['theme'],PATHINFO_FILENAME);
        }
        // Transform the language label to its ISO code
        $config['def_lang'] = !is_null($config['def_lang']) ?
            \app\model\Languages::getLanguageCode($config['def_lang']) : 'en';
        // Reset to NULL the database parameters when no database is required
        if ($request->create_db === 'no_database') {
            $config['host'] = NULL;
            $config['database'] = NULL;
            $config['user'] = NULL;
            $config['user_pwd'] = NULL;
        }
    }
    
    /**
     * Provides the POST parameters used to generate the default application
     * @param array $locale Parameters concerning the localization
     * @param array $config Parameters for global configuration
     * @param array $dbParams Parameters for database connection
     * @param array $uploadError Upload status and error message
     */
    static private function getGenerateParameters(&$locale, &$config, &$dbParams, &$uploadError) {
        $request = new \Request();
        self::getPreviewParameters($locale, $config);
        $dbParams = $request->getValuesAsMap('create_db','create_tables',
                'admin','admin_pwd');
        $uploadError = $request->upload_ok === 'yes' ? NULL : $request->upload_msg;
    }

}
