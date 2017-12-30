<?php


/* General PHP localization settings (used by the PHP 'setlocale' function) */
define ('LC_LOCALE_ALL', serialize(array('pt_BR','pt'))); 


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
define('LC_LANG_ISO_CODE','pt');

/* General labels */
define('LC_PAGE_TITLE', 'SPIMA');

/* Menu item labels */
define('LA_MENU_HOME','Home');
define('LA_MENU_THEMES','Temas');
define('LA_MENU_WIDGETS','Componentes');


/* Heading labels */
define('LC_HEAD_TITLE', 'Fiscalização Administrativa/16º BIMtz');
define('LC_HEAD_SUBTITLE', 'Sessão de Patrimônio Imobiliário e Meio Ambiente - SPIMA');
define('LC_HEAD_LNK_LOGOUT','Sair');
define('LC_HEAD_LNK_HELP','Ajuda');

/* Heading images */
define('LC_HEAD_IMG_LOGO', ZNETDK_APP_URI.'images/logo.png');

/* Footer labels */
define('LC_FOOTER_LEFT','Versão '.SPIMA_VERSION);
define('LC_FOOTER_CENTER', 'Copyright 2018');
define('LC_FOOTER_RIGHT', '2º Ten QAO Lúcio Flávio Lemos - Desenvolvedor');


/* Home page labels */
define('LC_HOME_WELCOME','Bem vindo ao nosso Sistema de Gestão.');
define('LC_HOME_LEGEND_DBSTATUS','Estado da base de dados da aplicação');
define('LC_HOME_TXT_DB_SETTINGS1','Configuracão');
define('LC_HOME_TXT_DB_SETTINGS2','Usuário: <strong>'.CFG_SQL_APPL_USR.'@'.CFG_SQL_HOST
        .'</strong>; base de dados utilizada: <strong>'. CFG_SQL_APPL_DB .'</strong>');
define('LC_HOME_TXT_DB_CONNECT1','Conexão com a base de dados');
define('LC_HOME_TXT_DB_CONNECT2_OK','<span class="success">Teste de conexão com a base de dados realizada com sucesso.</span>');
define('LC_HOME_TXT_DB_CONNECT2_KO','<span class="failed">failed to connect</span>');
define('LC_HOME_TXT_DB_TABLES1','Tabelas de segurança');
define('LC_HOME_TXT_DB_TABLES2_OK','<span class="success">Corretamente instaladas.</span>');
define('LC_HOME_TXT_DB_TABLES2_KO','<span class="failed">instalación errónea</span>');
define('LC_HOME_DATABASE_ERROR','Erro: ');

define('LC_HOME_LEGEND_START','Desenvolva com ZnetDK');
define('LC_HOME_TXT_START_MENU1',"Definicão do menu");
define('LC_HOME_TXT_START_MENU2',"O menu da aplicação em exibição, está configurado no script de endereço <strong>"
        . ZNETDK_APP_ROOT . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "menu.php</strong>"
        . " podendo ser totalmente personalizado para exibir novas 'views' desenvolvidas.");
define('LC_HOME_TXT_START_CONCEPTS1','Tecnologias utilizadas:');
define('LC_HOME_TXT_START_CONCEPTS2','JQuery, PrimeUI, HTML5, PHP e SQL. ' 
       
        . '<a href="http://www.znetdk.fr/concepts" target="_blank">Conceitos ZnetDK</a>, '. 'um '
        . '<a href="http://www.znetdk.fr/tutoriel" target="_blank">tutorial</a> '. 'e várias '
        . '<a href="http://www.znetdk.fr/demonstration" target="_blank">demonstrações</a> voce encontrará em...' );
define('LC_HOME_TXT_START_API1','Referências da API');
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
define('LC_WINMGR_TITLE',"Comportamento das abertas");
define('LC_WINMGR_AUTOCLOSE',"Fechar uatomático");
define('LC_WINMGR_ADJUST_HORIZ',"Ajuste horizontal.");
define('LC_WINMGR_ADJUST_VERTI',"Ajuste vertical.");
define('LC_WINMGR_CLOSE_ALL',"Fechar todas");

