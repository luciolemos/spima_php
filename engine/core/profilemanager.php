<?php

/*
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
 * Core User Profiles API  
 *
 * File version: 1.1
 * Last update: 02/11/2017
 */

/**
 * ZnetDK core profile management API
 */
class ProfileManager {

    /**
     * Returns the list of profiles defined for the application
     * @param string $sortCriteria
     * @param array $profiles Array in which the profiles are to be loaded
     * @return int Number of profiles returned in the $profile variable
     */
    static public function getAllProfiles($sortCriteria,&$profiles) {
        $profilesDAO = new \model\Profiles(); // Get profiles from DB
        $profilesDAO->setSortCriteria($sortCriteria);
        try {
            while ($row = $profilesDAO->getResult()) {
                $menuItemTextList = '';
                $menuItemIDs = array();
                if (self::getProfileMenu($row['profile_id'], $menuItemTextList, $menuItemIDs)) {
                    $row['menu_items'] = $menuItemTextList;
                    $row['menu_ids[]'] = $menuItemIDs;
                }
                $row['profile_description'] = isset($row['profile_description']) ? $row['profile_description'] : '';
                $profiles[] = $row;
            }
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("PFL-001: unable to request the user profile list", $e, TRUE);
        }
        return $profilesDAO->getCount();
    }
    
    /**
     * Returns the profile information stored in the database from its name.
     * @param string $profileName Profile's name
     * @return array Profile information
     */
    static public function getProfileInfos($profileName) {
        $profilesDAO = new \model\Profiles();
        $profilesDAO->setFilterCriteria($profileName);
        try {
            return $profilesDAO->getResult();
        } catch (\Exception $e) {
            $response = new \Response();
            $response->setCriticalMessage(
                    "PFL-009: unable to query the profile information for the"
                    . "profile named '$profileName'!", $e,TRUE);
        }
    }

    /**
     * Returns The menu items allowed for the specified profile
     * @param int $profileID Identifier of the profile in the database
     * @param string $menuItemTextList List of the menu items returned by the 
     * method in string format
     * @param array $menuItemIDs Menu item identifiers returned by the method
     * @return boolean TRUE if a menu is defined for the profile, otherwise FALSE
     */
    static private function getProfileMenu($profileID, &$menuItemTextList, &$menuItemIDs) {
        $profileMenusDAO = new \model\ProfileMenus();
        $menuItems = array();
        $profileMenusDAO->SetFilterCriteria($profileID);
        try {
            while ($menuRow = $profileMenusDAO->getResult()) {
                $menuItem = \MenuManager::getMenuItem($menuRow['menu_id']);
                if (\MenuManager::isMenuItemAleaf($menuItem[0])) {
                    $menuItems[] = $menuItem[1]; // Leaf only displayed
                }
                $menuItemIDs[] = $menuRow['menu_id'];
            }
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("PFL-002: unable to request the menu items of the profile", $e, TRUE);
        }
        if (count($menuItems)) {
            asort($menuItems);
            $menuItemTextList = implode(', ', $menuItems);
            return TRUE;
        } else { return FALSE; }
    }
    
    /**
     * Stores the profile in the database and the allowed menu items
     * @param array $profileRow Data row containing the data to store
     * @param array $menuItems Menu items to store for the profile
     */
    static public function storeProfile($profileRow,$menuItems) {
        $profileDAO = new \model\Profiles();
        try {
            $profileDAO->beginTransaction();
            $profileID = $profileDAO->store($profileRow, FALSE);
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("PFL-003: unable to store the user profile", $e, TRUE);
        }
        // Existing menu items for the profile are removed first
        self::removeMenuItems($profileID);
        // Then menu items are inserted for the profile
        if (isset($menuItems)) {
            self::insertMenuItems($profileID, $menuItems);
        }
        $profileDAO->commit();    
    }
    
    /**
     * Removes from the database the menu items of the specified profile
     * @param int $profileID Identifier of the profile for which the menu items
     * are to be removed
     */
    static private function removeMenuItems($profileID) {
        $profileMenusDAO = new \model\ProfileMenus();
        // Existing menu items for the profile are removed first
        $profileMenusDAO->setFilterCriteria($profileID);
        try {
            $profileMenusDAO->remove(null, FALSE);
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("PFL-004: unable to remove the existing menu items of the profile", $e, TRUE);
        }
    }
    
