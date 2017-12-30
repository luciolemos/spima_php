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
 * Helpdialog : online help dialog displayed when the help icon is clicked on
 *
 * File version: 1.0
 * Last update: 09/17/2015
 */
/**
 * zdkhelpdialog widget
 * Online help dialog displayed when user clicks on the help icon
 */
$.widget("znetdk.zdkhelp", {
    /**
     * Widget's constructor
     */
    _create: function () {
        this.closeButtonLabel = this.element.attr('data-zdk-helpclosebutton');
        this.helpLabel = this.element.attr('data-zdk-helptitle');
        
        this.helpLink = this.element.children('a').first();
        // Bind events...
        if (this.helpLink.length)
            this._bindEvents();
    },
    /**
     * Handles the clic event on the help icon
     */
    _bindEvents: function () {
        var $this = this;
        this.helpLink.click(function (event) {
            event.preventDefault();
            var selectedMenu = znetdk.getSelectedMenu();
            if (selectedMenu) {
                $this._show(selectedMenu.id, selectedMenu.label);
            }
        });
    },
    /**
     * Loads and shows the specified help view
     * @param {String} menuItemId Identifier of the currently selected menu item
     * @param {String} menuLabel Label to display
     */
    _show: function (menuItemId, menuLabel) {
        var $this = this;
        var helpWindowId = 'zdk_help_window';
        if ($('#'+helpWindowId).length) { // Remove help window if yet exists
            $('#'+helpWindowId).remove();
        }
        var title = '<span>' + this.helpLabel + '</span><span>' + menuLabel + '</span>';
        var helpWindow = $('<div id="'+helpWindowId+'" title="'+title+'"></div>');
        var winContainer = $('body');
        var maxHeight = ($(window).height()*0.75);
        znetdk.loadView({
            htmlTarget: helpWindow,
            control: menuItemId,
            action: "help",
            callback: function (response) {
                if (response.success === false) {
                    znetdk.message('warn', title, response.msg);
                } else {
                    helpWindow.puidialog({
                        minimizable: true,
                        showEffect: 'fade',
                        hideEffect: 'fade',
                        height:(maxHeight > 500 ? 500 : maxHeight),
                        width:($(window).width()*0.5),
                        appendTo: winContainer,
                        visible: true,
                        buttons: [{  
                            text: $this.closeButtonLabel,  
                            icon: 'ui-icon-close',  
                            click: function() {  
                                    helpWindow.puidialog('hide');  
                            }
                        }]
                    });
                }
            }
        });
    }
});
