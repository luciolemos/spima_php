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
 * ZnetDK Application Initialization process
 *
 * File version: 1.0
 * Last update: 09/17/2015
 */
/**
 * Initialization of the application when the HTML page is fully loaded 
 */
$(function () {
    $('#zdk-messages').puigrowl({life: 5000}); //Message initialization
    $('#zdk-critical-err').puinotify({easing: 'easeInOutCirc'});
    $("#zdk-logout").zdklogout(); // Logout process
    $("#zdk-header-logo").zdklogo(); // Clic on banner logo
    $('#zdk-connection-area').zdkuserpanel(); // Init User Infos Panel
    $('#zdk-help-area').zdkhelp(); // Init Online Help facility
    $('#zdk-ajax-loading-img').zdkajaxloader(); // Init Ajax loader
    znetdk.initNavigationMenu(); // Initialization of the navigation menu
    znetdk.setFooterSticky(); // Set a minimum height of the page content
    if (znetdk.isAuthenticationRequired()) {
        /********* Show login dialog if no menu exists **********/
        znetdk.showLoginDialog();
    } else {
        /* The class "zdk-filled" is set when:
         - CFG_VIEW_PRELOAD = true, all the views are pre-loaded in the page in one step,
         - CFG_VIEW_PAGE_RELOAD = true, the page is reloaded for each view to display.
         */
        $('.zdk-filled').each(function () {
            znetdk.autoInitWidgets($(this)); // UI components are instanciated...
        });
        if (!znetdk.isPageToBeReloaded()) {
            znetdk.showMenuView(); // Display the first view of the navigation menu
        } else {
            znetdk.goToPageAnchor(); // Go to the page anchor if specified in the page URL
        }
    }
});