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
 * ZnetDK form widget
 *
 * File version: 1.6
 * Last update: 04/14/2017
 */

/**
 * ZnetDK form widget
 */
$.widget("znetdk.zdkform", {
    options: {
        controller: null, /* ZnetDK PHP controller name */
        action: null, /* ZnetDK PHP action name */
        complete: null, /* Call back function triggered on success Ajax response */
        failed: null, /* Call back function triggered on failed Ajax response */
        initdone: null, /* Call back function triggered after form initialization */
        ready: null, /* Call back function triggered after form instantiation */
        msgsuccess: true   /* Show message when form is successfully submited */
    },
    /**
     * Instantiates the form widget.
     * The input elements into the form are automatically instantiated and styled.
     */
    _create: function () {
        /***** Controller and action set in the data-zdk-action ******/
        var actionAttrib = this.element.attr('data-zdk-action');
        if (actionAttrib !== undefined) {
            var actionArray = actionAttrib.split(":");
            this.options.controller = actionArray[0];
            this.options.action = actionArray[1];
        }
        /***************** Create Error message area ********************/
        this.formErrorMsgArea = $('<p class="ui-helper-hidden ui-state-error ui-corner-all" style="padding:5px;margin:0 0 7px;"/>').prependTo(this.element);
        this.formErrorMsgArea.append('<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>');
        this.formErrorMsgArea.append('<span class="error_message"/>');
        this.isErrorMsgDisplayed = false;
        this.firstElementNameInError = "";
        /***************** Bind form events ****************/
        this._bindEvents();
        /***************** Surround form entries (couple label/text field) with div element for style purpose *************/
        this.element.find('input:not(:radio),textarea,.zdk-radiobuttongroup,.zdk-listbox,.zdk-dropdown,.zdk-textvalue,.zdk-inputhtml,.zdk-multiupload').each(function () {
            var labelElement = $(this).prev();
            if (labelElement.is('label')) {
                var extraElement = null;
                if ($(this).prop('required') === true || $(this).attr('data-required') === 'true') {
                    // An asterisk is added to the label for a required input element
                    labelElement.addClass('zdk-required');
                }
                if ($(this).is("textarea,:checkbox,.zdk-radiobuttongroup,.zdk-listbox, .zdk-dropdown, :file, .zdk-inputhtml, .zdk-multiupload")
                        && !$(this).parent().hasClass('zdk-inputrows')) {
                    labelElement.addClass('zdk-align-top');
                }
                if ($(this).is(":checkbox")) {
                    var spanElement = $(this).next();
                    if (spanElement.is('span')) {
                        extraElement = spanElement;
                    }
                }
                if (!$(this).parent().hasClass('zdk-form-entry')
                        && !$(this).parent().hasClass('zdk-inputrows')) {
                    var contentToWrap = extraElement === null ? 
                        $(this).add(labelElement): 
                        $(this).add(labelElement).add(extraElement);
                    contentToWrap.wrapAll('<div class="zdk-form-entry"/>');
                }
            }
        });
        /***************** Surround buttons with div element for style purpose *************/
        this.element.children('button').wrapAll('<div class="zdk-form-buttonpane ui-widget-content ui-helper-clearfix"/>');
        var buttonPane = this.element.children('.zdk-form-buttonpane'),
            buttonPanePrevElem = buttonPane.prev();
        if (buttonPanePrevElem.length && buttonPanePrevElem.is('fieldset')) {
            buttonPane.css({'border-width':'0','margin':0});
        }
        /************* Init PrimeUI widgets for common input elements in the form **********/
        znetdk.initCommonWidgets(this.element);
        /******************** The Data form are not Modified ********************/
        this.formDataModified = false;
        /************ 'zdkformready' event raised (all widgets are instantiated) ***********/
        this._trigger("ready");
    },
    /**
     * Binds all the events managed by the form widget:
     * - 'submit' event of the form
     * - 'reset' event of the form
     * - 'invalid' event of the input widgets
     * - 'change' event of the input widgets
     */
    _bindEvents: function() {
        var $this = this;
        /******************** Bind submit event to the form *******************/
        this.element.on('submit.zdkform', this, this._submit);
        /******************** Bind reset event to the form ********************/
        this.element.on('reset.zdkform', this, this._reset);
        /**************** Bind invalid event to the form elements *************/
        if (this._html5validation()) { /* HTML5 form validation is supported */
            this.element.find('input,.zdk-listbox,textarea').each(function () {
                $(this).uniqueId().on('invalid.zdkform_' + $(this).attr('id'), $this, $this._setControlInvalid)
                        .on('change.zdkform_' + $(this).attr('id'), $this, $this._setControlValid);
            });
        }
        /******************** Bind dropdown change events *********************/
        this.element.find('.zdk-dropdown').each(function () {
           $(this).uniqueId().bind('zdkdropdownchange.zdkform_' + $(this).attr('id'), $this, $this._setControlValid);
        });
        /******************** Bind inputhtml change events *********************/
        this.element.find('.zdk-inputhtml').each(function () {
           $(this).uniqueId().bind('zdkinputhtmlchange.zdkform_' + $(this).attr('id'), $this, $this._setControlValid);
        });
        /******************** Bind multiupload selection change events *********************/
        this.element.find('.zdk-multiupload').each(function () {
           $(this).uniqueId().bind('zdkmultiuploadselect.zdkform_' + $(this).attr('id'), $this, $this._setControlValid);
        });
        /************** Bind autocomplete selection change events *************/
        this.element.find('.zdk-autocomplete').each(function () {
           $(this).uniqueId().bind('zdkautocompleteselect.zdkform_' + $(this).attr('id'), $this, $this._setControlValid);
        });
        /********** Bind initialization events of the inputrows widget ********/
        this.element.find('.zdk-inputrows').bind('zdkinputrowsinitialized', function() {
            $(this).find('tr:gt(0) input').each(function () {
                $(this).uniqueId().on('invalid.zdkform_' + $(this).attr('id'), $this, $this._setControlInvalid)
                        .on('change.zdkform_' + $(this).attr('id'), $this, $this._setControlValid);
            });
        });
        /*************** Bind row events of the inputrows widget **************/
        this.element.find('.zdk-inputrows')
            .bind('zdkinputrowsnewrow', function(event, row) { // New row
            $(row).find('input').each(function () {
                $(this).uniqueId().on('invalid.zdkform_' + $(this).attr('id'), $this, $this._setControlInvalid)
                        .on('change.zdkform_' + $(this).attr('id'), $this, $this._setControlValid);
            });
        }).bind('zdkinputrowsremoved', function() { // Filled Row removed
            $this.formDataModified = true;
        }).bind('zdkinputrowserased', function() { // Filled Row erased 
            $this.formDataModified = true;
        });
    },
    /**
     * Empties the data in the form.
     * This method raises the standard JavaScript 'reset' event for the form.  
     */
    reset: function () {
        // The reset event is fired for the form
        this.element[0].reset();
        // The data form state is reset to not modified
        this.formDataModified = false;
    },
    /**
     * Initializes the data in the form from the specified values in parameter.
     * The form is reset before being initalized.
     * The 'initdone' event is triggered when initialization is terminated.
     * @param {Object} values Object where each property matches the name of the 
     * input to initialize and where its associated value matches the value 
     * entered for the input.
     */
    init: function (values) {
        // Reset form values and items selection
        this.reset();
        // Load values in the form
        if (values !== undefined) {
            this.setValues(values);
            this._trigger("initdone");
        }
    },
    /**
     * Sets the data in the form from the specified values in parameter.
     * The form is NOT reset before being initalized.
     * @param {Object} values Object where each property matches the name of the 
     * input to initialize and where its associated value matches the value 
     * entered for the input.
     */
    setValues: function(values) {
        if (values !== undefined) {
            var property, inputElement;
            for (property in values) {
                inputElement = this.element.find(":input,.zdk-listbox,.zdk-tree,.zdk-dropdown,.zdk-textvalue,.zdk-inputhtml,.zdk-multiupload")
                        .filter("[name='" + property + "'],[data-name='" + property + "']");
                if (inputElement.length) {
                    if (inputElement.attr("type") === "checkbox") {
                        if (inputElement.val() === values[property]) {
                            inputElement.puicheckbox('check');
                        } else {
                            inputElement.puicheckbox('uncheck');
                        }
                    } else if (inputElement.attr("type") === "radio") {
                        inputElement.puiradiobutton('select', values[property]);
                    } else if (inputElement.hasClass("zdk-listbox")) {
                        var selectedOptions = values[property];
                        inputElement.zdklistbox('selectItemsByValues', selectedOptions);
                    } else if (inputElement.hasClass("zdk-dropdown")) {
                        var selectedOption = values[property];
                        inputElement.zdkdropdown('selectValue', selectedOption);
                    } else if (inputElement.hasClass('zdk-tree')) {
                        inputElement.zdktree('selectNodes', values[property]);
                    } else if (inputElement.hasClass('zdk-date')) {
                        inputElement.zdkinputdate('setW3CDate', values[property]);
                    } else if (inputElement.hasClass('zdk-inputhtml')) {
                        inputElement.zdkinputhtml('setValue', values[property]);
                    } else if (inputElement.hasClass('zdk-multiupload')) {
                        inputElement.zdkmultiupload('setUploadedFiles', values[property]);
                    } else if (inputElement.hasClass('zdk-textvalue')) {
                        inputElement.text(values[property]);
                    } else {
                        inputElement.val(values[property]);
                    }
                }
            }
        }
    },
    /**
     * Checks if the web browser is compatible with HTML5 form validation.
     * @returns {Boolean} true if HTML5 form validation is supported by the web
     * browser, false otherwise.
     */
    _html5validation: function () {
        return 'noValidate' in document.createElement('form');
    },
    /**
     * Event handler of the 'invalid' event triggered when the data validation
     * failed for an input in the form.
     * This method evaluates the custom error message to be displayed according
     * to the type of error detected ('validity' property value set to true) and
     * display the appropriate message.   
     * @param {type} event Contains the context of the form to access to its
     * properties and methods
     */
    _setControlInvalid: function (event) {
        var message = '';
        if (this.validity.valueMissing && $(this).attr("data-zdkerrmsg-required") !== undefined) {
            message = $(this).attr("data-zdkerrmsg-required");
        } else if (this.validity.valueMissing && event.data._getRequiredMsgFromAttr()) {
            message = event.data._getRequiredMsgFromAttr();
        } else if (this.validity.typeMismatch && $(this).attr("data-zdkerrmsg-type") !== undefined) {
            message = $(this).attr("data-zdkerrmsg-type");
        } else if (this.validity.rangeUnderflow && $(this).attr("data-zdkerrmsg-min") !== undefined) {
            message = $(this).attr("data-zdkerrmsg-min");
        } else if (this.validity.rangeOverflow && $(this).attr("data-zdkerrmsg-max") !== undefined) {
            message = $(this).attr("data-zdkerrmsg-max");
        } else if (this.validity.patternMismatch && $(this).attr("data-zdkerrmsg-pattern") !== undefined) {
            message = $(this).attr("data-zdkerrmsg-pattern");
        } else if (this.validity.stepMismatch && $(this).attr("data-zdkerrmsg-step") !== undefined) {
            message = $(this).attr("data-zdkerrmsg-step");
        } else {
            message = this.validationMessage;
        }
        event.preventDefault();
        event.data._showErrorMessage(message, event.data._getElementNameInError($(this)));
    },
    /**
     * Returns the name of the input element detected in error during the form
     * validation. When several elements exist with the same name, the position
     * of the element is added to the name (i.e "myinput:0") for marking the
     * right element in error.
     * @param {jQuery} elementInError The element detected in error
     * @returns {String} The name of the element ('name' HTML attribute) with
     * its position in suffix if several elements exist with the same name. 
     */
    _getElementNameInError: function(elementInError) {
        var elementName = elementInError.attr("name"),
            elementsWithSameName = this.element.find("[name='"  + elementName + "']"),
            elementPosition = 0;
        if (elementsWithSameName.length > 1) {
            elementsWithSameName.each(function(index){
                if ($(this).is(elementInError)) {
                    elementPosition = index;
                    return false;
                }
            });
            elementName = elementName + ':' + elementPosition;
        }
        return elementName;
    },
    /**
     * Event handler for the 'change' event triggered by the form inputs.
     * The red outline is removed to the input widget and the form error context
     * is reset.
     * @param {Event} event Contains the context of the form to access to its
     * properties.
     */
    _setControlValid: function (event) {
        if (event.type === 'zdkdropdownchange') {
            $(this).parents('.pui-dropdown').removeClass('znetdk_error');
        } else if ($(this).is(':file')) {
            $(this).next('.pui-button').removeClass('znetdk_error');
        } else if (event.type === 'zdkmultiuploadselect') {
            $(this).children('.pui-button').removeClass('znetdk_error');
        } else {
            $(this).removeClass('znetdk_error');
        }
        event.data.isErrorMsgDisplayed = false;
        event.data.firstElementNameInError = "";
        event.data.formDataModified = true;
        event.data._hideErrorMessage(false, function(){});
    },
    /**
     * Returns the HTML element in error matching the specified element name.
     * When the specified name matches several input elements (its name is 
     * suffixed by '[]') and a position is specified (the name ends by ':99' 
     * where 99 is the position), the element matching the indicated position
     * is returned.
     * The position of the input element in error is returned by the web server
     * into the 'response.ename' property.
     * @param {string} elementName
     * @returns {jQuery} The jQuery element matching the specified element name
     * in parameter. 
     */
    _getElementInError: function (elementName) {
        if (elementName === undefined) {
            return undefined;
        }
        var namePieces = elementName.split(':',2);
        if (namePieces.length === 2 && $.isNumeric(namePieces[1]) 
                && Math.floor(namePieces[1]) == namePieces[1]
                && this.element.find("[name='" + namePieces[0] + "']").eq(namePieces[1]).length === 1) {
            return this.element.find("[name='" + namePieces[0] + "']").eq(namePieces[1]);
        } else {
            return this.element.find("[name='" + elementName + "'],[data-name='" + elementName + "']").first();
        }
    },
    /**
     * Displays into the form the error message for the input that has not been
     * been validated.
     * @param {String} message Text of the message to display.
     * @param {String} elementName Name of the input in error ('name' attribute).
     */
    _showErrorMessage: function (message, elementName) {
        if (elementName===undefined && message===undefined) {
            return;
        }
        if (this.firstElementNameInError === "") {
            this.firstElementNameInError = elementName;
        }
        if (this.firstElementNameInError === elementName) {
            this.formErrorMsgArea.children('span.error_message').html(message);
            var element = this._getElementInError(elementName),
                $this = this;
            this.formErrorMsgArea.slideDown(400, function() {
                $this._setFocusOnFieldInError(element);
            });
            if (!this.isErrorMsgDisplayed) {
               this.isErrorMsgDisplayed = true;
            }
        }
    },
    /**
     * Sets the focus to the specified input element and surround it in red.  
     * @param {jQuery object} element Input element for which the focus is to set.
     */
    _setFocusOnFieldInError: function(element) {
        if (element === undefined) {
            return;
        }
        var elementForFocus;
        if (element.hasClass('zdk-dropdown')) {
            elementForFocus = element.parents('.pui-dropdown');
            element.zdkdropdown('setFocus');
        } else if (element.is(':file')) {
            elementForFocus = element.next('.pui-button');
            element.zdkinputfile('setFocus');
        } else if (element.hasClass('zdk-multiupload')) {
            elementForFocus = element.children('.pui-button').first();
            element.zdkmultiupload('setFocus');
        } else {
            elementForFocus = element;
            elementForFocus.focus();
            if (element.is('input:not(:radio):not(:checkbox)')) {
                elementForFocus.select();
            }
        }
        elementForFocus.addClass('znetdk_error');
    },
    /**
     * Hides the error message displayed in the form when data validation has
     * failed.
     * @param {Boolean} immediate Indicates whether the message has to be hidden
     * immediately (true) or with animation (false).
     * @param {function} callback Call back function to call when aninmation is
     * terminated.
     */
    _hideErrorMessage: function (immediate, callback) {
        this.isErrorMsgDisplayed = false;
        if (immediate) {
            this.formErrorMsgArea.hide();
        } else {
            this.formErrorMsgArea.slideUp(200, callback);
        }
    },
    /**
     * Returns the error message set for the form and to be displayed when a 
     * required input is not filled out.
     * @returns {String|Boolean} message set for the attribute 'data-zdkerrmsg-required'
     * or false if the attribute is not set.
     */
    _getRequiredMsgFromAttr: function () {
        return znetdk.getTextFromAttr(this.element,'data-zdkerrmsg-required');
    },
    /**
     * Indicates whether the entry form validation is required or not, according
     * to the 'novalidate=""' property is set or not. 
     * @returns {Boolean} true if form validation is required, otherwise false.
     */
    _isFormValidationRequired: function() {
        var novalidatePropVal = this.element.attr('novalidate');
        return  novalidatePropVal === undefined ? true : false;
    },
    /**
     * Validates the format of the date entered in the input date widget. 
     * The error message set for the inputdate widget (attribute
     * 'data-zdkerrmsg-date')is displayed if the date format is invalid.
     * This method is called when the form is submitted.
     * @returns {Boolean} true if validation is OK otherwise false.
     */
    _validateDate: function() {
        if (!this._isFormValidationRequired()) {
            return true; // No validation required
        }
        var $this = this, result = true;
        this.element.find('input.zdk-date').each(function () {
            if (!$(this).zdkinputdate('checkDate')) {
                var errorMessage = $(this).attr("data-zdkerrmsg-date");
                if (errorMessage === undefined) {
                    errorMessage = 'Date format is invalid!';
                }
                $this.isErrorMsgDisplayed = false;
                $this._showErrorMessage(errorMessage, $(this).attr("name"));
                result = false;
                return false; // Ends the loop
            }
        });
        return result;
    },
    /**
     * Checks if an option has been selected in the dropdown widget when the
     * 'required' property is set. 
     * The error message set for the dropdown widget or for the form (attribute
     * 'data-zdkerrmsg-required')is displayed if no option is selected.
     * This method is called when the form is submitted.
     * @returns {Boolean} true if validation is OK otherwise false.
     */
    _validateDropdown: function() {
        if (!this._isFormValidationRequired()) {
            return true; // No validation required
        }
        var $this = this, result = true;
        this.element.find('select.zdk-dropdown[required]').each(function () {
            if (!$(this).zdkdropdown('isValueSelected')) {
                var errorMessage = $(this).attr("data-zdkerrmsg-required");
                if (errorMessage === undefined) {
                    errorMessage = $this._getRequiredMsgFromAttr();
                    if (errorMessage === false) {
                        errorMessage = 'A value must be selected!';
                    }
                }
                $this.isErrorMsgDisplayed = false;
                $this._showErrorMessage(errorMessage, $(this).attr("name"));
                result = false;
                return false; // Ends the loop
            }
        });
        return result;
    },
    /**
     * Checks if a text has been entered in the Imputhtml widget when the
     * 'data-required' property is set.
     * The error message set for the widget or for the form (attribute
     * 'data-zdkerrmsg-required') is displayed if no text is entered.
     * This method is called when the form is submitted.
     * @returns {Boolean} true if validation is OK otherwise false.
     */
    _validateInputHtml: function() {
        if (!this._isFormValidationRequired()) {
            return true; // No validation required
        }
        var $this = this, result = true;
        this.element.find('div.zdk-inputhtml[data-required=true]').each(function () {
            if ($(this).zdkinputhtml('isEmpty')) {
                var errorMessage = $(this).attr("data-zdkerrmsg-required");
                if (errorMessage === undefined) {
                    errorMessage = $this._getRequiredMsgFromAttr();
                    if (errorMessage === false) {
                        errorMessage = 'A value must be entered!';
                    }
                }
                $this.isErrorMsgDisplayed = false;
                $this._showErrorMessage(errorMessage, $(this).attr("data-name"));
                result = false;
                return false; // Ends the loop
            }
        });
        return result;
    },
    /**
     * Checks if a file has been selected in the Multipleupload widget when the
     * 'data-required' property is set. 
     * The error message set for the widget or for the form (attribute
     * 'data-zdkerrmsg-required') is displayed if no file is selected.
     * This method is called when the form is submitted.
     * @returns {Boolean} true if validation is OK otherwise false.
     */ 
    _validateMultiUpload: function() {
        if (!this._isFormValidationRequired()) {
            return true; // No validation required
        }
        var $this = this, result = true;
        this.element.find('div.zdk-multiupload[data-required=true]').each(function () {
            if ($(this).zdkmultiupload('isEmpty')) {
                var errorMessage = $(this).attr("data-zdkerrmsg-required");
                if (errorMessage === undefined) {
                    errorMessage = $this._getRequiredMsgFromAttr();
                    if (errorMessage === false) {
                        errorMessage = 'At least one file must be selected!';
                    }
                }
                $this.isErrorMsgDisplayed = false;
                $this._showErrorMessage(errorMessage, $(this).attr("data-name"));
                result = false;
                return false; // Ends the loop
            }
        });
        return result;
    },
    /**
     * Validates additional form inputs that are not directly validated by the 
     * HTML5 form validation mechanism.
     * The value entered for the widgets 'zdkinputdate' and 'zdkdropdown' are 
     * validated by the widget itself.
     * For browsers not compatibles with HTML5, the inputs with the 'required'
     * property are checked by this method.
     * @returns {Boolean} true when form data are validated, otherwise false.
     */
    _validate: function () {
        if (!this._validateDate() || !this._validateDropdown()
                || !this._validateInputHtml() || !this._validateMultiUpload()) {
            return false;
        }
        if (this._html5validation()) {
            //HTML5 form validation support
            return true;
        } else {
            // IE9 and below : checkValidity() not supported
            // Server side data validation is required...
            // Only check if value is required...
            if (!this._isFormValidationRequired()) {
                return true; // No validation required
            }
            var $this = this, result = true;
            this.element.find('input[required]').each(function () {
                if ($(this).val() === '') {
                    var errorMessage = $(this).attr("data-zdkerrmsg-required");
                    if (errorMessage === undefined) {
                        errorMessage = $this._getRequiredMsgFromAttr();
                        if (errorMessage === false) {
                            errorMessage = 'This is a required field!';
                        }
                    }
                    $this.isErrorMsgDisplayed = false;
                    $this._showErrorMessage(errorMessage, $(this).attr("name"));
                    result = false;
                    return false;
                }
            });
            return result;
        }
    },
    /**
     * Provides the form data thru an array of objects where each object contains
     * the following properties:
     * - 'name': name of the input widget in the form ('name' HTML attribute value)
     * - 'value' value typed in the input widget.
     * @param {Boolean} getEmptyInputs true if empty input values must be also
     * returned. By default, only no empty values are returned. 
     * @returns {Array} Array of objects with 'name' & 'value' properties.
     */
    getFormData: function (getEmptyInputs) {
        // Get values of the form
        var formData = new Array(),
            getAll = getEmptyInputs === undefined ? false : Boolean(getEmptyInputs);
        this.element.find("input[type!='radio'],textarea,select,.zdk-tree,.zdk-radiobuttongroup,.zdk-inputhtml,button:submit:focus")
                .filter("[name],[data-name]").each(function () {
            var noValue = "";
            var elementName = $(this).attr('name') === undefined ? $(this).attr('data-name') : $(this).attr('name');
            if (elementName !== "" && elementName !== undefined) {
                if ($(this).hasClass('zdk-date')) {
                // Date values are returned in W3C format
                    if ($.trim($(this).val()) !== "" || getAll) {
                        formData.push({name: elementName, value: $(this).zdkinputdate('getW3CDate')});
                    }
                } else if ($(this).hasClass('zdk-listbox')) {
                // Multiple selection for listbox
                    var selectedOptions = $(this).val();
                    if (selectedOptions === null && getAll) {
                        formData.push({name: elementName, value: noValue});
                    } else if (selectedOptions !== null && $.type(selectedOptions) === 'array') {
                        for (i = 0; i < selectedOptions.length; ++i) {
                            formData.push({name: elementName, value: selectedOptions[i]});
                        }
                    }
                } else if ($(this).hasClass('zdk-dropdown')) {
                    if ($(this).val() !== "_" || getAll) {
                        formData.push({
                            name: elementName,
                            value: ($(this).val() === "_" ? noValue : $(this).val())
                        });
                    }
                }
                else if ($(this).is(':checkbox')) {
                // Check box
                    if ($(this).is(':checked') || getAll) {
                        var checkedValue = $(this).is(':checked') ? $(this).val() : noValue;
                        formData.push({name: elementName, value: checkedValue});
                    }
                } else if ($(this).hasClass('zdk-radiobuttongroup')) {
                // Radio buttons
                    var checkedValue = $(this).find(':checked').val();
                    if (checkedValue !== undefined || getAll) {
                        formData.push({
                            name: elementName,
                            value: (checkedValue === undefined ? noValue : checkedValue)
                        });
                    }
                } else if ($(this).hasClass('zdk-tree')) {
                // Selected nodes of tree components
                    var selectedNodes = $(this).zdktree('getSelection');
                    if (selectedNodes.length || getAll) {
                        formData = formData.concat(selectedNodes);
                    }
                } else if ($(this).hasClass('zdk-inputhtml')) {
                    if (!$(this).zdkinputhtml('isEmpty') || getAll) {
                        formData.push({name: elementName, value: $(this).zdkinputhtml('getValue')});
                    }
                } else if ($(this).is(':file')) {
                    if ($(this).val() !== "" || getAll) {
                        formData.push({name: elementName, value: $(this).val()});                        
                    }
                } else if ($(this).is(':button')) {
                    if ($(this).val() !== "" || getAll) {
                        formData.push({name: elementName, value: $(this).val()});                        
                    }
                } else if (getAll || !$(this).is(':disabled') || // enabled or disabled but filled in...
                        ($(this).is(':disabled') && $.trim($(this).val()) !== "")) {
                    if ($.trim($(this).val()) !== "" || getAll) {
                        formData.push({name: elementName, value: $.trim($(this).val())});
                    }
                } 
            }
        });
        return formData;
    },
    /**
     * Shows a custom error message and highlight the specified field in error
     * @param {String} $message Text of the error message
     * @param {String} $fieldInError Name of the field in error
     */
    showCustomError: function($message, $fieldInError) {
        this._submitFailed({msg:$message,ename:$fieldInError});  
    },
    /**
     * Indicates whether the form data have been modified
     * @returns {Boolean} Value true if the form data have been modified, false
     * otherwise 
     */
    isFormModified: function() {
        return this.formDataModified;
    },
    /**
     * Changes the modification state of the form
     * @param {boolean} isModified When true, the form data state is set to
     * 'modified', otherwise to 'not modified'  
     */
    setFormModified: function(isModified) {
        this.formDataModified = isModified ? true : false;
    },
    /**
     * Reset event handler of the form.
     * This method is called when an input or a button of type 'reset' is pressed
     * down into the form.
     * It empties all the input form widgets, reset their validation error state
     * and the error message eventually displayed is hidden.
     * @param {Event} event Event object containing the widget context to access
     * to its methods and properties.
     */
    _reset: function(event) {
        var $this = event.data;
        // Remove znetdk_error class
        $this.element.find('.znetdk_error').removeClass('znetdk_error');
        // Reset 'firstElementNameInError'
        $this.firstElementNameInError = "";
        // Reset checkboxes to their default state
        $this.element.find(':checkbox').each(function () {
            if ($(this).is('[checked]')) {
                $(this).puicheckbox('check');
            } else {
                $(this).puicheckbox('uncheck');
            }
        });
        // Reset radio buttons to default selection
        $this.element.find('.zdk-radiobuttongroup').each(function () {
            $(this).zdkradiobuttongroup('resetSelection');
        });
        // Reset Listboxes to default selection
        $this.element.find('select.zdk-listbox').each(function () {
            $(this).zdklistbox('resetSelection');
        });
        // Select default item of the Dropdown components
        $this.element.find('select.zdk-dropdown').each(function () {
            $(this).zdkdropdown('resetSelection');
        });
        // Unselect Tree components
        $this.element.find('.zdk-tree').each(function () {
            $(this).zdktree('unselectAllNodes');
        });
        // Unselect InputFile components
        $this.element.find(':file').each(function () {
            $(this).zdkinputfile('reset');
        });
        // Empty Multiupload components
        $this.element.find('.zdk-multiupload').each(function () {
            $(this).zdkmultiupload('reset');
        });
        // Empty InputHtml widgets
        $this.element.find('.zdk-inputhtml').each(function () {
            $(this).zdkinputhtml('empty');
        });
        // Empty the read only text values
        $this.element.find('.zdk-textvalue').each(function () {
            $(this).text('');
        });
        // Empty inputs, textareas (for compatibility with IE11) and hidden inputs
        $this.element.find('input[name],textarea').not(':radio')
                .not(':checkbox').not(':file').each(function () {
            $(this).val(null);
        });
        // Hide error message if displayed
        $this._hideErrorMessage(false,function() {
            // Set focus to the first element into the form
            $this.element.find('input[type!=hidden]:visible:enabled:not(:radio),input:radio:visible:enabled:checked,select:visible:enabled').first().focus();
        });
        $this._trigger("resetdone");
        return false;
    },
    /**
     * Executes complementary operations when form has been submitted successfully.
     * - A 'complete' event is triggered,
     * - The datatable linked to the form is refreshed,
     * - The last error message displayed in the form is hidden,
     * - The server response is displayed.
     * @param {Object} response Response returned by the controller action.
     */
    _submitSucceeded: function(response) {
        this.formDataModified = false;
        var $this = this;
        this._hideErrorMessage(false, function () {
            $this._trigger("complete", $this, response);
            var datatable = znetdk.getElementFromAttr($this.element, 'data-zdk-datatable');
            if (datatable !== false) {
                datatable.zdkdatatable('option','keepSelectionOnId', $this._getRowIdParamName());
                datatable.zdkdatatable('refresh');
            }
        });
        if (response !== undefined && response.summary && response.msg && this.options.msgsuccess) {
            var levelMsg = response.warning === true ? 'warn' : 'info';
            znetdk.message(levelMsg, response.summary, response.msg);
        }
    },
    /**
     * Returns the column name set for the ZnetDK datatable to store the row
     * identifier. This column is commonly named 'id'. Otherwise, the column
     * name is evaluated by finding in the data form, the input field set with
     * the 'zdk-row-id' HTML attribute  
     * @returns {String} Name of the datatable column containing the row 
     * identifier.
     */
    _getRowIdParamName: function () {
        var idName = 'id', inputElement = this.element.find('input.zdk-row-id');
        if (inputElement.length) {
            var idNameFound = znetdk.getTextFromAttr(inputElement, 'name');
            idName = idNameFound ? idNameFound : idName;
        }
        return idName;
    },
    /**
     * Executes complementary operations when form submitting has failed.
     * - A 'failed' event is triggered,
     * - The server response is displayed in the form.
     * @param {Object} response Response returned by the controller action.
     */
    _submitFailed: function(response) {
        this.firstElementNameInError = "";
        this.isErrorMsgDisplayed = false;
        this._showErrorMessage(response.msg, response.ename);
        this.isErrorMsgDisplayed = false;
        this._trigger("failed", this, response);
    },
    /**
     * Submits to the server in AJAX the form data excepted the files to
     * be uploaded (see '_submitFilesToUpload' method). 
     * @returns {Boolean} Value true if data is sent to the remote controller
     * thru an AJAX request, otherwise false.
     */
    _submitFormData: function() {
        if (this.options.controller && this.options.action) {
            var $this = this, allFormData = this.getFormData(true),
                submitElement = this.element.find(':submit');
            if (allFormData.length === 0 && submitElement.length === 0) {
                return false; // No data sent to the remote controller
            }
            // Empty values are sent to the server when a zdkinputrows widget
            // exists in the form (empty values are validated by the server)
            var fullData = this.element.find('.zdk-inputrows').length > 0;
            var formData = this.getFormData(fullData);
            znetdk.request({
                control: this.options.controller,
                action: this.options.action,
                data: formData,
                callback: function (response) {
                    if (response.success) {
                        $this._submitSucceeded(response);
                    } else {
                        $this._submitFailed(response);
                    }
                }
            });
            return true; // Data is sent to the remote controller
        }
        return false; // No data sent to the remote controller
    },
    /**
     * Submits to the server in AJAX the files to upload
     * @param {Integer} index Index of the InputFile widget for which a file is
     * to upload. If undefined, 0 is retained by default.
     * @param {Object} successResponse Last success response returned by the 
     * server after file upload.
     */
    _submitFilesToUpload: function(index, successResponse) {
        if (index === undefined) {
            index = 0;
        }
        var inputElement = this.element.find(':file:not(.zdk-nosubmit)').slice(index,index+1);
        if (inputElement.length === 0) { // No more file to upload
            if (this._submitFormData() === false) { // Remote action is not called
                 // Success response of the last upload or undefined if no upload done
                this._submitSucceeded(successResponse);
            };
        } else if (inputElement.zdkinputfile('isFileSelected')) {
            var $this = this;
            try {
                inputElement.zdkinputfile('upload',function(response) {
                    if (response.success) {
                        $this._submitFilesToUpload(index+1, response);
                    } else {
                        $this._submitFailed(response);
                    }
                });
            } catch(error) {
                $this._submitFailed({msg:error.message,ename:inputElement.attr('name')});
            }
        } else { // No file selected (input not required)
            this._submitFilesToUpload(index+1, successResponse);
        } 
    },
    /**
     * Submit event handler of the form.
     * This method is called when an input or a button of type 'submit' is pressed
     * down into the form, only if the HTML5 form validation succeeds or directly
     * if the web browser is not compatibles with HTML5.
     * Calls the server-side controller action set in the options of the widget.
     * The 'complete' event is sent when the form validation succeeds and the
     * 'failed' event is sent when the remote controller action execution has
     * failed.
     * @param {Event} event Event object containing the widget context to access
     * to its methods and properties.
     */
    _submit: function (event) {
        var $this = event.data;
        $this._trigger('beforesubmit');
        if ($this._validate()) {
            $this._submitFilesToUpload();
        }
        event.preventDefault();
    }
});