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
 * Application Wizard french translations
 *
 * File version: 1.1
 * Last update: 10/25/2015
 * 
 */

/* General labels */
define('LC_PAGE_TITLE','ZnetDK AppWizard');

/* Heading labels */
define('LC_HEAD_TITLE',"Assistant d'@pplication ZnetDK");
define('LC_HEAD_SUBTITLE',"Configuration et personnalisation de l'application de démarrage...");

/* Footer labels */
define('LC_FOOTER_CENTER','<a href="http://www.pm-consultant.fr/" target="_blank">PM Consultant</a>, Copyright © 2015');
define('LC_FOOTER_RIGHT','Assistant développé avec <a href="http://www.znetdk.fr" target="_blank">ZnetDK</a>');

/* Heading images */
define('LC_HEAD_IMG_LOGO',ZNETDK_APP_URI.'images/logoappwiz.png');

/* Menu item labels */
define('LA_MENU_WELCOME','Bienvenue');
define('LA_MENU_STEP1','Etape 1 - Libellés');
define('LA_MENU_STEP2','Etape 2 - Graphisme');
define('LA_MENU_STEP3','Etape 3 - Base de données');
define('LA_MENU_STEP4','Etape 4 - Générer...');

/* General error labels */
define('LA_ERR_APP_EXISTS_SUMMARY',"L'application existe déjà !");
define('LA_ERR_APP_EXISTS_MSG',"L'application par défaut existe déjà et ne peut pas être générée à nouveau.<br>"
        . "Supprimez le répertoire '<strong>%1</strong>' si vous souhaitez générer à nouveau l'application de démarrage.<br>"
        . "Pour désactiver l'<strong>". LC_HEAD_TITLE . "</strong> (<em>conseillé en production</em>), initialisez la constante PHP "
        . "'<strong>ZDK_TOOLS_DISABLED</strong>' à <strong>TRUE</strong> dans le fichier '<strong>%2</strong>'.");

/* Button labels */
define('LA_BUTTON_NEXT','Etape suivante...');
define('LA_BUTTON_START','Démarrer...');
define('LA_BUTTON_PREVIEW','Aperçu...');
define('LA_BUTTON_GENERATE',"Générer l'application");
define('LA_BUTTON_SHOW_APP',"Afficher l'application");

/* Step's description */
define('LA_MENU_STEP1_DESC',"Sélectionnez la <strong>langue</strong> d'affichage de votre application, renseignez son <strong>nom</Strong> et indiquez le texte à afficher en <strong>en-tête</strong> et dans le <strong>pied de page</strong>");
define('LA_MENU_STEP2_DESC','Choisissez le <strong>logo</strong> à afficher en en-tête de page, sélectionnez un <strong>thème</strong> pré-défini et le <strong>modèle de page</strong> souhaité');
define('LA_MENU_STEP3_DESC','Configurez et testez la connexion à la <strong>base de données MySQL</strong> de votre application');
define('LA_MENU_STEP4_DESC','Générez le <strong>code source</strong> et la <strong>base de données</strong> de votre application de démarrage');

/* Welcome labels */
define('LA_LBL_WELCOME_INTRO',"L'<strong>Assistant d'@pplication ZnetDK</strong></q> va vous faciliter la configuration et personnalisation de votre première application.");
define('LA_LBL_WELCOME_REQUIREMENTS','Les <strong>4 étapes</strong> suivantes sont à dérouler :');
define('LA_LBL_WELCOME_LETSGO',"Exécutez à présent la première étape de l'assistant : ");

/* Step 1 labels */
define('LA_LEGEND_STEP1_LANGUAGE',"Langue d'affichage");
define('LA_LBL_STEP1_LANGUAGE','Langue par défaut');
define('LA_INF_STEP1_LANGUAGE',"Choisissez la langue principale d'affichage de votre application");

define('LA_LEGEND_STEP1_APPNAME',"Application");
define('LA_LBL_STEP1_APPNAME',"Nom de l'application");
define('LA_INF_STEP1_APPNAME',"Ce libellé est celui affiché dans l'onglet de page du navigateur");

