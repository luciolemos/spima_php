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
* Core english translations of the application
*
* File version: 1.6
* Last update: 02/11/2017
*/

/* General PHP localization settings (used by the PHP 'setlocale' function) */
define ('LC_LOCALE_ALL', serialize(array('en_US','english'))); 

/* Overriden localization settings (instead of the 'setlocale' settings) */
define('LC_LOCALE_DECIMAL_SEPARATOR', NULL);
define('LC_LOCALE_THOUSANDS_SEPARATOR', NULL);
define('LC_LOCALE_NUMBER_OF_DECIMALS', NULL);
define('LC_LOCALE_CURRENCY_SYMBOL', NULL);
define('LC_LOCALE_CURRENCY_SYMBOL_PRECEDE', NULL);
define('LC_LOCALE_CURRENCY_SYMBOL_SEPARATE', NULL);
define('LC_LOCALE_DATE_FORMAT', NULL);
define('LC_LOCALE_CSV_SEPARATOR', ',');

/* jQueryUI datePicker language ISO code */
define('LC_LANG_ISO_CODE','en');

/* General labels */
define('LC_PAGE_TITLE','ZnetDK application');

/* Heading labels */
define('LC_HEAD_TITLE','ZnetDK core application');
define('LC_HEAD_SUBTITLE','Ready for development...');
define('LC_HEAD_LNK_LOGOUT','Logout');
define('LC_HEAD_LNK_HELP','Help');

/* Heading images */
define('LC_HEAD_IMG_LOGO',ZNETDK_ROOT_URI . CFG_ZNETDK_IMG_DIR . '/logoznetdk.png');

/* Footer labels */
define('LC_FOOTER_LEFT',"Version ".ZNETDK_VERSION);
define('LC_FOOTER_CENTER','Copyright 2014-2017 <a href="http://www.pm-consultant.fr" target="_blank">PM Consultant</a>');
define('LC_FOOTER_RIGHT','Realized with <a href="http://www.znetdk.fr" target="_blank">ZnetDK</a>');

/* Home page labels */
define('LC_HOME_WELCOME','Welcome to ZnetDK');
define('LC_HOME_LEGEND_DBSTATUS','Status of the application database');
define('LC_HOME_TXT_DB_SETTINGS1','Settings');
define('LC_HOME_TXT_DB_SETTINGS2','user = <strong>'.CFG_SQL_APPL_USR.'@'.CFG_SQL_HOST
        .'</strong>, database = <strong>'. CFG_SQL_APPL_DB .'</strong>');
define('LC_HOME_TXT_DB_CONNECT1','Connection to the database');
define('LC_HOME_TXT_DB_CONNECT2_OK','<span class="success">tested successfully</span>');
define('LC_HOME_TXT_DB_CONNECT2_KO','<span class="failed">failed to connect</span>');
define('LC_HOME_TXT_DB_TABLES1','Security tables');
define('LC_HOME_TXT_DB_TABLES2_OK','<span class="success">properly installed</span>');
define('LC_HOME_TXT_DB_TABLES2_KO','<span class="failed">error detected</span>');
define('LC_HOME_DATABASE_ERROR','Error: ');

define('LC_HOME_LEGEND_START','Start your development with ZnetDK');
define('LC_HOME_TXT_START_MENU1',"Menu definition");
define('LC_HOME_TXT_START_MENU2',"the application's menu currently displayed is configured into the <strong>"
        . ZNETDK_APP_ROOT . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "menu.php</strong>"
        . " and can be totally customized to display your new developed views.");
define('LC_HOME_TXT_START_CONCEPTS1','Concepts, Tutorial & Demos');
define('LC_HOME_TXT_START_CONCEPTS2','you will find on the official website '
        . 'a presentation of the <a href="http://www.znetdk.fr/concepts" target="_blank">ZnetDK concepts</a>, '
        . 'a <a href="http://www.znetdk.fr/tutoriel" target="_blank">tutorial</a> '
        . 'and several <a href="http://www.znetdk.fr/demonstration" target="_blank">demonstrations</a>.');
define('LC_HOME_TXT_START_API1','API reference');
define('LC_HOME_TXT_START_API2','the full development API in <a href="http://www.znetdk.fr/api" target="_blank">PHP</a> '
        . 'and <a href="http://www.znetdk.fr/api#local_api" target="_blank">JavaScript</a> is also available on the website, '
        . 'including the documentation about the <a href="http://www.znetdk.fr/composants_graphiques" target="_blank">ZnetDK widgets</a>.');

/* Theme page label */
define('LC_THEME_MESSAGE','Click on a <strong>theme thumbnail</strong> below to see its appearance into your application.'
	.'<br/>You can also edit one of these themes or <strong>create your own theme</strong> from the jQuery UI page <a href="http://jqueryui.com/themeroller/" target="_blank">ThemeRoller</a>...');

/* Widgets page label */
define('LC_WIDGETS_MESSAGE',"Find here a sample of the <strong>PrimeUI widgets</strong> with which you can develop your client-side applications' views."
        . '<br>See a demonstration of all the <strong>available widgets</strong> on the official <a href="http://www.primefaces.org/primeui/" target="_blank">PrimeUI website</a>.');

