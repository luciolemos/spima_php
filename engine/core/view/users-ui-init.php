<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
Authorizations view | UI View initialization
        
File version: 1.0
Last update: 09/18/2015
-->
<script>
    $(document).ready(function () {
        /******* Load specific CSS file ********/
        znetdk.useStyleSheet('<?php echo ZNETDK_ROOT_URI . \General::getFilledMessage(CFG_ZNETDK_CSS, "authoriz_users"); ?>');
    });
</script>