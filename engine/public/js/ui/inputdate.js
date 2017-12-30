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
 * ZnetDK input date widget
 *
 * File version: 1.1
 * Last update: 09/04/2016 
 */
$(function () {
    $.widget("znetdk.zdkinputdate", $.primeui.puiinputtext, {
        options: {
            regional: ""
        },
        /**
         * Widget's constructor
         */
        _create: function () {
            this.element.attr('type', 'text');
            this.element.addClass('zdk-date');
            if (this.options.regional === 'en') { // "en" is unknown for the datepicker
                this.options.regional = ""; // "" is English as default language
            }
            try {
                this.dateFormat = $.datepicker.regional[this.options.regional].dateFormat;
                this.element.datepicker($.datepicker.regional[this.options.regional]);
            } catch (error) {
                console.info('Regional attribute "' + this.options.regional + '" not supported by the datepicker widget.');
                this.dateFormat = $.datepicker.regional[""].dateFormat;
                this.element.datepicker($.datepicker.regional[""]);
            }

            /* Call of the default constructor */
            this._super();
        },
        /**
         * Sets the date from its W3C string format
         * @param {String} W3CstringDate Date in format 'yy-mm-dd'
         */
        setW3CDate: function (W3CstringDate) {
            try {
                var dateObject = $.datepicker.parseDate('yy-mm-dd', W3CstringDate);
                this.element.val($.datepicker.formatDate(this.dateFormat, dateObject));
            } catch (error) {
                console.info('String date "' + W3CstringDate + '" is not a valid W3C date.');
                this.element.val(W3CstringDate);
            }
        },
        /**
         * Init the input date with the current date
         * @param {String} difference number of days, weeks, months or years
         * before of after the current date to set.
         */
        setCurrentDate: function(difference) {
            if (difference === undefined) {
                this.element.datepicker('setDate', 'today');
            } else {
                this.element.datepicker('setDate', difference);
            }
        },
        /**
         * Returns the entered date in W3C string format 
         * @returns {String} Date in format 'yy-mm-dd'
         */
        getW3CDate: function () {
            return this._getDate();
        },
        /**
         * Checks if the entered date is OK
         * @returns {Boolean} Value true is the date is valid, false otherwise
         */
        checkDate: function() {
            return this._getDate(true);
        },
        /**
         * Returns the date typed in the widget
         * @param {type} checkOnly Indicates whether the date has only to be
         * checked
         * @returns {Boolean|String} The entered date or its validity status
         */
        _getDate:  function (checkOnly) {
            var inputTextDate = this.element.val(),
                checking = checkOnly === undefined ? false : Boolean(checkOnly);
            try {
                var dateObject = $.datepicker.parseDate(this.dateFormat, inputTextDate);
                return checking ? true : $.datepicker.formatDate('yy-mm-dd', dateObject);
            } catch (error) {
                console.info('Input date "' + inputTextDate + '" does not match the format ' + this.dateFormat + '.');
                return checking ? false : inputTextDate;
            }
        }
    });
});
