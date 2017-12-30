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
 * ZnetDK TabMenu widget: 2 levels tab menu
 *
 * File version: 1.0
 * Last update: 09/18/2015 
 */
$.widget("znetdk.zdktabmenu", {
    options: {
        /** Has the main page to be reloaded when a menu tab is clicked?
         * See ZnetDK configuration parameter 'CFG_VIEW_PAGE_RELOAD'
         */
        pageReload: false
    },
    /**
     * Instantiates the TabMenu widget
     */
    _create: function () {
        var $this = this;
        // Flag 'noLoadFirstLevel2Tab' set to false by default.
        this.noLoadFirstLevel2Tab = false;
        // Init panel component for LEVEL ONE menu if no subtab exists
        this.element.find('div.tab_panel').puipanel();
        // Init panel component for level 1 menu
        this.element.puitabview({
            orientation: 'left',
            activeIndex: this._getLevel1SelectedTab(),
            change: function (event, index) {
                var menuId;
                var selectedTabNbr = isNaN(Number(index.index)) ? 0 : index.index;
                var selectedTab = $(this).children('.pui-tabview-panels').children('.pui-tabview-panel').eq(selectedTabNbr);
                if (!selectedTab.length) {
                    //No selected tab 
                    return;
                }
                if ($this.options.pageReload) {
                    var tabLink = $(this).children('ul').children('li').eq(selectedTabNbr).children('a').first();
                    location.assign(tabLink.attr('href'));
                    return;
                }
                if (selectedTab.has('.zdk-classic-menu-level2').length) {
                    // A level 2 puitaview menu exists...
                    if ($this.noLoadFirstLevel2Tab) {
                        // The level2 tab must not be selected
                        $this.noLoadFirstLevel2Tab = false;
                    } else {
                        var L2selectedTabNbr = selectedTab.find('.zdk-classic-menu-level2').puitabview('getActiveIndex');
                        L2selectedTabNbr = isNaN(Number(L2selectedTabNbr)) ? 0 : L2selectedTabNbr;
                        // Selection of the level 2 menu tab for loading its content if necessary...
                        selectedTab.find('.zdk-classic-menu-level2').puitabview('select', L2selectedTabNbr);
                    }
                } else {
                    // No level 2 puitaview menu exists...
                    menuId = selectedTab.attr('id');
                    znetdk.addLabelToTitle($(this).children('ul').children('li').eq(selectedTabNbr).children('a').text());
                    var controller = menuId.substr(5); // Remove prefix "menu-" to the menu ID 
                    // Loading tab content if it is empty and if the dynamic load is enabled (parameter CFG_VIEW_PRELOAD or CFG_VIEW_PAGE_RELOAD is false)
                    znetdk.loadView({
                        htmlTarget: selectedTab.find('.zdk-dynamic-content:empty'),
                        control: controller,
                        action: "show",
                        callback: function (response) {
                            // A custom event is sent to notify the developer that the view has been loaded
                            $.event.trigger({
                                type: "L1menuViewLoad",
                                index: selectedTabNbr,
                                menuId: menuId
                            });
                        }
                    });
                }
                // A custom event is sent to notify the developer that the tab has been selected
                $.event.trigger({
                    type: "L1menuTabChange",
                    index: selectedTabNbr,
                    menuId: menuId
                });
            }
        });
        // Init panel component for LEVEL TWO menu
        this.element.find('.zdk-classic-menu-level2').puitabview({
            activeIndex: this._getLevel2SelectedTab(),
            contentOverflow: true,
            change: function (event, index) {
                var menuId;
                var selectedTabNbr = isNaN(Number(index.index)) ? 0 : index.index;
                if ($this.options.pageReload) {
                    var tabLink = $(this).children('ul').children('li').eq(selectedTabNbr).children('a').first();
                    location.assign(tabLink.attr('href'));
                    return;
                }
                znetdk.addLabelToTitle($(this).children('ul').children('li').eq(selectedTabNbr).children('a').text());
                var selectedTab = $(this).find('.pui-tabview-panel:eq(' + selectedTabNbr + ')');
                menuId = selectedTab.attr('id');
                var controller = menuId.substr(5); // Remove prefix "menu-" to the menu ID 
                znetdk.loadView({
                    htmlTarget: selectedTab.find('.zdk-dynamic-content:empty'),
                    control: controller,
                    action: "show",
                    callback: function (response) {
                        // A custom event is sent to notify the developer that the view has been loaded
                        $.event.trigger({
                            type: "L2menuViewLoad",
                            index: selectedTabNbr,
                            menuId: menuId
                        });
                    }
                });
                // A custom event is sent to notify the developer that a new tab has been selected
                $.event.trigger({
                    type: "L2menuTabChange",
                    index: selectedTabNbr,
                    menuId: menuId
                });
            }
        });
    },
    /**
     * Returns the tab menu that is currently selected
     * @returns {Object} Label and identifier of the selected tab
     */
    getSelectedTab: function () {
        var selectedMenu = null, menuElement = this.element,
                selectedTabNbr = menuElement.puitabview('getActiveIndex');
        selectedTabNbr = isNaN(Number(selectedTabNbr)) ? 0 : selectedTabNbr;
        var selectedTab = menuElement.children('.pui-tabview-panels').children('.pui-tabview-panel').eq(selectedTabNbr);
        if (selectedTab.has('.zdk-classic-menu-level2').length) {
            // A level 2 puitaview menu exists...
            menuElement = selectedTab.find('.zdk-classic-menu-level2');
            selectedTabNbr = menuElement.puitabview('getActiveIndex');
            selectedTabNbr = isNaN(Number(selectedTabNbr)) ? 0 : selectedTabNbr;
            selectedTab = menuElement.find('.pui-tabview-panel').eq(selectedTabNbr);
        }
        // Initialization of the returned object
        selectedMenu = new Object();
        var menuId = selectedTab.attr('id');
        selectedMenu.id = menuId.substr(5); // Remove prefix "menu-" to the menu ID 
        selectedMenu.label = menuElement.children('ul').children('li').eq(selectedTabNbr).children('a').text();
        return selectedMenu;
    },
    /**
     * Selects the menu tab matching the specified view name
     * @param {String} viewName Identifier of the view
     */
    selectTab: function (viewName) {
        var menuItem = this.element.find('#menu-' + viewName);
        if (menuItem.length) {
            var level2Parent = menuItem.parents(".zdk-classic-menu-level2");
            if (level2Parent.length) {
                // Level 2 menu item
                if (!this.options.pageReload) {
                    this.noLoadFirstLevel2Tab = true;
                    this.element.puitabview('select', level2Parent.parent().index());
                }
                level2Parent.puitabview('select', menuItem.index());
            } else {
                // Level 1 menu item
                this.element.puitabview('select', menuItem.index());
            }
        } else {
            this.element.puitabview('select', 0);
        }
    },
    /**
     * Returns the level 1 menu tab currently selected 
     * @returns {Number} Index of the selected level 1 menu tab
     */
    _getLevel1SelectedTab: function () {
        if (this.options.pageReload) {
            var L1tabWithContent = this.element.find('div > div[id]:has(div.zdk-filled)');
            return L1tabWithContent.length ? L1tabWithContent.index() : 0;
        } else
            return 0;
    },
    /**
     * Returns the level 2 menu tab currently selected 
     * @returns {Number} Index of the selected level 2 menu tab
     */
    _getLevel2SelectedTab: function () {
        if (this.options.pageReload) {
            var L2tabWithContent = this.element.find('.zdk-classic-menu-level2 > div > div[id]:has(div.zdk-filled)');
            return L2tabWithContent.length ? L2tabWithContent.index() : 0;
        } else
            return 0;
    }
});