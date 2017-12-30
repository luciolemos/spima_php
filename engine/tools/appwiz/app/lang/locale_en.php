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
 * Application Wizard english translations
 *
 * File version: 1.1
 * Last update: 10/25/2015
 * 
 */

/* General labels */
define('LC_PAGE_TITLE','ZnetDK AppWizard');

/* Heading labels */
define('LC_HEAD_TITLE','ZnetDK @pplication Wizard');
define('LC_HEAD_SUBTITLE','Configuring and customizing the starter application...');

/* Footer labels */
define('LC_FOOTER_CENTER','<a href="http://www.pm-consultant.fr/" target="_blank">PM Consultant</a>, Copyright © 2015');
define('LC_FOOTER_RIGHT','Wizard developed with <a href="http://www.znetdk.fr" target="_blank">ZnetDK</a>');

/* Heading images */
define('LC_HEAD_IMG_LOGO',ZNETDK_APP_URI.'images/logoappwiz.png');

/* Menu item labels */
define('LA_MENU_WELCOME','Welcome!');
define('LA_MENU_STEP1','Step 1 - Labels');
define('LA_MENU_STEP2','Step 2 - Theming');
define('LA_MENU_STEP3','Step 3 - Database access');
define('LA_MENU_STEP4','Step 4 - Generate...');

/* General error labels */
define('LA_ERR_APP_EXISTS_SUMMARY','Application already exists!');
define('LA_ERR_APP_EXISTS_MSG',"The default application already exists and can't be generated again.<br>"
        . "Remove the folder '<strong>%1</strong>' if you want to generate a new starter application.<br>"
        . "To disable the '<strong>". LC_HEAD_TITLE . "</strong>' (<em>required in production</em>), set the global constant "
        . "'<strong>ZDK_TOOLS_DISABLED</strong>' to <strong>TRUE</strong> in the '<strong>%2</strong>' file.");

/* Button labels */
define('LA_BUTTON_NEXT','Next step...');
define('LA_BUTTON_START','Start up...');
define('LA_BUTTON_PREVIEW','Preview...');
define('LA_BUTTON_GENERATE','Generate the application');
define('LA_BUTTON_SHOW_APP','Show the application');

/* Step's description */
define('LA_MENU_STEP1_DESC','Select the display default <strong>language</strong> of your application, enter its <strong>name</Strong> and set the text to display into the <strong>banner</strong> and the <strong>footer</strong>');
define('LA_MENU_STEP2_DESC','Choose the <strong>logo</strong> to display in the banner, select a pre-defined <strong>theme</strong> and the desired <strong>layout</strong>');
define('LA_MENU_STEP3_DESC','Configure and check the <strong>MySQL database</strong> connection of your application');
define('LA_MENU_STEP4_DESC','Generate the <strong>source code</strong> and the <strong>database</strong> of your starter application');

/* Welcome labels */
define('LA_LBL_WELCOME_INTRO','The <q><strong>ZnetDK Application Wizard</strong></q> will help you to configure and customize easily your first application.');
define('LA_LBL_WELCOME_REQUIREMENTS','The <strong>4 steps</strong> below are required:');
define('LA_LBL_WELCOME_LETSGO','Perform right now the first step of the Wizard: ');

/* Step 1 labels */
define('LA_LEGEND_STEP1_LANGUAGE','Display language');
define('LA_LBL_STEP1_LANGUAGE','Default language');
define('LA_INF_STEP1_LANGUAGE','Choose the main display language of your application');

define('LA_LEGEND_STEP1_APPNAME','Name of the application');
define('LA_LBL_STEP1_APPNAME','Application name');
define('LA_INF_STEP1_APPNAME','This label will be displayed into the browser tab header');

define('LA_LEGEND_STEP1_BANNER','Banner labels');
define('LA_LBL_STEP1_TITLE','Main title');
define('LA_INF_STEP1_TITLE','It can be different from the application name');
define('LA_LBL_STEP1_SUBTITLE','Subtitle');
define('LA_INF_STEP1_SUBTITLE','Only displayed by the <q>Classic</q> layout (see step 2)');

define('LA_LEGEND_STEP1_FOOTER','Footer labels');
define('LA_LBL_STEP1_FOOTER_LEFT','Left');
define('LA_LBL_STEP1_FOOTER_MID','Middle');
define('LA_LBL_STEP1_FOOTER_RIGHT','Right');