/* FORM titles */
define('LC_FORM_TITLE_LOGIN','SPIMA/16ºBIMtz');
define('LC_FORM_TITLE_CHANGE_PASSWORD','Alterar senha');
define('LC_FORM_TITLE_HELP','Ayuda en línea - ');
define('LC_FORM_TITLE_USER_NEW','Novo usuário');
define('LC_FORM_TITLE_USER_MODIFY',"Editar usuário");
define('LC_FORM_TITLE_USER_REMOVE',"Excluir usuário");
define('LC_FORM_TITLE_PROFILE_NEW','Novo perfil');
define('LC_FORM_TITLE_PROFILE_MODIFY',"Editar perfil");
define('LC_FORM_TITLE_PROFILE_REMOVE',"Excluir perfil");

/* Authorizations menu label */
define('LC_MENU_AUTHORIZATION','Permissões');
define('LC_MENU_AUTHORIZ_USERS','Usuários');
define('LC_MENU_AUTHORIZ_PROFILES','Perfis');

/* Authorizations view labels */
define('LC_VIEW_AUTHORIZATION_USER','Usuário');
define('LC_VIEW_AUTHORIZATION_PROFILES','Perfis');
define('LC_VIEW_AUTHORIZATION_USERS','Usuários registrados');

/* Authorizations Datatable labels */
define('LC_TABLE_AUTHORIZ_USERS_CAPTION','usuários registrados');
define('LC_TABLE_AUTHORIZ_PROFILES_CAPTION','perfis de usuario');
define('LC_TABLE_COL_LOGIN_ID','Login de conexão');
define('LC_TABLE_COL_USER_NAME','Nome do usuário');
define('LC_TABLE_COL_USER_EMAIL','Email');
define('LC_TABLE_COL_USER_STATUS','Status');
define('LC_TABLE_COL_MENU_ACCESS','Acesso ao menu');
define('LC_TABLE_COL_USER_PROFILES','Perfis');
define('LC_TABLE_COL_PROFILE_NAME','Perfil');
define('LC_TABLE_COL_PROFILE_DESC','Descrição');
define('LC_TABLE_COL_MENU_ITEMS','Elementos de menu');

/* Login Form labels */
define('LC_FORM_LBL_LOGIN_ID','Usuário');
define('LC_FORM_LBL_PASSWORD','Senha');
define('LC_FORM_LBL_ORIG_PASSWORD','Senha atual');
define('LC_FORM_LBL_NEW_PASSWORD','Nova senha');
define('LC_FORM_LBL_PASSWORD_CONFIRM','Confirme senha');
define('LC_FORM_LBL_ACCESS','Acesso');
define('LC_FORM_LBL_PUBL_ACC','público (finaliza a sessão)');
define('LC_FORM_LBL_PRIV_ACC','privado');

/* User Form labels */
define('LC_FORM_FLD_USER_IDENTITY','Dados pessoais');
define('LC_FORM_FLD_USER_CONNECTION','Conexão');
define('LC_FORM_FLD_USER_RIGHTS','Permissões');
define('LC_FORM_LBL_USER_NAME','Nome');
define('LC_FORM_LBL_USER_EMAIL','Email');
define('LC_FORM_LBL_USER_EXPIRATION_DATE','Expira em');
define('LC_FORM_LBL_USER_STATUS','Status');
define('LC_FORM_LBL_USER_STATUS_ENABLED','Ativado');
define('LC_FORM_LBL_USER_STATUS_DISABLED','Desativado');
define('LC_FORM_LBL_USER_MENU_ACCESS','Acesso ao menu');
define('LC_FORM_LBL_USER_MENU_ACCESS_FULL','Completo');
define('LC_FORM_LBL_USER_PROFILES','Perfis');

/* Other Form labels */
define('LC_FORM_LBL_NO_FILE_SELECTED','&lt; No archivo seleccionnado! &gt;');
define('LC_ACTION_ROWS_LABEL','Líneas por página');

/* BUTTON labels */
define('LC_BTN_LOGIN','Entrar');
define('LC_BTN_CANCEL','Cancelar');
define('LC_BTN_CLOSE','Fechar');
define('LC_BTN_SAVE','Salvar');
define('LC_BTN_NEW','Novo');
define('LC_BTN_MODIFY','Editar');
define('LC_BTN_OPEN','Abrir');
define('LC_BTN_OK','Ok');
define('LC_BTN_REMOVE','Excluir');
define('LC_BTN_MANAGE','Administrar');
define('LC_BTN_YES','Sim');
define('LC_BTN_NO','Não');
define('LC_BTN_SELECTFILE','Elegir...');
define('LC_BTN_EXPORT','Exportar...');
define('LC_BTN_IMPORT','Importar...');
define('LC_BTN_ARCHIVE','Archivar...');
define('LC_ACTION_SEARCH_KEYWORD_BTN_RUN','Iniciar la búsqueda');
define('LC_ACTION_SEARCH_KEYWORD_BTN_CLEAR','Eliminar la palabra clave');

