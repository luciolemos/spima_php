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
 * Core security API  
 *
 * File version: 1.2
 * Last update: 04/09/2017
 */

/**
 * ZnetDK core user management API
 */
class UserManager {
    
    /**
     * Returns all the declared users for using the application
     * @param string $sortCriteria Sorting criteria of the user list
     * @param array $users The array of users to fill in by the method
     * @return int The number of users returned in the $users array
     */
    static public function getAllUsers($sortCriteria,&$users) {
        $usersDAO = new \model\Users();
        $usersDAO->excludeAutoexecUser();
        $usersDAO->setSortCriteria($sortCriteria);
        try {
            while ($userRow = $usersDAO->getResult()) {
                $profiles = array();
                $profileIDs = array();
                self::getUserProfiles($userRow['user_id'], $profiles, $profileIDs);
                if (count($profileIDs)) {
                    $userRow['user_profiles'] = $profiles;
                    $userRow['profiles[]'] = $profileIDs;
                }
                // Original password is not provided, a dummy value is returned instead
                $userRow['login_password'] = \General::getDummyPassword();
                $userRow['login_password2'] = \General::getDummyPassword();
                $users[] = $userRow;
            }
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("USR-001: unable to request the user list", $e, TRUE);
        }
        return $usersDAO->getCount();
    }
    
    /**
     * Returns the user information stored in the database from his login name.
     * @param string $loginName User's login name
     * @return array User information
     */
    static public function getUserInfos($loginName) {
        $usersDAO = new \model\Users();
        $usersDAO->setFilterCriteria($loginName);
        try {
            return $usersDAO->getResult();
        } catch (\Exception $e) {
            $response = new \Response();
            $response->setCriticalMessage(
                    "USR-002: unable to query the user information", $e,TRUE);
        }
    }
    
    /**
     * Returns the user information stored in the database from the user ID.
     * @param string $userId User identifier
     * @return array User information
     */
    static public function getUserInfosById($userId) {
        $usersDAO = new \model\Users();
        try {
            return $usersDAO->getById($userId);
        } catch (\Exception $e) {
            $response = new \Response();
            $response->setCriticalMessage(
                    "USR-018: unable to query the user informations by ID", $e,TRUE);
        }
    }
    
    /**
     * Returns the user information stored in the database from her or his name.
     * @param string $userName Name of the user
     * @return array User information
     */
    static public function getUserInfosByName($userName) {
        $usersDAO = new \model\Users();
        $usersDAO->setNameAsFilter($userName);
        try {
            return $usersDAO->getResult();
        } catch (\Exception $e) {
            $response = new \Response();
            $response->setCriticalMessage(
                    "USR-019: unable to query the user informations by name", $e,TRUE);
        }
    }
    
    
    /**
     * Returns the profiles granted to the specified user
     * @param string $loginName Login name of the user
     * @param string $profileNamesAsList List of the profile names in string
     * format, each profile name sepated by a comma (ie ", ").
     * @param array $profileIDs Profile Identifiers
     */
    static public function getUserProfiles($loginName, &$profileNamesAsList, &$profileIDs) {
        $userProfilesDAO = new \model\UserProfiles();
        $userProfilesDAO->SetFilterCriteria($loginName);
        try {
            while ($profileRow = $userProfilesDAO->getResult()) {
                $profileNames[] = $profileRow['profile_name'];
                $profileIDs[] = $profileRow['profile_id'];
            }
            if (isset($profileNames) && count($profileNames)) {
                $profileNamesAsList = implode(', ', $profileNames);
            }
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("USR-003: unable to get the profiles for the user $loginName", $e, TRUE);
        }
    }
    
    /**
     * Stores in database the user informations
     * @param array $userRow User informations to store in database as an indexed
     * array. If the 'user_id' key is set, the user is updated. Otherwise the user
     * is inserted.
     * @param array $userProfiles User profiles granted to the user
     */
    static public function storeUser($userRow, $userProfiles) {
        $userDAO = new \model\Users();
        $userDAO->beginTransaction();
        try { // First Insert user row
            $userID = $userDAO->store($userRow, FALSE);
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("USR-004: unable to store the user", $e, TRUE);
        }
        // Next Insert/Update user profiles
        // --> Existing profiles for the user are removed first
        self::removeUserProfiles($userID);
        if (isset($userProfiles) && is_array($userProfiles)) {
        // --> Insert profiles for the user
            self::addProfilesToUser($userID, $userProfiles);
        }
        $userDAO->commit();
    }
    
