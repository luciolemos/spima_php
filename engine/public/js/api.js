/**
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website http://www.znetdk.fr 
 * Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
 * License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
 * --------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------
 * ZnetDK Javascript Client API
 *
 * File version: 1.6
 * Last update: 03/30/2017
 */
var znetdk = {
    requestContext: [],
    ajaxInProgress: 0,
    /**
     * Send an AJAX request to the specified controller 
     * @param {Object} options object containing the parameters of the request
     * @returns {jqXHR} object used for synchronization purpose.
     */
    request: function (options) {
        var $this = this, ajaxURL = this.getParamsFromAjaxURL(znetdkAjaxURL);
        if (options.control && options.action) {
            var ajaxOptions = {
                type: "POST",
                url: ajaxURL.url,
                success: function (response) {
                    if (options.htmlTarget) {
                        options.htmlTarget.prepend(response);
                        var innerForm = options.htmlTarget.find('form.zdk-form'),
                            functionToCallback = typeof options.callback === "function";
                        if (innerForm.length === 1 && functionToCallback) {
                            innerForm.one('zdkformready', function() {
                                options.callback(response);
                            });
                        }
                        try {
                            $this.autoInitWidgets(options.htmlTarget);
                        } catch (err) {
                            $this.message('critical','Widgets initialization failed', 
                                "<strong>Error message</strong>: '<i>" + err.message + "</i>'<br><br>"  
                                + "See the browser's console for the full error message.");
                            console.error(err.stack);
                        }
                        if (innerForm.length !== 1 && functionToCallback) {
                            options.callback(response);
                        }
                    } else if (typeof response === "object") {
                        if (typeof options.callback === "function") {
                            options.callback(response);
                        } else {
                            var levelMsg = response.success === false ? 'error' :
                                    response.warning === true ? 'warn' : 'info',
                                    summary = response.summary === undefined ? 'Message' :
                                    response.summary;

                            $this.message(levelMsg, summary, response.msg);
                        }
                    } else {
                        $this.message('error', 'Message', 'Invalid Ajax response sent by the web server!');
                    }
                },
                error: function (response) {
                    var errorMsg, errorSummary,
                            errorLevel = response.status === 401 ? 'warn' : 'critical';
                    if (/application\/json/.test(response.getResponseHeader('Content-Type'))) {
                        try {
                            var errorObject = JSON.parse(response.responseText);
                            errorMsg = errorObject.msg;
                            errorSummary = errorObject.summary;
                        } catch (e) {
                        }
                    }
                    if (response.status === 0) {
                        var msgArray = $('#zdk-network-error-msg').html().split("|");
                        errorSummary = msgArray[0];
                        errorMsg = msgArray[1];
                        errorLevel = 'error';
                    } else if (errorMsg === undefined) {
                        errorMsg = "The JSON response returned by the controller='" + options.control + "' and the action='" + options.action +
                            "' can't be parsed! HTTP status: " + response.status + ' ' + response.statusText;
                        errorSummary = 'Error parsing server response';
                    }
                    $this.message(errorLevel, errorSummary, errorMsg);
                    if (response.status === 401) {
                        $this.requestContext.push(options);
                        if ($this.requestContext.length === 1) {
                            $this.showLoginDialog(); // Login dialog to renew user credentials
                        }
                    }
                }
            };
            if (options.data && typeof options.data === "object") {
                var requestData;
                if (options.data.length === undefined) {
                    var property;
                    requestData = 'control=' + options.control + '&action=' + options.action;
                    for (property in options.data) {
                        var value = options.data[property],
                            encodedValue = typeof value === 'string' ? encodeURIComponent(value) : value;
                        if (value !== undefined && value !== null) {
                            requestData += '&' + property + '=' + encodedValue;
                        }
                    }
                } else {
                    requestData = 'control=' + options.control + '&action=' + options.action + '&' + $.param(options.data);
                }
                if (ajaxURL.paramName !== undefined) {
                    requestData += '&' + ajaxURL.paramName + '=' + ajaxURL.paramValue;
                }
                ajaxOptions.data = requestData;
            } else if (options.fileToUpload && typeof options.fileToUpload === "object") {
                var formData = new FormData();
                formData.append('control', options.control);
                formData.append('action', options.action);
                if (ajaxURL.paramName !== undefined) {
                    formData.append(ajaxURL.paramName, ajaxURL.paramValue);
                } 
                if (options.fileToUpload.inputName !== undefined
                        && options.fileToUpload.file !== undefined) {
                    formData.append(options.fileToUpload.inputName, options.fileToUpload.file,
                            options.fileToUpload.file.name);
                    ajaxOptions.data = formData;
                    ajaxOptions.processData = false;
                    ajaxOptions.contentType = false;
                } else {
                    throw new Error("znetdk.request: 'fileToUpload' option is not properly set!");
                }
            } else {
                ajaxOptions.data = {control: options.control, action: options.action};
                if (ajaxURL.paramName !== undefined) {
                    ajaxOptions.data[ajaxURL.paramName] = ajaxURL.paramValue;
                } 
            }
            return $.ajax(ajaxOptions);
        } else {
            console.log("Call to znetdk.request failed : ", options.control, options.action);
        }
    },
    /**
     * Loads the view from the web server in under a HTML element
     * @param {Object} options options of the view to load
     * @returns {undefined}
     */
    loadView: function (options) {
        if (options.htmlTarget.length) {
            znetdk.request(options);
        }
    },
    /**
     * Shows the specified ZnetDK modal dialog and execute the callback 
     * functions before and after its display.
     * If the dialog does not exist in the DOM, it is loaded first.
     * @param {String} dialogId Identifier of the 'zdkmodal' dialog Element
     * @param {String} viewName Name of the view containing the dialog to display
     * @param {function} beforeShow Function to call before the display of the
     * dialog
     * @param {function} afterShow Function to call after the diasplay of the 
     * dialog
     */
    showModalDialog: function(dialogId, viewName, beforeShow, afterShow) {
        var callbackFunction = function() {
            if (typeof afterShow === "function") {
                $('#' + dialogId).one('zdkmodalaftershow',function() {
                    afterShow($(this));
                });
            }
            if (typeof beforeShow === "function") {
                beforeShow($('#' + dialogId));
            }
            $('#' + dialogId).zdkmodal('show');
        };
        if ($('#' + dialogId).length === 0) {
            znetdk.loadView({
                control:viewName,
                action:'show',
                htmlTarget:$('body'),
                callback: callbackFunction
            });
        } else {
            callbackFunction();
        }
    },
    /**
     * Execute the pending AJAX requests from the 'requestContext' queue.
     * This requests were not executed due to an error HTTP 401 (user session 
     * expired). 
     */
    requestFromQueue: function () {
        var queueSize = this.requestContext.length;
        for (index = 0; index < queueSize; index++) {
            this.request(this.requestContext[index]);
        }
        this.requestContext.splice(0, queueSize);
    },
    /**
     * Returns the language set for the page
     * @returns {String} language code (for example 'fr')
     */
    getCurrentLanguage: function () {
        var currentLanguage = $('html').attr('lang');
        if (currentLanguage) {
            return currentLanguage;
        } else {
            return '';
        }
    },
    /**
     * Returns an object that contains a property for the URL and 2 more properties
     * for the specified GET parameter (name + value). 
     * @returns {Object} AJAX URL and the GET parameter
     */
    getParamsFromAjaxURL: function () {
        var URLArray = znetdkAjaxURL.split("?"),
            paramArray = [],
            result = new Object();
        result.url = URLArray[0];
        if (URLArray.length === 2) {
            paramArray = URLArray[1].split("=");
            result.paramName = paramArray[0];
            result.paramValue = paramArray[1];
        }
        return result;
    },
    /**
     * Initializes the ZnetDK widgets which are descendants of the specified
     * HTML element 
     * @param {jQuery} parentElement HTML element under which the widgets have 
     * to be initialized
     */
    autoInitWidgets: function (parentElement) {
        if (parentElement instanceof jQuery && parentElement.length) {
            parentElement.find('.zdk-form').zdkform();
            parentElement.find('.zdk-modal').zdkmodal();
            parentElement.find('.zdk-datatable:not(.zdk-nocreate)').zdkdatatable();
            parentElement.find('.zdk-action-bar').zdkactionbar();
        }
    },
    /**
     * Initializes the widgets which are descendants of the specified HTML element
     * This method is called by the form and action bar ZnetDK widgets.
     * @param {jQuery} parentElement HTML element under which the widgets have 
     * to be initialized
     */
    initCommonWidgets: function (parentElement) {
        if (parentElement instanceof jQuery && parentElement.length) {
            // zdkinputrows
            var inputRowsElement = parentElement.find('.zdk-inputrows');
            if (inputRowsElement.length > 0) {
                try {
                    inputRowsElement.zdkinputrows();
                } catch(exception) {
                    console.error("znetdk.initCommonWidgets: the 'zdkinputrows' widget does not exist!");
                }
            }
            // puiinputtext
            parentElement.find(':text,:password,input[type="email"],input[type="number"],input[type="url"],input[type="time"]')
                    .not('.zdk-autocomplete').puiinputtext();
            // zdkautocomplete
            parentElement.find('input[type="text"].zdk-autocomplete').zdkautocomplete();
            // zdkinputdate
            parentElement.find('input[type="date"]').zdkinputdate({regional: znetdk.getCurrentLanguage()});
            // zdkinputfile
            parentElement.find('input[type="file"]').zdkinputfile();
            // zdkinputhtml
            parentElement.find('div.zdk-inputhtml').zdkinputhtml();
            // zdkmultiupload
            parentElement.find('div.zdk-multiupload').zdkmultiupload();
            // puiinputtextarea
            parentElement.find('textarea').puiinputtextarea();
            // puibutton
            // yes)
            parentElement.find('button.zdk-bt-yes').puibutton({icon: 'ui-icon-check'});
            // no)
            parentElement.find('button.zdk-bt-no').puibutton({icon: 'ui-icon-close'});
            // add)
            parentElement.find('button.zdk-bt-add').puibutton({icon: 'ui-icon-circle-plus'});
            // edit)
            parentElement.find('button.zdk-bt-edit').puibutton({icon: 'ui-icon-pencil'});
            // remove)
            parentElement.find('button.zdk-bt-remove').puibutton({icon: 'ui-icon-circle-minus'});
            // cancel)
            parentElement.find('button.zdk-bt-cancel').puibutton({icon: 'ui-icon-close'});
            // save)
            parentElement.find('button.zdk-bt-save').puibutton({icon: 'ui-icon-disk'});
            // reset)
            parentElement.find('button.zdk-bt-reset').puibutton({icon: 'ui-icon-arrowreturnthick-1-s'});
            // search)
            parentElement.find('button.zdk-bt-search').puibutton({icon: 'ui-icon-search'});
            // clear)
            parentElement.find('button.zdk-bt-clear').puibutton({icon: 'ui-icon-circle-close'});
            // refresh)
            parentElement.find('button.zdk-bt-refresh').puibutton({icon: 'ui-icon-refresh'});
            // download)
            parentElement.find('button.zdk-bt-download').puibutton({icon: 'ui-icon-circle-arrow-s'});
            // upload)
            parentElement.find('button.zdk-bt-upload').puibutton({icon: 'ui-icon-arrowthickstop-1-n'});
            // Custom)
            parentElement.find('button.zdk-bt-custom').each(function() {
                var iconAttr = znetdk.getTextFromAttr($(this),'data-zdk-icon'),
                    options = {};
                if (iconAttr) {
                    var attrArray = iconAttr.split(":");
                    options.icon = attrArray[0];
                    options.iconPos = attrArray.length === 2 ? attrArray[1] : 'left';
                }
                $(this).puibutton(options);
            });
            // puicheckbox
            parentElement.find(':checkbox').puicheckbox();
            // zdkradiobuttongroup
            parentElement.find('.zdk-radiobuttongroup').zdkradiobuttongroup();
            // puiradiobutton
            parentElement.find(':radio').puiradiobutton();
            // puifieldset
            parentElement.find('fieldset').puifieldset();
            // zdklistbox
            parentElement.find('select.zdk-listbox').zdklistbox();
            // zdkdropdown
            parentElement.find('select.zdk-dropdown').zdkdropdown();
        }
    },
    /**
     * Displays the specified message in the PrimeUI growl widget 
     * @param {String} severity severity of the message ('info','warn','error'
     *  or 'critical')
     * @param {String} summary summary of the message
     * @param {String} detail detailed message
     */
    message: function (severity, summary, detail) {
        if (severity === 'critical') {
            var htmlMessage = '<span class="pui-growl-image zdk-image-fatal" />' +
                    '<h3>' + summary + '</h3><p>' + detail + '</p>';
            $('#zdk-critical-err').puinotify('show', htmlMessage);
        } else {
            $('#zdk-messages').puigrowl('show', [{severity: severity, summary: summary, detail: detail}]);
        }
    },
    /**
     * Displays the specified messages in the PrimeUI growl widget
     * @param {array} messages An array of message objects according to PrimeUI
     * growl syntax (severity, summary and detail properties)
     */
    multiMessages: function (messages) {
        $('#zdk-messages').puigrowl('show', messages);
    },
    /*
     * Displays the specified view of the navigation menu
     * @param {undefined|String} viewName name of the view to display
     * @param {object} options Values to pass as an object to the called view
     */
    showMenuView: function (viewName, options) {
        $('#zdk-classic-menu').zdktabmenu('selectTab', viewName); /* classic tab menu */
        $('#zdk-office-menu').zdkofficemenu('openWindow', viewName, options); /* office vertical menu */
        $('#zdk-custom-menu').zdkgenericmenu('displayView', viewName); /* custom menu */
    },
    /**
     * Displays a confirmation dialog box
     * @param {Object} options The properties of the dialog box:
     * 'title': title to display into the dialog window bar,
     * 'message': the text of the confirmation message,
     * 'yesLabel': the label to display for the 'Yes' button.
     * 'noLabel': the label to display for the 'No' button
     * 'focusOnYes': if true, the Yes button is focused (No button focused by default)
     * 'callback': the function to call back when user has clicked on a button.
     * the parameter of the function is boolean value set to 'true' when user 
     * clicks on the 'Yes' button and set to false otherwise.
     */
    getUserConfirmation: function (options) {
        var dialogID = 'znetdk_confirmation_dialog';
        var dialogElement = $('#' + dialogID);
        if (dialogElement.length) { //Dialog already exists...
            // ...then is removed...
            dialogElement.remove();
        }
        // DIV element as dialog wrapper
        $('body').append('<div id="' + dialogID + '"/>');
        dialogElement = $('#' + dialogID);
        dialogElement.attr('title', options.title);
        dialogElement.append('<div class="zdk-image-question"></div>');
        dialogElement.append('<p class="zdk-text-question">' + options.message + '</p>');
        dialogElement.append('<div class="ui-helper-clearfix"/>');
        // Button pane
        dialogElement.append('<div class="pui-dialog-buttonpane ui-widget-content ui-helper-clearfix"/>');
        dialogElement.find('.pui-dialog-buttonpane').append('<button type="button"/>');
        dialogElement.find('.pui-dialog-buttonpane').append('<button type="button"/>');
        var buttonYesElement = dialogElement.find('.pui-dialog-buttonpane > button').eq(0);
        var buttonNoElement = dialogElement.find('.pui-dialog-buttonpane > button').eq(1);
        buttonYesElement.text(options.yesLabel);
        buttonNoElement.text(options.noLabel);
        // Dialog component creation
        dialogElement.puidialog({
            resizable: false,
            showEffect: 'fade',
            hideEffect: 'fade',
            minimizable: false,
            maximizable: false,
            modal: true,
            width: 350,
            visible: true
        });
        // Button components creation
        buttonYesElement.puibutton({icon: 'ui-icon-check'}).click(function () {
            dialogElement.puidialog('hide');
            if (typeof options.callback === "function") {
                options.callback(true);
            }
        });
        buttonNoElement.puibutton({icon: 'ui-icon-close'}).click(function () {
            dialogElement.puidialog('hide');
            if (typeof options.callback === "function") {
                options.callback(false);
            }
        });
        if (options.focusOnYes === true) {
            buttonYesElement.focus();
        } else {
            buttonNoElement.focus();
        }
    },
    /* Insert in the HTML tag <head> the style sheet of the specified file */
    useStyleSheet: function (styleSheetFile) {
        var cssLink = $("<link rel='stylesheet' type='text/css' href='" + styleSheetFile + "'>");
        $("head").append(cssLink);
    },
    /* Insert in the HTML tag <head> the script of the specified file */
    useScriptFile: function (scriptFile) {
        var jsLink = $("<script type='text/javascript' src='" + scriptFile + "'>");
        $("head").append(jsLink);
    },
    /***** Show message when user cancels connection or logs out *****/
    showFinalMessage: function (message) {
        $('#zdk-classic-menu').remove();
        $('#zdk-office-menu').remove();
        $('#zdk-custom-menu').remove();
        $('#zdk-win-container').remove();
        $('#zdk-login-dialog').remove();
        $('.zdk-modal').remove();
        $('.ui-widget-overlay').remove();
        $('#zdk-content').empty();
        $("#zdk-connection-area").addClass("ui-helper-hidden-accessible");
        $("#zdk-help-area").addClass("ui-helper-hidden-accessible");
        $("#zdk-language-area").addClass("ui-helper-hidden-accessible");
        $("#zdk-breadcrumb-text").addClass("ui-helper-hidden-accessible");
        $("#zdk-navi-toolbar").addClass("ui-helper-hidden-accessible");
        $('#default_content').removeClass("ui-helper-hidden");
        $("#default_content").html(message);
        znetdk.setFooterSticky(true);
        $('body').trigger('disconnected');
    },
    /**
     * Set document title according to menu item selection
     * @param {string} label the label to display as title of the page
     */
    addLabelToTitle: function (label) {
        var separator = " | ";
        var newTitle = document.title;
        var sepIndex = newTitle.indexOf(separator);
        if (sepIndex === -1) {
            newTitle = label === null || label === "" ? newTitle : label + separator + newTitle;
        } else {
            newTitle = label === null || label === "" ? newTitle.substring(sepIndex + separator.length) : label + separator + newTitle.substring(sepIndex + separator.length);
        }
        document.title = newTitle;
    },
    /**
     * Show the login or the change password dialog
     * @param {undefined|boolean} changepwd if true, display the change password dialog
     */
    showLoginDialog: function (changepwd) {
        var changePwdValue = changepwd !== undefined && changepwd ? true : false,
                applyOption = function () {
                    $('#zdk-login-dialog').zdklogindialog('option', 'changepwd', changePwdValue);
                };
        if ($('#zdk-login-dialog').length) {
            // Login dialog already exists in the DOM
            applyOption();
            $('#zdk-login-dialog').zdklogindialog('show');
        } else {
            // Login dialog must be added in the DOM
            $('body').append('<div id="zdk-login-dialog" />');
            var defaultLoginName = $('#zdk-connection-area').attr('data-zdk-login');
            $('#zdk-login-dialog').zdklogindialog({loginName: defaultLoginName});
            applyOption();
            // Cancel button action
            $('#zdk-login-dialog').bind("zdklogindialogcancel", function (event, response) {
                znetdk.showFinalMessage(response.msg);
                $('#zdk-login-dialog').zdklogindialog('hide');
            });
            // Login button action
            $('#zdk-login-dialog').bind("zdklogindialogsuccess", function (event, response) {
                $('#zdk-login-dialog').zdklogindialog('hide');
                if (znetdk.isMenuExists()) {
                    // Last expected requests are sent
                    znetdk.requestFromQueue();
                } else {
                    // Menu does not exist, page is reloaded for the authenticated user
                    location.reload();
                }
            });
        }
    },
    /***** Return an object containing the id and label of the selected menu *****/
    getSelectedMenu: function () {
        var selectedMenu = null;
        if ($('#zdk-classic-menu').length) { /* classic tab menu */
            selectedMenu = $('#zdk-classic-menu').zdktabmenu('getSelectedTab');
        } else if ($('#zdk-office-menu').length) { /* office vertical menu */
            selectedMenu = $('#zdk-office-menu').zdkofficemenu('getSelectedMenuItem');
        } else if ($('#zdk-custom-menu').length) { /* custom menu */
            selectedMenu = $('#zdk-custom-menu').zdkgenericmenu('getSelectedMenuItem');
        }
        return selectedMenu; /* Returned value */
    },
    /* Go to the page anchor specified in the page URL */
    goToPageAnchor: function () {
        var pageURL = document.URL;
        var sepIndex = pageURL.lastIndexOf('#');
        if (sepIndex !== -1) {
            var anchor = pageURL.substring(sepIndex);
            var tempLink = $('<a href="' + anchor + '"></a>').appendTo('body');
            tempLink[0].click();
            tempLink.remove();
        }
    },
    /**
     * Init the navigation menu of the application
     */
    initNavigationMenu: function () {
        /* classic tab menu */
        $('#zdk-classic-menu').zdktabmenu({pageReload: znetdk.isPageToBeReloaded()});
        /* office vertical menu */
        $('#zdk-office-menu').zdkofficemenu();
        /* custom menu */
        $('#zdk-custom-menu').zdkgenericmenu({htmlTarget: $("#zdk-content")});
    },
    /**
     * Evaluates whether a menu exists in the DOM of the page or not
     * @returns {Boolean} true if a menu exists in the DOM else false
     */
    isMenuExists: function () {
        return Boolean($('#zdk-classic-menu').length || $('#zdk-office-menu').length
                || $('#zdk-custom-menu').length);
    },
    /**
     * Evaluates whether authentication is required or not once the page is loaded 
     * @returns {Boolean} 
     */
    isAuthenticationRequired: function () {
        var isDefaultContentHidden = $('#default_content').hasClass("ui-helper-hidden");
        return (!znetdk.isMenuExists() && isDefaultContentHidden);
    },
    /**
     * Evaluates if the page must be reloaded when the user clicks on a menu item
     * @returns {Boolean}
     */
    isPageToBeReloaded: function () {
        return ($('#zdk-classic-menu').hasClass('zdk-pagereload')
                || $('#zdk-office-menu').hasClass('zdk-pagereload')
                || $('#zdk-custom-menu').hasClass('zdk-pagereload'));
    },
    /**
     * Returns the jQuery object of the element matching the ID specified in the
     * attribute of a HTML element
     * @param {jQuery} attribElement jQuery element in which the element ID is to be read
     * @param {String} attribute name of the attribute containing the element ID. 
     * @returns {jQuery|Boolean} jQuery object of the element found or false if 
     * no element matches
     */
    getElementFromAttr: function (attribElement, attribute) {
        /* Read html attribute */
        var attribText = attribElement.attr(attribute);
        if (attribText !== undefined) {
            var element = $('#' + attribText);
            if (element.length) {
                return element;
            } else {
                return false;
            }
        } else {
            return false;
        }
    },
    /**
     * Returns the value of the specified element attribute
     * @param {jQuery} attribElement jQuery object of an HTML element
     * @param {String} attribute name of the attribute to read
     * @returns {String|Boolean} value of the attribute or false if no value is found
     */
    getTextFromAttr: function (attribElement, attribute) {
        /* Read html attribute */
        var attribText = attribElement.attr(attribute);
        if (attribText !== undefined) {
            return attribText;
        } else {
            return false;
        }
    },
    /*
     * Returns the controller and the action specified for the attribute
     * "data-zdk-action" of a HTML element
     * @param {jQuery} attribElement jQuery object of the element to be read
     * @returns {Object|Boolean} Object containing the names of the controller 
     * and action or false if the attribute "data-zdk-action" does not exist.
     */
    getActionFromAttr: function (attribElement) {
        var actionAttrib = attribElement.attr('data-zdk-action');
        if (actionAttrib !== undefined) {
            var actionArray = actionAttrib.split(":");
            var result = new Object();
            result.controller = actionArray[0];
            result.action = actionArray[1];
            return result;
        } else {
            return false;
        }
    },
    /**
     * Retrieves the value stored in the web browser local storage for the
     * specified key
     * @param {String} storageKey Identifier of the value stored locally
     * @returns {DOMString|Boolean} The value found or false if the value does
     * not exist.
     */
    getLocalSettings: function(storageKey) {
        try {
            var storedValue = localStorage.getItem(storageKey);
            if(storedValue) {
                return storedValue;
            } else {
                return false;
            }
        } catch(e) {
            console.log("ZnetDK 'api.js': local storage not supported by the browser!");
            return false;
        }
    },
    /**
     * Stores in the web browser local storage the specified value
     * @param {String} storageKey Identifier of the value stored locally
     * @param {String} value The value to store
     * @returns {Boolean} true if the storage succeeded, false otherwise.
     */
    storeLocalSettings: function(storageKey, value) {
        try {
            localStorage.setItem(storageKey, value);
            return true;
        } catch(e) {
            console.log("ZnetDK 'api.js': local storage not supported by the browser!");
            return false;
        }
    },
    /**
     * Sticks the footer at the bottom of the browser client area when the 
     * content of the page is little filled in.
     * @param {undefined|Boolean} fixed indicate if the footer must be fixed at
     * the bottom of the browser client area. By default (undefined), the footer
     * is not fixed.
     */
    setFooterSticky: function (fixed) {
        if ($("#zdk-footer").hasClass('zdk-sticky')) {
            var isFixed = fixed === undefined ? false : fixed;
            if (isFixed) {
                $('#zdk-footer').css({'position': 'fixed', 'bottom': '0'});
            } else {
                var minHeightContent,
                        pageHeight = $(window).height(),
                        contentPosition = $("#zdk-content").offset(),
                        contentExtraHeight = $("#zdk-content").css(["borderTopWidth", "marginTop", "paddingTop"]),
                        footerHeight = $("#zdk-footer").outerHeight(true);
                minHeightContent = pageHeight - footerHeight - contentPosition.top - 1;
                minHeightContent -= parseFloat(contentExtraHeight.borderTopWidth) + parseFloat(contentExtraHeight.marginTop)
                        + parseFloat(contentExtraHeight.paddingTop);
                $("#zdk-content").css('min-height', minHeightContent + 'px');
            }
        }
    }
};