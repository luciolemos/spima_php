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
* Core spanish translations of the application
*
* File version: 1.6
* Last update: 02/11/2017
*/

/* General PHP localization settings (used by the PHP 'setlocale' function) */
define ('LC_LOCALE_ALL', serialize(array('es_ES','spanish'))); 

/* Overriden localization settings (instead of the 'setlocale' settings) */
define('LC_LOCALE_DECIMAL_SEPARATOR', NULL);
define('LC_LOCALE_THOUSANDS_SEPARATOR', NULL);
define('LC_LOCALE_NUMBER_OF_DECIMALS', NULL);
define('LC_LOCALE_CURRENCY_SYMBOL', NULL);
define('LC_LOCALE_CURRENCY_SYMBOL_PRECEDE', NULL);
define('LC_LOCALE_CURRENCY_SYMBOL_SEPARATE', NULL);
define('LC_LOCALE_DATE_FORMAT', NULL);
define('LC_LOCALE_CSV_SEPARATOR', ';');

/* jQueryUI datePicker language ISO code */
define('LC_LANG_ISO_CODE','es');

/* General labels */
define('LC_PAGE_TITLE','Aplicación ZnetDK');

/* Heading labels */
define('LC_HEAD_TITLE','Aplicación ZnetDK (core)');
define('LC_HEAD_SUBTITLE','Lista para desarrollar...');
define('LC_HEAD_LNK_LOGOUT','desconectarse');
define('LC_HEAD_LNK_HELP','Ayuda');

/* Heading images */
define('LC_HEAD_IMG_LOGO',ZNETDK_ROOT_URI . CFG_ZNETDK_IMG_DIR . '/logoznetdk.png');

/* Footer labels */
define('LC_FOOTER_LEFT','Versión '.ZNETDK_VERSION);
define('LC_FOOTER_CENTER','Copyright 2014-2017 <a href="http://www.pm-consultant.fr" target="_blank">PM Consultant</a>');
define('LC_FOOTER_RIGHT','Realizado con <a href="http://www.znetdk.fr" target="_blank">ZnetDK</a>');

/* Home page labels */
define('LC_HOME_WELCOME','Bienvenido en ZnetDK');
define('LC_HOME_LEGEND_DBSTATUS','Estado de la base de datos de la aplicación');
define('LC_HOME_TXT_DB_SETTINGS1','configuración');
define('LC_HOME_TXT_DB_SETTINGS2','usuario = <strong>'.CFG_SQL_APPL_USR.'@'.CFG_SQL_HOST
        .'</strong>, base de datos = <strong>'. CFG_SQL_APPL_DB .'</strong>');
define('LC_HOME_TXT_DB_CONNECT1','Conexión a la base de datos');
define('LC_HOME_TXT_DB_CONNECT2_OK','<span class="success">prueba exitosa</span>');
define('LC_HOME_TXT_DB_CONNECT2_KO','<span class="failed">failed to connect</span>');
define('LC_HOME_TXT_DB_TABLES1','Tablas de seguridad');
define('LC_HOME_TXT_DB_TABLES2_OK','<span class="success">corectamente instaladas</span>');
define('LC_HOME_TXT_DB_TABLES2_KO','<span class="failed">instalación errónea</span>');
define('LC_HOME_DATABASE_ERROR','Error: ');

define('LC_HOME_LEGEND_START','Comience su desarrollo con ZnetDK');
define('LC_HOME_TXT_START_MENU1',"Definición de menú");
define('LC_HOME_TXT_START_MENU2',"el menú de la aplicación que aparece actualmente, está configurado en el script <strong>"
        . ZNETDK_APP_ROOT . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "menu.php</strong>"
        . " y puede ser totalmente personalizado para mostrar las nuevas vistas desarrolladas.");
define('LC_HOME_TXT_START_CONCEPTS1','Conceptos, Tutorial y Demos');
define('LC_HOME_TXT_START_CONCEPTS2','encontrará en el sitio web oficial '
        . 'una presentación de los  <a href="http://www.znetdk.fr/concepts" target="_blank">conceptos ZnetDK</a>, '
        . 'un <a href="http://www.znetdk.fr/tutoriel" target="_blank">tutorial</a> '
        . 'y varias <a href="http://www.znetdk.fr/demonstration" target="_blank">demostraciones</a>.');
