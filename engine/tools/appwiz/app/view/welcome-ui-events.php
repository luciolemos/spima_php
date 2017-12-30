<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
Application Wizard welcome view UI events.
File version: 1.0
Last update: 09/18/2015
-->
<script>
    $(document).ready(function () {
        znetdk.initCommonWidgets($('#menu-welcome .zdk-dynamic-content'));
        $('#menu-welcome button.zdk-bt-custom[data-zdk-step="step1"]').puibutton('disable');
        $('#menu-welcome button.zdk-bt-custom[data-zdk-step="step2"]').puibutton('disable');
        $('#menu-welcome button.zdk-bt-custom[data-zdk-step="step3"]').puibutton('disable');
        $('#menu-welcome button.zdk-bt-custom[data-zdk-step="step4"]').puibutton('disable');
        $('#menu-welcome button.zdk-bt-custom').on('click', function() {
            var step = znetdk.getTextFromAttr($(this),'data-zdk-step');
            enableNextStep();
            znetdk.showMenuView(step ? step : 'step1');
        });

        $(document).bind("L1menuViewLoad",  function(event) {
            if (event.menuId === 'menu-welcome') {
                checkDefaultAppExists();
            }
        });
        
        function checkDefaultAppExists() {
            znetdk.request({
                control:'wizard',
                action:'doesappexist',
                callback: function(response) {
                    if (response.success) {
                        setFocusWelcome();
                    } else {
                        $('#menu-welcome button.zdk-bt-custom.start').puibutton('disable');
                        znetdk.message('critical',response.summary,response.msg);
                    }
                }
            });
        }
        
    });
</script>