    /**
     * Removes the profiles granted to the specified user.
     * No commit is performed by the method.
     * @param string $userID User ID to remove in database
     */
    static private function removeUserProfiles($userID) {
        $userProfilesDAO = new \model\UserProfiles();
        $userProfilesDAO->setFilterCriteria($userID);
        try {
            $userProfilesDAO->remove(NULL, FALSE);   
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("USR-005: unable to remove the profiles for the user ID '$userID'", $e, TRUE);
        }
    }
    
    /**
     * Adds the specified profiles to the user indicated as input parameter
     * @param string $userID User identifier in database
     * @param array $userProfiles User profiles to grant to the user
     */
    static private function addProfilesToUser($userID, $userProfiles) {
        $userProfilesDAO = new \model\UserProfiles();
        try {
            foreach ($userProfiles as $value) {
                $userProfileRow = array('user_id' => $userID, 'profile_id' => $value);
                $userProfilesDAO->store($userProfileRow, FALSE);
            }
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("USR-006: unable to add profiles to the user ID '$userID'", $e, TRUE);
        }
    }
    
    /**
     * Removes the specified user in the database
     * @param string $userID User identifier in database
     */
    static public function removeUser($userID) {
        $userDAO = new \model\Users();
        $userDAO->beginTransaction();
        // First existing user's profiles are removed
        self::removeUserProfiles($userID);
        //Finally, the user is removed
        try {
            $userDAO->remove($userID, FALSE);   
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("USR-007: unable to remove the user with user ID '$userID'", $e, TRUE);
        }
        //Changes are commited
        $userDAO->commit();
    }
    
    /**
     * Return the user name of the specified user
     * @param string $loginName User login name
     * @return string The user name or NULL if the user name can't be queried. 
     */
    static public function getUserName($loginName) {
        $userDAO = new \model\Users();
        $userDAO->setFilterCriteria($loginName);
        try {
            $userRow = $userDAO->getResult();
            $userName = $userRow['user_name'];
        } catch (\PDOException $e) {
            \General::writeErrorLog("ZNETDK ERROR", "USR-008: unable to query the user name for the login name '$loginName'! (".
                    $e->getCode().")", TRUE);
            $userName = NULL;
        }
        return $userName;
    }
    
    /**
     * Returns the email address of the specified user
     * @param string $loginName User login name
     * @return string The user email or NULL if the user email can't be queried.
     */
    static public function getUserEmail($loginName) {
        $userDAO = new \model\Users();
        $userDAO->setFilterCriteria($loginName);
        try {
            $userRow = $userDAO->getResult();
            $userEmail = $userRow['user_email'];
        } catch (\PDOException $e) {
            \General::writeErrorLog("ZNETDK ERROR", "USR-009: unable to query the user email for the login name '$loginName'! (".
                    $e->getCode().")", TRUE);
            $userEmail = NULL;
        }
        return $userEmail;
    }
    
    /**
     * Changes the password for the specified user 
     * @param string $loginName User login name
     * @param string $newPassword New user password to store
     */
    static public function changeUserPassword($loginName, $newPassword) {
        $userDAO = new \model\Users();
        $userDAO->setFilterCriteria($loginName);
        try {
            $userRow = $userDAO->getResult();
            $user['user_id'] = $userRow['user_id'];
            $user['login_password'] = $newPassword;
            $user['expiration_date'] = self::getUserExpirationDate();
            $userDAO->store($user);
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("USR-010: unable to change the user password for the login name '$loginName'", $e, TRUE);
        }
    }
    
    /**
     * Returns the calculated date when user account will expire. This date is
     * evaluated from the current date and the number of months configured for 
     * the parameter CFG_DEFAULT_PWD_VALIDITY_PERIOD.
     * @return DateTime Expiration date of the user account in W3C format
     */
    static private function getUserExpirationDate() {
        $expiration_date = new \DateTime('now');
        $expiration_date->add(new \DateInterval('P' . CFG_DEFAULT_PWD_VALIDITY_PERIOD . 'M'));
        return $expiration_date->format('Y-m-d');
    }
    
    /**
     * Disables the specified user
     * @param string $loginName User's login name
     */
    static public function disableUser($loginName) {
        $userDAO = new \model\Users();
        $userDAO->setFilterCriteria($loginName);
        try {
            $userRow = $userDAO->getResult();
            $user['user_id'] = $userRow['user_id'];
            $user['user_enabled'] = 0;
            $user['expiration_date'] = \General::getCurrentW3CDate();
            $userDAO->store($user);
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("USR-011: unable to disable the user account for the login name '$loginName'", $e, TRUE);
        }        
    }
    