define('LC_HOME_TXT_START_API1','Referencia de la API');
define('LC_HOME_TXT_START_API2','la API de desarrollo en <a href="http://www.znetdk.fr/api" target="_blank">PHP</a> '
        . 'y <a href="http://www.znetdk.fr/api#local_api" target="_blank">JavaScript</a> también está disponible en el sitio web, '
        . 'incluyendo la documentación relativa a los <a href="http://www.znetdk.fr/composants_graphiques" target="_blank">widgets de ZnetDK</a>.');

/* Theme page label */
define('LC_THEME_MESSAGE','Haga clic sobre una <strong>miniatura del tema</strong> para visualizarla en su aplicación.'
	.'<br/>También puede editar uno de estos temas o <strong>crear su propio tema</strong> desde la página <a href="http://jqueryui.com/themeroller/" target="_blank">ThemeRoller</a> de jQuery UI...');

/* Widgets page label */
define('LC_WIDGETS_MESSAGE',"Encuentra aquí una muestra de los <strong>widgets PrimeUI</strong> con el que puede desarrollar las vistas de su aplicación del lado del cliente."
        . '<br>Vea una demostración de <strong>todos los widgets disponibles</strong> en el <a href="http://www.primefaces.org/primeui/" target="_blank">sitio web PrimeUI</a>.');

/* Windows manager labels */
define('LC_WINMGR_TITLE',"Ventanas");
define('LC_WINMGR_AUTOCLOSE',"Cierre auto.");
define('LC_WINMGR_ADJUST_HORIZ',"Ajuste horizontal.");
define('LC_WINMGR_ADJUST_VERTI',"Ajuste vertical.");
define('LC_WINMGR_CLOSE_ALL',"Cerrar todo");

/* FORM titles */
define('LC_FORM_TITLE_LOGIN','Conexión');
define('LC_FORM_TITLE_CHANGE_PASSWORD','Cambio de contraseña');
define('LC_FORM_TITLE_HELP','Ayuda en línea - ');
define('LC_FORM_TITLE_USER_NEW','Nuevo usuario');
define('LC_FORM_TITLE_USER_MODIFY',"Modificar un usuario");
define('LC_FORM_TITLE_USER_REMOVE',"Eliminar usuario");
define('LC_FORM_TITLE_PROFILE_NEW','Nuevo perfil');
define('LC_FORM_TITLE_PROFILE_MODIFY',"Modificar un perfil");
define('LC_FORM_TITLE_PROFILE_REMOVE',"Eliminar perfil");

/* Authorizations menu label */
define('LC_MENU_AUTHORIZATION','Permisos');
define('LC_MENU_AUTHORIZ_USERS','Usarios');
define('LC_MENU_AUTHORIZ_PROFILES','Perfiles');

/* Authorizations view labels */
define('LC_VIEW_AUTHORIZATION_USER','Usuario');
define('LC_VIEW_AUTHORIZATION_PROFILES','Perfiles');
define('LC_VIEW_AUTHORIZATION_USERS','Usuarios registrados');

/* Authorizations Datatable labels */
define('LC_TABLE_AUTHORIZ_USERS_CAPTION','usuarios registrados');
define('LC_TABLE_AUTHORIZ_PROFILES_CAPTION','perfiles de usuario');
define('LC_TABLE_COL_LOGIN_ID','Login de conexión');
define('LC_TABLE_COL_USER_NAME','Nombre Usario');
define('LC_TABLE_COL_USER_EMAIL','Email');
define('LC_TABLE_COL_USER_STATUS','Estado');
define('LC_TABLE_COL_MENU_ACCESS','Accesso al menú');
define('LC_TABLE_COL_USER_PROFILES','Perfiles');
define('LC_TABLE_COL_PROFILE_NAME','Perfil');
define('LC_TABLE_COL_PROFILE_DESC','Descripción');
define('LC_TABLE_COL_MENU_ITEMS','Elementos de menú');

