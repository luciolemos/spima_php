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
 * ZnetDK Action bar widget
 *
 * File version: 1.5
 * Last update: 02/13/2017
 */
$.widget("znetdk.zdkactionbar", {
    options: {
        dialogID: null, /* The dialog to open when an action button "New" or "Add" is clicked */
        datatableID: null, /* The datatable to refresh when the action button "Remove" is clicked */
        whenadd: null, /* Call back function called when the add button is pressed  */
        whenedit: null, /* Call back function called when the edit button is pressed  */
        whenremove: null, /* Call back function called when the remove button is pressed  */
        getRemoveConfirm: null /* Call back function called to get removal confirmation */
    },
    _create: function () {
        /* Set dialog and datatable options from their matching attributes */
        this._setOptionsFromAttr();

        /* Action buttons */
        this.element.children('button').wrapAll("<div class='zdk-buttons' />").each(function () {
            $(this).attr('type', 'button');
        });

        /* Selection of the number of rows per page */
        this.selectRows = this.element.children('select.zdk-select-rows').first();
        if (this.selectRows.length) {
            this.selectRows.attr('style', 'width:70px;').removeClass('zdk-select-rows');
            this.selectRows.wrap('<div class="zdk-select-rows"></div>');
            var selectRowsLabel = this.selectRows.attr('title');
            if (selectRowsLabel !== undefined) {
                this.selectRows.before('<div class="zdk-select-rows-label">' + selectRowsLabel + '</div>');
            }
            var selectedOption = this._getDefaultNumberOfSelectedRows();
            this.selectRows.find('option[value=' + selectedOption + ']').first().prop('selected',true);
            /* Init dropdown widget for the select element */
            this.selectRows.puidropdown();
        }
        /* Filter applied to the datatable */
        var filterRows = this.element.children('div.zdk-filter-rows').first();
        if (filterRows.length) {
            this.filterSearch = filterRows.children('.zdk-bt-search').first();
            this.filterClear = filterRows.children('.zdk-bt-clear').first();
            this.filterClear.attr('type', 'button');
            this.filterSearch.attr('type', 'submit');
            this.filterAutoComplete = filterRows.children('input').first();
            this.filterAutoComplete.add(this.filterClear).add(this.filterSearch).wrapAll('<form/>');
            this.filterForm = filterRows.children('form');
            this.filterAutoComplete.each(function () {
                var elementTitle = $(this).attr('title');
                if (elementTitle !== undefined) {
                    $(this).attr("placeholder", elementTitle);
                    $(this).removeAttr('title');
                }
                $(this).attr({"type": "text", "autocomplete": "off"});
                /* Init autocomplete widget */
                var options = {effect: 'fade', effectSpeed: 'fast'},
                    delay = znetdk.getTextFromAttr($(this), 'data-zdk-delay');
                if (delay !== false) {
                    options.delay = delay;
                }
                $(this).puiautocomplete(options);
            });
        }
        this.element.after('<div class="ui-helper-clearfix"/>');
        /* Init button widgets */
        znetdk.initCommonWidgets(this.element);
        /* The clear button is hidden by default */
        this._emptySearchField();
        /* Bind click events of the action bar widgets */
        this._bindEvents();
    },
    _bindEvents: function () {
        var $this = this;

        /* Add button */
        var addButton = this.element.find('button.zdk-bt-add');
        if (addButton.length && this.options.dialogID) {
            addButton.click(function () {
                var dialogTitle = addButton.attr('title');
                if (dialogTitle === undefined) {
                    dialogTitle = addButton.text();
                }
                $this._getDialog().find('span.pui-dialog-title').text(dialogTitle);
                $this._getDialog().find('form').zdkform('reset');
                /* Show dialog and trigger the specified event */
                $this._showDialogAfterSynchronization("whenadd");
            });
        }

        /* Edit button */
        var editButton = this.element.find('button.zdk-bt-edit');
        if (editButton.length && this.options.dialogID && this.options.datatableID) {
            editButton.click(function () {
                var datatableSelection = $this._getSelectedRowInDatatable();
                if (datatableSelection !== false) {
                    $this.editRow(datatableSelection);
                } else {
                    var noSelectionMsg = znetdk.getTextFromAttr(editButton, 'data-zdk-noselection');
                    if (noSelectionMsg !== false) {
                        znetdk.message('warn', $this._getEditDialogTitle(), noSelectionMsg);
                    }
                }
            });
        }

        /* Remove button */
        this.removeButton = this.element.find('button.zdk-bt-remove');
        if (this.removeButton.length && this.options.datatableID) {
            this._on(this.removeButton,{"click": function () {
                var datatableSelection = $this._getSelectedRowInDatatable();
                // Get the dialog title
                var dialogTitle = $this.removeButton.attr('title');
                if (dialogTitle === undefined) {
                    dialogTitle = $this.removeButton.text();
                }
                if (datatableSelection !== false) {
                    /* Event triggered for initialization purpose */
                    if ($this._trigger("whenremove") === false) {
                        return; // action is prevented
                    };
                    // Confirmation message
                    var confirmationMsg = znetdk.getTextFromAttr($this.removeButton, 'data-zdk-confirm');
                    var idName = $this._getRowIdParamName();
                    if (confirmationMsg !== false) {
                        var msgArray = confirmationMsg.split(":");
                        znetdk.getUserConfirmation({
                            title: dialogTitle,
                            message: msgArray[0],
                            yesLabel: msgArray[1],
                            noLabel: msgArray[2],
                            callback: function (confirmation) {
                                if (confirmation) {
                                    $this._removeRow(datatableSelection[idName], idName);
                                }
                            }
                        });
                    } else if (typeof $this.options.getRemoveConfirm === "function") {
                        $this.options.getRemoveConfirm.call($this,$this._removeRow,
                            datatableSelection[idName], idName, dialogTitle);
                    } else {
                        $this._removeRow(datatableSelection[idName], idName);
                    }
                } else {
                    var noSelectionMsg = znetdk.getTextFromAttr($this.removeButton, 'data-zdk-noselection');
                    if (noSelectionMsg !== false) {
                        znetdk.message('warn', dialogTitle, noSelectionMsg);
                    }
                }
            }});
        }
        
        /* Refresh button */
        var refreshButton = this.element.find('button.zdk-bt-refresh');
        if (refreshButton.length && this.options.datatableID) {
            refreshButton.click(function () {
                $this._refreshDatatable(true);
            });
        }

        /* Number of lines selector */
        if (this.selectRows.length && this.options.datatableID) {
            this.selectRows.puidropdown('option', 'change', function () {
                $this._getDatatable().zdkdatatable('setPaginator', 'rows', $(this).val());
                $this._refreshDatatable(true);
                $this._storeDefaultNumberOfSelectedRows($(this).val());
            });
        }

        if (this.filterAutoComplete !== undefined) {
            /* Rows filter input */
            if (this.filterAutoComplete.length
                    && this.options.datatableID) {
                var ctrlAction = znetdk.getActionFromAttr(this.filterAutoComplete);
                this.filterAutoComplete.puiautocomplete('option', 'completeSource', function (request, response) {
                    var autoCompleteElement = this;
                    znetdk.request({
                        control: ctrlAction.controller,
                        action: ctrlAction.action,
                        data: {criteria: request.query},
                        callback: function (data) {
                            response.call(autoCompleteElement, data);
                        }
                    });
                });
            }

            /* Rows filter search button */
            if (this.filterSearch.length && this.filterAutoComplete.length && this.options.datatableID) {
                this.filterForm.submit(function (event) {
                    var filterValue = $this.filterAutoComplete.val();
                    if (filterValue === '') {
                        var noValueMsg = znetdk.getTextFromAttr($this.filterSearch, 'data-zdk-novalue');
                        if (noValueMsg !== false) {
                            znetdk.message('warn', $this.filterSearch.attr('title'), noValueMsg);
                        }
                    } else {
                        if ($this._trigger('search', event, {value: filterValue})) {
                            $this._getDatatable().zdkdatatable('filterRows', filterValue);
                        }
                        if ($this.filterClear.length) {
                            $this.filterClear.show();
                            $this.filterAutoComplete.select();
                        }
                    }
                    event.preventDefault();
                });
            }

            /* Rows filter clear button */
            if (this.filterClear.length && this.filterAutoComplete.length && this.options.datatableID) {
                this.filterClear.click(function (event) {
                    $this._emptySearchField();
                    if ($this._trigger('search', event, {value: null})) {
                        $this._getDatatable().zdkdatatable('filterRows', null);
                    }
                    $this.filterAutoComplete.focus();
                });
            }
        }
    },
    editRow: function(row) {
        if (this.options.dialogID && this.options.datatableID) {
            // Get the dialog title
            var dialogTitle = this._getEditDialogTitle();
            // Init the data of the dialog form from the datatable row
            this._getDialog().find('form').zdkform('init', row);
            // Set the title of the dialog
            this._getDialog().find('span.pui-dialog-title').text(dialogTitle);
            // Show the dialog and trigger the specified event
            this._showDialogAfterSynchronization("whenedit");
        } 
    },
    _showDialogAfterSynchronization: function(eventName) {
        var $this = this, 
            synchronizedWidget = this._getDialog().find('.zdk-synchronize');
        if (synchronizedWidget.length === 0) {
            this._trigger(eventName);
            this._getDialog().zdkmodal("show", true);
            return;
        }
        if (synchronizedWidget.hasClass('zdk-datatable')) {
            synchronizedWidget.one("zdkdatatabledataloaded", function () {
                $this._getDialog().zdkmodal("show", true);
            });
            this._trigger(eventName);
        } else if (synchronizedWidget.hasClass('zdk-inputrows')) {
            synchronizedWidget.one("zdkinputrowsinitialized", function () {
                $this._getDialog().zdkmodal("show", true);
            });
            this._trigger(eventName);
        } else {
            // Unsupported widget
            this._trigger(eventName);
            this._getDialog().zdkmodal("show", true);
        }
    },
    _getEditDialogTitle: function() {
        var editButton = this.element.find('button.zdk-bt-edit');
        if (editButton.length === 1) {
            var dialogTitle = editButton.attr('title');
            if (dialogTitle === undefined) {
                dialogTitle = editButton.text();
            }
            return dialogTitle;
        } else {
            return 'No title!';
        }
    },
    _getDatatable: function () {
        return $('#' + this.options.datatableID);
    },
    _refreshDatatable: function(keepSelection) {
        if (this.options.datatableID === null) {
            return;
        }
        if (keepSelection === true) {
            this._getDatatable().zdkdatatable('option','keepSelectionOnId', this._getRowIdParamName());
        } else {
            this._getDatatable().zdkdatatable('option','keepSelectionOnId', null);
        }
        this._getDatatable().zdkdatatable('refresh');
    },
    _getDialog: function () {
        return $('#' + this.options.dialogID);
    },
    _setOptionsFromAttr: function () {
        var datatableID = znetdk.getTextFromAttr(this.element, 'data-zdk-datatable');
        if (datatableID !== false) {
            this.options.datatableID = datatableID;
        }
        var dialogID = znetdk.getTextFromAttr(this.element, 'data-zdk-dialog');
        if (dialogID !== false) {
            this.options.dialogID = dialogID;
        }
    },
    /**
     * Retrieve in the local storage the number of rows to display in the 
     * tied datatable widget 
     * @returns {String} Number of rows displayed by default
     */
    _getDefaultNumberOfSelectedRows: function() {
        var storageKeyPrefix = 'zdkdatatable_rows_',
            storageKey = storageKeyPrefix + this.options.datatableID,
            firstOptionValue = this.selectRows.find('option').first().val();
        if(storageKey === storageKeyPrefix) {
            return firstOptionValue;
        }
        try {
            var storedValue = localStorage.getItem(storageKey)
            if(storedValue) {
                return storedValue;
            } else {
                return firstOptionValue;
            }
        } catch(e) {
            console.log('zdkactionbar: no local storage available to retrieve the number of rows to display!');
            return firstOptionValue;
        }
    },
    _storeDefaultNumberOfSelectedRows: function(numberOfRows) {
        var storageKeyPrefix = 'zdkdatatable_rows_',
            storageKey = storageKeyPrefix + this.options.datatableID;
        if(storageKey === storageKeyPrefix) {
            return false;
        }
        try {
            localStorage.setItem(storageKey, numberOfRows);
        } catch(e) {
            console.log('zdkactionbar: no local storage available to store the number of rows to display!');
            return false;
        }
    },
    _getSelectedRowInDatatable: function () {
        if (this.options.datatableID) {
            var selections = this._getDatatable().zdkdatatable('getSelection');
            if (selections.length === 1 && selections[0]) {
                return selections[0];
            } else {
                return false;
            }
        } else {
            return false;
        }
    },
    _removeRow: function (rowID, idName) {
        var $this = this,
            ctrlAction = znetdk.getActionFromAttr(this.removeButton),
            row = new Object();
        row[idName] = rowID;
        znetdk.request({
            control: ctrlAction.controller,
            action: ctrlAction.action,
            data: row,
            callback: function (response) {
                if (response.success) {
                    if ($this.options.datatableID) {
                        $this._refreshDatatable();
                    }
                    $this._trigger('rowremoved');
                }
                if (response.summary && response.msg) {
                    var levelMsg = response.success === false ? 'error' : 
                            response.warning === true ? 'warn' : 'info';
                    znetdk.message(levelMsg, response.summary, response.msg);
                }
            }
        });
    },
    _getRowIdParamName: function () {
        var idName = 'id', inputElement = this._getDialog().find('input.zdk-row-id');
        if (inputElement.length) {
            var idNameFound = znetdk.getTextFromAttr(inputElement, 'name');
            idName = idNameFound ? idNameFound : idName;
        }
        return idName;
    },
    /**
     * Empties the search field and hides the clear button.
     */
    _emptySearchField: function() {
        if (this.filterAutoComplete !== undefined) {
            this.filterAutoComplete.val('');
        }
        if (this.filterClear !== undefined) {
            this.filterClear.hide();
        }
    }
    
});
