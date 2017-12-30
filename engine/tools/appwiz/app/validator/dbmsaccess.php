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
 * Application Wizard Step 2 Validator
 *
 * File version: 1.1
 * Last update: 10/25/2015
 */

namespace app\validator;

class DBMSAccess extends \Validator {

    protected function initVariables() {
        return array('host', 'database', 'admin', 'user',
            'create_tables', 'user_pwd', 'admin_pwd');
    }

    protected function initOptionalVariables() {
        return array('create_tables', 'admin', 'admin_pwd', 'host', 'database',
            'user', 'user_pwd');
    }

    /**
     * Checks if the connection to the DBMS is OK from the credential parameter
     * returned by the 'Wizard::getConnectionParameters()' controller method.
     * @param string $value Hostname of the DBMS
     * @return boolean TRUE if the DBMS connection has succeeded, FALSE otherwise.
     */
    protected function check_host($value) {
        if ($this->getValue('create_db') === 'no_database') {
            return TRUE;
        }
        $database = NULL;
        $user = NULL;
        $password = NULL;
        \app\controller\Wizard::getConnectionParameters($database, $user, $password);
        try {
            \Database::getCustomDbConnection($value, $database, $user['value'], $password['value']);
        } catch (\PDOException $ex) {
            $this->setErrorMessage($ex->getMessage());
            switch ($ex->getCode()) {
                case 2002:
                    $this->setErrorMessage(utf8_encode($ex->getMessage()));
                    $this->setErrorVariable('host');
                    break;
                case 1044: $this->setErrorVariable($user['field']);
                    break;
                case 1045: $this->setErrorVariable($password['field']);
                    break;
                case 1049: $this->setErrorVariable('database');
                    break;
                default: $this->setErrorVariable('database');
            }
            return FALSE;
        }
        return TRUE;
    }

