<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
Application Wizard step 2 view.
File version: 1.0
Last update: 09/18/2015
-->
<p class="teaser"><?php echo LA_MENU_STEP2_DESC;?>.</p>
<form id="zdkappwiz-form-step2" class="zdk-form">
    <fieldset id="zdkappwiz-fieldset-file">
        <legend><?php echo LA_LEGEND_STEP2_LOGO;?></legend>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP2_LOGO;?></label>
            <input type="file" name="logo" data-zdk-action="wizard:upload"
                data-zdk-nofilelabel="<?php echo LC_FORM_LBL_NO_FILE_SELECTED;?>"
                data-zdk-selbuttonlabel="<?php echo LC_BTN_SELECTFILE;?>"
                data-zdkerrmsg-required="<?php echo LA_MSG_STEP2_LOGO_MANDATORY;?>"
                required>
            <span class="zdkappwiz-field-desc-s2"><?php echo LA_INF_STEP2_LOGO;?></span>
        </div>
    </fieldset>
    <fieldset id="zdkappwiz-fieldset-theme">
        <legend><?php echo LA_LEGEND_STEP2_THEME;?></legend>
        <div class="zdk-form-entry">
            <label><?php echo LA_LBL_STEP2_THEME;?></label>
            <select class="zdk-dropdown" name="theme" data-zdk-value="flat-blue.png"
                    data-zdk-action="wizard:themes"
                    data-zdk-noselection="<?php echo LA_LBL_STEP2_THEME_EMPTY;?>"
                    data-zdkerrmsg-required="<?php echo LA_MSG_STEP2_THEM_MANDATORY;?>"
                    required>
            </select>
            <img id="zdkappwiz-img-theme-preview" class="ui-helper-hidden" src="">
            <span class="zdkappwiz-field-desc-s2"><?php echo LA_INF_STEP2_THEME;?></span>
        </div>
    </fieldset>
    <fieldset id="zdkappwiz-fieldset-layout">
        <legend><?php echo LA_LEGEND_STEP2_LAYOUT;?></legend>
        <div class='zdk-form-entry'>
            <label class="zdk-required"><?php echo LA_LBL_STEP2_LAYOUT;?></label>
            <div class="zdk-radiobuttongroup" data-name="layout">
                <input type="radio" value="classic" checked>
                <label>Classic</label>
                <input type="radio" value="office">
                <label>Office</label>
            </div>
            <span id="zdkappwiz-info-classic" class="zdkappwiz-field-desc"><?php echo LA_INF_STEP2_LAYOUT_CLASSIC;?></span>
            <span id="zdkappwiz-info-office" class="zdkappwiz-field-desc ui-helper-hidden"><?php echo LA_INF_STEP2_LAYOUT_OFFICE;?></span>
        </div>
        <span class="zdkappwiz-field-desc-s2"><?php echo LA_INF_STEP2_LAYOUT;?></span>
    </fieldset>
    <button class="zdk-bt-custom preview" data-zdk-icon="ui-icon-search" type="submit"><?php echo LA_BUTTON_PREVIEW;?></button>
    <button class="zdk-bt-custom next" data-zdk-icon="ui-icon-circle-arrow-e:right" type="submit"><?php echo LA_BUTTON_NEXT;?></button>
</form>
<img id="zdkappwiz-img-step2" src="<?php echo ZNETDK_APP_URI; ?>images/step2.png" alt="step2">
<a id="zdkappwiz-link-preview"></a>