<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
Application Wizard step 3 view.
File version: 1.1
Last update: 10/25/2015
-->
<p class="teaser"><?php echo LA_MENU_STEP3_DESC;?>.</p>
<form id="zdkappwiz-form-step3" class="zdk-form" data-zdk-action="wizard:connect">
    <fieldset id="zdkappwiz-fieldset-database">
        <legend><?php echo LA_LEGEND_STEP3_DATABASE;?></legend>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP3_HOST;?></label>
            <input type="text" name="host" value="127.0.0.1"
                data-zdkerrmsg-required="<?php echo LA_MSG_STEP3_HOST_MANDATORY?>"
                required>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP3_HOST;?></span>
        </div>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP3_DB;?></label>
            <input type="text" name="database" value="znetdk-db"
                data-zdkerrmsg-required="<?php echo LA_MSG_STEP3_DB_MANDATORY?>"
                required>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP3_DB;?></span>
            </div>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP3_USER;?></label>
            <input type="text" name="user" value="znetdk"
                data-zdkerrmsg-required="<?php echo LA_MSG_STEP3_USER_MANDATORY?>"
                required>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP3_USER;?></span>
            </div>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP3_PASSWORD;?></label>
            <input type="password" name="user_pwd" value="password"
                data-zdkerrmsg-required="<?php echo LA_MSG_STEP3_PWD_MANDATORY?>"
                required>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP3_PASSWORD;?></span>
        </div>
    </fieldset>
    <fieldset id="zdkappwiz-fieldset-creating">
        <legend><?php echo LA_LEGEND_STEP3_CREATEDB;?></legend>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP3_ACTIONS;?></label>
            <div class="zdk-radiobuttongroup" data-name="create_db">
                <input type="radio" value="yes" checked>
                <label><?php echo LA_LBL_STEP3_CREATE_DATABASE;?></label>
                <br>
                <input type="radio" value="no">
                <label><?php echo LA_LBL_STEP3_NOCREATE_DATABASE;?></label>
                <br>
                <input type="radio" value="no_database">
                <label><?php echo LA_LBL_STEP3_NO_USE_DATABASE;?></label>

            </div>
            <span class="zdkappwiz-field-desc-s31"><?php echo LA_INF_STEP3_ACTIONS;?></span>
        </div>
        <div id="zdkappwiz-form-entry-create-tables" class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP3_CREATE_DBTABLES;?></label>
            <input type="checkbox" name="create_tables" value="yes" checked disabled>
            <span><?php echo LA_LBL_STEP3_CREATE_DBTABLES_CHKBOX;?></span>
            <span class="zdkappwiz-field-desc-s32"><?php echo LA_INF_STEP3_CREATE_DBTABLES;?></span>
        </div>
    </fieldset>
    <fieldset id="zdkappwiz-fieldset-admin">
        <legend><?php echo LA_LEGEND_STEP3_ADMIN;?></legend>
        <div class="zdk-form-entry">
            <label class="zdk-required"><?php echo LA_LBL_STEP3_ADMIN;?></label>
            <input type="text" name="admin" value="root"
                data-zdkerrmsg-required="<?php echo LA_MSG_STEP3_ADMI_MANDATORY?>"
                required>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP3_ADMIN;?></span>
        </div>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP3_PASSWORD;?></label>
            <input type="password" name="admin_pwd">
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP3_ADMIN_PWD;?></span>
        </div>
    </fieldset>
    <button class="zdk-bt-custom next" data-zdk-icon="ui-icon-circle-arrow-e:right" type="submit"><?php echo LA_BUTTON_NEXT;?></button>
</form>
<img id="zdkappwiz-img-step3" src="<?php echo ZNETDK_APP_URI; ?>images/step3.png" alt="step3">
<input id="zdkappwiz-create-tables-prev" type="text" hidden>