/* Step 1 default values */
define('LA_VAL_STEP1_APPNAME','My first application');
define('LA_VAL_STEP1_SUBTITLE','Generated from the ZnetDK @pplication Wizard');
define('LA_VAL_STEP1_FOOTER_LEFT','Version 1.0');
define('LA_VAL_STEP1_FOOTER_MID','Developed with ZnetDK');
define('LA_VAL_STEP1_FOOTER_RIGHT','Copyright ' . date('Y'));

/* Step 2 labels */
define('LA_LEGEND_STEP2_LOGO','Banner logo');
define('LA_LBL_STEP2_LOGO','Logo');
define('LA_INF_STEP2_LOGO','For an optimal display, choose an image with a height of 90 pixels.<br>'
        . 'The image size is limited to 100 KB.');

define('LA_LEGEND_STEP2_THEME','Widget theme');
define('LA_LBL_STEP2_THEME','Theme');
define('LA_INF_STEP2_THEME','Click on the <q><strong>'.LA_BUTTON_PREVIEW.'</strong></q> button below'
        . ' to experiment the choosen theme');

define('LA_LBL_STEP2_THEME_EMPTY','Choose a theme...');
define('LA_LEGEND_STEP2_LAYOUT','Layout of the application');
define('LA_LBL_STEP2_LAYOUT','Layout');
define('LA_INF_STEP2_LAYOUT','Click on the <q><strong>'.LA_BUTTON_PREVIEW.'</strong></q> button below'
        . ' to experiment the choosen layout');
define('LA_INF_STEP2_LAYOUT_CLASSIC','The <q><strong>Classic</strong></q> layout is based on a navigation tab menu.');
define('LA_INF_STEP2_LAYOUT_OFFICE','The <q><strong>Office</strong></q> layout offers a multi-windows display of the views.');


/* Step 3 labels */
define('LA_LEGEND_STEP3_CREATEDB','Creating the database and the security tables');
define('LA_LBL_STEP3_HOST','Hostname');
define('LA_INF_STEP3_HOST','Keep value <strong>127.0.0.1</strong> when MySQL is installed locally');
define('LA_LBL_STEP3_DB','Database name');
define('LA_INF_STEP3_DB','');
define('LA_LBL_STEP3_USER','User account');
define('LA_INF_STEP3_USER','');
define('LA_LBL_STEP3_PASSWORD','Password');
define('LA_INF_STEP3_PASSWORD','Initialized by default to the value <q><strong>password</strong></q> (change it!)');
define('LA_LEGEND_STEP3_DATABASE','Database of the application');
define('LA_LBL_STEP3_ACTIONS','Create the database?');
define('LA_INF_STEP3_ACTIONS','Keep <q><strong>Yes</strong></q> if ZnetDK is installed for the first time');
define('LA_LBL_STEP3_CREATE_DATABASE',"Yes, create the specified database and user account");
define('LA_LBL_STEP3_NOCREATE_DATABASE',"No, the above database already exists");
define('LA_LBL_STEP3_NO_USE_DATABASE',"No, the access to a database is not required");
define('LA_LBL_STEP3_CREATE_DBTABLES','ZnetDK security tables');
define('LA_LBL_STEP3_CREATE_DBTABLES_CHKBOX',"Create the ZnetDK's tables required for user authentication");
define('LA_INF_STEP3_CREATE_DBTABLES','');
define('LA_LEGEND_STEP3_ADMIN','Super user authorized to create the database');
define('LA_LBL_STEP3_ADMIN','Super user account');
define('LA_INF_STEP3_ADMIN','The user account <q><strong>root</strong></q> exists in standard in MySQL');
define('LA_INF_STEP3_ADMIN_PWD','');

/* Step 4 labels (report) */
define('LA_INF_STEP4_APPNAME',"See <strong>LC_PAGE_TITLE</strong> in '<strong>locale.php</strong>'");
define('LA_INF_STEP4_TITLE',"See <strong>LC_HEAD_TITLE</strong> in '<strong>locale.php</strong>'");
define('LA_INF_STEP4_SUBTITLE',"See <strong>LC_HEAD_SUBTITLE</strong> in '<strong>locale.php</strong>'");
define('LA_INF_STEP4_LOGO',"See <strong>LC_HEAD_IMG_LOGO</strong> in '<strong>locale.php</strong>'");
define('LA_INF_STEP4_FOOTER_LEFT',"See <strong>LC_FOOTER_LEFT</strong> in '<strong>locale.php</strong>'");
define('LA_INF_STEP4_FOOTER_MID',"See <strong>LC_FOOTER_CENTER</strong> in '<strong>locale.php</strong>'");
define('LA_INF_STEP4_FOOTER_RIGHT',"See <strong>LC_FOOTER_RIGHT</strong> in '<strong>locale.php</strong>'");

