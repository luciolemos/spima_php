<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
Application Wizard step 1 view UI events.
File version: 1.0
Last update: 09/18/2015
-->
<script>
    $(document).ready(function () {

        $('#zdkappwiz-form-step1').zdkform({
            ready: function() {
                setFocusStep1();
            },
            complete: function() {
                enableNextStep();
                znetdk.showMenuView('step2');
            }
        });
        
    });
</script>