    /**
     * Adds menu items in database to the specified profile
     * @param int $profileID Identifier of the profile for which the menu items
     * are to be added
     * @param array $menuItems Menu items to add to the profile
     */
    static private function insertMenuItems($profileID, $menuItems) {
        $profileMenusDAO = new \model\ProfileMenus();
        foreach ($menuItems as $value) {
            $menuItemRow = array('profile_id' => $profileID, 'menu_id' => $value);
            try {
                $profileMenusDAO->store($menuItemRow, FALSE);
            } catch (\PDOException $e) {
                $response = new \Response();
                $response->setCriticalMessage("PFL-005: unable to insert menu item for the profile", $e, TRUE);
            }
        }
    }
    
    /**
     * Removes the table rows associated to the specified profile in the table
     * 'zdk_profile_rows'.
     * @param int $profileID $profileID Identifier of the rows to remove
     */
    static private function removeProfileRows($profileID) {
        $profileRowsDAO = new \model\ProfileRows();
        $profileRowsDAO->setProfileIdAsFilter($profileID);
        try {
            $profileRowsDAO->remove(NULL, FALSE);
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("PFL-010: unable to remove the existing rows associated to the profile", $e, TRUE);
        }
    }
    
    /**
     * Removes from the database the specified profile
     * @param int $profileID Identifier of the profile to remove
     */
    static public function removeProfile($profileID) {
        $profileDAO = new \model\Profiles();
        $profileDAO->beginTransaction();
        self::removeMenuItems($profileID);
        self::removeProfileRows($profileID);
        try { /* Finally, remove profile in the database */
            $profileDAO->remove($profileID, FALSE);
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("PFL-006: unable to remove the user profile", $e, TRUE);
        }
        $profileDAO->commit();
    }

    /**
     * Checks whether the specified profile is currently granted to users or not
     * @param int $profileID Identifier of the profile in database 
     * @return boolean TRUE if currently granted to users, FALSE otherwise.
     */
    static public function isProfileGrantedToUsers($profileID) {
        $userProfileDAO = new \model\UserProfiles();
        $userProfileDAO->setProfileIdAsFilter($profileID);
        try {
            if ($userProfileDAO->getCount() === 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("PFL-007: unable to check whether the profile to remove is currently granted to a user", $e, TRUE);
        }
    }

    /**
     * Checks whether the specified profile is currently associated to table 
     * rows or not.
     * @param int $profileID Identifier of the profile in database 
     * @return boolean TRUE if the profile is currently associated to table rows,
     * FALSE otherwise.
     */
    static public function isProfileAssociatedToRows($profileID) {
        $profileRowsDAO = new \model\ProfileRows();
        $profileRowsDAO->setProfileIdAsFilter($profileID);
        try {
            if ($profileRowsDAO->getCount() === 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("PFL-011: unable to check whether the "
                . "profile to remove is currently associated to table rows", $e, TRUE);
        }
    }
    
    /**
     * Return all the profiles set in the application
     * @return array 2 dimensions array where each line is an indexed array with
     * the keys 'label' and 'value'.
     */
    static public function getProfiles() {
        $profilesDAO = new \model\Profiles();
        $profilesDAO->setSortCriteria('profile_name');
        $profiles = array();
        try {
            while ($row = $profilesDAO->getResult()) {
                $profiles[] = array('label' => $row['profile_name'], 'value' => $row['profile_id']);
            }
        } catch (PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("PFL-008: unable to get the profiles list", $e, TRUE);
        }
        return $profiles;
    }
    
    /**
     * Removes the associated profiles for the specified table row
     * @param string $tableName Name of the table for which the associated 
     * profiles have to be removed
     * @param int $rowID Database identifier of the table row to be removed
     */
    static public function removeProfilesRow($tableName,$rowID) {
        $profileRowsDAO = new \model\ProfileRows();
        $profileRowsDAO->setFilterCriteria($rowID,$tableName);
        try {
            $profileRowsDAO->remove(NULL, FALSE);
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("PFL-012: unable to remove the associated profiles to the table row", $e, TRUE);
        }        
    }
    
    /**
     * Checks whether a menu item is set for the specified profile
     * @param string $profileName The name of the profile
     * @param string $menuItemId The menu item identifier
     * @return boolean Value TRUE if the menu item is set for the profile,
     * otherwise returns FALSE.
     */
    static public function isMenuItemSetForProfile($profileName, $menuItemId) {
        $profileMenusDAO = new \model\ProfileMenus();
        $profileMenusDAO->setProfileAndMenuAsFilter($profileName, $menuItemId);
        try {
            return $profileMenusDAO->getCount() > 0;
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("PFL-013: unable to check if the '$menuItemId' menu item is set for the '$profileName' profile", $e, TRUE);
        }
    }
}
