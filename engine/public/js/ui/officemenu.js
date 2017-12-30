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
 * ZnetDK Office menu
 *
 * File version: 1.1
 * Last update: 11/15/2015 
 */
$.widget("znetdk.zdkofficemenu", {
    options: {
        /**
         * Specifies whether the previously opened window has to be closed before
         * opening a new window.
         */
        winAutoClose: true
    },
    /**
     * Constructs the menu widget for the 'office' page layout from the PrimeUI
     * puimenu widget.
     * It includes the window manager panel located under the navigation menu
     */
    _create: function () {
        this.openedWindowsCount = 0;
        // Set windows container
        this.winContainer = $('#zdk-win-container');
        // Create navigation menu
        this.element.children('ul').puitieredmenu({autoDisplay: false});
        this.menuWidget = this.element.children('.pui-menu').first();
        // Create window manager 
        /* Get localized labels... */
        var winMgrElement = this.element.children('.zdk-win-manager').first();
                titleLabel = winMgrElement.attr('title'),
                autoCloseLabel = winMgrElement.children('span').first().text(),
                adjustHorizLabel = winMgrElement.children('span').eq(1).text(),
                adjustVerticLabel = winMgrElement.children('span').eq(2).text(),
                closeAllLabel = winMgrElement.children('span').last().text();
        // Remove element content...
        winMgrElement.empty();
        // Remove title attribute
        winMgrElement.attr('title', null);
        // Add new content...
        var menu = $('<ul/>').appendTo(winMgrElement),
                dataIcon = this.options.winAutoClose ? 'ui-icon-check' : 'ui-icon-blank';
        menu.append('<li><h3>' + titleLabel + '</h3></li>');
        this.autoCloseItem = $('<li><a data-icon="' + dataIcon + '">' + autoCloseLabel + '</a></li>').appendTo(menu);
        this.adjustHorizItem = $('<li><a data-icon="ui-icon-grip-dotted-horizontal">' + adjustHorizLabel + '</a></li>').appendTo(menu);
        this.adjustVerticItem = $('<li><a data-icon="ui-icon-grip-dotted-vertical">' + adjustVerticLabel + '</a></li>').appendTo(menu);
        this.closeAllItem = $('<li><a data-icon="ui-icon-close">' + closeAllLabel + '</a></li>').appendTo(menu);
        // Create window manager as a puimenu...
        menu.puimenu();
        // Bind events...
        this._bindEvents();
        // Init buttons state 
        this._setOpenedWindowsCount(0);
    },
    /**
     * Handles events of navigation menu and the window manager panel
     */
    _bindEvents: function () {
        var $this = this;
        this.autoCloseItem.children('a').click(function () { // Auto-close checkbox click
            if ($this.options.winAutoClose) {
                $(this).attr('data-icon', 'ui-icon-blank');
                $(this).children('span').first().removeClass('ui-icon-check');
                $(this).children('span').first().addClass('ui-icon-blank');
                $this.options.winAutoClose = false;
            } else {
                $(this).attr('data-icon', 'ui-icon-check');
                $(this).children('span').first().removeClass('ui-icon-blank');
                $(this).children('span').first().addClass('ui-icon-check');
                $this.options.winAutoClose = true;
            }
        });
        this.adjustHorizItem.children('a').click(function () { // Adjust Horizontally button click
            var frontWindows = $this._getFrontWindows();
            if (frontWindows.first.length === 1 && frontWindows.second.length === 1) {
                $this._adjustWindows(frontWindows, true);
            }
        });
        this.adjustVerticItem.children('a').click(function () {// Adjust vertically button click
            var frontWindows = $this._getFrontWindows();
            if (frontWindows.first.length === 1 && frontWindows.second.length === 1) {
                $this._adjustWindows(frontWindows, false);
            }
        });
        this.closeAllItem.children('a').click(function () { // Close all button click
            $this._closeAllWindows();
        });
        if (this.menuWidget.length === 1) { // Main menu item click
            this.menuWidget.find('ul > li > a').click(function (event) {
                if ($(this).next('ul').length === 0) {
                    var menuItemId = ($(this).attr('href')).substr(1),
                            menuItemLabel = $(this).children('span.ui-menuitem-text').text();
                    $this._openWindow(menuItemId, menuItemLabel);
                }
                event.preventDefault();
            });
        }
    },
    /**
     * Opens a new window for the specified menu item identifier
     * @param {String} menuItemId HTML identifier of the menu item 
     * @param {String} menuItemLabel Label to display on the window title bar
     * @param {object} callParameter Value to transmit to the caller through the
     * 'initview' event
     */
    _openWindow: function (menuItemId, menuItemLabel, callParameter) {
        var $this = this,
                dialogId = 'znetdk-' + menuItemId + '-view',
                dialogElement = $('#' + dialogId),
                dlgIsCreated = (dialogElement.length > 0),
                dlgIsHidden = dlgIsCreated ? dialogElement.puidialog('isHidden') : true;
        if (dlgIsHidden && this.options.winAutoClose === true) {
            // First close all windows if "winAutoClose" option is enabled
            this._closeAllWindows();
        }
        if (dlgIsCreated) {
            // Dialog already created...
            this._trigger('initview', this, {
                firstInit:false,
                menuItem:menuItemId,
                dialog:dialogElement,
                value:callParameter
            });
            if (dlgIsHidden) {
                dialogElement.puidialog('option', 'width', 'auto').
                        puidialog('option', 'height', 'auto').
                        puidialog('option', 'location', '0,0');
            }
            dialogElement.puidialog('show').puidialog('moveToTop');
        } else {
            // Dialog does not exist...
            dialogElement = $('<div id="' + dialogId + '" title="' + menuItemLabel + '"/>');
            var winContainer = this._getWinContainer();
            znetdk.loadView({
                htmlTarget: dialogElement,
                control: menuItemId,
                action: "show",
                callback: function () {
                    var isVisible = true,
                        synchronizedWidget = dialogElement.find('.zdk-synchronize');
                    if (synchronizedWidget.length === 1 && synchronizedWidget.hasClass('zdk-datatable')) {
                        synchronizedWidget.one("zdkdatatabledataloaded", function () {
                            dialogElement.puidialog('show');
                        });
                        isVisible = false;
                    } else if (synchronizedWidget.length === 1 && synchronizedWidget.hasClass('zdk-inputrows')) {
                        synchronizedWidget.one("zdkinputrowsinitialized", function () {
                            dialogElement.puidialog('show');
                        });
                        isVisible = false;
                    }
                    dialogElement.puidialog({
                        location: '0,0',
                        width: 'auto',
                        height: 'auto',
                        closeOnEscape: false,
                        appendTo: winContainer,
                        visible: isVisible,
                        showEffect: 'fade',
                        hideEffect: 'fade',
                        afterShow: function () {
                            $this._reduceWindowWidth(dialogElement);
                            znetdk.addLabelToTitle(menuItemLabel);
                            $this._setOpenedWindowsCount(+1);
                            $this._trigger('aftershowview', $this, {
                                menuItem:menuItemId,
                                dialog:dialogElement,
                                value:callParameter
                            });
                        },
                        afterHide: function () {
                            znetdk.addLabelToTitle(null);
                            $this._setOpenedWindowsCount(-1, menuItemLabel);
                        }
                    });
                    dialogElement.on("puidialogmovedtotop", function () {
                        znetdk.addLabelToTitle(menuItemLabel);
                    });
                    $this._trigger('initview', $this, {
                        firstInit:true,
                        menuItem:menuItemId,
                        dialog:dialogElement,
                        value:callParameter
                    });
                }
            });
        }
    },
    /**
     * Returns the two front windows currently displayed
     * @returns {Object|Boolean} An object with the two properties 'first' and
     * 'second', each one containing the jQuery object of the dialog element 
     * located on the front. Returns false if no window is displayed.
     */
    _getFrontWindows: function () {
        if (this.menuWidget.length === 1) {
            var firstZindex = 0, secondZindex = 0, currentZindex = 0, winContainer = this._getWinContainer();
            winContainer.children('.pui-dialog[aria-hidden="false"]').each(function () {
                currentZindex = parseInt($(this).css('z-index'), 10);
                if (currentZindex > firstZindex) {
                    secondZindex = firstZindex;
                    firstZindex = currentZindex;
                } else if (currentZindex > secondZindex) {
                    secondZindex = currentZindex;
                }
            });
            if (firstZindex) {
                var response = new Object();
                response.first = winContainer.children('.pui-dialog[style*="z-index: ' + firstZindex + ';"][aria-hidden="false"]').first();
                response.second = false;
                if (secondZindex) {
                    response.second = winContainer.children('.pui-dialog[style*="z-index: ' + secondZindex + ';"][aria-hidden="false"]').first();
                }
                return response;
            }
        }
        return false;
    },
    /**
     * Returns the windows container
     * @returns {jQuery} Element that is the window container (the body element
     * if not specified as a div element identified 'zdk-win-container').
     */
    _getWinContainer: function () {
        return this.winContainer.length ? this.winContainer : $('body');
    },
    /**
     * Closes all the windows currently opened
     */
    _closeAllWindows: function () {
        var winContainer = this._getWinContainer();
        winContainer.children('.pui-dialog[aria-hidden="false"]').each(function () {
            $(this).puidialog('hide');
        });
    },
    /**
     * Increases or decreases the number of currently opened windows
     * @param {type} gap Number of opened or closed windows (+1 or -1)
     * @param {type} menuItemLabel Menu Item label displayed on the window title
     * bar of the window newly opened or closed. 
     */
    _setOpenedWindowsCount: function (gap, menuItemLabel) {
        this.openedWindowsCount += gap;
        if (this.openedWindowsCount > 1) {
            // Adjust buttons are enabled
            this._enableButton(this.adjustHorizItem, true);
            this._enableButton(this.adjustVerticItem, true);
        } else if (this.openedWindowsCount > 0) {
            // Adjust buttons are disabled
            this._enableButton(this.adjustHorizItem, false);
            this._enableButton(this.adjustVerticItem, false);
            // Close All button is enabled
            this._enableButton(this.closeAllItem, true);
        } else {
            // All button are disabled
            this._enableButton(this.adjustHorizItem, false);
            this._enableButton(this.adjustVerticItem, false);
            this._enableButton(this.closeAllItem, false);
            // The label of the last selected menu item is removed from the title of the page 
        }
        if (this.openedWindowsCount > 0 && gap === -1) {
            var frontWindows = this._getFrontWindows(), windowTitle;
            if (frontWindows && frontWindows.second.length === 1 && !frontWindows.second.puidialog('isHidden')) {
                windowTitle = frontWindows.second.puidialog('getTitle');
                if (menuItemLabel !== windowTitle) {
                    znetdk.addLabelToTitle(frontWindows.second.puidialog('getTitle'));
                }
            }
            if (frontWindows && frontWindows.first.length === 1 && !frontWindows.first.puidialog('isHidden')) {
                windowTitle = frontWindows.first.puidialog('getTitle');
                if (menuItemLabel !== windowTitle) {
                    znetdk.addLabelToTitle(frontWindows.first.puidialog('getTitle'));
                }
            }
        }
    },
    /**
     * Enables or disables the specified button of the windows manager
     * @param {jQuery} button Element of the button to enable or disable
     * @param {Boolean} enabled Value true to enable the button, false otherwise
     */
    _enableButton: function (button, enabled) {
        if (enabled) {
            button.removeClass("ui-state-disabled").children('a').removeClass("ui-state-disabled");
        } else {
            button.addClass("ui-state-disabled").children('a').addClass("ui-state-disabled");
        }
    },
    /**
     * Adjusts the position and size of the windows currently displayed
     * @param {Object} frontWindows The two front windows currently displayed on
     * top that are specified in the properties 'first' and 'second' as jQuery
     * object.
     * @param {Boolean} horizontally If true, the 2 windows are positioned 
     * horizontally, otherwise vertically
     */
    _adjustWindows: function (frontWindows, horizontally) {
        var winContainer = this._getWinContainer(),
                position = winContainer.offset(),
                winWidth = 0,
                winHeight = 0,
                firstLocation = '',
                secondLocation = '';
        if (horizontally) {
            winWidth = winContainer.width() - 5;
            winHeight = (winContainer.height() / 2) - 50;
            firstLocation = 'left,top';
            secondLocation = position.left + ',' + (position.top + (parseInt(winContainer.height() / 2, 10)));
        } else {
            winWidth = (winContainer.width() / 2) - 5;
            winHeight = winContainer.height() - 50;
            firstLocation = position.left + ',' + (position.top + 1);
            secondLocation = (position.left + winWidth + 5) + ',' + (position.top + 1);
        }
        frontWindows.first.puidialog('option', 'width', winWidth);
        frontWindows.first.puidialog('option', 'height', winHeight);
        frontWindows.first.puidialog('option', 'location', firstLocation);
        frontWindows.second.puidialog('option', 'width', winWidth);
        frontWindows.second.puidialog('option', 'height', winHeight);
        frontWindows.second.puidialog('option', 'location', secondLocation);
    },
    /**
     * Reduces the width of the specified window to fit in the windows container
     * @param {jQuery} dialogElement Element of the window
     */
    _reduceWindowWidth: function(dialogElement) {
        var winWidth = dialogElement.width(),
            winContainerWidth = this._getWinContainer().width();
            
        if (winContainerWidth < winWidth) {
            dialogElement.puidialog('option', 'width', winContainerWidth);
        }
    },
    /**
     * Returns the menu item identifier of the specified hyperlink element 
     * @param {jQuery} element Hyperlink element
     * @returns {String} Menu item identifier
     */
    _getMenuItemId: function (element) {
        return (element.attr('href')).substr(1);
    },
    /**
     * Returns the first menu item identifier in the menu
     * @returns {String|Boolean} Menu item identifier or false if no menu item
     * exists.
     */
    _getFirstMenuItemId: function () {
        var firstItem = this.menuWidget.find('ul > li > a').first();
        if (firstItem.length) {
            return this._getMenuItemId(firstItem);
        } else {
            return false;
        }
    },
    /**
     * Returns the first menu item identifier of the specified parent menu item
     * @param {jQuery} parent Parent menu item 
     * @returns {String|Boolean} The child menu item identifier or false if not
     * found 
     */
    _getFirstChildMenuItemId: function (parent) {
        if (parent === undefined) {
            parent = this.menuWidget.find('ul > li').first();
        }
        if (parent.length) {
            var child = parent.find('ul>li').first();
            if (child.length) {
                return this._getFirstChildMenuItemId(child);
            } else {
                var linkElement = parent.children('a').first();
                return this._getMenuItemId(linkElement);
            }
        } else {
            return false;
        }
    },
    /**
     * Opens a new windows and loads its content if not yet loaded 
     * @param {String} viewID Identifier of the view to display in the window
     * @param {object} callParameter Parameter to transmit to the caller once the
     * view is initialized ('initview' event).
     */
    openWindow: function (viewID, callParameter) {
        if (this.menuWidget.length === 1) {
            var menuItemId = viewID === undefined ? this._getFirstChildMenuItemId() : viewID, menuItemLabel;
            menuItemLabel = this.menuWidget.find('ul > li > a[href=#' + menuItemId + '] > span.ui-menuitem-text').text();
            if (menuItemLabel) {
                this._openWindow(menuItemId, menuItemLabel, callParameter);
            }
        }
    },
    /**
     * Closes the specified windows 
     * @param {String} viewID Identifier of the view to hide 
     */
    closeWindow: function (viewID) {
        if (this.menuWidget.length === 1 && viewID !== undefined) {
            var dialogId = 'znetdk-' + viewID + '-view',
                dialogElement = $('#' + dialogId);
            if (dialogElement.length) {
                dialogElement.puidialog('hide');
            }
        }
    },
    /**
     * Sets the specified window title  
     * @param {string} viewID Identifier of the view for which the title is to change
     * @param {string} title New title of the window
     */
    setWindowTitle: function (viewID, title) {
        if (this.menuWidget.length === 1 && viewID !== undefined) {
            var dialogId = 'znetdk-' + viewID + '-view',
                dialogElement = $('#' + dialogId);
            if (dialogElement.length) {
                dialogElement.puidialog('setTitle',title);
            }
        }
    },
    /**
     * Returns the identifier and label of the current window displayed on top 
     * @returns {Object} Identifier and label of the window currently displayed
     * on top
     */
    getSelectedMenuItem: function () {
        var selectedMenu = null,
            frontWindows = this._getFrontWindows();
        if (frontWindows && frontWindows.first.length === 1) {
            selectedMenu = new Object();
            var menuId = frontWindows.first.attr('id'), suffixPos = menuId.lastIndexOf('-view');
            selectedMenu.id = menuId.substring(7, suffixPos); // Remove prefix "znetdk-" and suffix "-view" to the menu ID 
            selectedMenu.label = frontWindows.first.find('span.pui-dialog-title').text();
        }
        return selectedMenu;
    }
});
