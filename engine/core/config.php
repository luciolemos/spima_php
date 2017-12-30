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
* Core parameters of the applications
*
* File version: 1.3
* Last update: 04/01/2017 
*/

/** Page layout chosen for the application.
 *  @return 'classic'|'office'|'custom' Name of the layout used by the application.
 */
define('CFG_PAGE_LAYOUT','classic');

/** Is online help enabled?
 * @return boolean TRUE when online help facility is enabled, FALSE when disabled.
 */
define('CFG_HELP_ENABLED',FALSE);

/** Relative path of the jQuery CSS file */
define('CFG_JQUERYUI_CSS','resources/jquery-ui-1.10.3/themes/base/minified/jquery-ui.min.css');

/** Relative path of the PrimeUI CSS file */
define('CFG_PRIMEUI_CSS','resources/primeui-1.1/production/primeui-1.1-min.css');

/** Relative path of the FontAwesome CSS file */
define('CFG_FONTAWESOME_CSS','resources/font-awesome-4.7.0/css/font-awesome.min.css');

/** Relative path of the ZnetDK CSS files */
define('CFG_ZNETDK_CSS','engine/public/css/minified/%1-min.css');

/** Relative path of the custom CSS file of the application */
define('CFG_APPLICATION_CSS',NULL);

/** Relative path of the directory containing the pictures displayed by ZnetDK */
define('CFG_ZNETDK_IMG_DIR','engine/public/images');

/** Relative path of the animated GIF image displayed during AJAX requests */
define('CFG_AJAX_LOADING_IMG',CFG_ZNETDK_IMG_DIR.'/ajax-loader.gif');

/** Relative path of the ZnetDK errors log file */
define('CFG_ZNETDK_ERRLOG','engine'. DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'errors.log');

/** Relative path of the ZnetDK system log file */
define('CFG_ZNETDK_SYSLOG','engine'. DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'system.log');

/** Relative path of the jQuery Javascript file */
define('CFG_JQUERY_JS','resources/jquery-ui-1.10.3/jquery-1.9.1.min.js');

/** Relative path of the jQueryUI Javascript file */
define('CFG_JQUERYUI_JS','resources/jquery-ui-1.10.3/ui/minified/jquery-ui.min.js');

/** Relative path of the jQueryUI date picker calendar */
define('CFG_JQUERYUI_DATE_JS','resources/jquery-ui-1.10.3/ui/minified/i18n/jquery.ui.datepicker-%1.min.js');

/** Relative path of the PrimeUI directory */
define('CFG_PRIMEUI_DIR','resources/primeui-1.1');

/** Relative path of the PrimeUI Javascript file */
define('CFG_PRIMEUI_JS',CFG_PRIMEUI_DIR.'/production/primeui-1.1-min.js');

/** Relative path of the PrimeUI Javascript development directory */
define('CFG_PRIMEUI_JS_DEV_DIR',CFG_PRIMEUI_DIR.'/development/js');

/** Relative path of the BlockUI Javascript file */
define('CFG_BLOCKUI_JS','resources/blockui-2.66/blockui.js');

/** Relative path of the ZnetDK Javascript directory */
define('CFG_ZNETDK_JS_DIR','engine/public/js');

/** Relative path of the ZnetDK Javascript file */
define('CFG_ZNETDK_JS',CFG_ZNETDK_JS_DIR.'/minified/znetdk-min.js');

/** Relative path of the Javascript file specially developed for the application */
define('CFG_APP_JS',NULL);

/** Load Development version of the PrimeUI & ZnetDK widgets for debug purpose */
define('CFG_DEV_JS_ENABLED',FALSE);

/** Is multilingual translation enabled for your application?
 * @return boolean Value TRUE if multilingual is enabled
 */
define('CFG_MULTI_LANG_ENABLED',FALSE);

/** Default selected language when the browser language is not supported by the
 * application
 * @return string 2-character code in ISO 639-1, for example 'fr'
 */ 