define('LA_LEGEND_STEP1_BANNER',"Bannière");
define('LA_LBL_STEP1_TITLE','Titre principal');
define('LA_INF_STEP1_TITLE',"Il peut éventuellement être le même que le nom de l'application");
define('LA_LBL_STEP1_SUBTITLE','Sous-titre');
define('LA_INF_STEP1_SUBTITLE',"Il n'est affiché que par le modèle de page <q>Classic</q> (voir étape 2)");

define('LA_LEGEND_STEP1_FOOTER','Pied de page');
define('LA_LBL_STEP1_FOOTER_LEFT','Gauche');
define('LA_LBL_STEP1_FOOTER_MID','Centre');
define('LA_LBL_STEP1_FOOTER_RIGHT','Droite');

/* Step 1 default values */
define('LA_VAL_STEP1_APPNAME','Ma première application');
define('LA_VAL_STEP1_SUBTITLE',"Générée par l'assistant d'@pplication ZnetDK");
define('LA_VAL_STEP1_FOOTER_LEFT','Version 1.0');
define('LA_VAL_STEP1_FOOTER_MID','Développée avec ZnetDK');
define('LA_VAL_STEP1_FOOTER_RIGHT','Copyright ' . date('Y'));

/* Step 2 labels */
define('LA_LEGEND_STEP2_LOGO',"Logo d'entête");
define('LA_LBL_STEP2_LOGO','Logo');
define('LA_INF_STEP2_LOGO',"Pour un affichage optimal, choisissez une image d'une hauteur de 90 pixels.<br>"
        . "La taille de l'image est limitée à 100 kilo-octets.");

define('LA_LEGEND_STEP2_THEME','Thème des widgets');
define('LA_LBL_STEP2_THEME','Thème');
define('LA_INF_STEP2_THEME','Cliquez sur le bouton <q><strong>'.LA_BUTTON_PREVIEW.'</strong></q> ci-dessous'
        . ' pour visualiser le thème choisi');

define('LA_LBL_STEP2_THEME_EMPTY','Choisir un thème...');
define('LA_LEGEND_STEP2_LAYOUT','Modèle de page');
define('LA_LBL_STEP2_LAYOUT','Modèle');
define('LA_INF_STEP2_LAYOUT','Cliquez sur le bouton <q><strong>'.LA_BUTTON_PREVIEW.'</strong></q> ci-dessous'
        . ' pour tester le modèle de page choisi');
define('LA_INF_STEP2_LAYOUT_CLASSIC','Le modèle <q><strong>Classic</strong></q> propose une navigation par menu à onglets.');
define('LA_INF_STEP2_LAYOUT_OFFICE','Le modèle <q><strong>Office</strong></q> offre un affichage en multi-fenêtres des vues.');

/* Step 3 labels */
define('LA_LEGEND_STEP3_CREATEDB','Création de la base de données et des tables de sécurité');
define('LA_LBL_STEP3_HOST',"Nom d'hôte");
define('LA_INF_STEP3_HOST','Conservez <strong>127.0.0.1</strong> quand MySQL est installé en local');
define('LA_LBL_STEP3_DB','Base de données');
define('LA_INF_STEP3_DB','');
define('LA_LBL_STEP3_USER','Utilisateur');
define('LA_INF_STEP3_USER','');
define('LA_LBL_STEP3_PASSWORD','Mot de passe');
define('LA_INF_STEP3_PASSWORD','Initialisé par défaut à la valeur <q><strong>password</strong></q> (à changer !)');
define('LA_LEGEND_STEP3_DATABASE',"Base de données de l'application");
define('LA_LBL_STEP3_ACTIONS','Créer la base de données ?');
define('LA_INF_STEP3_ACTIONS','Conservez le choix <q><strong>Oui</strong></q> si vous installez ZnetDK pour la première fois');
define('LA_LBL_STEP3_CREATE_DATABASE',"Oui, créer la base de données et l'utilisateur indiqués");
define('LA_LBL_STEP3_NOCREATE_DATABASE',"Non, la base de données ci-dessus existe déjà");
define('LA_LBL_STEP3_NO_USE_DATABASE',"Non, l'accès à une base de données n'est pas requis");
define('LA_LBL_STEP3_CREATE_DBTABLES','Tables de sécurité ZnetDK');
define('LA_LBL_STEP3_CREATE_DBTABLES_CHKBOX',"Créer les tables ZnetDK requises à l'authentification");
define('LA_INF_STEP3_CREATE_DBTABLES','');
define('LA_LEGEND_STEP3_ADMIN','Super utilisateur autorisé à créer la base de données');
define('LA_LBL_STEP3_ADMIN','Super utilisateur');
define('LA_INF_STEP3_ADMIN','Le compte utilisateur <q><strong>root</strong></q> existe en standard dans MySQL');
define('LA_INF_STEP3_ADMIN_PWD','');