/* Windows manager labels */
define('LC_WINMGR_TITLE',"Windows");
define('LC_WINMGR_AUTOCLOSE',"Auto-closing");
define('LC_WINMGR_ADJUST_HORIZ',"Adjust horizontally");
define('LC_WINMGR_ADJUST_VERTI',"Adjust vertically");
define('LC_WINMGR_CLOSE_ALL',"Close all");

/* FORM titles */
define('LC_FORM_TITLE_LOGIN','Authentication');
define('LC_FORM_TITLE_CHANGE_PASSWORD','Change password');
define('LC_FORM_TITLE_HELP','Online help - ');
define('LC_FORM_TITLE_USER_NEW','New user');
define('LC_FORM_TITLE_USER_MODIFY',"Edit a user");
define('LC_FORM_TITLE_USER_REMOVE',"Remove a user");
define('LC_FORM_TITLE_PROFILE_NEW','New profile');
define('LC_FORM_TITLE_PROFILE_MODIFY',"Edit a profile");
define('LC_FORM_TITLE_PROFILE_REMOVE',"Remove a profile");

/* Authorizations menu label */
define('LC_MENU_AUTHORIZATION','Authorizations');
define('LC_MENU_AUTHORIZ_USERS','Users');
define('LC_MENU_AUTHORIZ_PROFILES','Profiles');

/* Authorizations Datatable labels */
define('LC_TABLE_AUTHORIZ_USERS_CAPTION','authorized users');
define('LC_TABLE_AUTHORIZ_PROFILES_CAPTION','user profiles');
define('LC_TABLE_COL_LOGIN_ID','Login ID');
define('LC_TABLE_COL_USER_NAME','User name');
define('LC_TABLE_COL_USER_EMAIL','Email');
define('LC_TABLE_COL_USER_STATUS','Status');
define('LC_TABLE_COL_MENU_ACCESS','Menu access');
define('LC_TABLE_COL_USER_PROFILES','Profiles');
define('LC_TABLE_COL_PROFILE_NAME','Profile');
define('LC_TABLE_COL_PROFILE_DESC','Description');
define('LC_TABLE_COL_MENU_ITEMS','Menu Items');

/* Login Form labels */
define('LC_FORM_LBL_LOGIN_ID','Login ID');
define('LC_FORM_LBL_PASSWORD','Password');
define('LC_FORM_LBL_ORIG_PASSWORD','Original password');
define('LC_FORM_LBL_NEW_PASSWORD','New passowrd');
define('LC_FORM_LBL_PASSWORD_CONFIRM','Confirmation');
define('LC_FORM_LBL_ACCESS','Access is');
define('LC_FORM_LBL_PUBL_ACC','public (session timeout)');
define('LC_FORM_LBL_PRIV_ACC','private');

/* User Form labels */
define('LC_FORM_FLD_USER_IDENTITY','Identity');
define('LC_FORM_FLD_USER_CONNECTION','Connection');
define('LC_FORM_FLD_USER_RIGHTS','Rights');
define('LC_FORM_LBL_USER_NAME','User name');
define('LC_FORM_LBL_USER_EMAIL','Email');
define('LC_FORM_LBL_USER_EXPIRATION_DATE','Expire on');
define('LC_FORM_LBL_USER_STATUS','Status');
define('LC_FORM_LBL_USER_STATUS_ENABLED','Enabled');
define('LC_FORM_LBL_USER_STATUS_DISABLED','Disabled');
define('LC_FORM_LBL_USER_MENU_ACCESS','Menu access');
define('LC_FORM_LBL_USER_MENU_ACCESS_FULL','Full');
define('LC_FORM_LBL_USER_PROFILES','Profiles');

/* Other Form labels */
define('LC_FORM_LBL_NO_FILE_SELECTED','&lt; No file selected! &gt;');
define('LC_ACTION_ROWS_LABEL','Rows per page');

/* BUTTON labels */
define('LC_BTN_LOGIN','Connect');
define('LC_BTN_CANCEL','Cancel');
define('LC_BTN_CLOSE','Close');
define('LC_BTN_SAVE','Save');
define('LC_BTN_NEW','New');
define('LC_BTN_MODIFY','Edit');
define('LC_BTN_OPEN','Open');
define('LC_BTN_OK','Ok');
define('LC_BTN_REMOVE','Remove');
define('LC_BTN_MANAGE','Manage');
define('LC_BTN_YES','Yes');
define('LC_BTN_NO','No');
define('LC_BTN_SELECTFILE','Choose...');
define('LC_BTN_EXPORT','Export...');
define('LC_BTN_IMPORT','Import...');
define('LC_BTN_ARCHIVE','Archive...');
define('LC_ACTION_SEARCH_KEYWORD_BTN_RUN','Launch the search');
define('LC_ACTION_SEARCH_KEYWORD_BTN_CLEAR','Clear the search keyword');

