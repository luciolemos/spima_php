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
 * PrimeUI List box, extended version for ZnetDK including:
 * OPTIONS:
 * - controller: name of the controller to call for loading listbox content
 * - action: name of the controller action to call for loading listbox content 
 * METHOD:
 * - refresh: refresh the listbox content by a new call to the specified action
 *
 * File version: 1.1
 * Last update: 03/02/2016
 */

/**
 * ZnetDK Listbox widget
 * This listbox can load its data from a ZnetDK controller action.
 */
$(function () {
    $.widget("znetdk.zdklistbox", $.primeui.puilistbox, {
        options: {
            /** PHP controller name */
            controller: null,
            /** PHP action name */
            action: null
        },
        /**
         * Constructs the widget (overrides the parent constructor)
         */
        _create: function () {
            this._super(); // The parent contructor is called...
            this._setActionFromAttribute();
            this._loadData();
        },
        /**
         * Loads the items of the Listbox from the controller action specified
         * through the widget's options
         * @param {Array} selectedItemValues Values of the items to select after
         * the loading.
         */
        _loadData: function (selectedItemValues) {
            if (this.options.controller && this.options.action) {
                var $this = this;
                znetdk.request({
                    control: this.options.controller,
                    action: this.options.action,
                    callback: function (response) {
                        if (response.success === true) {
                            $this._setOption('data', response.rows);
                            $this.selectItemsByValues(selectedItemValues);
                            $this._trigger('dataloaded');
                        } else {
                            znetdk.message('error', response.summary, response.msg);
                        }
                    }
                });
            } else {
                this.resetSelection();
            }
        },
        /**
         * Selects the listbox's items from their specified values
         * @param {Array} selectedItemValues
         */
        selectItemsByValues: function(selectedItemValues) {
            this.unselectAll();
            if ($.type(selectedItemValues) === "array") {
                for (i = 0; i < selectedItemValues.length; ++i) {
                    this.selectItemByValue(selectedItemValues[i]);
                }
            }
        },
        /**
         * Returns the values of the selected items in the Listbox
         * @returns {Array} Selected values
         */
        getSelectedItemValues: function () {
            var selectedItems = new Array();
            this.choices.filter(':selected').each(function() {
                selectedItems.push($(this).attr('value'));
            });
            return selectedItems;
        },
        /**
         * Refresh the Listbox's items from remote data provided by the controller
         * action set as options
         * @param {Boolean} keepSelection Specifies whether the selected items
         * have to be selected again after the refresh
         */
        refresh: function (keepSelection) {
            if (keepSelection) {
                var selectedItems = this.getSelectedItemValues();
                this._loadData(selectedItems);
            } else {
                this._loadData();
            }
        },
        /**
         * Returns the position in the Listbox of its selected items
         * @returns {Array} Positions of the selected items
         */
        getSelectedItemPositions: function () {
            var selectedItems = new Array();
            this.items.filter('.ui-state-highlight').each(function() {
                selectedItems.push($(this).index());
            });
            return selectedItems;
        },
        selectItemsByPosition: function (selectedItems) {
            this.unselectAll();
            if ($.type(selectedItems) === "array") {
                for (i = 0; i < selectedItems.length; ++i) {
                    this.selectItem(selectedItems[i]);
                }
            }
        },
        /**
         * Resets the item selection to its original state (property 'selected'
         * taken in account) 
         */
        resetSelection: function() {
            var $this = this;
            this.unselectAll();
            this.choices.filter('[selected]').each(function () {
                $this.selectItemByValue($(this).val());
            });  
        },
        /**
         * Selects an item in the ListBox from the specified value
         * @param {String} value Value set into the 'value' HTML attribute for
         * the 'choice' HTML element. 
         */
        selectItemByValue: function(value) {
            var index = this.choices.filter("[value='" + value + "']").index();
            this.selectItem(index);
        },
        /**
         * Initializes the controller action from the HTML5 attribute 'data-zdk-action'
         */
        _setActionFromAttribute: function () {
            var actionAttrib = znetdk.getActionFromAttr(this.element);
            if (actionAttrib !== false) {
                this.options.controller = actionAttrib.controller;
                this.options.action = actionAttrib.action;
            }
        }
    });
});