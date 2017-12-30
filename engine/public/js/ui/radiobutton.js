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
 * PrimeUI RadioButton Widget, extended version with:
 *   - Method 'select' to select a radio button from its value
 *   - Version compatible with jQuery 1.9
 *
 * File version: 1.1
 * Last update: 12/30/2015
 */
$(function () {
    var checkedRadios = {};
    $.widget("primeui.puiradiobutton", $.primeui.puiradiobutton, {
        /**
         * Overrides the parent constructor to take in account the HTML 'checked'
         * property
         */
        _create: function () {
            /* Call of the default constructor */
            this.silent = false; /* change events are triggered by default */
            this._super();
            if (this.element.prop('checked')) {
                checkedRadios[this.element.attr('name')] = this.box;
            }
        },
        /**
         * Overrides the parent's method to re-develop the handlers of the 'clic'
         * and 'change' events. 
         */
        _bindEvents: function () {
            // First parent method is called
            this._super();

            var $this = this;
            this.box.off('click.puiradiobutton');
            this.box.on('click.puiradiobutton', function () {
                if (!$this._isChecked()) {
                    $this.element.trigger('click');
                }
            });
            this.element.off('change');
            this.element.change(function (e) {
                var name = $this.element.attr('name');
                if (checkedRadios[name]) {
                    checkedRadios[name].removeClass('ui-state-active ui-state-focus ui-state-hover').children('.pui-radiobutton-icon').removeClass('ui-icon ui-icon-bullet');
                }

                $this.icon.addClass('ui-icon ui-icon-bullet');
                if (!$this.element.is(':focus')) {
                    $this.box.addClass('ui-state-active');
                }

                checkedRadios[name] = $this.box;
                if ($this.silent) {
                    $this.silent = false;
                } else {
                    $this._trigger('selectionchange', null);
                }
            });
        },
        /**
         * Overrides the parent's method: event handlers are disabled if no label
         * is set for the button radio
         */
        _unbindEvents: function () {
            // First parent method is called
            this._super();

            if (this.label.length > 0) {
                this.element.off();
            }
        },
        /**
         * Overrides the parent's method to set the 'disabled' property to false
         */
        enable: function () {
            this.element.prop('disabled', false);
            // Next, parent method is called
            this._super();

        },
        /**
         * Overrides the parent's method to set the 'disabled' property to false
         */
        disable: function () {
            // First, parent method is called
            this._super();
            this.element.prop('disabled', true);
        },
        /**
         * Selects the radio button if its value matches the specified value
         * @param {String} value Value of the HTML input element 
         */
        select: function (value) {
            if (!this.disabled && !this._isChecked() && this.element.val() === value) {
                this.silent = true; // Change event is not triggered
                this.element.trigger('click');
            }
        },
        /**
         * Unselects the radio button
         */
        unselect: function () {
            if (!this.disabled && this._isChecked()) {
                this.box.removeClass('ui-state-active ui-state-focus ui-state-hover').children('.pui-radiobutton-icon').removeClass('ui-icon ui-icon-bullet');
                this.element.removeProp('checked');
                delete checkedRadios[this.element.attr('name')];
            }
        }
    });
});