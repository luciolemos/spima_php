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
 * PrimeUI sticky widget, extended version:
 * NEW OPTIONS:
 *  - scrollableParent: parent element object other than the window element.
 *  - autoWidth       : Does sticky element get css style "auto"? Value true or false.
 *
 * File version: 1.0
 * Last update: 09/18/2015 
 */
$(function () {

    $.widget("primeui.puisticky", $.primeui.puisticky, {
        /**
         * Overrides the default constructor to take in account the two new 
         * 'scrollableParent' and 'autoWidth' options.
         */
        _create: function () {

            var element = this.element;
            var $this = this, win;
            var topPosition, topFixedPosition;

            if (this.options.scrollableParent.length) {
                win = this.options.scrollableParent;
                topPosition = element.position().top;
                topFixedPosition = element.offset().top;
            } else {
                win = $(window);
                topPosition = element.offset().top;
                topFixedPosition = 0;
            }

            this.initialState = {
                top: topPosition,
                topFixed: topFixedPosition,
                height: element.height()
            };

            win.on('scroll', function () {
                if (win.scrollTop() > $this.initialState.top) {
                    $this._fix();
                }
                else {
                    $this._restore();
                }
            });
        },
        /**
         * Returns the width of the sticky element taking in account the new
         * 'autoWidth' option.
         * @returns {Number|String}
         */
        _getWidth: function () {
            if (this.options.autoWidth) {
                return 'auto';
            } else {
                return this.element.width();
            }
        },
        /**
         * Overrides the parent's method to set new values for the 'top' and
         * 'width' css properties.
         */
        _fix: function () {
            if (!this.fixed) {
                this.element.css({
                    'position': 'fixed',
                    'top': this.initialState.topFixed,
                    'z-index': 1000,
                    'width': this.element.width()
                }).addClass('pui-shadow ui-sticky');

                $('<div class="ui-sticky-ghost"></div>').height(this.initialState.height).insertBefore(this.element);

                this.fixed = true;
            }
        },
        /**
         * Overrides the parent's method: the width is fixed thru a call to the
         * new '_getWidth' method
         */
        _restore: function () {
            if (this.fixed) {
                this.element.css({
                    'position': 'static',
                    'top': 'auto',
                    'width': this._getWidth()
                }).removeClass('pui-shadow ui-sticky');
                this.element.prev('.ui-sticky-ghost').remove();
                this.fixed = false;
            }
        }
    });
});