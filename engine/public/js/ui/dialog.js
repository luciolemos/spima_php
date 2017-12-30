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
 * PrimeUI Dialog Widget
 * ZnetDK Extended version of the PrimeUI Dialog Widget whith:
 *   - Option 'maximizable' is initialized properly
 *   - Option 'location' works properly with relative position like "50,100"
 *   - Option 'appendTo' forced to 'body' if not set
 *   - Set CSS 'max-height' and 'max-width' properties from container size (option 'appendTo')
 *   - Public methods getTitle and moveToTop()
 *   - Move window to top on click event
 *   - New method '_setOption()'
 *
 * File version: 1.4
 * Last update: 04/28/2017
 */
$(function () {
    $.widget("primeui.puidialog", $.primeui.puidialog, {
        options: {
            autoHeight: false
        },
        /**
         * Overloads the original constructor to resize the window from the 
         * container size and to attach the dialog to the body element by default
         */
        _create: function () {
            /* Fix dialog height according to the browser client area height */
            this._setAutoHeightFromAttribute();
            
            this._super();
            
            // ZnetDK extension : size is fixed by the _setSize method
            this._setSize();

            if (this.options.appendTo === null) { // ZnetDK extension : dialog appended to 'body' by default
                this.element.appendTo('body');
            }
        },
        /**
         * Provides the window title displayed on the title bar
         * @returns {String} Title displayed on the window title bar
         */
        getTitle: function () {
            return this.element.find('div.pui-dialog-titlebar > span.pui-dialog-title').text();
        },
        /**
         * Sets the dialog title
         * @param {string} title Title of the dialog
         */
        setTitle: function (title) {
            this.element.find('span.pui-dialog-title').text(title);
        },
        /**
         * Sizes the window taking in account the height of the container element 
         */
        _setSize: function () {
            this.element.css('width', this.options.width);
            if (this.options.autoHeight) {
                this._setAutoHeight();
            } else {
                this.element.css('height', 'auto');
                this.content.height(this.options.height);
            }
            if (this.options.appendTo) {
                this.content.css('max-height', this.options.appendTo.height() - 40);
            }
        },
        _setAutoHeight: function() {
            var windowHeight = $(window).height();
            this.element.height(windowHeight);
            this.content.height(windowHeight - 40);
        },
        /**
         * Overloads the original parent method to fix a bug: the 'position' option
         * is read (not exists!) instead of the 'location' option.
         */
        _initPosition: function () {
            //reset
            this.element.css({left: 0, top: 0});

            if (/(center|left|top|right|bottom)/.test(this.options.location)) {
                this.options.location = this.options.location.replace(',', ' ');

                this.element.position({
                    my: 'center',
                    at: this.options.location,
                    collision: 'fit',
                    of: window,
                    //make sure dialog stays in viewport
                    using: function (pos) {
                        var l = pos.left < 0 ? 0 : pos.left,
                                t = pos.top < 0 ? 0 : pos.top;

                        $(this).css({
                            left: l,
                            top: t
                        });
                    }
                });
            }
            else {
                var coords = this.options.location.split(','),
                        x = $.trim(coords[0]),
                        y = $.trim(coords[1]);

                this.element.offset({
                    left: x,
                    top: y
                });
            }

            this.positionInitialized = true;
        },
        /**
         * Overloads the original 'show' method to force the window to be
         * resized to its original size.
         */
        show: function () {
            var sizeToOriginal = false;
            if (this.element.is(':visible')) {
                sizeToOriginal = true;
            }
            this._super();
            if (sizeToOriginal) {
                this._setSize();
            }
        },
        /**
         * Adds a new event handler to the original parent method to move the
         * window on top when it is clicked on.
         */
        _bindEvents: function () {
            this._super();
            var $this = this;
            // ZnetDK extension: on click, move to top
            this.element.click(function (e) {
                if (typeof e.originalEvent !== 'undefined' &&
                        $(e.originalEvent.target).is("div") &&
                        ($(e.originalEvent.target).hasClass('ui-widget-header') ||
                                $(e.originalEvent.target).hasClass('ui-widget-content'))) {
                    $this.moveToTop();
                }
            });
            // ZnetDK extension: event 'close' is triggered and can be prevented
            // Click on the banner close icon
            this.closeIcon.off('click.puidialog');
            this.closeIcon.on('click.puidialog', function(e) {
                if ($this._trigger('close')) {
                    $this.hide();
                }
                e.preventDefault();
            });
            // ZnetDK extension: event 'close' is triggered and can be prevented
            // The Escape key is pressed
            if(this.options.closeOnEscape) {
                $(document).off('keydown.dialog_' + this.element.attr('id'));
                $(document).on('keydown.dialog_' + this.element.attr('id'), function(e) {
                    var keyCode = $.ui.keyCode,
                    active = parseInt($this.element.css('z-index'), 10) === PUI.zindex;

                    if(e.which === keyCode.ESCAPE && $this.element.is(':visible') && active) {
                        if ($this._trigger('close')) {
                            $this.hide();
                        }
                    }
                });
            }
            // ZnetDK extension: the 'resize' event is handled for resizing the
            // height of the dialog height if the 'autoHeight' option is set
            if (this.options.autoHeight) {
                $(window).on('resize', function(event) {
                    if (event.target === window) {
                        $this._setAutoHeight();
                    }
                });
            }

        },
        /**
         * Overloads the original parent method to force the position 'absolute'
         * to the window.
         */
        _setupResizable: function () {
            if (this.options.appendTo) {
                this.element.resizable({
                    maxWidth: this.options.appendTo.width(),
                    maxHeight: this.options.appendTo.height()
                });
            }
            this._super();
            // ZnetDK : Position forced to 'absolute'
            this.element.css('position', '');
            this.element.css('position', 'abosolute');
        },
        /**
         * Overloads the original parent method to force the position 'absolute'
         * to the window.
         */
        _setupDraggable: function () {
            this._super();
            // ZnetDK : Position forced to 'absolute'
            this.element.css('position', '');
            this.element.css('position', 'abosolute');
        },
        /**
         * Moves the window to the top to be visible when it is behind others
         * windows.
         */
        moveToTop: function () {
            if (this.element.is(':visible')) {
                var active = parseInt(this.element.css('z-index'), 10) === PUI.zindex;
                if (!active) {
                    this._moveToTop();
                    this._trigger('movedtotop', null);
                }
            }
        },
        /**
         * Overloads the original setOption method to add extra css settings
         * when the options 'location', 'width' and 'height' are updated.
         * @param {String} key Name of the option
         * @param {Mixed} value Value of the option.
         */
        _setOption: function (key, value) {
            this._super(key, value);
            if (key === 'location') {
                this._initPosition();
            } else if (key === 'width') {
                this.element.css('width', value);
                this.content.css('width', 'auto');
            } else if (key === 'height') {
                this.element.css('height', 'auto');
                this.content.css('height', value);
            }
        },
        /**
         * Evaluates if the window is hidden or not.
         * @returns {Boolean} Value true if the window is hidden, otherwise false
         */
        isHidden: function () {
            return this.element.attr('aria-hidden') === "true";
        },
        /**
         * Overrides the original method to focus on radio buttons only when they
         * are checked, to include select elements and to take in account the
         * zdk-autofocus class for buttons and input elements. 
         */
        _applyFocus: function() {
            var autofocusElement = this.element.find(
                ':input:visible:enabled.zdk-autofocus,input:radio:visible:enabled:checked.zdk-autofocus,'
                + 'select:visible:enabled.zdk-autofocus,button:visible:enabled.zdk-autofocus,'
                + 'textarea:visible:enabled.zdk-autofocus').first();
            if (autofocusElement.length === 0) {
                autofocusElement = this.element.find(
                    ':not(:submit):not(:button):not(:radio):input:visible:enabled,'
                    + 'input:radio:visible:enabled:checked,select:visible:enabled,'
                    + 'textarea:visible:enabled').first();
            }
            if (autofocusElement.length === 1) {
                if (autofocusElement.is('input')) {
                    autofocusElement.select();
                }
                autofocusElement.focus();
            }
        },
        _postHide: function() {
            if(this.options.modal) {
                this._disableModality(true);
            }
            this._super();
        },
        _disableModality: function(noSilent){
            if (noSilent && this.modality !== null) {
                this._super();
            }
        },
        /**
         * Initializes the width of the dialog from the 'data-zdk-width' HTML5
         * attribute.
         * The dialog location is forced to 0,0 when the dialog width is set to 
         * the value '100%'.
         */
        _setAutoHeightFromAttribute: function () {
            var responsiveAttrib = znetdk.getTextFromAttr(this.element, 'data-zdk-autoheight');
            if (responsiveAttrib !== false && responsiveAttrib === 'true') {
                this.options.autoHeight = true;
                if (this.options.width === '100%') {
                    this.options.location = '0,0';
                }
            }
        },
    });
});
