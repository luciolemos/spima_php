/**
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website http://www.znetdk.fr 
 * Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
 * License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
 * --------------------------------------------------------------------
 * ZnetDK Application Wizard global Javascript actions & events
 * File version: 1.0
 * Last update: 09/18/2015 
 */

$(document).ready(function () {

    $('#zdk-classic-menu').puitabview('disable','1')
                    .puitabview('disable','2').puitabview('disable','3')
                    .puitabview('disable','4');

    $(document).bind("L1menuTabChange", function(event) {
        switch(event.menuId) {
            case 'menu-welcome': setFocusWelcome(); break;
            case 'menu-step1': setFocusStep1(); disableNextSteps(); break;
            case 'menu-step2': setFocusStep2(); disableNextSteps(); break;
            case 'menu-step3': setFocusStep3(); disableNextSteps(); break;
            case 'menu-step4': setFocusStep4(); disableNextSteps(); break;
        }
    });

});

function setFocusWelcome() {
    $('#menu-welcome button.start').focus();
}

function setFocusStep1() {
    $('#zdkappwiz-form-step1 input[name="def_lang"]').focus().select();
}

function setFocusStep2() {
    $("#zdkappwiz-form-step2 :file").zdkinputfile('setFocus');
}

function setFocusStep3() {
    $("#zdkappwiz-form-step3 input[name='host']").focus().select();
}

function setFocusStep4() {
    $('#zdkappwiz-bt-generate').focus();
}

function enableNextStep() {
    var selectedTabNbr = $('#zdk-classic-menu').puitabview('getActiveIndex');
    $('#zdk-classic-menu').puitabview('enable', selectedTabNbr + 1);
    $('#menu-welcome button.zdk-bt-custom[data-zdk-step="step'
        + (selectedTabNbr + 1) + '"]').puibutton('enable');
}

function disableNextSteps() {
    var selectedTabNbr = $('#zdk-classic-menu').puitabview('getActiveIndex');
    for (i = selectedTabNbr + 1; i <= 4; i++) {
        $('#zdk-classic-menu').puitabview('disable', i);
        $('#menu-welcome button.zdk-bt-custom[data-zdk-step="step'
            + i + '"]').puibutton('disable');
    }
}