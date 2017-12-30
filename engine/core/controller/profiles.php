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
 * Core application controller for user profile management
 *
 * File version: 1.0
 * Last update: 09/18/2015
 */

namespace controller;

/**
 * ZnetDK Core controller for profile management
 */
class Profiles extends \AppController {

    /** Return the profiles defined for the application and to be displayed in
     *  the profile datatable
     */
    static protected function action_all() {
        $request = new \Request();
        $sortfield = $request->sortfield;
        $sortorder = $request->sortorder;
        $sortCriteria = isset($sortfield) && isset($sortorder) ? $sortfield . ($sortorder == -1 ? ' DESC' : '') : 'profile_name';
        $profiles = array();
        // Success response returned to the main controller
        $response = new \Response();
        $response->total = \ProfileManager::getAllProfiles($sortCriteria, $profiles);
        $response->rows = $profiles;
        $response->success = TRUE;
        return $response;
    }

    /** Save the profile created or modified from the profile view form */
    static protected function action_save() {
        $response = new \Response();
        $request = new \Request();
        $summary = $request->profile_id ? LC_FORM_TITLE_PROFILE_MODIFY : LC_FORM_TITLE_PROFILE_NEW;
        $validator = new \validator\Profile();
        if ($validator->validate()) {
            $profileRow = $request->getValuesAsMap('profile_id', 'profile_name', 'profile_description');
            $menuItems = $request->menu_ids;
            \ProfileManager::storeProfile($profileRow, $menuItems);
            $response->setSuccessMessage($summary, LC_MSG_INF_SAVE_RECORD);
        } else { //Data validation failed...
            $response->setFailedMessage($summary, $validator->getErrorMessage(),
                    $validator->getErrorVariable());
        }
        return $response; // JSON response sent back to the main controller
    }

    /** Get the remove confirmation message */
    static protected function action_removeconfirm() {
        $request = new \Request();
        $message['question'] = LC_MSG_ASK_REMOVE;
        $message['yes_label'] = LC_BTN_YES;
        $message['no_label'] = LC_BTN_NO;
        if (\ProfileManager::isProfileAssociatedToRows($request->profile_id)) {
            $message['question'] .= LC_MSG_WARN_PROFILE_ROWS_EXIST;
        }
        $response = new \Response();
        $response->setResponse($message);
        return $response;
    }

    /** Remove the profile from its identifier */
    static protected function action_remove() {
        $response = new \Response();
        $request = new \Request();
        $profileID = $request->profile_id;
        /* First, check whether the user profile is defined for a user ... */
        if (\ProfileManager::isProfileGrantedToUsers($profileID)) {
            $response->setFailedMessage(LC_FORM_TITLE_PROFILE_REMOVE, LC_MSG_ERR_REMOVE_PROFILE);
            return $response;
        }
        /* Next, remove the profile... */
        \ProfileManager::removeProfile($profileID);
        /* Response returned to the main controller */
        $response->setSuccessMessage(LC_FORM_TITLE_PROFILE_REMOVE, LC_MSG_INF_REMOVE_RECORD);
        return $response;
    }

    /** Return the menu items of the application as Tree Nodes format */
    static protected function action_menuitems() {
        $response = new \Response();
        $response->success = true;
        $response->treenodes = \MenuManager::getMenuItemsAsTreeNodes();
        return $response;
    }

}
