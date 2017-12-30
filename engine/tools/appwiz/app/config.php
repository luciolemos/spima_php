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
* Parameters of the Application Wizard
*
* File version: 1.0
* Last update: 09/18/2015 
*/


/** Is multilingual translation enabled for your application?
 * @return boolean Value true if multilingual is enabled
 */
define('CFG_MULTI_LANG_ENABLED',TRUE);

/** Relative path of the custom CSS file of the application */
define('CFG_APPLICATION_CSS','engine/tools/'.ZNETDK_APP_NAME.'/public/css/appwiz.css');

/** Relative path of the Javascript file specially developed for the application */
define('CFG_APP_JS','engine/tools/'.ZNETDK_APP_NAME.'/public/js/appwiz.js');

/** Relative path of the animated GIF image displayed during AJAX requests */
define('CFG_AJAX_LOADING_IMG','engine/tools/'.ZNETDK_APP_NAME.'/public/images/ajax-loader.gif');

/** Load Development version of the PrimeUI & ZnetDK widgets for debug purpose */
define('CFG_DEV_JS_ENABLED',FALSE);