/* Login Form labels */
define('LC_FORM_LBL_LOGIN_ID','Login');
define('LC_FORM_LBL_PASSWORD','Contraseña');
define('LC_FORM_LBL_ORIG_PASSWORD','Contraseña actual');
define('LC_FORM_LBL_NEW_PASSWORD','Nueva contraseña');
define('LC_FORM_LBL_PASSWORD_CONFIRM','Confirmación');
define('LC_FORM_LBL_ACCESS','Accesso');
define('LC_FORM_LBL_PUBL_ACC','público (finaliza la sesión)');
define('LC_FORM_LBL_PRIV_ACC','privado');

/* User Form labels */
define('LC_FORM_FLD_USER_IDENTITY','Datos personales');
define('LC_FORM_FLD_USER_CONNECTION','Conexión');
define('LC_FORM_FLD_USER_RIGHTS','Permisos');
define('LC_FORM_LBL_USER_NAME','Nombre');
define('LC_FORM_LBL_USER_EMAIL','Email');
define('LC_FORM_LBL_USER_EXPIRATION_DATE','Expira el');
define('LC_FORM_LBL_USER_STATUS','Estado');
define('LC_FORM_LBL_USER_STATUS_ENABLED','Activado');
define('LC_FORM_LBL_USER_STATUS_DISABLED','Desactivado');
define('LC_FORM_LBL_USER_MENU_ACCESS','Accesso al menú');
define('LC_FORM_LBL_USER_MENU_ACCESS_FULL','Completo');
define('LC_FORM_LBL_USER_PROFILES','Perfiles');

/* Other Form labels */
define('LC_FORM_LBL_NO_FILE_SELECTED','&lt; No archivo seleccionnado! &gt;');
define('LC_ACTION_ROWS_LABEL','Líneas por página');

/* BUTTON labels */
define('LC_BTN_LOGIN','Conectarse');
define('LC_BTN_CANCEL','Cancelar');
define('LC_BTN_CLOSE','Cerrar');
define('LC_BTN_SAVE','Guardar');
define('LC_BTN_NEW','Nuevo');
define('LC_BTN_MODIFY','Editar');
define('LC_BTN_OPEN','Abrir');
define('LC_BTN_OK','Ok');
define('LC_BTN_REMOVE','Eliminar');
define('LC_BTN_MANAGE','Administrar');
define('LC_BTN_YES','Sí');
define('LC_BTN_NO','No');
define('LC_BTN_SELECTFILE','Elegir...');
define('LC_BTN_EXPORT','Exportar...');
define('LC_BTN_IMPORT','Importar...');
define('LC_BTN_ARCHIVE','Archivar...');
define('LC_ACTION_SEARCH_KEYWORD_BTN_RUN','Iniciar la búsqueda');
define('LC_ACTION_SEARCH_KEYWORD_BTN_CLEAR','Eliminar la palabra clave');

/* CRITICAL ERROR messages */
define('LC_MSG_CRI_ERR_SUMMARY','Problema técnico');
define('LC_MSG_CRI_ERR_DETAIL',"Ha ocurrido un problema. Por favor, póngase en contacto con su administrador para informar de los detalles del error a continuación:<br><span class='zdk-err-detail'>\"%1\"</span>");

