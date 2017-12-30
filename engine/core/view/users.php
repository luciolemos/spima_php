<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
ZnetDK Core users view
        
File version: 1.0
Last update: 09/18/2015
-->
<div id="znetdk_user_actions" class="zdk-action-bar"
        data-zdk-dialog="znetdk_user_dialog"
        data-zdk-datatable="znetdk_users_datatable">
    <button class="zdk-bt-add" title="<?php echo LC_FORM_TITLE_USER_NEW; ?>"><?php echo LC_BTN_NEW; ?></button>
    <button class="zdk-bt-edit" title="<?php echo LC_FORM_TITLE_USER_MODIFY; ?>"
            data-zdk-noselection="<?php echo LC_MSG_WARN_ROW_NOTSELECTED; ?>"
            ><?php echo LC_BTN_MODIFY; ?>
    </button>
    <button class="zdk-bt-remove" title="<?php echo LC_FORM_TITLE_USER_REMOVE; ?>"
            data-zdk-noselection="<?php echo LC_MSG_WARN_ROW_NOTSELECTED; ?>"
            data-zdk-action="users:remove"
            data-zdk-confirm="<?php echo LC_MSG_ASK_REMOVE.':'.LC_BTN_YES.':'.LC_BTN_NO; ?>"
            ><?php echo LC_BTN_REMOVE; ?>
    </button>
</div>
<div id="znetdk_users_datatable" class="zdk-datatable zdk-synchronize" title='<?php echo LC_TABLE_AUTHORIZ_USERS_CAPTION;?>'
            data-zdk-action="users:all"
            data-zdk-columns='[
                {"field":"login_name", "headerText": "<?php echo LC_TABLE_COL_LOGIN_ID;?>", "sortable":true},
                {"field":"user_name", "headerText": "<?php echo LC_TABLE_COL_USER_NAME;?>", "sortable":true, "tooltip":true},
                {"field":"user_email", "headerText": "<?php echo LC_TABLE_COL_USER_EMAIL;?>", "sortable":true,"tooltip":true},
                {"field":"status", "headerText": "<?php echo LC_TABLE_COL_USER_STATUS;?>", "sortable":true},
                {"field":"menu_access", "headerText": "<?php echo LC_TABLE_COL_MENU_ACCESS;?>", "sortable":true},
                {"field":"user_profiles", "headerText": "<?php echo LC_TABLE_COL_USER_PROFILES;?>", "sortable":false,"tooltip":true}
            ]'>
</div>
<div id="znetdk_user_dialog" class="zdk-modal" title="<?php echo LC_FORM_TITLE_USER_NEW; ?>" data-zdk-width="694px">
    <form class="zdk-form" autocomplete="off"
          data-zdkerrmsg-required="<?php echo LC_MSG_ERR_MISSING_VALUE; ?>"
          data-zdk-action="users:save" data-zdk-datatable="znetdk_users_datatable">
        <!-- User ID -->
        <input class="zdk-row-id" type="hidden" name="user_id">
         <!-- Identity -->
        <fieldset>
            <legend><?php echo LC_FORM_FLD_USER_IDENTITY; ?></legend>
            <!-- User name -->
            <label><?php echo LC_FORM_LBL_USER_NAME; ?></label>
            <input type="text" name="user_name" maxlength="100" required >
            <!-- Email -->
            <label><?php echo LC_FORM_LBL_USER_EMAIL; ?></label>
            <input type="email" name="user_email" required 
                   data-zdkerrmsg-type="<?php echo LC_MSG_ERR_EMAIL_INVALID; ?>">
        </fieldset>
        <fieldset> <!-- Connection -->
            <legend><?php echo LC_FORM_FLD_USER_CONNECTION; ?></legend>
            <!-- Login ID -->
            <label><?php echo LC_FORM_LBL_LOGIN_ID; ?></label>
            <input type="text" name="login_name" autocomplete="off" value="" maxlength="20" required >
            <!-- Password -->
            <label><?php echo LC_FORM_LBL_PASSWORD; ?></label>
            <input type="password" name="login_password" autocomplete="off" value="" maxlength="20" required >
            <!-- Password confirmation -->
            <label><?php echo LC_FORM_LBL_PASSWORD_CONFIRM; ?></label>
            <input type="password" name="login_password2" autocomplete="off" value="" maxlength="20" required >
            <!-- Expiration date -->
            <label><?php echo LC_FORM_LBL_USER_EXPIRATION_DATE; ?></label>
            <input type="date" name="expiration_date" required
                data-zdkerrmsg-date="<?php echo LC_MSG_ERR_DATE_INVALID; ?>">
        </fieldset>
        <fieldset> <!-- User rights -->
            <legend><?php echo LC_FORM_FLD_USER_RIGHTS; ?></legend>
            <!-- Status -->
            <label><?php echo LC_FORM_LBL_USER_STATUS; ?></label>
            <div class="zdk-radiobuttongroup" data-name="user_enabled">
                <input type="radio" value="1"/>
                <label><?php echo LC_FORM_LBL_USER_STATUS_ENABLED; ?></label>
                <input type="radio" value="0"/>
                <label><?php echo LC_FORM_LBL_USER_STATUS_DISABLED; ?></label>
            </div>
            <!-- Menu access -->
            <label><?php echo LC_FORM_LBL_USER_MENU_ACCESS; ?></label>
            <input type="checkbox" name="full_menu_access" value="1"/>
            <span><?php echo LC_FORM_LBL_USER_MENU_ACCESS_FULL; ?></span>
            <!-- Profiles -->
            <label title="<?php echo LC_MSG_INF_SELECT_LIST_ITEM; ?>"><?php echo LC_FORM_LBL_USER_PROFILES; ?></label>
            <select class="zdk-listbox" name="profiles[]" multiple="multiple"
                    data-zdk-action="users:profiles"></select>
        </fieldset>
        <!-- Form buttons -->
        <button class="zdk-bt-save zdk-close-dialog" type="submit"><?php echo LC_BTN_SAVE; ?></button>
        <button class="zdk-bt-cancel zdk-close-dialog" type="button"><?php echo LC_BTN_CANCEL; ?></button>
    </form>
</div>