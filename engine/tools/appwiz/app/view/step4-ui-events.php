<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
Application Wizard step 4 view UI events.
File version: 1.1
Last update: 10/25/2015
-->
<script>
    $(document).ready(function () {
        $('#zdkappwiz-bt-generate').puibutton({icon: 'ui-icon-gear'})
            .on('click', function() {
                var confirmMsg = "<?php echo LA_LBL_STEP4_GEN_CONFIRM;?>",
                    emptyFields = getEmptyMandatoryFields();
                if (emptyFields.length) {
                    confirmMsg += '<br><?php echo LA_LBL_STEP4_GEN_CONFIRM_EXT;?>';
                    confirmMsg += '<span style="color:red;font-style:italic;">'+ emptyFields.join() + '</span>';
                }
                znetdk.getUserConfirmation({
                   title: "<?php echo LA_BUTTON_GENERATE;?>",
                   message: confirmMsg,
                   yesLabel: '<?php echo LC_BTN_YES;?>',
                   noLabel: '<?php echo LC_BTN_NO;?>',
                   callback:function(confirmation) {
                       if (confirmation) {
                            uploadLogoAndGenerateApp();           
                       }
                   }
                });
            });
        $(document).bind("L1menuTabChange", function(event) {
            if (event.menuId === 'menu-step4') {
                initFormStep4();
            }
        });
        
        $('#zdkappwiz-form-step4').zdkform({
            ready: function() {
                setFocusStep4();
                initFormStep4();
            }
        });
        
        $('#zdkappwiz-bt-showapp').on('click', function(){
            location.assign($(this).attr('data-url'));
        });
        
        function initFormStep4() {
            var formDataStep1 = $('#zdkappwiz-form-step1').zdkform('getFormData',true),
                formDataStep2 = $('#zdkappwiz-form-step2').zdkform('getFormData',true),
                formDataStep3 = $('#zdkappwiz-form-step3').zdkform('getFormData',true),
                formDataStep4 = {}, databaseInfosHidden = false;
            for (i = 0; i < formDataStep1.length; i++) {
                formDataStep4[formDataStep1[i].name] = formDataStep1[i].value;
            }
            for (i = 0; i < formDataStep2.length; i++) {
                var currentValue = formDataStep2[i].value;
                if (formDataStep2[i].name === 'logo' && currentValue !== undefined) {
                    currentValue = currentValue.replace('fakepath','...');
                } 
                formDataStep4[formDataStep2[i].name] = currentValue;
            }
            for (i = 0; i < formDataStep3.length; i++) {
                var currentValue = formDataStep3[i].value;
                if (formDataStep3[i].name === 'create_db' && currentValue !== undefined) {
                    var textValue = currentValue === 'yes'
                        ? "<?php echo LA_LBL_STEP3_CREATE_DATABASE;?>"
                        : currentValue === 'no' ? "<?php echo LA_LBL_STEP3_NOCREATE_DATABASE;?>"
                        : "<?php echo LA_LBL_STEP3_NO_USE_DATABASE;?>";
                    formDataStep4['create_db_text'] = textValue;
                    databaseInfosHidden = currentValue === 'no_database' ? true : false;
                }
                formDataStep4[formDataStep3[i].name] = currentValue;
            }
            $('#zdkappwiz-form-step4').zdkform('init',formDataStep4);
            showDatabaseInfos(databaseInfosHidden);
            showRequiredValueIsMissing();
        }

        function getEmptyMandatoryFields() {
            var emptyFields = [];
            $('#zdkappwiz-form-step4 label.zdk-required+:text:visible').each(function() {
                if ($(this).val() === '') {
                    emptyFields.push(' ' + $(this).prev('label').text());
                }
            });
            return emptyFields;
        }
        
        function showDatabaseInfos(isHidden) {
            if (isHidden) {
                $('#zdkappwiz-fieldset-database-step4 .zdk-form-entry:not(.zdk-always-visible)').hide();
            } else {
                $('#zdkappwiz-fieldset-database-step4 .zdk-form-entry').show();
            }
        }
        
        function showRequiredValueIsMissing() {
            $('#zdkappwiz-form-step4 label.zdk-required+:text').each(function() {
                if ($(this).val() === '') {
                    $(this).prev('label').addClass('zdkappwiz-missing');
                    $(this).addClass('zdkappwiz-missing');
                } else {
                    $(this).prev('label').removeClass('zdkappwiz-missing');
                    $(this).removeClass('zdkappwiz-missing');
                }
            });
        }
        
        function uploadLogoAndGenerateApp() {
            var generateApp = function(response) {
                var statusUpload = [];
                if(response.success) {
                    statusUpload = [{name:'upload_ok',value:'yes'}];
                } else { // Upload has failed...
                    statusUpload = [{name:'upload_ok',value:'no'},
                                    {name:'upload_msg',value:response.msg}];
                }
                var formDataStep4 = $('#zdkappwiz-form-step4').zdkform('getFormData');
                znetdk.request({
                    control: 'wizard',
                    action: 'generate',
                    data: formDataStep4.concat(statusUpload),
                    callback: function(responseGenerate) {
                        if (responseGenerate.success) {
                            $('#zdkappwiz-report-table').zdkdatatable({
                               datasource:responseGenerate.report,
                               scrollable:true,
                               scrollHeight:'220',
                               caption:"<?php echo LA_RPT_STATUS_NB_ERRORS;?>" + responseGenerate.nberrors
                            });
                            $('#zdkappwiz-bt-showapp').attr('data-url', responseGenerate.url);
                            $('#zdkappwiz-report-dlg').zdkmodal('show');
                            $('#zdkappwiz-bt-generate').puibutton('disable');
                            $('#zdk-classic-menu').puitabview('disable','0')
                                .puitabview('disable','1').puitabview('disable','2')
                                .puitabview('disable','3');
                            $('#zdk-header-logo').zdklogo('disable');
                        } else {
                            znetdk.message('critical',responseGenerate.summary, responseGenerate.msg);
                        }
                    }
                });
            };
            if ($('#zdkappwiz-form-step4 input[name="logo"]').val() === '') {
                //No logo selected then no image upload...
                generateApp({success:false,msg:"<?php echo LA_MSG_UPLOAD_NO_FILE;?>"});
            } else {
                $("#zdkappwiz-form-step2 :file").zdkinputfile('upload', generateApp);
            }
        }
        
    });
</script>