define('CFG_DEFAULT_LANGUAGE','en');

/** Labels displayed for selecting a translation language of the application
 * @return array Serialized array of language labels where each key is a
 *  2-character code in ISO 639-1.<br>For example:
 * <code>serialize(array('fr'=>'Français','en'=>'English',
 * 'es'=>'Español'))</code>
 */
define('CFG_COUNTRY_LABELS', serialize(array('fr'=>'Français','en'=>'English','es'=>'Español')));

/** Relative path of the directory where country icons are located
 * @return array Serialized array of paths where each key is a
 *  2-character code in ISO 639-1.<br>For example:
 * <code>serialize(array(
 * 'fr'=>CFG_ZNETDK_IMG_DIR.'/lang_flag_fr.png',
 * 'en'=>CFG_ZNETDK_IMG_DIR.'/lang_flag_en.png',
 * 'es'=>CFG_ZNETDK_IMG_DIR.'/lang_flag_es.png'))</code>
 */
define('CFG_COUNTRY_ICONS', serialize(array('fr'=>CFG_ZNETDK_IMG_DIR.'/lang_flag_fr.png','en'=>CFG_ZNETDK_IMG_DIR.'/lang_flag_en.png','es'=>CFG_ZNETDK_IMG_DIR.'/lang_flag_es.png')));

/** Session Time out in minutes
 * @return integer Number of minutes without user activity before his session expires
 */
define('CFG_SESSION_TIMEOUT',10);

/** Specifies whether the user session expires or not
 * @return 'public'|'private' When set to 'public', the user session expires.
 * <br>When set to 'private', the user session never expires.    
 */
define('CFG_SESSION_DEFAULT_MODE','public');

/** Specifies if the user can change the session expiration mode
 * @return boolean Value TRUE if the user is authorized to change the way his
 *  session expires when the login dialog is displayed.<br>Value FALSE if the
 *  user can't do it. In this last case, the session expiration mode imposed to
 *  the user is the one specified for the parameter CFG_SESSION_DEFAULT_MODE
 */
define('CFG_SESSION_SELECT_MODE',TRUE);

/** Is authentication required?
 * @return boolean Value TRUE if the user must authenticate to access to the
 *  application
 */
define('CFG_AUTHENT_REQUIRED',FALSE);

/** Validity period in months of the password before its expiration
 * @return integer Number of months before the password expires
 */
define('CFG_DEFAULT_PWD_VALIDITY_PERIOD',6);

/** Regular expression used to check if a new entered password is valid 
 * @return string Regular expression for new password checking
 */
define('CFG_CHECK_PWD_VALIDITY','/^.{8,14}$/');

/** Number attempts allowed to user to type in his password successfully
 * @return integer Number of login attempts allowed to user 
 */
define('CFG_NBR_FAILED_AUTHENT',3);

/** Is view content preloaded before access?
 * @return boolean Value TRUE if all the views of the application are to be
 * preloaded as soon as the application is loaded in the user's browser.
 */
define('CFG_VIEW_PRELOAD',FALSE);

/** Is page reloaded each time a view is opened from the navigation menu?
 * @return boolean Value TRUE if the page of the application is to be reloaded
 * when the user click on a menu item. This case is suitable for content 
 *  publishing. 
 */
define('CFG_VIEW_PAGE_RELOAD',FALSE);

/** SQL engine of the databases used by ZnetDK */
define('CFG_SQL_ENGINE','mysql');

/** Host name of the machine where the database MySQL is installed.
 * @return string For example, '127.0.0.1' or 'mysql78.perso'
 */
define('CFG_SQL_HOST',NULL);

/** Database which contains the core tables of ZnetDK.
 * @return string For example 'znetdk-core'
 */
define('CFG_SQL_CORE_DB',NULL);

