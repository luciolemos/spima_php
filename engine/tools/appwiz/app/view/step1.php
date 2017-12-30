<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
Application Wizard step 1 view.
File version: 1.0
Last update: 09/18/2015
-->
<p class="teaser"><?php echo LA_MENU_STEP1_DESC;?>.</p>
<form id="zdkappwiz-form-step1" class="zdk-form">
    <fieldset>
        <legend><?php echo LA_LEGEND_STEP1_LANGUAGE;?></legend>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP1_LANGUAGE;?></label>
            <input type="text" class="zdk-autocomplete" name="def_lang"
                data-zdk-action="wizard:languages"
                value="<?php echo \app\model\Languages::getCurrentLanguageLabel();?>"
                data-zdkerrmsg-required="<?php echo LA_MSG_STEP1_LANG_MANDATORY;?>"
                required>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP1_LANGUAGE;?></span>
        </div>
    </fieldset>
    <fieldset>
        <legend><?php echo LA_LEGEND_STEP1_APPNAME;?></legend>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP1_APPNAME;?></label>
            <input type="text" name="appl_name"
                value ="<?php echo LA_VAL_STEP1_APPNAME;?>"
                data-zdkerrmsg-required="<?php echo LA_MSG_STEP1_APPL_MANDATORY;?>"
                required>
                <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP1_APPNAME;?></span>
        </div>
    </fieldset>
    <fieldset>
        <legend><?php echo LA_LEGEND_STEP1_BANNER;?></legend>
        <div class="zdk-form-entry">
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP1_TITLE;?></label>
            <input type="text" name="banner_title"
                value ="<?php echo LA_VAL_STEP1_APPNAME;?>"
                data-zdkerrmsg-required="<?php echo LA_MSG_STEP1_TITL_MANDATORY;?>"
                required>
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP1_TITLE;?></span>
        </div>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP1_SUBTITLE;?></label>
            <input type="text" name="banner_subtitle" value ="<?php echo LA_VAL_STEP1_SUBTITLE;?>">
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP1_SUBTITLE;?></span>
        </div>
    </fieldset>
    <fieldset>
        <legend><?php echo LA_LEGEND_STEP1_FOOTER;?></legend>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP1_FOOTER_LEFT;?></label>
            <input type="text" name="footer_left" value ="<?php echo LA_VAL_STEP1_FOOTER_LEFT;?>">
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP1_SUBTITLE;?></span>
        </div>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP1_FOOTER_MID;?></label>
            <input type="text" name="footer_mid" value ="<?php echo LA_VAL_STEP1_FOOTER_MID;?>">
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP1_SUBTITLE;?></span>
        </div>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP1_FOOTER_RIGHT;?></label>
            <input type="text" name="footer_right" value ="<?php echo LA_VAL_STEP1_FOOTER_RIGHT;?>">
            <span class="zdkappwiz-field-desc"><?php echo LA_INF_STEP1_SUBTITLE;?></span>
        </div>
    </fieldset>
    <button class="zdk-bt-custom next" data-zdk-icon="ui-icon-circle-arrow-e:right" type="submit"><?php echo LA_BUTTON_NEXT;?></button>
</form>
<img id="zdkappwiz-img-step1" src="<?php echo ZNETDK_APP_URI; ?>images/step1.png" alt="step1">