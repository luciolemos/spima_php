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
 * Core Layout controller
 *
 * File version: 1.2
 * Last update: 10/15/2016 
 */

/**
 * Renders the main page of the application
 */
Class Layout {

    /**
     * Checks if the specified controller is an HTTP error 
     * @param String $controller Name of the controller
     * @return Boolean TRUE if the specified controller is an HTTP error, FALSE
     * otherwise.
     */
    static private function isHttpError($controller) {
        return $controller === 'httperror';
    }

    /**
     * Returns the HTML definition of the default content
     * @return string HTML default content
     */
    static private function getDefaultContent() {
        return "<div id=\"default_content\" class=\"ui-widget ui-widget-content ui-corner-all";
    }

    /**
     * Renders the layout of the application main page
     * @return boolean TRUE if rendering is OK, FALSE otherwise.
     */
    static public function render() {
        $returnStatus = TRUE;
        $controller = \Request::getController();
        $language = \UserSession::getLanguage();
        $pageTitle = LC_PAGE_TITLE;
        $loginName = NULL;
        $connectedUser = NULL;
        $userEmail = NULL;
        $metaDescription = NULL;
        $metaKeywords = NULL;
        $metaAuthor = NULL;

        if (self::isHttpError($controller)) {
            $menuItem = \MenuManager::getMenuItemFromURI();
            if (isset($menuItem)) {
                $controller = $menuItem[0];
                // HTTP Header changed to status 200 OK
                header("Status: 200 OK", false, 200);
            }
        } elseif (\Parameters::isSetPageReload() && isset($controller)) {
            $menuItem = \MenuManager::getMenuItem($controller);
            if (!isset($menuItem)) {
                // The requested controller does not match any menu item !
                $controller = 'httperror';
                \Request::setHttpError('404');
            }
        } elseif (\Parameters::isAuthenticationRequired()) {
            $loginName = \UserSession::getLoginName();
            try {
                $connectedUser = \MainController::execute('users', 'getUserName');
                $userEmail = \MainController::execute('users', 'getUserEmail');
            } catch (\Exception $e) {
                \General::writeErrorLog('ZNETDK ERROR', "LAY-003: unable to get connected user's infos for '" .
                        $loginName . "'! (".$e->getCode().")", true);
                /* Despite this error, the generation of the page layout continue... */
            }
        }

        try {
            if (\Parameters::isSetPageReload(FALSE)) {
                $currentMenuItem = isset($menuItem) ? $menuItem : \MenuManager::getFirstLeafMenuItem();
                $pageTitle = $currentMenuItem[1] . ' | ' . LC_PAGE_TITLE;
                $metaDescription = $currentMenuItem[5];
                $metaKeywords = $currentMenuItem[6];
                $metaAuthor = $currentMenuItem[7];
            }    
        } catch (\Exception $ex) { // ZnetDK settings are inconsistent
            $controller = 'httperror';
            \Request::setHttpError('500');
            \General::writeErrorLog('ZNETDK ERROR',$ex->getMessage(), TRUE);
        }

        // Display the page layout...
        \Parameters::checkIfPageLayoutNameIsValid(); // For logging purpose only
        $pageLayout = 'layout' . DIRECTORY_SEPARATOR . \Parameters::getPageLayoutName() . '.php';
        if (file_exists(ZNETDK_APP_ROOT . DIRECTORY_SEPARATOR . 'app' .
                        DIRECTORY_SEPARATOR . $pageLayout)) { // Application level
            include 'app' . DIRECTORY_SEPARATOR . $pageLayout;
        } elseif (file_exists(ZNETDK_CORE_ROOT .
                        DIRECTORY_SEPARATOR . $pageLayout)) { // Core level
            include $pageLayout;
        } else { // Default core page layout selected
            $message = "LAY-002: the page layout '" . $pageLayout .
                    "' does not exist! ";
            \General::writeErrorLog('ZNETDK ERROR', $message, true);
            $returnStatus = FALSE;
        }
        return $returnStatus;
    }

    /**
     * Renders the navigation menu of the application
     * @param String $controller Name of the specified controller
     */
    static private function renderNavigationMenu($controller) {
        switch (\Parameters::getPageLayoutName()) {
            case "office":
                $menuRenderingMethod = 'renderVerticalMenu';
                break;
            case "classic":
                $menuRenderingMethod = 'renderTabViewMenu';
                break;
            case "custom":
                $menuRenderingMethod = 'renderCustomMenu';
        }

        if (self::isHttpError($controller)) {
            // HTTP error, dedicated error view is rendered without checking authentication...
            if (\Parameters::getPageLayoutName() !== "custom") {
                echo self::getDefaultContent() . '">' . PHP_EOL;
                \MainController::renderView($controller, 'show', false);
                echo PHP_EOL . "\t\t\t</div>" . PHP_EOL;
            }
            return; // The menu is not rendered and the execution of the function stops here.
        }
        $allowedMenuItems = \MenuManager::getAllowedMenuItems();
        if ($allowedMenuItems !== FALSE) {
            \MenuRenderer::$menuRenderingMethod(1, \MenuManager::getMenuItems(), $controller, $allowedMenuItems);
        }
        echo "\t\t\t" . self::getDefaultContent() . ' ui-helper-hidden"></div>' . PHP_EOL;
    }

    /**
     * Renders the HTML breadcrumb for the 'custom' layout
     */
    static private function renderBreadcrumb() {
        $breadCrumbValue = '&nbsp;';
        echo '<div id="zdk-breadcrumb-text">' . $breadCrumbValue . '</div>' . PHP_EOL;
    }

    /**
     * Renders the content for the 'custom' layout
     * @param String $selectedMenuItem The selected item in the menu
     * @param Array $sortedMenuItems The sorted list of the menu items 
     * @param Array $allowedMenuItems The menu items allowed to the connected
     * user according his or her profile
     */
    static private function renderCustomContent($selectedMenuItem, $sortedMenuItems = NULL, $allowedMenuItems = NULL) {
        $customContent = '<div id="zdk-content">';
        $firstView = FALSE;
        $rootElement = FALSE;
        if (!isset($sortedMenuItems)) {
            $pageReloadEnabled = \Parameters::isSetPageReload();
            if (self::isHttpError($selectedMenuItem)) {
                // The httperror view is displayed...
                echo $customContent . PHP_EOL;
                echo "\t\t\t" . self::getDefaultContent() . '">' . PHP_EOL;
                \MainController::renderView($selectedMenuItem, 'show', FALSE);
                echo PHP_EOL . "\t\t\t</div>" . PHP_EOL;
                echo "\t\t</div>" . PHP_EOL;
                return; // No more if HTTP Error!
            } elseif (!CFG_VIEW_PRELOAD && !$pageReloadEnabled) {
                // View content loaded on demand
                echo '<div id="zdk-content" class="zdk-dynamic-content"></div>' . PHP_EOL;
                return;
            } elseif (CFG_VIEW_PRELOAD && $pageReloadEnabled) {
                \General::writeErrorLog('ZNETDK ERROR','LAY-006: setting to TRUE both CFG_VIEW_PRELOAD and CFG_PAGE_RELOAD is not allowed!', true);
                return; //combination of parameters not allowed!
            } elseif (CFG_VIEW_PRELOAD && !$pageReloadEnabled) {
                $sortedMenuItems = \MenuManager::getMenuItems();
                $allowedMenuItems = \MenuManager::getAllowedMenuItems();
                if ($allowedMenuItems === FALSE) {
                    return; // The user is not authenticated...
                }
            } else { // $pageReloadEnabled
                if (!isset($selectedMenuItem)) {
                    // The first menu item is loaded
                    $firstMenuItem = \MenuManager::getFirstLeafMenuItem();
                    if (!isset($firstMenuItem)) {
                        \General::writeErrorLog('ZNETDK ERROR','LAY-004: no menu item found!', TRUE);
                        return; // No menu item exists!
                    } else {
                        $sortedMenuItems[] = $firstMenuItem;
                    }
                } else {
                    $sortedMenuItems[] = \MenuManager::getMenuItem($selectedMenuItem);
                }
            }
            $firstView = TRUE;
            $rootElement = TRUE;
            echo $customContent . PHP_EOL;
        }
        $nbTabs = 2;
        foreach ($sortedMenuItems as $value) {
            $hidden = $firstView ? NULL : ' style="display:none;"';
            $firstView = FALSE;
            if ((!is_array($allowedMenuItems) || (is_array($allowedMenuItems) && array_search($value[0], $allowedMenuItems) !== FALSE))) {
                if (isset($value[2])) { // submenu exists...
                    self::renderCustomContent(NULL, $value[2], $allowedMenuItems);
                } else {
                    echo str_repeat("\t", $nbTabs + 1) . '<div id="znetdk-' . $value[0] . '-view" class="zdk-filled zdk-view"' . $hidden . '>' . PHP_EOL;
                    \MainController::renderView($value[0], 'show');
                    echo str_repeat("\t", $nbTabs + 1) . '</div>' . PHP_EOL;
                }
            }
        }
        if ($rootElement) {
            echo str_repeat("\t", $nbTabs) . '</div>' . PHP_EOL;
        }
    }

    /**
     * Renders the meta tag definition of the page in the HTML header section 
     * @param type $description Description (meta name="description")
     * @param type $keywords Keyword (meta name="keyword")
     * @param type $author Author (meta name="author")
     */
    static private function renderMetaTags($description, $keywords, $author) {
        $tabs = "\t";
        echo "<meta charset=\"UTF-8\">" . PHP_EOL;
        echo $tabs . "<meta name=\"generator\" content=\"ZnetDK ".ZNETDK_VERSION." (http://www.znetdk.fr)\">" . PHP_EOL;
        if (isset($description)) {
            echo $tabs . "<meta name=\"description\" content=\"$description\">" . PHP_EOL;
        }
        if (isset($keywords)) {
            echo $tabs . "<meta name=\"keywords\" content=\"$keywords\">" . PHP_EOL;
        }
        if (isset($author)) {
            echo $tabs . "<meta name=\"author\" content=\"$author\">" . PHP_EOL;
        }
    }

    /**
     * Renders the URL of the banner logo
     */
    static private function renderLogoURL() {
        if (\Parameters::isSetPageReload()) {
            echo \General::getAbsoluteURI(TRUE);
        } else {
            echo '#';
        }
    }

    /**
     * Renders the language selection JavaScript code
     */
    static private function renderLangSelection() {
        if (CFG_MULTI_LANG_ENABLED) {
            $allLanguages = \api\Locale::getActiveLanguages(true);
            if (count($allLanguages) === 0) {
                \General::writeErrorLog('ZNETDK ERROR','LAY-005: no language defined for the application!', true);
                return;
            } else {
                $mainScript = \General::getMainScript();
                $mainScriptWithGetParams = \General::addGetParameterToURI(\General::getMainScript(TRUE), 'lang', current($allLanguages));
                $otherLanguages = \api\Locale::getActiveLanguages();
                $sessionLanguage = \UserSession::getLanguage();
                require('layout/languages-layout.php');
            }
        }
    }

    /**
     * Renders the dependencies CSS et JavaScript of the page
     */
    static private function renderDependencies() {
        \Dependencies::render();
    }

}
