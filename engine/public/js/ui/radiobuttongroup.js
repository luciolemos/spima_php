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
 * ZnetDK Group box for radio buttons
 *
 * File version: 1.1
 * Last update: 11/20/2016
 */

/**
 * zdkradiobuttongroup widget
 * Instantiates the primeUI radio buttons and positions them in an HTML table.
 * The html BR element is used as a line breaker between two radio buttons.
 */
$(function () {
    $.widget("znetdk.zdkradiobuttongroup", {
        options: {
            name: null /** 'name' attribute value for the radio buttons in the group  */
        },
        /**
         * Constructs the widget: all the radio buttons declared within the 
         * widget's element are instantiated
         */
        _create: function () {
            this._setNameFromAttribute();
            // Init the 'name' attribute to each radio button
            var $this = this, tableElement = null, currentRow = null;
            this.element.find(':radio').each(function () {
                var labelElement = $(this).next(),
                    lineBreakElement = labelElement.next();

                $(this).uniqueId().attr('name',$this.options.name);
                
                if ($(this).prop('checked')) { // Default value is memorized 
                    $this.defaultValue = $(this).val();
                }
                
                if (!labelElement.is('label')) {
                    labelElement = $('<label>no label!</label>').insertAfter($(this));
                }
                labelElement.attr('for',$(this).attr('id'));
                
                $(this).wrap('<td/>');
                labelElement.wrap('<td/>');

                var elementsToWrap = $(this).parent().add(labelElement.parent());
                if (currentRow === null) {
                    elementsToWrap.wrapAll('<tr/>');
                    currentRow = $(this).parent().parent();
                } else {
                    elementsToWrap.appendTo(currentRow);
                }
                if (tableElement === null) {
                    currentRow.wrap('<table/>');
                    tableElement = currentRow.parent();
                } else {
                    tableElement.append(currentRow);
                }
                
                if (lineBreakElement.is('br')) {
                    currentRow = null;
                    lineBreakElement.remove();
                }
            });
        },
        /**
         * Sets the group name of the radio buttons declared into the widget
         */
        _setNameFromAttribute: function () {
            var nameAttrib = znetdk.getTextFromAttr(this.element, 'data-name');
            if (nameAttrib !== false) {
                this.options.name = nameAttrib;
            }
        },
        /**
         * Resets the selected radio button in the group
         * If a radio button is originally selected by default, its selection
         * is restored.
         */
        resetSelection: function() {
            this.element.find(':radio').each(function () {
                $(this).puiradiobutton('unselect');
                $(this).puiradiobutton('enable');
            });
            if (this.defaultValue !== undefined) {
                var radioToSelect = this.element.find('[value="'+this.defaultValue+'"]');
                if (radioToSelect.length === 1) {
                    radioToSelect.puiradiobutton('select',this.defaultValue);
                }
            }
        }
    });
});