/* CRITICAL ERROR messages */
define('LC_MSG_CRI_ERR_SUMMARY','Technical hitch');
define('LC_MSG_CRI_ERR_DETAIL',"A technical problem has occurred while processing the last requested action. Please contact your administrator to report the error details below:<br><span class='zdk-err-detail'>\"%1\"</span>");

/* ERROR messages */
define('LC_MSG_ERR_EXCEPTION','Technical problem');
define('LC_MSG_ERR_LOGIN','Login ID or password is invalid!');
define('LC_MSG_ERR_DIFF_LOGIN','You must use the same login ID to renew the connection!');
define('LC_MSG_ERR_LOGIN_DISABLED','Your user account is disabled.<br>Contact your security manager to reactivate your account.');
define('LC_MSG_ERR_LOGIN_EXPIRATION','Your password has expired! Please, renew your password.');
define('LC_MSG_ERR_LOGIN_TOO_MUCH_ATTEMPTS','The maximum number of attempts allowed has been achieved!<br>Your account has been disabled.');
define('LC_MSG_ERR_HTTP','<h3>HTTP Error %1!</h3><p><a href="%2">Clic here</a> to return to the home page.</p>');
define('LC_MSG_ERR_SELECT_RECORD',"A database error occured! Unable to select the data!");
define('LC_MSG_ERR_SAVE_RECORD',"A database error occured! The record can't be saved!");
define('LC_MSG_ERR_REMOVE_RECORD',"A database error occured! The record can't be removed!");
define('LC_MSG_ERR_MISSING_VALUE',"Please enter a value!");
define('LC_MSG_ERR_MISSING_VALUE_FOR',"Please enter a value for '%1'!");
define('LC_MSG_ERR_PWD_MISMATCH','The first password does not match the second password for confirmation!');
define('LC_MSG_ERR_PWD_IDENTICAL','The new password must be different than the current password!');
define('LC_MSG_ERR_PASSWORD_BADLENGTH','The password must contain between 8 and 14 characters!');
define('LC_MSG_ERR_EMAIL_INVALID','The email address is not a valid email!');
define('LC_MSG_ERR_LOGIN_BADLENGTH','The login ID must contain between 6 and 20 characters!');
define('LC_MSG_ERR_VALUE_BADLENGTH','Bad length for this value!');
define('LC_MSG_ERR_LOGIN_EXISTS','A user already exists with the same login ID!');
define('LC_MSG_ERR_PROFILE_EXISTS',"The profile '%1' already exists with the same name!");
define('LC_MSG_ERR_EMAIL_EXISTS','A user already exists with the same email!');
define('LC_MSG_ERR_DATE_INVALID','Date format is invalid!');
define('LC_MSG_ERR_VALUE_INVALID','Value not expected!');
define('LC_MSG_ERR_REMOVE_PROFILE','Can not delete! The profile is currently assigned to one or more users.');
define('LC_MSG_ERR_NETWORK','Network error|Check your network connection and try again.');
define('LC_MSG_ERR_FORBIDDEN_ACTION_SUMMARY','Operation not allowed');
define('LC_MSG_ERR_FORBIDDEN_ACTION_MESSAGE',"You're not allowed to do the requested operation.");

/* WARNING messages */
define('LC_MSG_WARN_NO_AUTH',"You're not authenticated, please log in first.");
define('LC_MSG_WARN_SESS_TIMOUT',"Your session has timed out, please log in again.");
define('LC_MSG_WARN_HELP_NOTFOUND',"No help is available for the current view.");
define('LC_MSG_WARN_ROW_NOTSELECTED',"Please, select a row in the data table first!");
define('LC_MSG_WARN_PROFILE_ROWS_EXIST',"<p><strong><span style='color:red;'>Attention</span></strong>: "
    . "<span style='font-style:italic;'>this profile is associated to data rows of the application which also will be removed!</span></p>");
define('LC_MSG_WARN_SEARCH_NO_VALUE','Please enter a keyword first!');

/* INFO messages */
define('LC_MSG_INF_LOGIN',"You have logged in successfully.");
define('LC_MSG_INF_PWDCHANGED',"Your password has been changed successfully.");
define('LC_MSG_INF_LOGOUT','<h3>Logout succeed.</h3><p><a href="">Clic here</a> to login again.</p>');
define('LC_MSG_INF_CANCEL_LOGIN','<h3>Connection canceled.</h3><p><a href="">Clic here</a> to login.</p>');
define('LC_MSG_INF_SAVE_RECORD','The record has been saved successfully.');
define('LC_MSG_INF_REMOVE_RECORD','The record has been removed successfully.');
define('LC_MSG_INF_SELECT_LIST_ITEM','For multiple selection, hold the <Control> key pressed while clicking on an item of the list.');
define('LC_MSG_INF_SELECT_TREE_NODE','For multiple selection, hold the <Control> key pressed while clicking on a tree node.');

/* QUESTION messages */
define('LC_MSG_ASK_REMOVE','Do you really want to remove this record?');
define('LC_MSG_ASK_CANCEL_CHANGES','The form data have been modified.'
        . '<br><br>Quit without saving?');