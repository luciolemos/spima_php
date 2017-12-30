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
 * PrimeUI Input multifiles widget
 *
 * File version: 1.0
 * Last update: 09/17/2016
 */

/**
 * Multiupload widget for uploading multiple files
 */
$(function () {
    $.widget("znetdk.zdkmultiupload", {
        options: {
            controller:null, // Controller name to which the uploaded file is sent
            action:null, // Action name to which the uploaded file is sent
            selectButtonLabel: 'Browse...', // Label for the file selection button
            selectButtonIcon: 'ui-icon-document', // Icon displayed on the file selection button
            selectButtonTooltip: null, // Tooltip of the file selection button
            inputNameAttribute: null, // Name given to the file inputs
            acceptedFiles: null // Type of file that the user can select
        },
        /**
         * Instantiates the widget 
         */
        _create: function () {
            this._setOptionsFromAttributes();
            this.element.addClass('zdk-multiupload');
            // Create file selection button
            this.selectFileButton = $('<button type="button">'+this.options.selectButtonLabel+'</button>').appendTo(this.element);
            if (this.options.selectButtonTooltip !== null) {
                this.selectFileButton.attr('title', this.options.selectButtonTooltip);
            }            
            this.selectFileButton.puibutton({icon: this.options.selectButtonIcon});
            // Create inputfile widgets container
            this.inputfileContainer = $('<div class="ui-helper-hidden-accessible"/>').appendTo(this.element);
            // Create selected files containter
            this.selectedfileContainer = $('<div class="zdk-multiupload-files"/>').appendTo(this.element);
            // Bind events
            this._bindEvents();
        },
        _bindEvents: function () {
            var $this = this;
            this.selectFileButton.click(function() {
                $this._selectNewFile();
            });
        },
        /**
         * Initializes the widget options through its specified HTML5 attributes
         */
        _setOptionsFromAttributes: function () {
            var actionAttrib = znetdk.getActionFromAttr(this.element),
                selectButtonAttrib = znetdk.getTextFromAttr(this.element,'data-zdk-selbuttonlabel'),
                buttonIcon = znetdk.getTextFromAttr(this.element,'data-zdk-icon'),
                buttonTooltip = znetdk.getTextFromAttr(this.element,'title'),
                inputName = znetdk.getTextFromAttr(this.element,'data-name'),
                acceptedFiles = znetdk.getTextFromAttr(this.element,'data-zdk-accept');
            if (actionAttrib !== false) {
                this.options.controller = actionAttrib.controller;
                this.options.action = actionAttrib.action;
                this.element.removeAttr('data-zdk-action');
            }
            if (selectButtonAttrib !== false) {
                this.options.selectButtonLabel = selectButtonAttrib;
                this.element.removeAttr('data-zdk-selbuttonlabel');
            }
            if (buttonIcon !== false) {
                this.options.selectButtonIcon = buttonIcon;
                this.element.removeAttr('data-zdk-icon');
            }
            if (buttonTooltip !== false) {
                this.options.selectButtonTooltip = buttonTooltip;
                this.element.removeAttr('title');
            }
            if (inputName !== false) {
                this.options.inputNameAttribute = inputName;
            }
            if (acceptedFiles !== false) {
                this.options.acceptedFiles = acceptedFiles;
                this.element.removeAttr('data-zdk-accept');
            }
        },
        /**
         * Checks if the expected options are properly set 
         */
        _checkAttributes: function() {
            if (this.options.controller === null || this.options.action === null) {
                throw new Error('zdkmultiupload: the controller and action are not properly set!');
            }
            if (this.options.inputNameAttribute === null) {
                throw new Error('zdkmultiupload: the name for the file inputs is not set!');
            }
        },
        /**
         * Checks if the specified filename has been already selected
         * @param {String} filename Name of the file
         * @returns {Boolean} Value true if the specified filename matches a
         * file previously selected
         */
        _isFileAlreadySelected: function(filename) {
            var isAlreadySelected = false;
            this.selectedfileContainer.find('.zdk-multiupload-filename').each(function(){
                if ($(this).text() === filename) {
                    isAlreadySelected = true;
                    return false;
                }
            });
            return isAlreadySelected;
        },
        /**
         * Attaches the 'select' event to the specified input file in order to
         * display the name of the selected file
         * @param {jQuery} inputfileElement DOM element for wich the 'select'
         * event is to attach 
         */
        _attachSelectfileEvents: function(inputfileElement) {
            var $this = this;
            inputfileElement.one('zdkinputfileselect', function(evt, filename){
                if (!$this._isFileAlreadySelected(filename)) {
                    $this._displaySelectedFile(filename, $(this).attr('id'));
                    $this._trigger('select', $this, filename);
                }
            });
        },
        /**
         * Attaches the 'click' event of the file remove icon for the specified
         * selected file  
         * @param {jQuery} selectedFileElement DOM element of the selected file
         */
        _attachRemovefileEvents: function(selectedFileElement) {
            var $this = this;
            selectedFileElement.find('.zdk-multiupload-remove-link').one('click', function(evt){
                $this._removeSelectedFile($(this).attr('href'));
                evt.preventDefault();
            });
        },
        /**
         * Removes the specified file that has been previously selected 
         * @param {String} inputfileId Identifier of the inputfile element
         */
        _removeSelectedFile: function(inputfileId) {
            var inputfileWidget = this.inputfileContainer.find('#' + inputfileId).parent(),
                selectedfileElement = this.selectedfileContainer.find('a[href='+ inputfileId + ']').parent(),
                nextFileSeparator = selectedfileElement.next('span'),
                prevFileSeparator = selectedfileElement.prev('span');
            inputfileWidget.remove();
            selectedfileElement.remove();
            if (nextFileSeparator.length) {
                nextFileSeparator.remove();
            } else if (prevFileSeparator.length) {
                prevFileSeparator.remove();
            }
        },
        /**
         * Displays the name of the specified file that has been selected.
         * @param {String} filename Name of the file to display as a selected file
         * @param {String} inputfileId Identifier of the 'inputfile' element. If
         * not defined, the removal icon is not displayed.
         */
        _displaySelectedFile: function(filename, inputfileId) {
            var fileElement = $('<div/>');
            if (this.selectedfileContainer.children().length) {
                this.selectedfileContainer.append('<span>,&nbsp;</span>');
            }
            fileElement.appendTo(this.selectedfileContainer);
            fileElement.append('<span class="zdk-multiupload-filename">' + filename + '</span>');
            if (inputfileId !== undefined) {
                fileElement.append('<a class="zdk-multiupload-remove-link" href="'
                        + inputfileId + '"><span class="fa fa-lg fa-remove"/></a>');
                this._attachRemovefileEvents(fileElement);
            }
        },
        /**
         * Adds to the DOM a new inputfile widget and shows the file selection
         * dialog.
         */
        _selectNewFile: function() {
            this._checkAttributes();
            var newInputfile = $('<input type="file" name="'
                    + this.options.inputNameAttribute
                    + '[]" accept="'+ this.options.acceptedFiles +'">');
            newInputfile.appendTo(this.inputfileContainer).zdkinputfile({
                controller: this.options.controller,
                action: this.options.action,
                showThumbnail:false,
                noFileLabel:''
            });
            this._attachSelectfileEvents(newInputfile);
            newInputfile.click();
        },
        /**
         * Reset the file selection (all files previously selected are removed)
         */
        reset: function() {
            this.inputfileContainer.empty();
            this.selectedfileContainer.empty();
        },
        /**
         * Sets the focus to the file selection button
         * @returns {undefined}
         */
        setFocus: function() {
            this.selectFileButton.focus();
        },
        /**
         * Sets the previously selected files for display purpose 
         * @param {Array} files Description of the files to display as selected
         * files.
         * @returns {Boolean} Value true if the display of the files succeeded,
         * false otherwise
         */
        setUploadedFiles: function(files) {
            var $this = this, isOk = true;
            if (!$.isArray(files) || files.length === 0) {
                return false;
            }
            $.each(files, function(index, value){
                if (typeof value !== 'object') {
                    isOk = false;
                    return false;
                }
                if (value.filename !== undefined && value.filename.length > 0) {
                   $this._displaySelectedFile(value.filename);
                }
            });
            return isOk;
        },
        /**
         * Disables the file selection button
         */
        disable: function() {
            this.selectFileButton.puibutton('disable');
        },
        /**
         * Enables the file selection button
         */
        enable: function() {
            this.selectFileButton.puibutton('enable');
        },
        /**
         * Idicates whether at least one file is selected or not
         * @returns {Boolean} Value true if no file is selected otherwise false.
         */
        isEmpty: function() {
            return this.selectedfileContainer.children().length === 0;
        }
    });
});