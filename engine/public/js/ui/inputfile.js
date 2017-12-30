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
 * PrimeUI Input file widget
 *
 * File version: 1.2
 * Last update: 09/20/2016
 */

/**
 * InputFile widget for uploading files
 */
$(function () {
    $.widget("znetdk.zdkinputfile", {
        options: {
            controller:null, // Controller name to which the uploaded file is sent
            action:null, // Action name to which the uploaded file is sent
            showThumbnail: true, // Show a thumbnail of the selected image
            selectButtonLabel: 'Browse...', // Label for the file selection button
            selectButtonIcon: 'ui-icon-document', // Icon displayed on the file selection button
            noFileLabel: '&lt; No file selected! &gt;' // Label displayed when no file is selected
        },
        /**
         * Instantiates the widget 
         */
        _create: function () {
            this.uploadedFileInfos = null;
            this._setOptionsFromAttribute();
            this.options.showThumbnail = this.element.hasClass('zdk-nothumbnail')
                ? false : this.options.showThumbnail;
            var titleAttr = this.element.attr('title');
            this.element.wrap('<div class="zdk-inputfile"/>');
            this.element.prop('hidden',true).uniqueId();
            this.container = this.element.parent();
            this.selectFileButton = $('<button type="button">'+this.options.selectButtonLabel+'</button>').appendTo(this.container);
            if (titleAttr) {
                this.selectFileButton.attr('title', titleAttr);
            }
            this.selectFileButton.puibutton({icon: this.options.selectButtonIcon});
            this.removeFileButton = $('<button type="button"/>').appendTo(this.container);
            this.removeFileButton.puibutton({icon: 'ui-icon-closethick'});
            this.selectedFileLabel = $('<span/>').appendTo(this.container);
            this.thumbnail = $('<img class="ui-widget-content">').appendTo(this.container);
            this.elementName = this.element.attr('name');
            this.reset();
            this._bindEvents();
        },
        /**
         * Binds events handled by the widget
         */
        _bindEvents: function () {
            var $this = this;
            this.element.on('change.zdkinputfile_' + $(this).attr('id'), $this, function() {
                var filesCount = this.files.length, selectedFile = this.files[0];
                if (filesCount) {
                    $this.currentFile = selectedFile;
                    $this.currentFileName = selectedFile.name;
                    $this.selectedFileLabel.text($this.currentFileName);
                    $this.currentFileSize = selectedFile.size;
                    $this.currentFileType = selectedFile.type;
                    if ($this.options.showThumbnail) {
                        $this.thumbnail.attr('src', window.URL.createObjectURL(selectedFile));
                        $this.thumbnail.show();
                    }
                    $this.removeFileButton.show();
                    if ($this.element.hasClass('zdk-autoupload')) {
                        $this.upload();
                    } else {
                        $this._trigger('select', $this, $this.currentFileName);
                    }
                } else { // No file selected
                    $this.reset();
                }
            });
            this.selectFileButton.click(function() {
                $this.element.click();
            });
            this.removeFileButton.click(function() {
                $this.reset();
                $this.setFocus();
            });
        },
        /**
         * Initializes the widget options through its specified HTML5 attributes
         */
        _setOptionsFromAttribute: function () {
            var actionAttrib = znetdk.getActionFromAttr(this.element),
                noFileAttrib = znetdk.getTextFromAttr(this.element,'data-zdk-nofilelabel'),
                selectButtonAttrib = znetdk.getTextFromAttr(this.element,'data-zdk-selbuttonlabel'),
                buttonIcon = znetdk.getTextFromAttr(this.element,'data-zdk-icon');
            if (actionAttrib !== false) {
                this.options.controller = actionAttrib.controller;
                this.options.action = actionAttrib.action;
            }
            if (noFileAttrib !== false) {
                this.options.noFileLabel = noFileAttrib;
            }
            if (selectButtonAttrib !== false) {
                this.options.selectButtonLabel = selectButtonAttrib;
            }
            if (buttonIcon !== false) {
                this.options.selectButtonIcon = buttonIcon;
            }
            
        },
        /**
         * Returns the file input name without brackets punctation eventually
         * defined as suffix.
         * @returns {String} Input name without brackets if exist
         */
        _getInputNameWithoutBrackets: function() {
            var pos = this.elementName.lastIndexOf('[]');
            if (pos === -1) {
                return this.elementName;
            } else {
                return this.elementName.substr(0, pos);
            }
        },
        /**
         * Returns the list of the selected files
         * @returns {FileList} List of selected files object
         */
        getFileData: function() {
            return this.currentFile;
        },
        /**
         * Returns the name of the selected file
         * @returns {String} Name of the selected file
         */
        getFileName: function() {
            return this.currentFileName;
        },
        /**
         * Returns the size of the selected file
         * @returns {Number} Size in bytes of the selected file
         */
        getFileSize: function() {
            return this.currentFileSize;
        },
        /**
         * Returns the MIME file type of the selected file
         * @returns {String} Mime type of the selected file 
         */
        getFileType: function() {
            return this.currentFileType;
        },
        /**
         * Indicates whether a file has been selected or not
         * @returns {Boolean} true if a file has been selected, false otherwise
         */
        isFileSelected: function() {
            return Boolean(this.currentFileName !== undefined && this.currentFileName!=='');
        },
        /**
         * Uploads the selected file to the web server controller action 
         * specified for the widget
         * @param {function} callback Function called when upload is over
         * @throws {Error} If controller, action and HTML element name are not
         * properly set and if no file is selected
         */
        upload: function(callback) {
            if (this.options.controller && this.options.action
                    && this.elementName && this.isFileSelected()) {
                var $this = this;
                znetdk.request({
                    control:this.options.controller,
                    action:this.options.action,
                    fileToUpload:{
                        inputName:this._getInputNameWithoutBrackets(),
                        file:this.getFileData() },
                    callback: function (response) {
                        if (callback === undefined) {
                            if (response.success) {
                                $this._trigger("success", $this, response);
                            } else {
                                $this._trigger("failed", $this, response);
                            }
                            if (response.summary && response.msg) {
                                var levelMsg = response.success === false ? 'error' :
                                    response.warning === true ? 'warn' : 'info';
                                znetdk.message(levelMsg, response.summary, response.msg);
                            }
                        } else if (typeof callback === "function") {
                            callback(response);
                        }
                    }
                });
            } else {
                throw new Error('zdkinputfile: unable to upload file!');
            }
        },
        /**
         * Resets the widget state (no file selected) 
         */
        reset: function() {
            this.element.val('');
            this.currentFile = null;
            this.currentFileName = '';
            this.currentFileType = '';
            this.currentFileSize = 0;
            this.selectedFileLabel.html(this.options.noFileLabel);
            this.thumbnail.attr('src', null);
            this.thumbnail.hide();
            this.removeFileButton.hide();
            this.uploadedFileInfos = null;
            this._trigger('reset');
        },
        /**
         * Initializes the widget for displaying the thumbnail and filename of 
         * a file previously uploaded.
         * @param {object} fileInfos Informations of the file previously uploaded
         */
        setUploadedFile: function(fileInfos) {
            if (fileInfos === undefined || fileInfos === null || typeof fileInfos !== 'object') {
                return false;
            } else {
                this.uploadedFileInfos = fileInfos;
            }
            if (fileInfos.url !== undefined && fileInfos.url.length > 0
                    && fileInfos.mimeType !== undefined && fileInfos.mimeType.length > 0
                    && this.options.showThumbnail && fileInfos.mimeType.indexOf("image/") === 0) {
                // The thumbnail picture is displayed...
                this.thumbnail.attr('src', fileInfos.url);
                this.thumbnail.show();
            }
            if (fileInfos.url !== undefined && fileInfos.url.length > 0
                    && fileInfos.mimeType !== undefined && fileInfos.mimeType.length > 0
                    && fileInfos.mimeType.indexOf("image/") === -1) {
                // The filename is displayed as hyperlink...
                // TODO
                // ....
                this.removeFileButton.show();
            } else if (fileInfos.filename !== undefined && fileInfos.filename.length > 0) {
                // The filename is displayed as simple text...
                this.selectedFileLabel.text(fileInfos.filename);
                this.removeFileButton.show();
            }
            return true;
        },
        /**
         * Returns the file informations of the previously uploaded. 
         * @returns {object} File informations or NULL if no uploaded file 
         * informations was not set.
         */
        getUploadedFile: function() {
            return this.uploadedFileInfos;
        },
        /**
         * Sets the entry focus to the widget 
         */
        setFocus: function() {
            this.selectFileButton.focus();
        }
    });
});