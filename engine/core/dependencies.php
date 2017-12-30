<?php
/*
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
 * Core renderer of the ressource dependencies  
 *
 * File version: 1.4
 * Last update: 01/27/2017
 */

/**
 * Adds the HTML dependencies to the page layout that are required by ZnetDK
 */
class Dependencies {

    /**
     * Main public method called by the Layout controller to insert the 
     * HTML dependencies to the main page of the application.
     */
    public static function render() {
        $icon = defined('LC_HEAD_ICO_LOGO') ? LC_HEAD_ICO_LOGO : LC_HEAD_IMG_LOGO;
        echo '<link rel="icon" type="image/png" href="'.$icon.'">'.PHP_EOL;
        self::renderCSS();
        self::renderJS();
    }
    
    /**
     * Renders the CSS dependencies
     */
    static private function renderCSS() {
        $activeTheme = ThemeManager::getActiveThemeCssFilePath();
        $cssDependencies = array(CFG_JQUERYUI_CSS, $activeTheme['cssPath'],
            CFG_PRIMEUI_CSS, CFG_FONTAWESOME_CSS, \General::getFilledMessage(CFG_ZNETDK_CSS,'primeui'),
            \General::getFilledMessage(CFG_ZNETDK_CSS,'form'));
        if (\Parameters::getPageLayoutName() !== 'custom') {
            $cssDependencies[] = \General::getFilledMessage(CFG_ZNETDK_CSS,'layout');
            $cssDependencies[] = \General::getFilledMessage(CFG_ZNETDK_CSS,'layout-'.\Parameters::getPageLayoutName());
        }
        self::addModulesDependencies('css', $cssDependencies);
        if (CFG_APPLICATION_CSS != '') {
            $cssDependencies[] =  CFG_APPLICATION_CSS;
        }
        if ($activeTheme['level'] === 'application') {
            $cssDependencies[] = \General::getFilledMessage(CFG_ZNETDK_CSS,'custom-theme');
        }
        foreach ($cssDependencies as $value) {
            echo "\t".'<link rel="stylesheet" type="text/css" href="'.ZNETDK_ROOT_URI.$value.'">' . PHP_EOL;
        }
    }
        
    /**
     * Render the JavaScript dependencies in their minified version
     */
    static private function renderJS() {
        $jsDependencies = array(CFG_JQUERY_JS,CFG_JQUERYUI_JS,CFG_BLOCKUI_JS);
        $datePickerJS = \General::getFilledMessage(CFG_JQUERYUI_DATE_JS,LC_LANG_ISO_CODE);
        if (file_exists(ZNETDK_ROOT.$datePickerJS)) {
            $jsDependencies[] = $datePickerJS;
        }
        if (CFG_DEV_JS_ENABLED) {
            self::renderDevelopmentJS($jsDependencies);
        } else {
            $jsDependencies[] = CFG_PRIMEUI_JS;
            $jsDependencies[] = CFG_ZNETDK_JS;
        }
        self::addModulesDependencies('js', $jsDependencies);
        if (CFG_APP_JS != '') {
            $jsDependencies[] = CFG_APP_JS;
        }
        echo "\t".'<script type="text/javascript">var znetdkAjaxURL = "'.\General::getMainScript(TRUE).'";</script>'.PHP_EOL;
        foreach ($jsDependencies as $value) {
            echo "\t".'<script type="text/javascript" src="'.ZNETDK_ROOT_URI.$value.'"></script>'.PHP_EOL;
        }
    }
    
    /**
     * Renders the JavaScript dependencies in their extended version for
     * development purpose 
     * @param array $jsDependencies Array filled with the relative path of the
     * scripts to include in the application page.
     */
    static private function renderDevelopmentJS(&$jsDependencies) {
        $extraDir = array('..', '.');
        $jsPrimeUiFiles = array_diff(scandir(ZNETDK_ROOT.CFG_PRIMEUI_JS_DEV_DIR),$extraDir);
        foreach ($jsPrimeUiFiles as $value) {
            $jsDependencies[] = CFG_PRIMEUI_JS_DEV_DIR."/".$value.'/'.$value.'.js';
        }
        $jsZnetDkFiles = array_diff(scandir(ZNETDK_ROOT.CFG_ZNETDK_JS_DIR.'/ui'),$extraDir);
        foreach ($jsZnetDkFiles as $value) {
            $jsDependencies[] = CFG_ZNETDK_JS_DIR."/ui/".$value;
        }
        $jsDependencies[] = CFG_ZNETDK_JS_DIR.'/api.js';
        $jsDependencies[] = CFG_ZNETDK_JS_DIR.'/init.js';
    }
    /**
     * Add module specific dependencies to the CSS or JS dependencies renderer.
     * @param string $subdir Subdirectory containing the files to add
     * @param array $dependencies Array containing all the dependencies to 
     * render and that is to be filled in.
     */
    static private function addModulesDependencies($subdir, &$dependencies) {
        $modules = \General::getModules();
        if ($modules === FALSE) {
            return;
        }
        $extraDir = array('..', '.','minified');
        if ($subdir === 'css') {
            $subdirectory = strstr(CFG_ZNETDK_CSS,'-min.') === FALSE ? $subdir : $subdir . '/minified';    
        } else {
            $subdirectory = CFG_DEV_JS_ENABLED ? $subdir : $subdir . '/minified';
        }
        foreach ($modules as $moduleName) {
            $directory = ZNETDK_MOD_ROOT . DIRECTORY_SEPARATOR . $moduleName
                    . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR
                    . $subdirectory;
            if (!file_exists($directory)) {
                continue;
            }
            $filesFound = array_diff(scandir($directory, SCANDIR_SORT_ASCENDING), $extraDir);
            foreach ($filesFound as $file) {
                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                if ($fileExtension === $subdir && $file !== 'gulpfile.js') {
                    $dependencies[] = 'engine/modules/' . $moduleName . '/public/'
                        . $subdirectory . '/' . $file;
                }
            }
        }
    }
}