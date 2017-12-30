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
 * Core Front controller of the application
 *
 * File version: 1.3
 * Last update: 04/01/2017
 */

/**
 * ZnetDK front controller
 */
Class MainController {

    /**
     * Checks whether the specified class is an application controller.
     * @param string $className Class name prefixed with its namespace
     * @param boolean $doError Value TRUE if an HTTP error 404 must be triggered
     * @return boolean TRUE is the specified class is an application controller,
     * FALSE otherwise.
     */
    static private function isControllerClass($className, $doError) {
        try {
            if (class_exists($className) && method_exists($className, 'doAction')) {
                return TRUE;
            } else {
                $message = "CTL-005: the method 'doAction' does not exist in the class '" .
                        $className . "'!";
            }
        } catch (Exception $e) { //Triggered by class_exists
            $message = "CTL-007: the class '" . $className . "' does not exist(" .
                    $e->getCode() . ")!";
        }
        if ($doError) {
            \General::writeErrorLog('ZNETDK ERROR', $message, TRUE);
            $response = new \Response(FALSE);
            $response->doHttpError(404, LC_MSG_CRI_ERR_SUMMARY, \General::getFilledMessage(LC_MSG_CRI_ERR_DETAIL, $message));
        } else {
            return FALSE;
        }
    }

    /** Returns the class name of the specified controller.
     * The class is searched among the custom controllers first (application level).
     * If the class is not found, it is searched among the modules and finally
     * among the ZnetDK core controllers.
     * @param string $controller Controller name
     * @param string $method Method name
     * @param boolean $isAction Indicates whether the specified method is an
     * action or not. Set to TRUE by default.
     * @param boolean $doError Indicates whether an HTTP error 404 must be returned
     * when no controller is found. The default value is FALSE.
     * @return string|boolean Class name found for the specified controller and
     * action. Returns FALSE if the controller is found but not the action. 
     */
    static public function getControllerName($controller, $method, $isAction = TRUE, $doError = FALSE) {
        $controllerSearchPaths = array(array('app\\controller\\' . $controller,FALSE));
        $moduleWithMatchingController = \General::getModules('mod' . DIRECTORY_SEPARATOR . 'controller'
                . DIRECTORY_SEPARATOR . $controller . '.php');
        if ($moduleWithMatchingController) {
            $controllerSearchPaths[] = array($moduleWithMatchingController . '\\mod\\controller\\'
                . $controller,FALSE);
        }
        $controllerSearchPaths[] = array('controller\\' . $controller,$doError);
        foreach ($controllerSearchPaths as $searchPath) {
            if (self::isControllerClass($searchPath[0], $searchPath[1]) && (
                    ($isAction && $searchPath[0]::isAction($method)) ||
                    (!$isAction && method_exists($searchPath[0], $method))
                    )
            ) {
                // Controller exists
                return $searchPath[0];
            }
        }
        return FALSE;
    }

    /**
     * Executes a controller action 
     * Return false if no controller and action are found!
     */
    static private function executeAction($controller, $action) {
        if (!isset($controller) || !isset($action)) {
            return FALSE; // Parameters not properly set
        }
        $className = self::getControllerName($controller, $action);
        if ($className === FALSE) {
            return FALSE; // No controller found!
        }
        try { // The controller & action are found
            $actionResponse = $className::doAction($action);
        } catch (\Exception $e) {
            $errorMessage = \ErrorHandler::formatErrorMessage(\Convert::toUTF8($e->getMessage()), $e->getFile(), $e->getLine());
            \General::writeErrorLog('EXCEPTION', $errorMessage, TRUE);
            $response = new \Response(FALSE);
            $response->doHttpError(500, LC_MSG_CRI_ERR_SUMMARY, \General::getFilledMessage(LC_MSG_CRI_ERR_DETAIL, $errorMessage));
        }
        if ($actionResponse === FALSE) {
            return FALSE;
        }
        $actionResponse->output();
        return TRUE;
    }

    /**
     * Executes the specified action as POST or GET HTTP parameter.
     */
    static public function doAction() {
        if (\AutoExec::launch()) {
            return; // This is an autoexec controller action to execute in background ...
        }
        $controller = \Request::getController(); // Controller name
        $action = \Request::getAction(); // Action name
        if (\Request::getMethod() === 'POST' || (\Request::getMethod() === 'GET'
                && isset($controller) && $action === 'download')) {
            if ($action === 'show' || $action === 'help') {
                // View requested by POST method
                self::renderView($controller, $action);
            } elseif (!self::executeAction($controller, $action)) { // Business action requested
                // No custom & core controller found!
                $message = "CTL-003: the controller '$controller' and the action '$action' does not exist!";
                \General::writeErrorLog('ZNETDK ERROR', $message, TRUE);
                $response = new \Response(FALSE);
                $response->doHttpError(404, LC_MSG_CRI_ERR_SUMMARY, \General::getFilledMessage(LC_MSG_CRI_ERR_DETAIL, $message));
            }
        } else { // show full HTML page layout
            $layoutStatus = \Layout::render();
            if (!$layoutStatus) {
                $errorMessage = "CTL-002: a severe error occurred while loading the layout of the application!";
                \General::writeErrorLog("ZNETDK ERROR", $errorMessage, TRUE);
                echo $errorMessage;
            }
        }
    }

    /**
     * Renders the specified view
     * @param String $controller Name of the controller in charge to render the 
     * view or directly the view name to render.
     * @param String $action Name of the controller action that renders the view
     * or directly the 'show' action to directly render the view name specified
     * as controller.  
     * @param Boolean $checkAuthentication If TRUE, checks if the user is
     * correctly authentified. If FALSE, the user authentication is not checked.
     */
    static public function renderView($controller, $action, $checkAuthentication = TRUE) {
        if (!self::executeAction($controller, $action)) {
            // No custom & core controller found!
            $response = new \Response($checkAuthentication);
            $viewType = $action === 'show' ? 'view' : $action;
            $response->setView($controller, $viewType);
            if (!$response->output()) {
                // No view exists with the name "{$controller}.php"
                $message = "CTL-001: the view '{$controller}' does not exist in the directory '" .
                        "{$viewType}' of the application, modules and of the ZnetDK engine!";
                \General::writeErrorLog('ZNETDK ERROR', $message, TRUE);
                $response = new \Response(FALSE);
                $response->doHttpError(404, LC_MSG_CRI_ERR_SUMMARY, \General::getFilledMessage(LC_MSG_CRI_ERR_DETAIL, $message), FALSE);
            }
        }
    }

    /** Executes a public method of the specified controller.
     * The method is searched in the app folder, next in the core folder.
     * The public method is not necessarily an action and can be just a public method.
     */
    static public function execute($controller, $method) {
        $className = self::getControllerName($controller, $method, FALSE);
        if ($className === FALSE) {
            // No custom & core controller found!
            return FALSE;
        } elseif (method_exists($className, $method)) {
            // The controller and the method are found
            try {
                $numargs = func_num_args();
                $parameters = $numargs > 1 ? array_slice(func_get_args(), 2) : array();
                return call_user_func_array(array($className, $method), $parameters);
            } catch (\Exception $e) {
                \General::writeErrorLog('ZNETDK ERROR', 'CTL-004: ' . $controller . ',' . $method . ' - ' . $e->getMessage(), TRUE);
                throw $e;
            }
        } else {
            return FALSE;
        }
    }
    
}
