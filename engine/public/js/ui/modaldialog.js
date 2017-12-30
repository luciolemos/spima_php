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
 * ZnetDK modal dialog widget
 *
 * File version: 1.3
 * Last update: 09/17/2016
 */
$(function () {
    $.widget("znetdk.zdkmodal", $.primeui.puidialog, {
        /**
         * Initializes the default options of the parent widget
         */
        options: {
            resizable: false,
            showEffect: 'fade',
            hideEffect: 'fade',
            minimizable: false,
            maximizable: false,
            modal: true,
            width:'auto',
            confirmationOnClose: null
        },
        /**
         * Instantiates the modal dialog and extends the event handlers to close
         * the dialog when the save & close buttons are clicked.  
         */
        _create: function () {
            /* Fix dialog width from HTML5 attribute */
            this._setWidthFromAttribute();
            
            /* Confirmation message from HTML5 attribute */
            this._setConfirmationFromAttribute();
            
            /* Call of the default constructor */
            this._super();

        },
        _bindEvents: function () {
            /* Call of the parent method */
            this._super();
            
            /* The clic events of the inner form cancel and save buttons are catched for closing the dialog */
            var $this = this,
                cancelButton = this.element.find('button.zdk-bt-cancel, button.zdk-bt-no').first(),
                saveButton = this.element.find('button.zdk-bt-save, button.zdk-bt-yes').first(),
                formElement = this.element.find('form.zdk-form');
            
            cancelButton.click(function () {
                if (cancelButton.length && cancelButton.hasClass('zdk-close-dialog')) {
                    $this._closeDialog();
                }
            });
            
            this.element.on('zdkmodalclose', function(event) {
                if (cancelButton.length && cancelButton.hasClass('zdk-close-dialog')) {
                    $this._closeDialog();
                    return false;
                }
            });
            
            if (formElement.length && saveButton.length && saveButton.hasClass('zdk-close-dialog')) {
                formElement.bind("zdkformcomplete", function () {
                    var focusedButton = $this.element.find('.zdk-form button:submit:focus');
                    if (focusedButton.length && !focusedButton.hasClass('zdk-close-dialog')) {
                        // The form was submitted by clicking on a button that does not have
                        // the 'zdk-close-dialog' CSS class
                        return false; // The dialog is not hidden
                    }
                    $this.hide();
                });
            }            
        },
        _closeDialog: function() {
            if (this.options.confirmationOnClose === null) {
                this.hide();
            } else {
                var formElement = this.element.find('form.zdk-form');
                if (formElement.length > 0 && formElement.zdkform('isFormModified')) {
                    // Get the dialog title
                    var closeButton = this.element.find('button.zdk-bt-cancel, button.zdk-bt-no').first(),
                        confirmationDialogTitle = closeButton.attr('title'),
                        $this = this;
                    if (confirmationDialogTitle === undefined) {
                        confirmationDialogTitle = closeButton.text();
                    } 
                    znetdk.getUserConfirmation({
                        title: confirmationDialogTitle,
                        message: this.options.confirmationOnClose[0],
                        yesLabel: this.options.confirmationOnClose[1],
                        noLabel: this.options.confirmationOnClose[2],
                        callback: function (confirmation) {
                            if (confirmation) {
                                $this.hide();
                            }
                        }
                    });
                } else {
                    this.hide();
                }
            }
        },
        /**
         * Initializes the width of the dialog from the 'data-zdk-width' HTML5
         * attribute
         */
        _setWidthFromAttribute: function () {
            var widthAttrib = znetdk.getTextFromAttr(this.element, 'data-zdk-width');
            if (widthAttrib !== false) {
                this.options.width = widthAttrib;
            }
        },
        _setConfirmationFromAttribute: function() {
            var confirmationMsg = znetdk.getTextFromAttr(this.element, 'data-zdk-confirm');
            if (confirmationMsg !== false) {
                this.options.confirmationOnClose = confirmationMsg.split(":");
            }
        },
        show: function(resetPosition) {
            if (resetPosition === true) {
                this.positionInitialized = false;
            }
            this._super();
        }
        
    });
});
