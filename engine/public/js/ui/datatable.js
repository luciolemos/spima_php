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
 * PrimeUI Datatable Widget: extended version with lazy mode enhancement
 *
 * File version: 1.6
 * Last update: 01/16/2017
 */
$(function () {
    $.widget("znetdk.zdkdatatable", $.primeui.puidatatable, {
        /**
         * New options of the overloaded ZnetDK datatable
         */
        options: {
            controller: null, /* ZnetDK PHP controller name */
            action: null, /* ZnetDK PHP action name */
            selectionMode: 'single', /* Single line selection by default */
            totalInCaption: true, /* The total number of rows is displayed in the caption */
            filterCriteria: null, /* Filter criteria to limit the rows to display */
            keepSelectionOnId: null, /* The selection is kept on refresh through the specified identifier name */
            resizableColumns: false
        },
        /**
         * Overrides the parent constructor to load the datatable content from the
         * data transmitted by the remote controller's action.
         */
        _create: function () {
            this.element.addClass('zdk-datatable');
            this.lastSelection = null; // Memorize the last selected row
            this._setColumnsFromAttribute(); // Definition of the columns provided thru the HTML attribute data-zdk-columns.
            this._setActionFromAttribute(); // Definition of the controller and action provided thru the HTML attribute data-zdk-action.
            this._setCaptionFromTitleAttribute(); // Table caption is set thru the HTML attribute title
            this._setPaginatorFromAttribute(); // Paginator option is set thru the HTML attribute data-zdk-paginator
            this._setResizableColumnsFromAttribute(); // ResizableColumns option is set thru the HTML attribute data-zdk-cols-resize
            this._setMultipleSelectionFromAttribute(); // selectionMode option set to 'multiple' thru the HTML attribute data-zdk-multiselect
            this._getNumberOfPaginatorRowsFromLocalStorage(); // The locally stored paginator 'rows' option is read if exists 
            if (this.options.controller && this.options.action) {
                this.options.lazy = true;
                this.options.datasource = this._getRemoteDataSource();
            }
            this._super(); // The parent contructor is called...
        },
        _initSelection: function() {
            this._super();
            this._handleDoubleClickEvent();
        },
        _handleDoubleClickEvent: function() {
            var $this = this;
            $(document).off('dblclick.datatable', this.rowSelector)
            .on('dblclick.datatable', this.rowSelector, null, function(event) {
                if(!$(event.target).is(':input,:button,a')) {
                    var row = $(this),
                        rowIndex = $this._getRowIndex(row),
                        selectedData = $this.data[rowIndex],
                        actionBarElement = $this.element.parent()
                            .find('div.zdk-action-bar[data-zdk-datatable='+ $this.id +']');
                    if (actionBarElement.length === 1) {
                        actionBarElement.zdkactionbar('editRow', selectedData);
                    }
                    $this._trigger("rowdblclicked", $this, selectedData);
                }
            });    
        },
        /**
         * Returns as a datasource the function calling the remote action 
         * controller in charge of loading the datatable rows. 
         * @returns {Function} The call back function for requesting table rows 
         */
        _getRemoteDataSource: function () {
            return function (callback_function, ui) {
                var $this = this, requestData = {};
                if (ui.sortField && ui.sortOrder) { // sort parameters
                    requestData.sortfield = ui.sortField;
                    requestData.sortorder = ui.sortOrder;
                }
                if (this.options.paginator) { // pagination parameters
                    var first = 0, rows = this.options.paginator.rows;
                    if (ui.first >= 0 && ui.rows >= 0) {
                        first = ui.first;
                        rows = ui.rows;
                    }
                    requestData.first = first;
                    requestData.rows = rows;
                }
                if (this.options.filterCriteria) { // filter parameter
                    requestData.search_criteria = this.options.filterCriteria;
                }
                znetdk.request({
                    control: this.options.controller,
                    action: this.options.action,
                    data: requestData,
                    callback: function (response) {
                        if (response.success === true) {
                            if (response.total >= 0) {
                                $this.setPaginator('totalRecords', parseInt(response.total));
                                $this._setTotalInCaption(response.total);
                            }
                            if (response.warning && response.summary && response.msg) {
                                znetdk.message('warn',response.summary,response.msg);
                            }
                            callback_function.call($this, response.rows);
                            $this._trigger('dataloaded', null);
                            if ($this.options.keepSelectionOnId !== null
                                    && $this.options.keepSelectionOnId !== undefined
                                    && $this.lastSelection !== undefined 
                                    && $this.lastSelection !== null) {
                                var selectedRowId = $this.lastSelection[$this.options.keepSelectionOnId];
                                $this.selectRowById(selectedRowId, false);
                                $this.lastSelection = null;
                            }
                        } else {
                            znetdk.message('error', response.summary, response.msg);
                        }
                    }
                });
            };
        },
        _onLazyLoad: function (data) { /* Method override : call of the puipaginator refresh method */
            if (this.paginator !== undefined) {
                // called before refreshing rows in the datatable because selected page could be modified
                this.paginator.puipaginator('refresh');
            }
            this._super(data);
        },
        /**
         * Refreshes the datatable content and the paginator page links.
         * @param {Integer} totalRecordsVariation Number of records added or
         * removed from the last content loading (default value is zero).
         */
        refresh: function (totalRecordsVariation) {
            if (this.options.keepSelectionOnId !== null) {
                this.lastSelection = this.getSelection().length === 1 ?
                    this.getSelection()[0] : null;
            }
            if (this.paginator) {
                var totalVariation = totalRecordsVariation ? totalRecordsVariation : 0,
                        totalRecords = this.paginator.puipaginator('option', 'totalRecords');
                totalRecords += totalVariation;
                totalRecords = totalRecords >= 0 ? totalRecords : 0;
                this.setPaginator('totalRecords', totalRecords);
                this.paginator.puipaginator('refresh');
                this.paginate();
            } else {
                this._updateDatasource(this.options.datasource);
            }
        },
        /**
         * Filters the content of the datatable from the parameter 'criteria'
         * @param {String} criteria Criteria sent to the remote controller to
         * filter the content of datatable.
         */
        filterRows: function (criteria) {
            this.setPaginator('page', 0); // Current page forced to zero because the total records is unknown
            if (criteria) {
                this.options.filterCriteria = criteria;
            } else {
                this.options.filterCriteria = null;
            }
            this.paginate();
        },
        /**
         * Sets a paginator option
         * @param {String} key Name of the option ('setPage' or 'totalRecords')
         * @param {Integer} value Value of the specified option
         */
        setPaginator: function (key, value) {
            if (this.paginator) {
                if (key === 'page') {
                    this.paginator.puipaginator('setPage', value, true); // The paginate must not be triggered
                } else {
                    this.paginator.puipaginator('option', key, value);
                }
            }
            if (this.options.paginator && this.options.paginator.totalRecords >= 0
                    && key === 'totalRecords') {
                this.options.paginator.totalRecords = value; // option value used by the parent _Initialize() method
            }
        },
        /**
         * Initializes the column definition from the 'data-zdk-columns' HTML5
         * attribute
         */
        _setColumnsFromAttribute: function () {
            var columnsAttrib = znetdk.getTextFromAttr(this.element, 'data-zdk-columns');
            if (columnsAttrib !== false) {
                this.options.columns = $.parseJSON(columnsAttrib);
                // The 'data-zdk-columns' attribute is removed because it is no longer usefull
                this.element.removeAttr('data-zdk-columns');
            }
        },
        /**
         * Initializes the remote controller and action from the HTML5 attribute
         * 'data-zdk-action'.
         */
        _setActionFromAttribute: function () {
            var actionAttrib = znetdk.getActionFromAttr(this.element);
            if (actionAttrib !== false) {
                this.options.controller = actionAttrib.controller;
                this.options.action = actionAttrib.action;
                // The 'data-zdk-action' attribute is removed because it is no longer usefull
                this.element.removeAttr('data-zdk-action');
            }
        },
        /**
         * Initializes the datatable caption from the HTML title attribute
         */
        _setCaptionFromTitleAttribute: function () {
            var titleAttrib = znetdk.getTextFromAttr(this.element, 'title');
            if (titleAttrib !== false) {
                this.options.caption = titleAttrib;
                // The title attribute is removed because it is no longer usefull
                this.element.removeAttr('title');
            }
        },
        /**
         * Initializes the datatable paginator from the data-zdk-paginator, in
         * particular the number of rows to display per page.
         */
        _setPaginatorFromAttribute: function () {
            var paginatorOptions = znetdk.getTextFromAttr(this.element, 'data-zdk-paginator');
            if (paginatorOptions !== false) {
                var paginatorArray = paginatorOptions.split(":"),
                        paginatorObject = {};
                paginatorObject.rows = paginatorArray.length > 0 ?
                        parseInt(paginatorArray[0]) : 10;
                paginatorObject.totalRecords = 0;
                paginatorObject.pageLinks = paginatorArray.length === 2 ?
                        parseInt(paginatorArray[1]) : 5;
                this.options.paginator = paginatorObject;
                // The 'data-zdk-paginator' attribute is removed because it is no longer usefull
                this.element.removeAttr('data-zdk-paginator');
            }
        },
        /**
         * Enables columns resizing if the data-zdk-cols-resize attribute is set
         * to 'true' for the datatable
         */
        _setResizableColumnsFromAttribute: function(){
            var resizableOption = znetdk.getTextFromAttr(this.element, 'data-zdk-cols-resize');
            if (resizableOption !== false) {
                this.options.resizableColumns = resizableOption === 'true' ? true : false;
                // The 'data-zdk-cols-resize' attribute is removed because it is no longer usefull
                this.element.removeAttr('data-zdk-cols-resize');
            }
        },
        _setMultipleSelectionFromAttribute: function() {
            var selectionModeOption = znetdk.getTextFromAttr(this.element, 'data-zdk-multiselect');
            if (selectionModeOption !== false) {
                this.options.selectionMode = selectionModeOption === 'true' ? 'multiple' : 'single';
                // The 'data-zdk-multiselect' attribute is removed because it is no longer usefull
                this.element.removeAttr('data-zdk-multiselect');
            }  
        },
        /**
         * Retrieves from the local storage the number of paginator rows and set
         * it by default for the datatable
         * @returns {Boolean} Value true if OK, false otherwise
         */
        _getNumberOfPaginatorRowsFromLocalStorage: function() {
            var storageKeyPrefix = 'zdkdatatable_rows_',
                storageKey = storageKeyPrefix + this.element.attr('id');
            if(storageKey === storageKeyPrefix) {
                return false;
            }
            try {
                var storedValue = localStorage.getItem(storageKey);
                if(storedValue) {
                    this.options.paginator.rows = Number(storedValue);
                    return true;
                } else {
                    return false;
                }
            } catch(e) {
                console.log('zdkdatatable: no local storage available to retrieve the paginator rows number!');
                return false;
            }
        },
        /**
         * Overrides the parent method to take in account the 'lazy' option (bug
         * fixing)
         * @param {jQuery element} row Element of the datatable row as a jQuery
         * object.
         * @returns {Number}
         */
        _getRowIndex: function (row) {
            var index = row.index();
            var first = this.options.lazy ? 0 : this._getFirst();
            return this.options.paginator ? first + index : index;
        },
        /**
         * Overrides the parent method to take in account the 'lazy' option (bug
         * fixing)
         * @param {Integer} rowIndex Index of the row to add to the selection
         */
        _addSelection: function (rowIndex) {
            var first = this.options.lazy ? this._getFirst() : 0;
            if (!this._isSelected(first + rowIndex)) {
                this.selection.push(first + rowIndex);
            }
        },
        /**
         * Overrides the parent method to take in account the lazy option
         * (bug fixing)
         * @param {Integer} rowIndex Index of the row to remove from the
         * selection.
         */
        _removeSelection: function (rowIndex) {
            var first = this.options.lazy ? this._getFirst() : 0;
            this.selection = $.grep(this.selection, function (value) {
                return value !== first + rowIndex;
            });
        },
        /**
         * Overloads the original 'getSelection' method (Bug fixing)
         * @returns {Array} The currently selected rows
         */
        getSelection: function () {
            var first = this.options.lazy ? this._getFirst() : 0;
            var selections = [];
            if (this.selection === undefined) {
                return [];
            }
            for (var i = 0; i < this.selection.length; i++) {
                selections.push(this.data[this.selection[i] - first]);
            }
            return selections;
        },
        /**
         * Selects a datatable row according to the value of the specified column
         * @param {string} columnField Name of the column (identifier)
         * @param {string} value Value to look for
         */
        selectRowByValue: function (columnField, value) {
            var columnIndex = null, selectedRow = null;

            for (var j = 0; j < this.options.columns.length; j++) {
                var columnOptions = this.options.columns[j];
                if (columnOptions.field === columnField) {
                    columnIndex = j + 1;
                    break;
                }
            }

            if (columnIndex === null) { // The specified column field does not exist!
                console.log("zdkdatatable.selectRowByValue(): column '" + columnField + "' not found!");
                return;
            }

            this.element.find('tr > td:nth-child(' + columnIndex + ')').each(function () {
                var currentValue = $(this).text();
                if (currentValue === value) {
                    selectedRow = $(this).parent();
                    return false;
                }
            });
            if (selectedRow !== null && selectedRow.length === 1) {
                this.selectRow(selectedRow);
            }
        },
        /**
         * Selects the datatable row matching the specified element Identifier 
         * @param {string} id Element identifier of the datatable row 
         * @param {boolean} silent If true, no event is triggered after the 
         * row selection
         */
        selectRowById: function(id, silent) {
            var idName = this.options.keepSelectionOnId !== null ?
                this.options.keepSelectionOnId : 'id',
                currentIndex = null;
            for (var j = 0; j < this.data.length; j++) {
                if (this.data[j][idName] === id) {
                    currentIndex = j + 1;
                    break;
                }
            }
            if (currentIndex !== null) {
                var matchingRow = this.element.find('tr:nth-child(' + currentIndex + ')');
                this.selectRow(matchingRow, silent);
            }
        },
        /**
         * Empties the table content (all rows are removed) 
         */
        empty: function() {
            this.data = [];
            this._setTotalInCaption(0);
            this.filterCriteria = null;
            if (this.paginator !== undefined) {
                this.setPaginator('page', 0);
                this.setPaginator('totalRecords', 0);
                this.paginator.puipaginator('refresh');
            }
            this._renderData();
        },
        /**
         * Overrides the original method to call the '_addColumnTooltips' each
         * time rows are loaded in the datatable.
         */
        _renderData: function() {
            this._super();
            this._addColumnTooltips();
            this._addIconLinks();
            this._makeColumnsResizable();
        },
        /**
         * Add tooltips to the datatable column cells set with the 'tooltip'
         * property set to true 
         */
        _addColumnTooltips: function() {
            if(this.data) {
                var tooltipColumns = [];
                // Is tooltips set for the datatable columns ?
                for(var j = 0; j < this.options.columns.length; j++) {
                    var columnOptions = this.options.columns[j];
                    if (columnOptions.tooltip === true) {
                        tooltipColumns.push(j+1);
                    }
                }
                // For each row, the 'title' attribute is added to the specified columns position
                for(var i = 0; i < tooltipColumns.length; i++) {
                    var columnPosition = tooltipColumns[i];
                    this.tbody.find('td:nth-child(' + columnPosition + ')').each(function(){
                        $(this).attr('title', $(this).text());
                    });
                }
            }
        },
        /**
         * When the property 'icon' is set for a column, the specified icon is
         * added to each row of the column as a hyperlink.
         */
        _addIconLinks: function() {
            var $this = this;
            if(this.data) {
                var iconColumns = [];
                // Is icon set for the datatable columns ?
                for(var j = 0; j < this.options.columns.length; j++) {
                    var columnOptions = this.options.columns[j];
                    if (columnOptions.icon !== undefined && columnOptions.fieldValueTriggered !== undefined) {
                        iconColumns.push({
                            position:j+1,
                            iconClass:columnOptions.icon,
                            fieldValueTriggered:columnOptions.fieldValueTriggered
                        });
                    }
                }
                // For each row, the icon is added to the specified columns position
                for(var i = 0; i < iconColumns.length; i++) {
                    var columnPosition = iconColumns[i].position;
                    this.tbody.find('td:nth-child(' + columnPosition + ')').each(function(){
                        var iconPrefix = iconColumns[i].iconClass.substring(0,3),
                            cellValue = $(this).text(),
                            doesCellValueExist = cellValue.length,
                            iconClassWithValue = doesCellValueExist && iconPrefix === 'ui-' ? ' ui-icon-with-value' : '',
                            iconClasses = (iconPrefix === 'fa-' ? 'fa ' : 'ui-icon ') + iconColumns[i].iconClass,
                            icon = $('<span class="' + iconClasses + iconClassWithValue + '"></span>');
                        if (doesCellValueExist) {
                            $(this).empty();
                            $(this).append('<span class="' + iconPrefix + 'cell-value">' + cellValue + '</span>');
                        }
                        $(this).prepend(icon);
                        $this._handleClickOnIconLinkEvents(icon);
                    });
                }
            }
        },
        /**
         * Handle the click events on the icon set for the column
         * @param {jQuery} icon
         */
        _handleClickOnIconLinkEvents: function(icon) {
            var rowPosition = this._getRowIndex(icon.closest('tr')),
                fieldValue = this._getCellValue('id', rowPosition),
                $this = this;
            icon.click(function(event){
                $this._trigger('iconclick', event, fieldValue);
            });
        },
        /**
         * Returns the cell value of the specified field name and row position
         * @param {string} fieldName Name of the field where the value is to read
         * @param {integer} rowPosition Row position starting to 0
         * @returns {string} The cell value found of null if not found
         */
        _getCellValue: function(fieldName, rowPosition) {
            var cellValue = null;
            if(rowPosition < this.data.length && rowPosition >= 0
                    && this.data[rowPosition][fieldName] !== undefined) {
                return this.data[rowPosition][fieldName];
            }
            return cellValue;
        },
        _makeColumnsResizable: function() {
            if (!this.options.resizableColumns) {
                return false;
            }
            var thHeight = this.thead.find('th:first').height();
            this.thead.find('th').resizable({
                handles: "e",
                minHeight: thHeight,
                maxHeight: thHeight,
                minWidth: 10,
                resize: function (event, ui) {
                  $(event.target).width(ui.size.width);
                }
            }); 
        },
        _setTotalInCaption: function(total) {
            if (this.options.totalInCaption) { // option "totalInCaption" is true... 
                var captionElement = this.element.children('.pui-datatable-caption').first();
                if (captionElement.length) {
                    captionElement.text(total + ' ' + this.originalCaption);
                } else {
                    this.originalCaption = this.options.caption;
                    this.options.caption = total + ' ' + this.originalCaption;
                }
            }
        }
    });
});
 