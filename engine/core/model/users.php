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
* Core DAO : users declared in the application 
*
* File version: 1.1
* Last update: 05/10/2017
*/
namespace model;

/**
 * Database access to the users configured for the application
 */
class Users extends \DAO
{
	protected function initDaoProperties() {
		$this->useCoreDbConnection();
		$this->table = "zdk_users";
		$this->IdColumnName = "user_id";
		$this->query = "select *, if(full_menu_access,'".LC_FORM_LBL_USER_MENU_ACCESS_FULL."','') as menu_access,
			if(user_enabled,'','".LC_FORM_LBL_USER_STATUS_DISABLED."') as status from zdk_users ";
		$this->filterClause = "where login_name = ?";
	}
	
        /**
         * Sets the email address as the filter criteria to retrieve a user 
         * @param string $email User's email address to search
         */
        public function setEmailAsFilter($email) {
            $this->filterClause = "WHERE user_email = ?";
            $this->setFilterCriteria($email);    
	}
        
        /**
         * Excludes the 'autoexec' user from the user list
         */
        public function excludeAutoexecUser() {
            $this->filterClause = "WHERE login_name != ?";
            $this->setFilterCriteria('autoexec'); 
        }
        
        /**
         * Sets the user name as filter
         * @param string $name Name of the user
         */
        public function setNameAsFilter($name) {
            $this->filterClause = "WHERE user_name = ?";
            $this->setFilterCriteria($name);
        }
        
}
