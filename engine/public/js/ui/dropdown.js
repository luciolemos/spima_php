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
 * PrimeUI Dropdown widget, extended version for ZnetDK
 *
 * File version: 1.4
 * Last update: 01/22/2017
 */

/**
 * zdkdropdown widget
 * This dropdown can load its data from a ZnetDK controller action.
 */
$(function () {
    $.widget("znetdk.zdkdropdown", $.primeui.puidropdown, {
        options: {
            /** PHP controller name */
            controller: null,
            /** PHP action name */
            action: null,
            /** No selection item text */
            noSelectionOption: null,
            /** Default selected value */
            defaultSelectedValue: null,
            /** Items are loaded on widget creation from a remote controller action */
            loadOnCreate: 'yes'
        },
        /**
         * Overloaded puidropdown method.
         * The widget width is fixed by default.
         * the options are loaded from the specified controller action.
         */
        _create: function () {
            // Options are initialized from the HTML5 attributes
            this._setOptionsFromAttribute();
            // Default width
            var userStyle = this.element.attr('style');
            if(!userStyle||userStyle.indexOf('width') === -1) {
                this.element.attr('style','width:184px;');
            }
            // Add no selection option if specified
            if (this.options.noSelectionOption &&
                    this.options.controller === null) {
                this.element.prepend('<option value="_">'+
                    this.options.noSelectionOption + '</option>');
                var defaultSel = this.element.find('[selected]');
                if (defaultSel.length === 0) {
                    this.element.val('_');
                }
            }
            // Content is loaded from the specified controller action
            // Only if the 'loadOnCreate' option is set to true
            if (this.options.loadOnCreate === 'yes') {
                this._loadData(this.options.defaultSelectedValue, this._super);
            } else {
                this._super();
            }
        },
        /**
         * Loads the dropdown options from the specified controler action
         * @param {String} selectedItemValue Value currently selected in the 
         * dropdown and that must be kept after loading.
         * @param {function} callbackMethod Method to call once the dropdown
         * elements are loaded
         */
        _loadData: function (selectedItemValue, callbackMethod) {
            selectedItemValue = selectedItemValue === undefined ? null : selectedItemValue;
            if (this.options.controller && this.options.action) {
                var $this = this;
                znetdk.request({
                    control: this.options.controller,
                    action: this.options.action,
                    callback: function (response) {
                        if (response.success === true) {
                            if ($this.options.noSelectionOption) {
                                response.rows.splice(0,0, {
                                    label:$this.options.noSelectionOption,
                                    value:'_'});
                            }
                            if (callbackMethod) {
                                $this.options.data = response.rows;
                                callbackMethod.call($this);
                            } else {
                                $this._setOption('data', response.rows);
                            }
                            $this._changeNoSelectionValue();
                            if (selectedItemValue !== null) {
                                $this.selectValue(selectedItemValue);
                            }
                            $this._trigger('dataloaded');
                        } else {
                            znetdk.message('error', response.summary, response.msg);
                            if (callbackMethod) {
                                callbackMethod.call($this);
                            }
                        }
                    }
                });
            } else {
                if (callbackMethod) {
                    callbackMethod.call(this);
                }
                this._changeNoSelectionValue();
            }
        },
        /**
         * Overloaded puidropdown method.
         * The 'change' event is triggered even if the 'change' option is not
         * defined.
         * @param {Boolean} edited see parent method description
         */
         _triggerChange: function(edited) {
            this._super(edited);
            if(this.options.change === undefined) {
                this._trigger('change');
            }
        },
        /**
         * Refresh the dropdown content.
         * @param {Boolean} keepSelection specifies whether the selection must 
         * be kept after refresh.
         */
        refresh: function (keepSelection) {
            if (keepSelection) {
                var selectedItem = this.getSelectedValue();
                this._loadData(selectedItem);
            } else {
                this._loadData();
            }
        },
        /**
         * Selects the first item of the list
         */
        selectFirst: function() {
            if (this.items !== undefined) {
                this._selectItem(this.items.eq(0), true);
            }
        },
        /**
         * Reset dropdown to default selection
         */
        resetSelection: function() {
            if (this.options.defaultSelectedValue) {
                var selectedOption = this.getSelectedValue();
                if (selectedOption !== this.options.defaultSelectedValue) {
                    this.selectValue(this.options.defaultSelectedValue);
                }
            } else {
                var defaultSel = this.element.find('[selected]');
                if (defaultSel.length === 0) {
                    this.selectFirst();
                } else {
                    this.selectValue(defaultSel.first().val());
                }
            }
        },
        /**
         * Sets the focus to the dropdown 
         */
        setFocus: function () {
            this.focusElement.trigger('focus.puidropdown');
        },
        /**
         * Checks whether a value is selected in the dropdown.
         * @returns {Boolean} true if the current selected value is different 
         * than the character '_' (used to identify the no selectable value). 
         */
        isValueSelected: function() {
            var selectedValue = this.getSelectedValue();
            if (selectedValue === '_') {
                return false;
            }
            return true;
        },
        /**
         * Initializes the widget options from the matching HTML5 attribute:
         * - 'controller' and 'action' from the attribute 'data-zdk-action'.
         * - 'noSelectionOption' from the attribute 'data-zdk-noselection'.
         * - 'defaultSelectedValue' from the attribute 'data-zdk-value'.
         */
        _setOptionsFromAttribute: function () {
            var actionAttrib = znetdk.getActionFromAttr(this.element),
                noSelAttrib = znetdk.getTextFromAttr(this.element,'data-zdk-noselection'),
                defValue = znetdk.getTextFromAttr(this.element,'data-zdk-value'),
                loadOnCreate = znetdk.getTextFromAttr(this.element,'data-zdk-loadoncreate');
            if (actionAttrib !== false) {
                this.options.controller = actionAttrib.controller;
                this.options.action = actionAttrib.action;
            }
            if (noSelAttrib !== false) {
                this.options.noSelectionOption = noSelAttrib;
            }
            if (defValue !== false) {
                this.options.defaultSelectedValue = defValue;
            }
            if (loadOnCreate !== false) {
                this.options.loadOnCreate = loadOnCreate;
            }
        },
        /**
         * Changes the empty value '' by the '_' value to avoid HTML5 validation
         * error when the required property is set for the dropdown.
         */
        _changeNoSelectionValue: function () {
            var optionForNoSelection = this.choices.filter("[value='']");
            if (optionForNoSelection.length) {
                optionForNoSelection.attr('value','_');
            }
        },
        /**
         * Overloads the parent method to force the panel's size to its 
         * container's size (bug fixing for FireFox)
         */
        _initDimensions: function() {
            this._super();
            var labelWidth = this.label.width() - (this.menuIcon.outerWidth(true)- this.menuIcon.width())
                    - (this.label.outerWidth(true) - this.label.width());
            this.label.width(labelWidth);
            this.panel.width(this.container.innerWidth());
        },
        /**
         * Overloads the parent method to enable the widget only if it is disabled
         * and change it's 'disabled' property to false.
         */
        enable: function() {
            if (this.label.hasClass('ui-state-disabled')) {
                this._super();
                this.element.prop('disabled', false);
                this.container.find('input').prop('disabled', false);
            }
        },
        /**
         * Overloads the parent method to disable the HTML select element by
         * setting its HTML 'disabled' property to true. 
         */
        disable: function() {
            this._super();
            this.element.prop('disabled', true);
            this.container.find('input').prop('disabled', true);
        },
        /**
         * Returns the data of the current selected value
         * @returns {object} Full data matching the current selected value 
         */
        getDataOfSelectedValue: function() {
            var value = this.getSelectedValue(),
                option = this.choices.filter('[value="' + value + '"]'),
                index = option.index();
            if (this.options.data && index >= 0 && index < this.options.data.length) {
                var data = this.options.data[index];
                return data;
            } else {
                return false;
            }
        },
        /**
         * Overloads the parent method
         * If the widget is set with the 'loadOnCreate' option set to 'no',
         * the items are loaded before selection if no items exist yet
         * @param {String} value
         */
        selectValue : function(value) {
            if (this.choices.length === 0 && this.options.loadOnCreate !== 'yes') {
                this._loadData(value);
            } else {
                    this._super(value);
                }
            }
    });
});