/* CRITICAL ERROR messages */
define('LC_MSG_CRI_ERR_SUMMARY','Problema técnico');
define('LC_MSG_CRI_ERR_DETAIL',"Ocorreu um problema. Entre em contato com o administrador do Sistema SPIMA para informar os detalhes do erro a seguir:<br><span class='zdk-err-detail'>\"%1\"</span>");

/* ERROR messages */
define('LC_MSG_ERR_LOGIN','Usuário ou senha incorretos !');
define('LC_MSG_ERR_DIFF_LOGIN','Tiene que utilizar el mismo login para conectarse de nuevo!');
define('LC_MSG_ERR_LOGIN_DISABLED','Sua conta de usuario foi bloqueada.<br>Pongase en contacto con su responsable de la seguridad para activar de nuevo su cuenta.');
define('LC_MSG_ERR_LOGIN_EXPIRATION','Sua senha expirou! Renove sua senha para poder ter acesso ao nosso sistema.');
define('LC_MSG_ERR_LOGIN_TOO_MUCH_ATTEMPTS','O número máximo de tentativas autorizadas foi alcançado!<br>Sua conta de usuario foi bloqueada.');
define('LC_MSG_ERR_HTTP','<h3>Error HTTP %1!</h3><p><a href="%2">Haga clic aquí</a> para volver a la página de inicio.</p>');
define('LC_MSG_ERR_SELECT_RECORD',"Ha ocurrido un error! No se pueden seleccionar los datos!");
define('LC_MSG_ERR_SAVE_RECORD',"Ha ocurrido un error! No se puede guardar el registro!");
define('LC_MSG_ERR_REMOVE_RECORD',"Ha ocurrido un error! No se puede eliminar el registro!");
define('LC_MSG_ERR_MISSING_VALUE',"Por favor, introduza um valor!");
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
define('LC_MSG_WARN_SESS_TIMOUT',"Sua sessão expirou. Por favor, inicie sua sessão novamente.");
define('LC_MSG_WARN_HELP_NOTFOUND',"No hay archivo de ayuda para la página actual.");
define('LC_MSG_WARN_ROW_NOTSELECTED',"Por favor selecione uma linha!");
define('LC_MSG_WARN_PROFILE_ROWS_EXIST',"<p><strong><span style='color:red;'>Atención</span></strong>: "
        . "<span style='font-style:italic;'>este perfil está asociado con registros de la aplicación que también se eliminarán!</span></p>");
define('LC_MSG_WARN_SEARCH_NO_VALUE', "Por favor introduza um criterio!");

/* INFO messages */
define('LC_MSG_INF_LOGIN',"Conexão realizada com sucesso.");
define('LC_MSG_INF_PWDCHANGED',"Senha alterada com sucesso.");
define('LC_MSG_INF_LOGOUT','<h3>Usuário desconectado do Sistema.</h3><p>Clique <a href="">aqui</a> para conectar-se novamente.</p>');
define('LC_MSG_INF_CANCEL_LOGIN','<h3>Sua conexão foi cancelada.</h3><p>Clique <a href="">aqui</a> para conectar-se.</p>');
define('LC_MSG_INF_SAVE_RECORD','Alterações salvas com sucesso!');
define('LC_MSG_INF_REMOVE_RECORD','Registro excluído com sucesso!');
define('LC_MSG_INF_SELECT_LIST_ITEM','Para seleccionar varios registros, mantenga la tecla <Control> presionada mientras hace clic en un elemento de la lista.');
define('LC_MSG_INF_SELECT_TREE_NODE','Para seleccionar varios registros, mantenga la tecla <Control> presionada mientras hace clic en un nodo del árbol.');

/* QUESTION messages */
define('LC_MSG_ASK_REMOVE','¿Realmente quiere eliminar el registro seleccionado?');
define('LC_MSG_ASK_CANCEL_CHANGES','Los datos del formulario han sido cambiados'
        . '<br><br>¿Realmente quiere quitar sin guardar los cambios?');