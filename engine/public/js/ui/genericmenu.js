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
* ZnetDK Generic Menu widget used by the 'custom' page layout
*
* File version: 1.1
* Last update: 12/20/2016
*/
$.widget("znetdk.zdkgenericmenu", {
	options: {
		htmlTarget:null
	},
        /**
         * Constructs the widget
         */
	_create: function() {
		// If option CFG_VIEW_PAGE_RELOAD is set to true,
		this.followLink = false;
		var filledContent = $("#zdk-content > .zdk-filled").eq(0);
		if (filledContent.length) {
			this.followLink = znetdk.isPageToBeReloaded();
			// Then the menu item of the displayed view is activated (with its parent if exists)
			var viewID = this._getViewID(filledContent.attr('id'));
			this._setMenuItemActive(viewID);
			// And the breadcrumb label is displayed
			this._setBreadCrumb(this._getMenuItemLabel(viewID));
		}

		// Bind events...
		this._bindEvents();
	},
        /**
         * Binds events handled by the widget
         */
	_bindEvents: function() {   
		var $this = this;
		this.element.find('ul > li > a').click(function(event) {
			if (!$this.followLink && $(this).next('ul').length === 0) {
				var menuItemId = $this._getMenuItemId($(this).parent()),
					menuItemLabel = $(this).text();
				$this.displayView(menuItemId,menuItemLabel);
			}
			if (!$this.followLink || $(this).next('ul').length) {
				event.preventDefault();
			}
		});
	},
        /**
         * Displays the specified view
         * @param {String} viewID Identifier of the view to display
         * @param {String} menuItemLabel Label of the menu item
         */
	displayView: function(viewID, menuItemLabel) {
		var $this = this,
			menuItemId = viewID === undefined ? this._getFirstChildMenuItemId() :  viewID,
			menuItemText = menuItemLabel === undefined ? this._getMenuItemLabel(menuItemId) :  menuItemLabel;
		if (this.followLink) { /* Page reload */
			var menuItemElement = this._getMenuItem(menuItemId);
			location.assign(menuItemElement.children('a').first().attr('href'));
			return;
		}
		var	containerID = this._getViewContainerID(menuItemId),
			containerElement = $('#'+containerID),
			containerExists = (containerElement.length > 0),
			containerIsVisible = containerExists ? containerElement.is(':visible'):false;
		if (containerExists && !containerIsVisible) {
			// View container already exists but is not visible...
			this._showView(menuItemId, menuItemText);
		} else if (!containerExists) {
			// View container does not yet exist...
			containerElement = $('<div id="'+containerID+'" class="zdk-view"/>');
			this._getViewsContainer().prepend(containerElement);
			containerElement.hide();
			znetdk.loadView({
				htmlTarget:containerElement,
				control:menuItemId,
				action:"show",
				callback: function() {
					var datatable = containerElement.find('.zdk-synchronize');
					if (datatable.length === 1) {
						datatable.one("zdkdatatabledataloaded", function() {
							$this._showView(menuItemId, menuItemText);
						});
					} else {
						$this._showView(menuItemId, menuItemText);
					}
				}
			});
		}
		znetdk.addLabelToTitle(menuItemText);
	},
        /**
         * Returns the element that contains the menu widget
         * @returns {jQuery object} jQuery element that is the container of the menu
         */
	_getViewsContainer: function() {
		return this.options.htmlTarget.length ? this.options.htmlTarget : $('body');
	},
        /**
         * Hides the view currently displayed
         */
	_hideDisplayedView: function() {
		var displayedView = this._getViewsContainer().children(".zdk-view").filter(":visible");
		if (displayedView.length >= 1) {
			this.lastHiddenView = displayedView.fadeOut(200,function(){console.info("Fade out has ended");});
		}
	},
        /**
         * Returns the HTML identifier of the view 
         * @param {String} viewID Identifier of the view
         * @returns {String} Identifier of the view
         */
	_getViewContainerID: function(viewID) {
		return 'znetdk-'+viewID+'-view';
	},
        /**
         * Returns the identifier of the view from its HTML identifier
         * @param {type} viewContainerID Identifier of the HTML element
         * @returns {String} Identifier of the view
         */
	_getViewID: function(viewContainerID) {
		var splittedText = viewContainerID.split("-");
		return splittedText[1];
	},
        /**
         * Shows the specified view
         * @param {String} viewID Identifier of the view
         * @param {String} menuItemLabel Label of the menu item to display into
         * the breadcrumb
         */
	_showView: function(viewID, menuItemLabel) {
		var $this = this;
		var showObj = function() { 
			$this._setBreadCrumb(menuItemLabel);
			$this._setMenuItemActive(viewID);
			$('#'+$this._getViewContainerID(viewID)).fadeIn(200, function(){
                            $this._trigger('aftershow', null, viewID);
                        });
		};
		var displayedView = this._getViewsContainer().children(".zdk-view").filter(":visible");
		if (displayedView.length >= 1) {
			displayedView.fadeOut(200);
			displayedView.promise().done(function(){
				showObj();
			});
		} else {
			showObj();
		}
	},
        /**
         * Returns the selected menu item
         * @returns {Object} Identifier and label of the selected menu item
         */
	getSelectedMenuItem: function() {
		var selectedMenuItem = this.element.find('ul > li.active');
		if (selectedMenuItem.hasClass('has-sub')) {
			selectedMenuItem = selectedMenuItem.find('li.active').not('.has-sub');
		}
		var response = new Object();
		response.id = this._getMenuItemId(selectedMenuItem);
		response.label = this._getMenuItemLabel(response.id);
		return response;
	},
        /**
         * Return the 'LI' HTML element matching the specified view 
         * @param {type} viewID
         * @returns {jQuery object} 'LI' HTML element of the specified view
         * identifier
         */
	_getMenuItem: function(viewID) {
		return this.element.find('ul > li[id=znetdk-'+viewID+'-menu]');
	},
        /**
         * Returns the root menu item of the specified child menu item
         * @param {jQuery object} menuElement HTML element of the child menu 
         * item 
         * @returns {jQuery object|boolean} Root menu item as a jQuery object,
         * false if no parent menu item is found
         */
	_getRootMenuItem: function(menuElement) {
		var $this = this,
			result = menuElement;
		menuElement.parentsUntil(this.element).filter('li').each(function(){
			if ($(this).parentsUntil($this.element).filter('li').length === 0) {
				result = $(this);
				return false;
			}
		});
		return result;
	},
        /**
         * Returns the first menu item of the menu
         * @returns {jQuery object|Boolean} The menu item found, false otherwise
         */
	_getFirstMenuItemId: function() {
		var firstItem = this.element.find('ul > li').first();
		if (firstItem.length) {
			return this._getMenuItemId(firstItem);
		} else {
			return false;
		}
	},
        /**
         * Returns the firstchild element of the specified parent menu item
         * @param {jQuery object} parent Parent menu item
         * @returns {jQuery object|Boolean} Menu item found, false otherwise
         */
	_getFirstChildMenuItemId: function(parent) {
		if (parent===undefined) {
			parent = this.element.find('ul > li').first();
		}
		if (parent.length) {
			var child = parent.find('ul>li').first();
			if (child.length) {
				return this._getFirstChildMenuItemId(child);
			} else {
				return this._getMenuItemId(parent);
			}
		} else {
			return false;
		}
	},
        /**
         * Returns the menu item HTML identifier of the specified element
         * @param {jQuery object} menuElement Menu item element
         * @returns {String} Identifier of the menu item
         */
	_getMenuItemId: function(menuElement) {
		var splittedID = (menuElement.attr('id')).split("-");
		return splittedID[1];
	},
        /**
         * Return the menu item label of the specified view identifier
         * @param {String} viewID Identifier of the view
         * @returns {String} Label of the matching menu item
         */
	_getMenuItemLabel: function(viewID) {
		return this._getMenuItem(viewID).children("a").text();
	},
        /**
         * Set the label to displayed into the breadcrumb
         * @param {String} menuText
         */
	_setBreadCrumb: function(menuText) {
		$("#zdk-breadcrumb-text").text(menuText);
	},
        /**
         * Sets active the specified menu item
         * @param {String} viewID Identifier of the matching view
         */
	_setMenuItemActive: function(viewID) {
		var menuItem = this._getMenuItem(viewID);
		this._resetMenuItemActive();
		menuItem.addClass('active');
		this._getRootMenuItem(menuItem).addClass('active');
	},
        /**
         * Resets the activation of the current active menu item 
         */
	_resetMenuItemActive: function() {
		this.element.find('li.active').removeClass('active');
	}
});
