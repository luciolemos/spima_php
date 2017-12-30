<?php

/**
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website http://www.znetdk.fr 
 * (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
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
 * Main page of the application
 * For XDEBUG activation : index.php?XDEBUG_SESSION_START=1234
 * Page encoded in UTF8 without BOM to avoid the JQuery json.parse unexpected character exception
 *
 * File version: 1.3
 * Last update: 04/03/2017
 */
// Auto load of classes used in this script
spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$initialIncludePath = get_include_path();

/** OS root absolute path of the directory where ZnetDK is installed */
define('ZNETDK_ROOT',getcwd().DIRECTORY_SEPARATOR);
/** OS absolute path of the ZnetDK core namespace */
define('ZNETDK_CORE_ROOT',ZNETDK_ROOT.'engine'.DIRECTORY_SEPARATOR.'core');
set_include_path(ZNETDK_CORE_ROOT);

/** Muti-application parameter (ZDK_TOOLS_DISABLED) */
set_include_path(get_include_path().PATH_SEPARATOR.ZNETDK_ROOT . 'applications'); 
@include('globalconfig.php');

/** Include modules in the path */
define('ZNETDK_MOD_ROOT',ZNETDK_ROOT.'engine'.DIRECTORY_SEPARATOR.'modules');
set_include_path(get_include_path().PATH_SEPARATOR.ZNETDK_MOD_ROOT);

/** Current internal name of the application */
define('ZNETDK_APP_NAME', \General::getApplicationID());
/** OS absolute path of the application namespace */
define('ZNETDK_APP_ROOT',getcwd(). DIRECTORY_SEPARATOR . \General::getApplicationRelativePath(ZNETDK_APP_NAME));
set_include_path(get_include_path().PATH_SEPARATOR.ZNETDK_APP_ROOT);

// Global configuration
@include("version.php"); // Current version of ZnetDK 
@include("app/config.php"); // Application configuration file is optional
\General::initModuleParameters();
@include("config.php"); // Core configuration file is mandatory

// Start user session if the script is not called from command line
if (!isset($argc)) {
    session_start();
}

// Error tracking enabled
\ErrorHandler::init();

/** ZnetDK absolute URI, for example "/znetdk/" */
define('ZNETDK_ROOT_URI', \General::getAbsoluteURI());

/** Current application absolute URI for accessing web ressources */
define('ZNETDK_APP_URI', ZNETDK_ROOT_URI 
        . \General::getApplicationPublicDirRelativeURI(ZNETDK_APP_NAME));

/** Localized strings */
\api\Locale::setApplicationLanguage();

/** Original include path is applied and is prioritary to the ZnetDK packages */
set_include_path($initialIncludePath . PATH_SEPARATOR . get_include_path());

/** Call of the front controller */
\MainController::doAction();
