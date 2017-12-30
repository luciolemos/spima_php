<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
Application Wizard step 4 view.
File version: 1.1
Last update: 10/25/2015
-->
<p class="left teaser"><?php echo LA_MENU_STEP4_DESC;?>.</p>
<button id="zdkappwiz-bt-generate"><?php echo LA_BUTTON_GENERATE;?></button>
<div class="ui-helper-clearfix"></div>
<form id="zdkappwiz-form-step4" class="zdk-form" data-zdk-action="wizard:generate">
    <fieldset>
        <legend><?php echo LA_MENU_STEP1;?></legend>
        <div class="zdk-form-entry">
            <label class="zdk-required"><?php echo LA_LBL_STEP1_LANGUAGE;?></label>
            <input type="text" name="def_lang" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP4_LANGUAGE;?></span>
        </div>
        <div class="zdk-form-entry">
            <label class="zdk-required"><?php echo LA_LBL_STEP1_APPNAME;?></label>
            <input type="text" name="appl_name" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP4_APPNAME;?></span>
        </div>
        <div class="zdk-form-entry">
            <label class="zdk-required"><?php echo LA_LBL_STEP1_TITLE;?></label>
            <input type="text" name="banner_title" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP4_TITLE;?></span>
        </div>
        <div class="zdk-form-entry">    
            <label><?php echo LA_LBL_STEP1_SUBTITLE;?></label>
            <input type="text" name="banner_subtitle" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP4_SUBTITLE;?></span>
        </div>
        <div class="zdk-form-entry">    
            <label><?php echo LA_LBL_STEP1_FOOTER_LEFT;?></label>
            <input type="text" name="footer_left" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP4_FOOTER_LEFT;?></span>
        </div>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP1_FOOTER_MID;?></label>
            <input type="text" name="footer_mid" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP4_FOOTER_MID;?></span>
        </div>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP1_FOOTER_RIGHT;?></label>
            <input type="text" name="footer_right" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP4_FOOTER_RIGHT;?></span>
        </div>
    </fieldset>
    <fieldset>
        <legend><?php echo LA_MENU_STEP2;?></legend>
        <div class="zdk-form-entry">
            <label class="zdk-required"><?php echo LA_LBL_STEP2_LOGO;?></label>
            <input type="text" name="logo" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP4_LOGO;?></span>
        </div>
        <div class="zdk-form-entry">
            <label class="zdk-required"><?php echo LA_LBL_STEP2_THEME;?></label>
            <input type="text" name="theme" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP4_THEME;?></span>
        </div>
        <div class="zdk-form-entry">
            <label class="zdk-required"><?php echo LA_LBL_STEP2_LAYOUT;?></label>
            <input type="text" name="layout" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP4_LAYOUT;?></span>
        </div>
    </fieldset>
    <fieldset id="zdkappwiz-fieldset-database-step4">
        <legend><?php echo LA_MENU_STEP3;?></legend>
        <div class="zdk-form-entry">
            <label class="zdk-required"><?php echo LA_LBL_STEP3_HOST;?></label>
            <input type="text" name="host" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP4_HOST;?></span>
        </div>
        <div class="zdk-form-entry">
            <label class="zdk-required"><?php echo LA_LBL_STEP3_DB;?></label>
            <input type="text" name="database" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP4_DB;?></span>
        </div>
        <div class="zdk-form-entry">
            <label class="zdk-required"><?php echo LA_LBL_STEP3_USER;?></label>
            <input type="text" name="user" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP4_USR;?></span>
        </div>
        <div class="zdk-form-entry">
            <label class="zdk-required"><?php echo LA_LBL_STEP3_PASSWORD;?></label>
            <input type="password" name="user_pwd" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP4_PWD;?></span>
        </div>            
        <div class="zdk-form-entry zdk-always-visible">
            <label><?php echo LA_LBL_STEP3_ACTIONS;?></label>
            <input type="text" name="create_db_text" placeholder="<?php echo LA_LBL_NOVALUE;?>" disabled>
            <span class="zdkappwiz-field-desc"></span>
        </div>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP3_CREATE_DBTABLES;?></label>
            <input type="checkbox" name="create_tables" value="yes" disabled>
            <span class="ui-state-disabled"><?php echo LA_LBL_STEP3_CREATE_DBTABLES_CHKBOX;?></span>
        </div>
        <input type="text" name="admin" hidden>
        <input type="password" name="admin_pwd" hidden>
    </fieldset>
    <input type="text" name="create_db" hidden>
</form>
<img id="zdkappwiz-img-step4" src="<?php echo ZNETDK_APP_URI; ?>images/step4.png" alt="step4">
<div id="zdkappwiz-report-dlg" class="zdk-modal" title="<?php echo LA_LBL_STEP4_REPORT_DLG;?>"
        data-zdk-width="640px">
    <img id="zdkappwiz-img-report" src="<?php echo ZNETDK_APP_URI; ?>images/report.png" alt="report">
    <p class="teaser"><?php echo LA_LBL_STEP4_REPORT_INTRO;?></p>
    <div id="zdkappwiz-report-table"
         data-zdk-columns='[
            {"field":"step", "headerText": "<?php echo LA_COL_STEP4_REPORT_STEP;?>"},
            {"field":"action", "headerText": "<?php echo LA_COL_STEP4_REPORT_ACTION;?>"},
            {"field":"result", "headerText": "<?php echo LA_COL_STEP4_REPORT_RESULT;?>"}]'>
    </div>
    <form class="zdk-form">
        <button id="zdkappwiz-bt-showapp" class="zdk-bt-custom"
                data-zdk-icon="ui-icon-play"><?php echo LA_BUTTON_SHOW_APP;?></button>
    </form>
</div>