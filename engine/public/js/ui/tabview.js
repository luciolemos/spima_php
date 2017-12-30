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
 * PrimeUI Tabview Widget, extended version with :
 *      - new "removeAll" method
 *      - new "allDisabled" option
 *      - new "enableFromList" method
 *      - new "contentOverflow" option
 *
 * File version: 1.0
 * Last update: 09/18/2015 
 */
$(function () {
    $.widget("primeui.puitabview", $.primeui.puitabview, {
        /**
         * Removes all existing tabs  
         */
        removeAll: function () {
            var header, panel;
            var tabCount = this.getLength();
            for (i = 0; i < tabCount; i++) {
                header = this.navContainer.children().eq(0);
                panel = this.panelContainer.children().eq(0);

                this._trigger('close', null, 0);

                header.remove();
                panel.remove();
            }
        },
        /**
         * Overrides the parent's method to take in account the 'contentOverflow'
         * property
         * @param {Number} index Index of the view
         */
        select: function (index) {
            this._super(index);
            if (this.options.contentOverflow) {
                this._resizeContent();
            }
        },
        /**
         * Overrides the parent constructor to take in account the new options 
         * 'allDisabled' & 'contentOverflow' and to initialize the 'selected'
         * option when the 'activeIndex' is initialized.
         */
        _create: function () {
            /* Default constructor call */
            this._super();
            /* When allDisabled option is set, all the panes are disabled by default */
            if (this.options.allDisabled) {
                this.navContainer.children().addClass('ui-state-disabled');
            }
            if (this.options.contentOverflow) {
                this._resizeContent();
            }
            if (this.options.activeIndex) {
                // Selected tab is memorized for the "getActiveIndex" method (otherwise 0 is returned)
                this.options.selected = this.options.activeIndex;
            }
        },
        /**
         * Resizes the container in function of the view content 
         */
        _resizeContent: function () {
            var navContainerHeight = this.navContainer.outerHeight(true);
            var tabViewHeight = this.panelContainer.parent().height();
            this.panelContainer.css({
                'height': tabViewHeight - navContainerHeight - 3
            });
        },
        /**
         * Enables the view tabs from a list of HTML identifier set for each
         * tab item
         * @param {Array} idList List of HTML identifiers
         */
        enableFromList: function (idList) {
            var menuItemSelector;
            for (var i in idList) {
                menuItemSelector = 'a[href="#' + idList[i] + '"]';
                this.navContainer.children().find(menuItemSelector).parent().removeClass('ui-state-disabled');
            }
        },
        /**
         * Overloads the original's method to resize the view container when the
         * browser window is resized
         */
        _bindEvents: function () {
            // First parent method is called
            this._super();

            // New event on window resize...
            if (this.options.contentOverflow) {
                var $this = this;
                $(window).bind('resize.zdk' + $this.uuid, function () {
                    $this._resizeContent();
                });
            }
        }
    });
});
