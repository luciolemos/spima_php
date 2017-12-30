<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
Application Wizard step 2 view UI events.
File version: 1.0
Last update: 09/18/2015
-->
<script>
    $(document).ready(function () {
        $('#zdkappwiz-link-preview').puilightbox({iframe:true,iframeWidth:'860', iframeHeight:'600'});
        
        $('#zdkappwiz-form-step2').zdkform({
            ready: function() {
                $('#zdkappwiz-form-step2 .zdk-dropdown').zdkdropdown({
                    content: function(option) {
                        if (option.value !== '_') {
                            return '<img class="zdkappwiz-img-theme" src="<?php echo ZNETDK_ROOT_URI;?>resources/images/themes/'
                                    + option.value + '" alt="'+option.label+'"><span class="zdkappwiz-lbl-theme">'
                                    + option.label + '</span>';
                        } else {
                            return option.label;
                        }
                    },
                    change: function() {
                        var fileName = $(this).zdkdropdown('getSelectedValue');
                        if (fileName === '_') {
                            $('#zdkappwiz-img-theme-preview').attr('src',null).hide();
                        } else {
                            $('#zdkappwiz-img-theme-preview').attr('src',
                                '<?php echo ZNETDK_ROOT_URI;?>resources/images/themes/'
                                    + fileName).show();
                        }
                    }
                });
                $('#zdkappwiz-form-step2 :radio').puiradiobutton({
                    selectionchange: function() {
                        if ($(this).val() === 'classic') {
                            $('#zdkappwiz-info-classic').show();
                            $('#zdkappwiz-info-office').hide();
                        } else {
                            $('#zdkappwiz-info-classic').hide();
                            $('#zdkappwiz-info-office').show();
                        }
                    }
                });
                setFocusStep2();
            },
            complete: function() {
                if (!showPreview.call()) {
                    enableNextStep();
                    znetdk.showMenuView('step3');
                }
            }
        });

        $("#zdkappwiz-form-step2 .preview").on('click',function() {
            isButtonPreviewClicked = true;
        });
        
        $("#zdkappwiz-form-step2 .next").on('click',function() {
            isButtonPreviewClicked = false;
        });
               
    });
    var isButtonPreviewClicked = false;
    function showPreview() {
        if (isButtonPreviewClicked) {
            var formDataStep1 = $('#zdkappwiz-form-step1').zdkform('getFormData'),
            formDataStep2 = $('#zdkappwiz-form-step2').zdkform('getFormData');
            znetdk.request({
                control: 'wizard',
                action: 'preview',
                data: formDataStep1.concat(formDataStep2),
                callback: function(responsePreview) {
                if (responsePreview.success) {
                    $('#zdkappwiz-link-preview').puilightbox('showURL',{src:responsePreview.url});
                } else {
                    $("#zdkappwiz-form-step2").zdkform('showCustomError',
                        responsePreview.msg, responsePreview.ename);
                }
            }});
            return true;
        } else {
            return false;
        }
    }
</script>