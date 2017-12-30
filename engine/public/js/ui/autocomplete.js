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
 * PrimeUI Autocomplete widget, extended version for ZnetDK.
 *
 * File version: 1.4
 * Last update: 02/13/2017
 */

/**
 * zdkautocomplete widget
 * This widget can request and retrieve data from a ZnetDK controller action.
 */
$(function () {
    $.widget("znetdk.zdkautocomplete", $.primeui.puiautocomplete, {
        options: {
            /** PHP controller name */
            controller: null,
            /** PHP action name */
            action: null,
            /** Criteria to pass to the controller to limit the suggestions */
            criteria: null
        },
        /**
         * Overloaded _create method.
         */
        _create: function () {
            // Controller and action are initialized as options from the 
            // matching HTML5 attribute.
            this._setOptionsFromAttribute();
            
            // Setting the AJAX request to send for getting suggestions...
            this._setOptionCompleteSource();
            
            // Add attribute autocomplete="off"...
            this.element.attr("autocomplete", "off");
            
            // Autocomplete not suspended by default
            this.suspended = false;
            
            // The parent contructor is called...
            this._super();
        },
        /**
         * Initializes the widget options from the matching HTML5 attribute:
         * - 'controller' and 'action' from the attribute 'data-zdk-action'.
         */
        _setOptionsFromAttribute: function () {
            var actionAttrib = znetdk.getActionFromAttr(this.element);
            if (actionAttrib !== false) {
                this.options.controller = actionAttrib.controller;
                this.options.action = actionAttrib.action;
            }
            var delay = znetdk.getTextFromAttr(this.element, 'data-zdk-delay');
            if (delay !== false) {
                this.options.delay = delay;
            }
        },
        /**
         * Initialize the option 'completeSource' to query suggestions from
         * the controller action specified for the widget. 
         */
        _setOptionCompleteSource: function() {
            if (this.options.action !== undefined) {
                var $this = this;
                this.options.completeSource = function(request, response) {
                    if ($this.suspended) {
                        return false;
                    }
                    var requestData = {query: request.query};
                    if ($this.options.criteria !== null) {
                        requestData.criteria = $this.options.criteria;
                    }
                    znetdk.request({
                        control: $this.options.controller,
                        action: $this.options.action,
                        data: requestData,
                        callback: function (data) {
                            response.call($this, data);
                        }
                    });
                };
            }
        },
        /**
         * Enables the inputtext widget
         */
        enable: function() {
            this.element.puiinputtext('enable');
        },
        /**
         * Disables the inputtext widget
         */
        disable: function() {
            this.element.puiinputtext('disable');
        },
        /**
         * Suspends the autocomplete function
         * @param {Boolean} isSuspended Value true for suspending the autocomplete
         * or false for restoring the autocomplete.
         */
        suspend: function(isSuspended) {
            this.suspended = isSuspended;
        },
        /**
         * Sets the criterium to pass to the controller to limit the suggested
         * items to those matching the criterium
         * @param {string} criteria Criterium to send to the PHP controller
         */
        setCriteria: function(criteria) {
            this.options.criteria = criteria;
        }
    });
});