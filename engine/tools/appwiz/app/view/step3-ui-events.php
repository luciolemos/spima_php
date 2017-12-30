<!--
ZnetDK, Starter Web Application for rapid & easy development
See official website http://www.znetdk.fr 
Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
--------------------------------------------------------------------
Application Wizard step 3 view UI events.
File version: 1.1
Last update: 10/25/2015
-->
<script>
    $(document).ready(function () {
        $('#zdkappwiz-form-step3').zdkform({
            ready: function () {
                setFocusStep3();
            },
            complete: function () {
                enableNextStep();
                znetdk.showMenuView('step4');
            }
        });
        
        /* Catches events on the radio button selection change */
        $("#zdkappwiz-form-step3 :radio").on('puiradiobuttonselectionchange', function () {
            var selectedValue = $(this).val();
            if (selectedValue === 'yes') { // Database creation
                // Checkbox state is memorized
                $("#zdkappwiz-create-tables-prev").val(
                        $("#zdkappwiz-form-step3 :checkbox").is(':checked'));
                // Checkbox is disabled and checked
                $("#zdkappwiz-form-step3 :checkbox")
                        .puicheckbox('disable').puicheckbox('check');
            } else { // No database creation
                if (selectedValue === 'no_database') {
                    // Checkbox state is memorized
                    $("#zdkappwiz-create-tables-prev").val(
                            $("#zdkappwiz-form-step3 :checkbox").is(':checked'));
                    // Checkbox is disabled and checked
                    $("#zdkappwiz-form-step3 :checkbox")
                            .puicheckbox('disable').puicheckbox('uncheck');
                    // The database form is hidden
                    $("#zdkappwiz-fieldset-database").hide();
                } else {
                    // Checkbox previous state is restored
                    var previousState = $("#zdkappwiz-create-tables-prev").val() === 'true'
                            ? 'check' : 'uncheck';
                    $("#zdkappwiz-form-step3 :checkbox")
                            .puicheckbox('enable').puicheckbox(previousState);
                }
            }
            // Admin form is shown or hidden according to the checkbox state
            $("#zdkappwiz-form-step3 :checkbox").trigger('puicheckboxchange');
            // The database form is shown or hidden according to the selected radio button
            showHideDatabaseForm(selectedValue);
        });

        /* Catches events on checkbox click */
        $("#zdkappwiz-form-step3 :checkbox").bind('puicheckboxchange', function () {
            if ($(this).puicheckbox('isChecked')) {
                $("#zdkappwiz-form-step3 input[name='admin']").prop('required', true);
                $("#zdkappwiz-fieldset-admin").show();
            } else {
                $("#zdkappwiz-form-step3 input[name='admin']").prop('required', false);
                $("#zdkappwiz-fieldset-admin").hide();
            }
        });
        
    });
    /* Hides or shows the Database form according to the radio button selection */
    function showHideDatabaseForm(selectedValue) {
        if (selectedValue === 'no_database') {
            $("#zdkappwiz-fieldset-database input").prop('required', false);
            $("#zdkappwiz-fieldset-database").hide();
            $("#zdkappwiz-form-entry-create-tables").hide();
        } else {
            $("#zdkappwiz-fieldset-database input").prop('required', true);
            $("#zdkappwiz-fieldset-database").show();
            $("#zdkappwiz-form-entry-create-tables").show();
        }
    }
</script>