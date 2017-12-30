<?php
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
* Menu definition of the Application Wizard
*
* File version: 1.0
* Last update: 09/18/2015 
*/

namespace app;
class Menu implements \iMenu {

    static public function initAppMenuItems() {
        \MenuManager::addMenuItem(null, 'welcome', LA_MENU_WELCOME);
        \MenuManager::addMenuItem(null, 'step1', LA_MENU_STEP1);
        \MenuManager::addMenuItem(null, 'step2', LA_MENU_STEP2);
        \MenuManager::addMenuItem(null, 'step3', LA_MENU_STEP3);
        \MenuManager::addMenuItem(null, 'step4', LA_MENU_STEP4);
    }

}