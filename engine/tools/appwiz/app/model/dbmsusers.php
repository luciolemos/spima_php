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
 * App Wizard DAO : existing DBMS users  
 *
 * File version: 1.0
 * Last update: 09/18/2015
 */

namespace app\model;

/**
 * Database access to the users configured for the application
 */
class DBMSUsers extends \DAO {

    protected function initDaoProperties() {
        $this->query = "select host, user, create_priv, alter_priv, show_db_priv, create_user_priv from mysql.user ";
        $this->filterClause = "where host = ? and user = ?";
    }

    /**
     * Sets the criteria Hostname and User name as filter values for the query 
     * @param string $hostname Hostname for which the user is authorized to connect to
     * @param string $username User account
     */
    public function setHostAndUserAsFilter($hostname, $username) {
        $this->setFilterCriteria($hostname, $username);
    }
    
    /**
     * Adds to the query the criteria required for selecting the super users 
     * authorized for creating a database and a user account.
     */
    public function setFilterAsSuperUser() {
        $this->filterClause .= " and create_priv='Y' and alter_priv='Y' and show_db_priv='Y' and create_user_priv='Y'";
    }
    
    /**
     * Adds to the query the criteria required for selecting the users 
     * authorized for creating a table.
     */
    public function setFilterAsUserWithCreate() {
        $this->filterClause .= " and create_priv='Y' and alter_priv='Y'";
    }
    
}
