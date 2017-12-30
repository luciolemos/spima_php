<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
Application Wizard welcome view.
File version: 1.0
Last update: 09/18/2015
-->
<img id="zdkappwiz-img-wizard" src="<?php echo ZNETDK_APP_URI; ?>images/wizard.png">
<p class="teaser"><?php echo LA_LBL_WELCOME_INTRO; ?></p>
<p class="teaser"><?php echo LA_LBL_WELCOME_REQUIREMENTS; ?></p>

<fieldset>
    <p class="teaser">
        <button class="zdk-bt-custom" data-zdk-icon="ui-icon-carat-1-e" data-zdk-step="step1">
            <?php echo LA_MENU_STEP1; ?>
        </button>
        <span class="zdkappwiz-step-desc"><?php echo LA_MENU_STEP1_DESC; ?>,</span>
    </p>
    <p class="teaser">
        <button class="zdk-bt-custom" data-zdk-icon="ui-icon-carat-1-e" data-zdk-step="step2">
            <?php echo LA_MENU_STEP2; ?>
        </button>
        <span class="zdkappwiz-step-desc"><?php echo LA_MENU_STEP2_DESC; ?>,</span>
    </p>
    <p class="teaser">
        <button class="zdk-bt-custom" data-zdk-icon="ui-icon-carat-1-e" data-zdk-step="step3">
            <?php echo LA_MENU_STEP3; ?>
        </button>
        <span class="zdkappwiz-step-desc"><?php echo LA_MENU_STEP3_DESC; ?>,</span>
    </p>
    <p class="teaser">
        <button class="zdk-bt-custom" data-zdk-icon="ui-icon-carat-1-e" data-zdk-step="step4">
            <?php echo LA_MENU_STEP4; ?>
        </button>
        <span class="zdkappwiz-step-desc"><?php echo LA_MENU_STEP4_DESC; ?>.</span>
    </p>
    <br>
</fieldset>

<p class="teaser"><?php echo LA_LBL_WELCOME_LETSGO; ?>
    <button class="zdk-bt-custom start" data-zdk-icon="ui-icon-circle-arrow-e:right">
        <?php echo LA_BUTTON_START; ?>
    </button>
</p>