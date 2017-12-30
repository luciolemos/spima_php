/**
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website http://www.znetdk.fr 
 * Copyright (C) 2016 Pascal MARTINEZ (contact@znetdk.fr)
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
 * PrimeUI InputText Widget
 * ZnetDK Extended version of the PrimeUI InputText Widget
 * File version: 1.0
 * Last update: 03/05/2016
 */
$(function () {
    $.widget("primeui.puiinputtext", $.primeui.puiinputtext, {
        _enableMouseEffects: function () {
            var input = this.element;
            input
                .on('mouseenter.puiinputtext mouseleave.puiinputtext', function () {
                    input.toggleClass('ui-state-hover');
                })
                .on('focus.puiinputtext', function () {
                    input.addClass('ui-state-focus');
                })
                .on('blur.puiinputtext', function () {
                    input.removeClass('ui-state-focus');
                });
        },
        _disableMouseEffects: function () {
            var input = this.element;
            input.off( "mouseenter.puiinputtext mouseleave.puiinputtext focus.puiinputtext blur.puiinputtext" );
        },
        disable: function () {
            if (this.element.prop('disabled') === false) {
                this._super();
            }
        },
        enable: function () {
            if (this.element.prop('disabled') === true) {
                this._super();
            }
        }
    });
});