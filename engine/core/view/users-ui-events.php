<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
Core authorizations view | UI Components events
        
File version: 1.0
Last update: 09/18/2015
-->
<script>
    $(document).ready(function () {

        /********* When the form is reset **********/
        $('#znetdk_user_actions').zdkactionbar({
            whenadd: function() {
                // Refresh profiles in the listbox
                $('#znetdk_user_dialog .zdk-listbox').zdklistbox('refresh');
                // Default expiration date value
                $("#znetdk_user_dialog form input[name=expiration_date]").zdkinputdate('setW3CDate', '<?php echo \General::getCurrentW3CDate(); ?>');
                // Default user status is enabled (value = "1")
                $("#znetdk_user_dialog form input[name=user_enabled]").puiradiobutton('select', "1");
            },
            whenedit: function() {
                // Refresh profiles in the listbox keeping current selection
                $('#znetdk_user_dialog .zdk-listbox').zdklistbox('refresh',true);
            }
        });

    });
</script>