/** Prefixes to replace from the ZnetDK core and application tables (for hosting
 * multiple applications in a unique database).
 * Multiple prefix replacements can be set through the serialized array.
 * @return string For example: serialize(array('zdk_'=>'new_', 'app_'=>'new_'))
 */
define('CFG_SQL_TABLE_REPLACE_PREFIXES',NULL);

/** User declared in the core database to access to the tables dedicated to
 *  ZnetDK
 * @return string For example 'core'
 */
define('CFG_SQL_CORE_USR',NULL);

/** User's password declared in the core database
 * @return string For example 'password'
 */
define('CFG_SQL_CORE_PWD',NULL);

/** Database which contains the tables specially created for the application.
 * @return string For example 'znetdk-app'
 */
define('CFG_SQL_APPL_DB',NULL);

/** User declared in the database of the application to access to the tables
 *  specially created for business needs
 * @return string For example 'app'
 */
define('CFG_SQL_APPL_USR',NULL);

/** User's password declared in the database of the application.
 * @return string For example 'password'
 */
define('CFG_SQL_APPL_PWD',NULL);

/** Relative path of the directory where are installed the PrimeUI themes */
define('CFG_THEME_PRIMEUI_DIR',CFG_PRIMEUI_DIR.'/themes');

/** Relative path of the directory where are installed the ZnetDK themes */
define('CFG_THEME_ZNETDK_DIR','engine/public/themes');

/** Relative path of the directory where is installed the custom theme specified
 *  for the parameter CFG_THEME_DIR
 *  @return string For example 'applications/default/public/themes'
 */
define('CFG_THEME_DIR','applications/'.ZNETDK_APP_NAME.'/public/themes');

/** Relative path of the directory where are stored the electronic documents
 *  @return string For example '/home/www/znetdk/applications/default/documents'
 */
define('CFG_DOCUMENTS_DIR',ZNETDK_APP_ROOT . DIRECTORY_SEPARATOR . 'documents');

/** Theme enabled for the application ('znetdk' in standard)
 * @return string Name of the theme chosen for the application.<br>
 * For example: 'znetdk', 'flat-blue', 'aristo', 'south-street' ...
 */
define('CFG_THEME','znetdk');

/** The PHP binary full path with its arguments for the auto-execution of the 
 * 'autoexec' controller action as a background process
 * Use %1 as placeholder of the absolute path of the application root directory
 * (value of ZNETDK_ROOT),
 * Use %2 as placeholder of the ZnetDK 'index.php' script,
 * Use %3 as placeholder of the 'autoexec' argument passed to the 'index.php'
 * script,
 * Use %4 as placeholder of the application ID argument (value of
 * ZNETDK_APP_NAME) passed to the 'index.php' script.
 * @return string The full path of the PHP interpreter binary<br>
 * For example on linux : '/usr/bin/php %1%2 %3 %4 >/dev/null 2>&1 &'<br>
 * For example on Windows : 'start "" /B "php" "%2" "%3" "%4" >NUL 2>&1'
 */
define('CFG_AUTOEXEC_PHP_BINARY_PATH', NULL);

/** The time elapsed in seconds before the next auto-execution of the 'autoexec'
 *  controller action as a background process 
 * @return integer Time elapsed in seconds before next execution<br>
 * For example : 3600 for one hour<br>
 */
define('CFG_AUTOEXEC_FREQUENCY', 3600);

/** The full path of the synchronization file where are stored the last date and
 * time when the the autoexec controller action has been launched 
 * @return string Absolute file path of the synchronization autoexec file
 */
define('CFG_AUTOEXEC_SYNCHRO_FILE', ZNETDK_ROOT . 'engine' 
        . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'autoexec.sync');

/** Enables or Disables the tracking of the 'autoexec' process through the 
 * system log file
 * @return boolean TRUE for tracking the execution of the 'autoexec' process,
 * otherwise FALSE.
 */
define('CFG_AUTOEXEC_LOG_ENABLED', FALSE);