/* ERROR messages */
define('LC_MSG_ERR_LOGIN','Login o contraseña incorrecta !');
define('LC_MSG_ERR_DIFF_LOGIN','Tiene que utilizar el mismo login para conectarse de nuevo!');
define('LC_MSG_ERR_LOGIN_DISABLED','Su cuenta de usuario ha sido desactivada.<br>Pongase en contacto con su responsable de la seguridad para activar de nuevo su cuenta.');
define('LC_MSG_ERR_LOGIN_EXPIRATION','Su contraseña ha expirado! Renueve su contraseña por favor.');
define('LC_MSG_ERR_LOGIN_TOO_MUCH_ATTEMPTS','El maximo de intentos autorizados ha sido alcanzado!<br>Su cuenta de usuario ha sido desactivada.');
define('LC_MSG_ERR_HTTP','<h3>Error HTTP %1!</h3><p><a href="%2">Haga clic aquí</a> para volver a la página de inicio.</p>');
define('LC_MSG_ERR_SELECT_RECORD',"Ha ocurrido un error! No se pueden seleccionar los datos!");
define('LC_MSG_ERR_SAVE_RECORD',"Ha ocurrido un error! No se puede guardar el registro!");
define('LC_MSG_ERR_REMOVE_RECORD',"Ha ocurrido un error! No se puede eliminar el registro!");
define('LC_MSG_ERR_MISSING_VALUE',"Por favor, introduzca un valor!");
define('LC_MSG_ERR_MISSING_VALUE_FOR',"Por favor, introduzca un valor por '%1'!");
define('LC_MSG_ERR_PWD_MISMATCH','La contraseña y su confirmación no corresponden!');
define('LC_MSG_ERR_PWD_IDENTICAL','La nueva contraseña tiene que ser diferente de la contraseña actual!');
define('LC_MSG_ERR_PASSWORD_BADLENGTH','La contraseña debe contener entre 8 y 14 caracteres!');
define('LC_MSG_ERR_EMAIL_INVALID','El email no es válido!');
define('LC_MSG_ERR_LOGIN_BADLENGTH','El login ID debe contener entre 6 y 20 caracteres!');
define('LC_MSG_ERR_VALUE_BADLENGTH','El número de caracteres es incorrecto para este valor!');
define('LC_MSG_ERR_LOGIN_EXISTS','Un usario ya existe con el mismo login de conexión!');
define('LC_MSG_ERR_PROFILE_EXISTS',"El perfil '%1' ya existe con el mismo nombre!");
define('LC_MSG_ERR_EMAIL_EXISTS','Un usario ya existe con el mismo email!');
define('LC_MSG_ERR_DATE_INVALID','El formato de la fecha no es válido !');
define('LC_MSG_ERR_VALUE_INVALID','Valor inesperado !');
define('LC_MSG_ERR_REMOVE_PROFILE','No se puede suprimir! El perfil está actualmente asignado a uno o más usuarios.');
define('LC_MSG_ERR_NETWORK','Error en la red|Compruebe su conexión de red y vuelve a intentarlo.');
define('LC_MSG_ERR_FORBIDDEN_ACTION_SUMMARY','Operación no permitida');
define('LC_MSG_ERR_FORBIDDEN_ACTION_MESSAGE',"No se le permite hacer la operación solicitada.");

/* WARNING messages */
define('LC_MSG_WARN_NO_AUTH',"No esta conectado. Por favor, identifíquese.");
define('LC_MSG_WARN_SESS_TIMOUT',"Su sesión ha caducado. Por favor, iniciar sesión nuevamente.");
define('LC_MSG_WARN_HELP_NOTFOUND',"No hay archivo de ayuda para la página actual.");
define('LC_MSG_WARN_ROW_NOTSELECTED',"Por favor seleccione primero una línea!");
define('LC_MSG_WARN_PROFILE_ROWS_EXIST',"<p><strong><span style='color:red;'>Atención</span></strong>: "
        . "<span style='font-style:italic;'>este perfil está asociado con registros de la aplicación que también se eliminarán!</span></p>");
define('LC_MSG_WARN_SEARCH_NO_VALUE', "Por favor introduzca primero un criterio!");

/* INFO messages */
define('LC_MSG_INF_LOGIN',"Se ha conectado con éxito.");
define('LC_MSG_INF_PWDCHANGED',"Su contraseña ha sido cambiada correctamente.");
define('LC_MSG_INF_LOGOUT','<h3>Desconexión correcta.</h3><p><a href="">Haga clic aquí</a> para conectarse de nuevo.</p>');
define('LC_MSG_INF_CANCEL_LOGIN','<h3>Conexión cancelada.</h3><p><a href="">Haga clic aquí</a> para conectarse.</p>');
define('LC_MSG_INF_SAVE_RECORD','Registro guardado correctamente.');
define('LC_MSG_INF_REMOVE_RECORD','Registro eliminado correctamente.');
define('LC_MSG_INF_SELECT_LIST_ITEM','Para seleccionar varios registros, mantenga la tecla <Control> presionada mientras hace clic en un elemento de la lista.');
define('LC_MSG_INF_SELECT_TREE_NODE','Para seleccionar varios registros, mantenga la tecla <Control> presionada mientras hace clic en un nodo del árbol.');

/* QUESTION messages */
define('LC_MSG_ASK_REMOVE','¿Realmente quiere eliminar el registro seleccionado?');
define('LC_MSG_ASK_CANCEL_CHANGES','Los datos del formulario han sido cambiados'
        . '<br><br>¿Realmente quiere quitar sin guardar los cambios?');