define('LA_INF_STEP4_LANGUAGE',"See <strong>CFG_DEFAULT_LANGUAGE</strong> in '<strong>config.php</strong>'");
define('LA_INF_STEP4_THEME',"See <strong>CFG_THEME</strong> in '<strong>config.php</strong>");
define('LA_INF_STEP4_LAYOUT',"See <strong>CFG_PAGE_LAYOUT</strong> in '<strong>config.php</strong>'");
define('LA_INF_STEP4_HOST',"See <strong>CFG_SQL_HOST</strong> in '<strong>config.php</strong>'");
define('LA_INF_STEP4_DB',"See <strong>CFG_SQL_APPL_DB</strong> in '<strong>config.php</strong>'");
define('LA_INF_STEP4_USR',"See <strong>CFG_SQL_APPL_USR</strong> in '<strong>config.php</strong>'");
define('LA_INF_STEP4_PWD',"See <strong>CFG_SQL_APPL_PWD</strong> in '<strong>config.php</strong>'");

define('LA_LBL_STEP4_GEN_CONFIRM','<strong>Do you really want to generate the application?</strong>');
define('LA_LBL_STEP4_GEN_CONFIRM_EXT','<p><span style="color:red;font-weight:bold">Attention</span><span style="font-style:italic;">, the '
        . 'mandatory values displayed below have not been entered and therefore will be initialized from default values:</span></p>');
define('LA_LBL_STEP4_REPORT_DLG','Generation report');
define('LA_LBL_STEP4_REPORT_INTRO',"The application has been generated in the following directory:<br><strong>"
        . ZNETDK_ROOT . 'applications' . DIRECTORY_SEPARATOR . 'default</strong><br><br>Find below the detailed report.');
define('LA_COL_STEP4_REPORT_STEP','#');
define('LA_COL_STEP4_REPORT_ACTION','Action');
define('LA_COL_STEP4_REPORT_RESULT','Result');

/* Mandatory field message */
define('LA_MSG_STEP1_LANG_MANDATORY','The display language of the application must be specified!');
define('LA_MSG_STEP1_APPL_MANDATORY','A name must be entered for the application!');
define('LA_MSG_STEP1_TITL_MANDATORY','A main title must be entered for the banner!');
define('LA_MSG_STEP2_LOGO_MANDATORY','An image must be selected for the banner logo!');
define('LA_MSG_STEP2_THEM_MANDATORY','A widget theme must be choosen!');
define('LA_MSG_STEP3_HOST_MANDATORY','The hostname of the machine where MySQL is installed, must be entered!'
        . '<br>For a local installation, the expected value is <q><strong>127.0.0.1</strong></q>');
define('LA_MSG_STEP3_USER_MANDATORY','A user account name must be entered!'
        . '<br>This account will be used by ZnetDK to access to your application database.');
define('LA_MSG_STEP3_PWD_MANDATORY',"A password is required for the user's account!");
define('LA_MSG_STEP3_DB_MANDATORY','A database name must be entered!'
        . '<br>This database will be used to store your application data.');
define('LA_MSG_STEP3_ADMI_MANDATORY','The super user account must be specified for creating the database!'
        . '<br>For example, use the standard <q><strong>root</strong></q> MySQL account to create de database.');

/* Controller messages */
define('LA_MSG_UPLOAD_SUMMARY','Upload of the logo image');
define('LA_MSG_UPLOAD_NO_IMAGE',"The file '<strong>%1</strong>' selected for the logo is not a picture!<br>"
        . "Please, select another image file.");
define('LA_MSG_UPLOAD_TOO_LARGE_IMAGE',"The file '<strong>%1</strong>' selected "
        . "for the logo exceeds the maximum size of '<strong>%2</strong>' bytes !<br>"
        . "Please, select another image file.");
define('LA_MSG_UPLOAD_ERROR',"Unable to upload the <strong>%1</strong>' file for the logo!<br>"
        . "The following error occurred: %2");
define('LA_MSG_UPLOAD_NO_FILE','No logo has been selected for the application');
define('LA_MSG_STEP3_CONNECT_SUMMARY','Test connection...');
define('LA_MSG_STEP3_CONNECT_ERROR','The SQL connection failed with the following error:<br>');
define('LA_MSG_STEP3_CREATE_TABLES_SUMMARY',"Create the ZnetDK's tables");
define('LA_MSG_STEP3_EXISTING_TABLE',"The <strong>'%1'</strong> security table already exists!<br>"
        . "You should uncheck the option '<strong>" . LA_LBL_STEP3_CREATE_DBTABLES
        . "</strong>' if the ZnetDK security tables are already installed.");