    /**
     * Indicates whether the specified user has a full access to the navigation 
     * menu.
     * @param string $loginName User's login name
     * @return boolean TRUE if the user has a full access to the navigation menu,
     *  otherwise FALSE
     */
    static public function hasUserFullMenuAccess($loginName) {
        $userDAO = new \model\Users();
        $userDAO->setFilterCriteria($loginName);
        try {
            $userRow = $userDAO->getResult();
        } catch (\PDOException $e) {
            \General::writeErrorLog("ZNETDK ERROR", "USR-012: unable to query the full menu access status for the login name '$loginName'! (".
                    $e->getCode().")", TRUE);
            return FALSE;
        }
        return $userRow['full_menu_access'] == TRUE;
    }
    
    /**
     * Indicates whether the specified user has the specified profile 
     * @param type $loginName User's login name
     * @param type $profileName Profile name
     * @return boolean TRUE if the user has the specified profile, otherwise FALSE
     */
    static public function hasUserProfile($loginName, $profileName) {
        if (!isset($loginName) || !isset($profileName)) {
            return FALSE;
        }
        $userProfilesDAO = new \model\UserProfiles();
        $userProfilesDAO->setLoginNameAndProfileNameAsFilters($loginName, $profileName);
        try {
            return $userProfilesDAO->getResult() !== FALSE ? TRUE : FALSE;
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("USR-013: unable to query the user's profile '$profileName' for the login name '$loginName'", $e, TRUE);
        }
    }
    
    /**
     * Returns the menu items granted to the specified user
     * @param string $loginName User's login name
     * @return array Menu items granted to the user
     */
    static public function getGrantedMenuItemsToUser($loginName) {
        $userMenuObj = new \model\UserMenus();
        $userMenuObj->setFilterCriteria($loginName);
        $allowedMenuItems = array();
        try {
            while ($row = $userMenuObj->getResult()) {
                $allowedMenuItems[] = $row['menu_id'];
            }
        } catch (\PDOException $e) {
            \General::writeErrorLog("ZNETDK ERROR", "USR-014: unable to query the granted menu items for the login name '$loginName'! (".
                    $e->getCode().")", TRUE);
        }
        return $allowedMenuItems;
    }
    
    /**
     * Returns the identifier of the user found from his email address
     * @param string $email Email address of the user to find
     * @return int Row identifier of the user found from his email address,
     * otherwise NULL
     */
    static public function getUserIdByEmail($email) {
        $usersDAO = new \model\Users();
        $usersDAO->setEmailAsFilter($email);
        try {
            $userRow = $usersDAO->getResult();
            $userID = $userRow === FALSE ? NULL : $userRow['user_id'];
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("USR-015: unable to query a user from his email '$email'", $e, TRUE);
        }
        return $userID;
    }
    
    /**
     * Returns the users matching the specified profile name
     * @param string $profileName Name of the profile assigned to the users who
     * are searched
     */
    static public function getUsersHavingProfile($profileName) {
        $users = array();
        $profile = ProfileManager::getProfileInfos($profileName);
        if ($profile === FALSE) {
            return $users;
        }
        $userProfilesDAO = new \model\UserProfiles();
        $userProfilesDAO->setProfileIdAsFilter($profile['profile_id']);
        try {
            while ($row = $userProfilesDAO->getResult()) {
                $users[] = $row;
            }
            return $users;
        } catch (\PDOException $e) {
            $response = new \Response();
            $response->setCriticalMessage("USR-016: unable to query the users matching the '$profileName' profile!", $e, TRUE);
        }
    }
    
    /**
     * Checks whether a user has access to the specified menu item  
     * @param string $loginName A user's login name
     * @param string $menuItem The identifier of the menu item
     * @return boolean TRUE if the user has access to the specified menu item. 
     * FALSE otherwise
     */
    static public function hasUserMenuItem($loginName, $menuItem) {
        $hasFullMenuAccess = self::hasUserFullMenuAccess($loginName);
        if ($hasFullMenuAccess && MenuManager::getMenuItem($menuItem) !== NULL) {
            return TRUE;
        }
        $userMenuObj = new \model\UserMenus();
        $userMenuObj->setLoginNameAndMenuItemAsFilter($loginName, $menuItem);
        try {
            return $userMenuObj->getResult() !== FALSE;
        } catch (\PDOException $e) {
            \General::writeErrorLog("ZNETDK ERROR", "USR-017: unable to request the menu item '$menuItem' for the login name '$loginName'! (".
                    $e->getCode().")", TRUE);
        }
    }
    
}