/* Step 4 labels (report) */
define('LA_INF_STEP4_APPNAME',"Voir <strong>LC_PAGE_TITLE</strong> dans '<strong>locale.php</strong>'");
define('LA_INF_STEP4_TITLE',"Voir <strong>LC_HEAD_TITLE</strong> dans '<strong>locale.php</strong>'");
define('LA_INF_STEP4_SUBTITLE',"Voir <strong>LC_HEAD_SUBTITLE</strong> dans '<strong>locale.php</strong>'");
define('LA_INF_STEP4_LOGO',"Voir <strong>LC_HEAD_IMG_LOGO</strong> dans '<strong>locale.php</strong>'");
define('LA_INF_STEP4_FOOTER_LEFT',"Voir <strong>LC_FOOTER_LEFT</strong> dans '<strong>locale.php</strong>'");
define('LA_INF_STEP4_FOOTER_MID',"Voir <strong>LC_FOOTER_CENTER</strong> dans '<strong>locale.php</strong>'");
define('LA_INF_STEP4_FOOTER_RIGHT',"Voir <strong>LC_FOOTER_RIGHT</strong> dans '<strong>locale.php</strong>'");

define('LA_INF_STEP4_LANGUAGE',"Voir <strong>CFG_DEFAULT_LANGUAGE</strong> dans '<strong>config.php</strong>'");
define('LA_INF_STEP4_THEME',"Voir <strong>CFG_THEME</strong> dans '<strong>config.php</strong>");
define('LA_INF_STEP4_LAYOUT',"Voir <strong>CFG_PAGE_LAYOUT</strong> dans '<strong>config.php</strong>'");
define('LA_INF_STEP4_HOST',"Voir <strong>CFG_SQL_HOST</strong> dans '<strong>config.php</strong>'");
define('LA_INF_STEP4_DB',"Voir <strong>CFG_SQL_APPL_DB</strong> dans '<strong>config.php</strong>'");
define('LA_INF_STEP4_USR',"Voir <strong>CFG_SQL_APPL_USR</strong> dans '<strong>config.php</strong>'");
define('LA_INF_STEP4_PWD',"Voir <strong>CFG_SQL_APPL_PWD</strong> dans '<strong>config.php</strong>'");

define('LA_LBL_STEP4_GEN_CONFIRM',"<strong>Confirmez-vous la génération de l'application ?</strong>");
define('LA_LBL_STEP4_GEN_CONFIRM_EXT','<p><span style="color:red;font-weight:bold">Attention</span><span style="font-style:italic;">,'
        . " les valeurs obligatoires affichées ci-dessous non pas été saisies et seront en conséquence initialisées à partir de valeurs par défaut :</span></p>");
define('LA_LBL_STEP4_REPORT_DLG','Rapport de la génération');
define('LA_LBL_STEP4_REPORT_INTRO',"L'application a été générée dans le répertoire suivant :<br><strong>"
        . ZNETDK_ROOT . 'applications' . DIRECTORY_SEPARATOR . 'default</strong>'
        . '<br><br>Le rapport détaillé vous est présenté ci-dessous.');
define('LA_COL_STEP4_REPORT_STEP','#');
define('LA_COL_STEP4_REPORT_ACTION','Action');
define('LA_COL_STEP4_REPORT_RESULT','Résultat');