define('LA_MSG_STEP3_MISSING_TABLE',"The '%1' security table does not exist!<br>"
        . "You should check the option '<strong>" . LA_LBL_STEP3_CREATE_DBTABLES
        . "</strong>' if the ZnetDK security tables are not yet installed.");
define('LA_MSG_STEP3_EXISTING_DB',"The specified database <strong>'%1'</strong>"
        . " already exists!<br>You should select <strong>'No'</strong> for the option '<strong>"
        . LA_LBL_STEP3_ACTIONS . "'</strong> or choose another database name.");
define('LA_MSG_STEP3_USER_EXISTS',"The '<strong>%1</strong>' user already exists!<br>"
        . "You should select <strong>'No'</strong> for the option '<strong>"
        . LA_LBL_STEP3_ACTIONS . "'</strong> or enter another user account.");
define('LA_MSG_STEP3_USER_BAD_PASSWORD',"The password entered for the user '<strong>%1</strong>' user "
        . "is invalid or the user does not exist!<br>Please, try to enter the password again or check if "
        . "this user really exists.");
define('LA_MSG_STEP3_ADMIN_NO_PRIVS',"The '<strong>%1</strong>' super user does not have "
        . "sufficient privileges!<br>You should select <strong>'No'</strong> for"
        . " the option '<strong>" . LA_LBL_STEP3_ACTIONS . "'</strong> or enter"
        . " another super user account like the '<strong>root</strong>' standard account of MySQL.");
define('LA_MSG_STEP3_USER_NO_PRIVS',"The '<strong>%1</strong>' super user does not have "
        . "sufficient privileges!<br>You should uncheck the option '<strong>" . LA_LBL_STEP3_CREATE_DBTABLES
        . "</strong>' or enter another super user account like the '<strong>root</strong>' standard account of MySQL.");
define('LA_MSG_STEP3_ADMIN_EQUAL_USER',"The '<strong>%1</strong>' super user can't be "
        . "the same user account than the one that has to be created!<br>"
        . "You should enter another super user account or another user to create");

/* Report labels */
define('LA_RPT_STATUS_OK','OK');
define('LA_RPT_STATUS_FAILED','failed!');
define('LA_RPT_STATUS_NB_ERRORS','Errors found: ');
define('LA_RPT_STATUS_ERROR_MSG','Error: %1');
define('LA_RPT_ACTION_SUMMARY','Summary');
define('LA_RPT_RESULT_SUMMARY_OK','Generation process terminated successfully');
define('LA_RPT_RESULT_SUMMARY_ERRORS','Generation process terminated with %1 errors!');

define('LA_RPT_ACTION_UPDATE_PARAM','Configuration');
define('LA_RPT_RESULT_UPDATE_PARAM',"Set value '%1' for the parameter '%2': %3");
define('LA_RPT_ACTION_CREATE_DB','Creating database');
define('LA_RPT_RESULT_CREATE_DB',"Create database '%1' : %2");
define('LA_RPT_RESULT_CREATE_DB_ERROR',"The database has not been created! Consequently, the user '%1' can't be in turn created");
define('LA_RPT_ACTION_CREATE_USR','Creating user');
define('LA_RPT_RESULT_CREATE_USR',"Create user '%1': %2");
define('LA_RPT_ACTION_CREATE_TABLE','Creating ZnetDK table');
define('LA_RPT_RESULT_CREATE_TABLE',"Create table '%1': %2");
define('LA_RPT_RESULT_ALTER_TABLE',"Add constraint on table '%1': %2");
define('LA_RPT_ACTION_CONNECT_SQL','Connecting to MySQL');
define('LA_RPT_RESULT_CONNECT_SQL',"Connection as '%1' user: %2");
define('LA_RPT_RESULT_CONNECT_SQL_ERROR',"No connection to MySQL!");
define('LA_RPT_ACTION_CREATE_DIR',"Generating directories");
define('LA_RPT_RESULT_CREATE_DIR',"Create directory '%1': %2");
define('LA_RPT_ACTION_COPY_FILE','Copying files');
define('LA_RPT_RESULT_COPY_FILE',"Copy file '%1' as '%2': %3");
define('LA_RPT_RESULT_COPY_LOGO_ERROR',"Error: '%1'. The default logo '%2' is copying instead.");

/* Other labels */
define('LA_LBL_NOVALUE','&lt; No value! &gt;');