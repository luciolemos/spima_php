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
 * ZnetDK User Informations panel
 *
 * File version: 1.0
 * Last updated: 09/18/2015 
 */
$.widget("znetdk.zdkuserpanel", {
    /**
     * Widget's constructor: initialization from the HTML5 attributes
     * 'data-zdk-username', 'data-zdk-usermail' and 'data-zdk-changepwd'.
     */
    _create: function () {
        var username = this.element.attr('data-zdk-username'),
            mail = this.element.attr('data-zdk-usermail'),
            pwdlabel = this.element.attr('data-zdk-changepwd');
        if (username.length) {
            this.menu = $('<ul id="zdk-profile-view"/>').appendTo('body');
            this.menu.append('<li class="username">' + username + '</li>');
            this.menu.append('<li class="usermail">' + mail + '</li>');
            this.menu.append('<li class="changepwd"><a data-icon="ui-icon-locked" class="ui-state-default">' + pwdlabel + '</a></li>');
            this.menu.puimenu({
                popup: true,
                trigger: $('#zdk-profile')
            });
            // Bind events...
            this._bindEvents();
        }
    },
    /**
     * Handles the clic event of the change password button
     */
    _bindEvents: function () {
        this.menu.find('li.changepwd').click(function () {
            znetdk.showLoginDialog(true);
        });
    }
});