    /**
     * THIS CHECKING IS NOT EXECUTED (DISABLED) AND WILL BE ENABLED IN A FUTURE
     * VERSION BECAUSE IT IS TOO RESTRICTING (ASK FOR CHECKING TABLES EXIST)!
     * Checks if the ZnetDK security tables :
     * - already exist whereas the tables have to be created,
     * - are missing whereas the creation of the tables is not required
     * @param string $value Specifies if the database have to be created 
     * (value 'yes') or not (value 'no').
     * @return boolean TRUE if the tables not exist whereas their creation is required
     * and when the tables exist whereas their creation is not required. Returns
     * FALSE otherwise. 
     */
    protected function check_create_db($value) {
        if ($value === 'no') { // Check if security tables exist or not
            $dbConnection = $this->getConnectionFromDBMS();
            if ($dbConnection === FALSE) {
                $this->setErrorMessage(LC_MSG_ERR_LOGIN);
                return FALSE;
            }
            $tables = array('zdk_users', 'zdk_profiles', 'zdk_profile_menus'
                , 'zdk_user_profiles', 'zdk_profile_rows');
            $existingTable = NULL;
            $missingTable = NULL;
            $areTablesToCreate = $this->getValue('create_tables') === 'yes';
            if ($areTablesToCreate) {
                try {
                    $databaseName = $this->getValue('database');
                    $dbConnection->exec("USE `$databaseName`");                    
                } catch (\PDOException $ex) {
                    $this->setErrorMessage($ex->getMessage());
                    return FALSE;
                }
            }
            foreach ($tables as $tableName) {
                $isTableExist = $this->checkTableExist($dbConnection, $tableName);
                $existingTable = $areTablesToCreate && $isTableExist ? $tableName : NULL;
                $missingTable = !$areTablesToCreate && !$isTableExist ? $tableName : NULL;
                if (!is_null($existingTable)) {
                    $this->setErrorMessage(\General::getFilledMessage(
                                    LA_MSG_STEP3_EXISTING_TABLE, $existingTable));
                    return FALSE;
                } elseif (!is_null($missingTable)) {
                    $this->setErrorMessage(\General::getFilledMessage(
                                    LA_MSG_STEP3_MISSING_TABLE, $missingTable));
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    /**
     * Checks if the database :
     * - already exists whereas the database is to be created
     * - is missing wheres the database creation not required
     * @param string $value Database name
     * @return boolean TRUE if the database exists when its creation is not
     * required and if the database is missing when its creation is required.
     * Returns FALSE otherwise. 
     */
    protected function check_database($value) {
        if ($this->getValue('create_db') === 'no_database') {
            return TRUE;
        }
        // Check if the specified database exists
        $dbConnection = $this->getConnectionFromDBMS();
        if ($dbConnection === FALSE) {
            $this->setErrorMessage(LC_MSG_ERR_LOGIN);
            return FALSE;
        }
        $databasesDAO = new \app\model\Databases($dbConnection);
        $databasesDAO->setFilterCriteria($value);
        try {
            $doesDatabaseExist = $databasesDAO->getResult();
        } catch (\PDOException $ex) {
            \General::writeErrorLog('APPWIZ', $ex->getMessage());
            $this->setErrorMessage(LC_MSG_ERR_SELECT_RECORD);
            return FALSE;
        }
        if ($doesDatabaseExist && $this->getValue('create_db') !== 'no') {
            $this->setErrorMessage(\General::getFilledMessage(
                            LA_MSG_STEP3_EXISTING_DB, $value));
            //$this->setErrorVariable('create_db');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Checks whether the Super user dedicated to the database creation exists
     * and has the required privileges to create a new database and a new user
     * or to create and alter tables.
     * This condition is checked only if the database has to be created and if
     * the ZnetDK tables have to be created. 
     * @param type $value Super user name
     * @return boolean TRUE is the super user exists and has sufficient privileges,
     * FALSE otherwise.
     */
    protected function check_admin($value) {
        if ($this->getValue('create_db') === 'no_database' || ($this->getValue('create_db') === 'no'
                && $this->getValue('create_tables') !== 'yes')) {
            return TRUE;
        }
        $dbConnection = $this->getConnectionFromDBMS();
        if ($dbConnection === FALSE) {
            $this->setErrorMessage(LC_MSG_ERR_LOGIN);
            return FALSE;
        }
        if ($this->getValue('create_db') === 'yes' && $value === $this->getValue('user')) {
            $this->setErrorMessage(\General::getFilledMessage(
                            LA_MSG_STEP3_ADMIN_EQUAL_USER, $value));
            return FALSE;
        }
        $dao = new \app\model\DBMSUsers($dbConnection);
        $dao->setHostAndUserAsFilter($this->getValue('host'), $value);
        if ($this->getValue('create_db') === 'yes') {
            $dao->setFilterAsSuperUser();
        } else {
            $dao->setFilterAsUserWithCreate();
        }
        try {
            $doesUserExist = $dao->getResult() !== FALSE;
        } catch (\PDOException $ex) {
            if ($ex->errorInfo[1] === 1142) { // select on table mysql.user is not authorized
                $doesUserExist = FALSE;
            } else {
                \General::writeErrorLog('APPWIZ', $ex->getMessage());
                $this->setErrorMessage(LC_MSG_ERR_SELECT_RECORD);
                return FALSE;
            }
        }
        if (!$doesUserExist) {
            $this->setErrorMessage(\General::getFilledMessage(
                            $this->getValue('create_db') === 'yes' ? LA_MSG_STEP3_ADMIN_NO_PRIVS : LA_MSG_STEP3_USER_NO_PRIVS, $value));
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Checks if the specified DBMS user account dedicated to the application
     * already exists whereas he has to be created (Database creation requested)
     * @param string $value User nane
     * @return boolean TRUE if the user does not exist whereas he's to be created
     * Returns FALSE otherwise.
     */
    protected function check_user($value) {
        if ($this->getValue('create_db') !== 'yes') {
            return TRUE;
        }
        $dbConnection = $this->getConnectionFromDBMS();
        if ($dbConnection === FALSE) {
            $this->setErrorMessage(LC_MSG_ERR_LOGIN);
            return FALSE;
        }
        $dao = new \app\model\DBMSUsers($dbConnection);
        $dao->setHostAndUserAsFilter($this->getValue('host'), $value);
        try {
            $isUserFound = $dao->getResult() !== FALSE;
        } catch (\PDOException $ex) {
            \General::writeErrorLog('APPWIZ', $ex->getMessage());
            $this->setErrorMessage(LC_MSG_ERR_SELECT_RECORD);
            return FALSE;
        }
        if ($isUserFound) {
            $this->setErrorMessage(\General::getFilledMessage(
                            LA_MSG_STEP3_USER_EXISTS, $value));
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Checks if the user's password is valid when the user is supposed to already exist. 
     * @param string $password User's password
     * @return boolean FALSE if the specified password is invalid when the database
     * and the user are supposed to already exist.
     */
    protected function check_user_pwd($password) {
        if ($this->getValue('create_db') !== 'no') {
            return TRUE;
        }
        try {
            return \Database::getCustomDbConnection($this->getValue('host'),
                    $this->getValue('database'), $this->getValue('user'), $password);
            return TRUE;
        } catch (\PDOException $ex) {
            \General::writeErrorLog('APPWIZ', $ex->getMessage());
            $this->setErrorMessage(\General::getFilledMessage(
                LA_MSG_STEP3_USER_BAD_PASSWORD, $this->getValue('user')));
            return FALSE;
        }
    }
    
    private function getConnectionFromDBMS() {
        $database = NULL;
        $user = NULL;
        $password = NULL;
        $host = $this->getValue('host');
        \app\controller\Wizard::getConnectionParameters($database, $user, $password);
        try {
            return \Database::getCustomDbConnection($host, $database, $user['value'], $password['value']);
        } catch (\PDOException $ex) {
            \General::writeErrorLog('APPWIZ', $ex->getMessage());
            return FALSE;
        }
    }

    private function checkTableExist($dbConnection, $tableName) {
        switch ($tableName) {
            case 'zdk_users':$dao = new \app\model\Users($dbConnection);
                break;
            case 'zdk_profiles':$dao = new \app\model\Profiles($dbConnection);
                break;
            case 'zdk_profile_menus':$dao = new \app\model\ProfileMenus($dbConnection);
                break;
            case 'zdk_user_profiles':$dao = new \app\model\UserProfiles($dbConnection);
                break;
            case 'zdk_profile_rows':$dao = new \app\model\ProfileRows($dbConnection);
                break;
            default : return FALSE;
        }
        try {
            $dao->getResult();
            return TRUE;
        } catch (\PDOException $ex) {
            \General::writeErrorLog('APPWIZ', $ex->getMessage());
            return FALSE;
        }
    }

}
