<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
ZnetDK Core Profiles view 
        
File version: 1.0
Last update: 09/18/2015
-->
<div id="znetdk_profile_actions" class="zdk-action-bar"
        data-zdk-dialog="znetdk_profile_dialog"
        data-zdk-datatable="znetdk_profiles_datatable">
    <button class="zdk-bt-add" title="<?php echo LC_FORM_TITLE_PROFILE_NEW; ?>"><?php echo LC_BTN_NEW; ?></button>
    <button class="zdk-bt-edit" title="<?php echo LC_FORM_TITLE_PROFILE_MODIFY; ?>"
            data-zdk-noselection="<?php echo LC_MSG_WARN_ROW_NOTSELECTED; ?>"
            ><?php echo LC_BTN_MODIFY; ?>
    </button>
    <button class="zdk-bt-remove" title="<?php echo LC_FORM_TITLE_PROFILE_REMOVE; ?>"
        data-zdk-noselection="<?php echo LC_MSG_WARN_ROW_NOTSELECTED; ?>"
        data-zdk-action="profiles:remove"><?php echo LC_BTN_REMOVE; ?>
    </button>
</div>
<div id="znetdk_profiles_datatable" class="zdk-datatable zdk-synchronize" title="<?php echo LC_TABLE_AUTHORIZ_PROFILES_CAPTION;?>"
    data-zdk-action="profiles:all"
    data-zdk-columns='[
        {"field":"profile_name", "headerText": "<?php echo LC_TABLE_COL_PROFILE_NAME;?>", "sortable":true},
	{"field":"profile_description", "headerText": "<?php echo LC_TABLE_COL_PROFILE_DESC;?>", "sortable":true},
	{"field":"menu_items", "headerText": "<?php echo LC_TABLE_COL_MENU_ITEMS;?>", "sortable":false}
    ]'>
</div>
<div id="znetdk_profile_dialog" class="zdk-modal" title="<?php echo LC_FORM_TITLE_PROFILE_NEW; ?>" data-zdk-width="398px">
    <form class="zdk-form"
        data-zdkerrmsg-required="<?php echo LC_MSG_ERR_MISSING_VALUE; ?>"
        data-zdk-action="profiles:save" data-zdk-datatable="znetdk_profiles_datatable">
        <!-- Profile name -->
        <label><?php echo LC_TABLE_COL_PROFILE_NAME; ?></label>
        <input type="text" name="profile_name" maxlength="100" required >
        <!-- Profile description -->
        <label><?php echo LC_TABLE_COL_PROFILE_DESC; ?></label>
        <textarea name="profile_description" rows="3" maxlength="100" required ></textarea>
        <!-- Menu items (tree widget instanciated in the 'users-ui-init' view) -->
        <div class="zdk-form-entry">
            <label class="zdk-tree-label"><?php echo LC_TABLE_COL_MENU_ITEMS; ?></label>
            <div id="znetdk_profile_tree" data-name="menu_ids[]" title="<?php echo LC_MSG_INF_SELECT_TREE_NODE; ?>"></div>
        </div>
        <!-- Profile ID -->
        <input class="zdk-row-id" type="hidden" name="profile_id">
        <!-- Form buttons -->
        <button class="zdk-bt-save zdk-close-dialog" type="submit"><?php echo LC_BTN_SAVE; ?></button>
        <button class="zdk-bt-cancel zdk-close-dialog" type="button"><?php echo LC_BTN_CANCEL; ?></button>
    </form>
</div>