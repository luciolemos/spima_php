<?php

define('CFG_DEFAULT_LANGUAGE','pt');

/** Is multilingual translation enabled for your application?
 * @return boolean Value true if multilingual is enabled
 */
define('CFG_MULTI_LANG_ENABLED',TRUE);

/** Page layout choosen for the application.
 *  @return 'classic'|'office'|'custom' Name of the layout used by the application.
 */
define('CFG_PAGE_LAYOUT', 'office');

/** Theme enabled for the application ('znetdk' in standard)
 * @return string Name of the theme choosen for the application.<br>
 * For example: 'znetdk', 'flat-blue', 'aristo', 'south-street' ...
 */
define('CFG_THEME', 'pepper-grinder');

/** Session Time out in minutes
 * @return integer Number of minutes without user activity before his session expires
 */
define('CFG_SESSION_TIMEOUT',10);

/** Is authentication required?
 * @return boolean Value true if the user must authenticate to access to the
 *  application
 */
define('CFG_AUTHENT_REQUIRED',TRUE);

/** Host name of the machine where the database MySQL is installed.
 * @return string For example, '127.0.0.1' or 'mysql78.perso'
 */
define('CFG_SQL_HOST', '127.0.0.1');

/** Database which contains the tables specially created for the application.
 * @return string For example 'znetdk-db'
 */
define('CFG_SQL_APPL_DB', 'spima');

/** User declared in the database of the application to access to the tables
 *  specially created for business needs
 * @return string For example 'app'
 */
define('CFG_SQL_APPL_USR', 'root');

/** User's password declared in the database of the application.
 * @return string For example 'password'
 */
define('CFG_SQL_APPL_PWD', 'root');

/** Relative path of the custom CSS file of the application */
define('CFG_APPLICATION_CSS','applications/'.ZNETDK_APP_NAME.'/public/css/custom.css');

/** Is online help enabled?
 * @return boolean true when online help facility is enabled, FALSE when disabled.
 */
define('CFG_HELP_ENABLED',TRUE);

/** Relative path of the directory containing the pictures displayed by ZnetDK */
define('CFG_ZNETDK_IMG_DIR','applications/default/public/images');



/** Labels displayed for selecting a translation language of the application
 * @return array Serialized array of language labels where each key is a
 *  2-character code in ISO 639-1.<br>For example:
 * <code>serialize(array('fr'=>'Fran�ais','en'=>'English',
 * 'es'=>'Espa�ol'))</code>
 */
define('CFG_COUNTRY_LABELS', serialize(array(
    'fr'=>'Frances',
    'en'=>'Ingles',
    'es'=>'Espanhol',
    'pt'=>'Portugues')));

/** Relative path of the directory where country icons are located
 * @return array Serialized array of paths where each key is a
 *  2-character code in ISO 639-1.<br>For example:
 * <code>serialize(array(
 * 'fr'=>CFG_ZNETDK_IMG_DIR.'/lang_flag_fr.png',
 * 'en'=>CFG_ZNETDK_IMG_DIR.'/lang_flag_en.png',
 * 'es'=>CFG_ZNETDK_IMG_DIR.'/lang_flag_es.png'))</code>
 */
define('CFG_COUNTRY_ICONS', serialize(array(
    'fr'=>CFG_ZNETDK_IMG_DIR.'/lang_flag_fr.png',
    'en'=>CFG_ZNETDK_IMG_DIR.'/lang_flag_en.png',
    'es'=>CFG_ZNETDK_IMG_DIR.'/lang_flag_es.png',
    'pt'=>CFG_ZNETDK_IMG_DIR.'/lang_flag_pt.png')));