/* Mandatory field message */
define('LA_MSG_STEP1_LANG_MANDATORY',"La langue d'affichage de l'application est obligatoire !");
define('LA_MSG_STEP1_APPL_MANDATORY',"Le nom de l'application est obligatoire !");
define('LA_MSG_STEP1_TITL_MANDATORY','Le titre principal affiché sur la bannière du site est obligatoire !');
define('LA_MSG_STEP2_LOGO_MANDATORY',"La sélection d'une image pour affichage en en-tête de votre application, est obligatoire !");
define('LA_MSG_STEP2_THEM_MANDATORY',"Le choix d'un thème pour les widgets est obligatoire !");
define('LA_MSG_STEP3_HOST_MANDATORY',"Le nom d'hôte de la machine sur laquelle MySQL est installé, est obligatoire !"
        . '<br>Pour une installation locale de MySQL, la valeur attendue est <q><strong>127.0.0.1</strong></q>');
define('LA_MSG_STEP3_USER_MANDATORY','Un nom de compte utilisateur doit être obligatoirement renseigné !'
        . '<br>Ce compte sera utilisé par ZnetDK pour accéder à la base de données de votre application.');
define('LA_MSG_STEP3_PWD_MANDATORY',"Un mot de passe doit être obligatoirement renseigné !");
define('LA_MSG_STEP3_DB_MANDATORY','Un nom de base de données doit être nécessairement saisi !'
        . '<br>Cette base de données est utilisée pour stocker les données de votre application.');
define('LA_MSG_STEP3_ADMI_MANDATORY',"La saisie d'un compte 'Super utilisateur' est obligatoire pour créer la base de données !"
        . '<br>Vous pouvez par exemple renseigner le compte MySQL <q><strong>root</strong></q> pour créer la base de données.');

/* Controller messages */
define('LA_MSG_UPLOAD_SUMMARY','Chargement du logo');
define('LA_MSG_UPLOAD_NO_IMAGE',"Le fichier sélectionné '<strong>%1</strong>' n'est pas une image !<br>"
        . "Veuillez sélectionner un autre fichier de type image.");
define('LA_MSG_UPLOAD_TOO_LARGE_IMAGE',"Le fichier sélectionné '<strong>%1</strong>' "
        . "dépasse la taille maximale autorisée de '<strong>%2</strong>' octets !<br>"
        . "Veuillez sélectionner un autre fichier de type image.");
define('LA_MSG_UPLOAD_ERROR',"Le chargement du fichier <strong>%1</strong>' a échoué !<br>"
        . "L'erreur suivante est survenue : %2");
define('LA_MSG_UPLOAD_NO_FILE',"Aucun logo n'a été sélectionné pour l'application");
define('LA_MSG_STEP3_CONNECT_SUMMARY','Test de connexion');
define('LA_MSG_STEP3_CONNECT_ERROR',"La connexion à MySQL a échoué avec l'erreur suivante :<br>");
define('LA_MSG_STEP3_CREATE_TABLES_SUMMARY',"Créer les tables ZnetDK");
define('LA_MSG_STEP3_EXISTING_TABLE',"La table ZnetDK <strong>'%1'</strong> existe déjà !<br>"
        . "Vous devriez décocher l'option '<strong>" . LA_LBL_STEP3_CREATE_DBTABLES
        . "</strong>' si les tables de sécurité ZnetDK sont déjà installées.");
define('LA_MSG_STEP3_MISSING_TABLE',"La table ZnetDK '%1' n'existe pas !<br>"
        . "Vous devriez cocher l'option '<strong>" . LA_LBL_STEP3_CREATE_DBTABLES
        . "</strong>' si les tables de sécurité ZnetDK n'ont pas été installées.");
define('LA_MSG_STEP3_EXISTING_DB',"La base de données renseignée <strong>'%1'</strong>"
        . " existe déjà !<br>Vous devriez répondre <strong>'Non'</strong> à la question '<strong>"
        . LA_LBL_STEP3_ACTIONS . "'</strong> ou indiquer un autre nom de base de données.");
define('LA_MSG_STEP3_USER_EXISTS',"L'utilisateur '<strong>%1</strong>' existe déjà !<br>"
        . "Vous devriez répondre <strong>'Non'</strong> à la question '<strong>"
        . LA_LBL_STEP3_ACTIONS . "'</strong> ou saisir un autre nom d'utilisateur.");
