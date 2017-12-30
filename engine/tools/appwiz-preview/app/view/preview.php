<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
View displayed as home view for the Application Wizard preview.
File version: 1.0
Last update: 09/18/2015
-->
<div id="zdk-appwiz-preview-view">
    <h3><?php echo LA_PREVIEW_WELCOME;?></h3>
    <form class="zdk-form">
        <fieldset>
            <img src='<?php echo ZNETDK_APP_URI; ?>../../appwiz/public/images/step2.png' alt='step2 preview'>
            <legend><?php echo LA_PREVIEW_LEGEND1;?></legend>
            <ul>
                <li><?php echo LA_PREVIEW_ITEM1;?></li>
                <li><?php echo LA_PREVIEW_ITEM2;?></li>
                <li><?php echo LA_PREVIEW_ITEM3;?></li>
            </ul>
        </fieldset>
        <br>
        <fieldset>
            <legend><?php echo LA_PREVIEW_LEGEND2;?></legend>
            <ul>
                <li><?php echo LA_PREVIEW_ITEM4;?></li>
                <li><?php echo LA_PREVIEW_ITEM5;?></li>
                <li><?php echo LA_PREVIEW_ITEM6;?></li>
            </ul>
        </fieldset>
    </form>
</div>