define('LA_MSG_STEP3_USER_BAD_PASSWORD',"Le mot de passe saisi pour l'utilisateur '<strong>%1</strong>' "
        . "est invalide ou le compte de l'utilisateur n'existe pas !<br>Veuillez saisir à nouveau le mot de passe ou vérifier "
        . "si cet utilisateur existe réellement.");
define('LA_MSG_STEP3_ADMIN_NO_PRIVS',"Le super utilisateur '<strong>%1</strong>' ne dispose pas des privilèges "
        . "suffisants !<br>Vous devriez répondre <strong>'Non'</strong>  à la question "
        . "'<strong>" . LA_LBL_STEP3_ACTIONS . "'</strong> ou saisir un autre compte utilisateur "
        . "tel que '<strong>root</strong>' disponible en standard dans MySQL.");
define('LA_MSG_STEP3_USER_NO_PRIVS',"Le super utilisateur '<strong>%1</strong>' ne dispose pas des privilèges "
        . "suffisants !<br>Vous devriez décocher l'option '<strong>" . LA_LBL_STEP3_CREATE_DBTABLES
        . "</strong>' ou saisir un autre compte utilisateur "
        . "tel que '<strong>root</strong>' disponible en standard dans MySQL.");
define('LA_MSG_STEP3_ADMIN_EQUAL_USER',"Le super utilisateur '<strong>%1</strong>' ne peut pas être "
        . "le même compte utilisateur que celui qui doit être créé !<br>"
        . "Vous devriez saisir un autre compte de super utilisateur ou un autre compte utilisateur"
        . " à créer pour le stockage des données de l'application.");

/* Report labels */
define('LA_RPT_STATUS_OK','OK');
define('LA_RPT_STATUS_FAILED','échec!');
define('LA_RPT_STATUS_NB_ERRORS','Erreurs trouvées : ');
define('LA_RPT_STATUS_ERROR_MSG','Erreur : %1');
define('LA_RPT_ACTION_SUMMARY','Résumé');
define('LA_RPT_RESULT_SUMMARY_OK',"La génération de l'application s'est terminée avec succès");
define('LA_RPT_RESULT_SUMMARY_ERRORS',"La génération de l'application s'est terminée avec %1 erreurs !");

define('LA_RPT_ACTION_UPDATE_PARAM','Configuration');
define('LA_RPT_RESULT_UPDATE_PARAM',"Valeur '%1' affectée au paramètre '%2': %3");
define('LA_RPT_ACTION_CREATE_DB','Créer la base de données');
define('LA_RPT_RESULT_CREATE_DB',"Création de la base de données '%1' : %2");
define('LA_RPT_RESULT_CREATE_DB_ERROR',"La base de données n'a pas été créée ! Par conséquent, l'utilisateur '%1' ne peut également pas être créé.");
define('LA_RPT_ACTION_CREATE_USR','Créer le compte utilisateur');
define('LA_RPT_RESULT_CREATE_USR',"Création de l'utilisateur '%1' : %2");
define('LA_RPT_ACTION_CREATE_TABLE','Créer les tables ZnetDK');
define('LA_RPT_RESULT_CREATE_TABLE',"Création de la table '%1' : %2");
define('LA_RPT_RESULT_ALTER_TABLE',"Ajout de contraintes à la table '%1' : %2");
define('LA_RPT_ACTION_CONNECT_SQL','Se connecter à MySQL');
define('LA_RPT_RESULT_CONNECT_SQL',"Connexion en tant que '%1' : %2");
define('LA_RPT_RESULT_CONNECT_SQL_ERROR',"Pas de connexion à MySQL!");
define('LA_RPT_ACTION_CREATE_DIR',"Générer les répertoires");
define('LA_RPT_RESULT_CREATE_DIR',"Création du répertoire '%1': %2");
define('LA_RPT_ACTION_COPY_FILE','Copier les fichiers');
define('LA_RPT_RESULT_COPY_FILE',"Copie du fichier '%1' en '%2': %3");
define('LA_RPT_RESULT_COPY_LOGO_ERROR',"Erreur : '%1'. Le logo '%2' sera copié à la place.");

/* Other labels */
define('LA_LBL_NOVALUE','&lt; Aucune valeur ! &gt;');