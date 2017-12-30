<?php
/**
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website http://www.znetdk.fr 
 * Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
 * --------------------------------------------------------------------
 * Controller - CRUD demonstration of ZnetDK (www.demo.znetdk.fr).
 */

namespace app\controller;

class DemoCrudCtrl extends \AppController {

    static protected function action_save() {
        // 1) Read POST parameter's values
        $request = new \Request();
        $row = $request->getValuesAsMap('id', 'part_number', 'name', 'description', 'price');

        // 2) Store values into the database
        $productsDAO = new \app\model\ProductsDAO();
        $response = new \Response();
        try {
            $productID = $productsDAO->store($row);
            $response->setSuccessMessage(LA_DEMO_DIALOG_SAVE, 
                    \General::getFilledMessage(LA_DEMO_INFO_MSG_SAVE, $productID));
        } catch (\PDOException $ex) {
            $response->setFailedMessage(LA_DEMO_DIALOG_SAVE,
            \General::getFilledMessage(LA_DEMO_ERROR_MSG_SAVE, $request->part_number
                    , $ex->getCode()));
        }

        // 3) Return JSON response
        return $response;
    }

    static protected function action_remove() {
        // 1) Get row ID from the POST parameter
        $request = new \Request();
        $rowID = $request->id;

        // 2) Remove the specified row from the database 
        $productsDAO = new \app\model\ProductsDAO();
        $response = new \Response();
        try {
            $productsDAO->remove($rowID);
            $response->setSuccessMessage(LA_DEMO_DIALOG_REMOVE, LA_DEMO_INFO_MSG_REMOVE);
        } catch (\PDOException $ex) {
            $response->setFailedMessage(LA_DEMO_DIALOG_REMOVE,
                    \General::getFilledMessage(LA_DEMO_ERROR_MSG_REMOVE, $rowID, $ex->getCode()));
        }

        // 3) Return JSON response
        return $response;
    }

    static protected function action_data() {
        // 1) Read POST parameters
        $request = new \Request();
        // --> Pagination
        $first = $request->first; $rows = $request->rows;
        // --> Sort criteria
        $sortField = $request->sortfield; $sortOrder = $request->sortorder;
        $sortCriteria = is_null($sortField) ? 'name' : $sortField . (is_null($sortOrder) ? ' ASC' : $sortOrder == 1 ? ' ASC' : ' DESC');
        // --> Filter criteria
        $criteria = $request->search_criteria;
        $keyword = '%' . $criteria . '%';
        
        // 2) Request rows from the database
        $response = new \Response();
        $productsDAO = new \app\model\ProductsDAO();
        $productsDAO->setKeywordAsFilter($keyword);
        $productsFound = array();
        try {
            $response->total = $productsDAO->getCount();
            $productsDAO->setSortCriteria($sortCriteria);
            $productsDAO->setLimit($first, $rows);

            while($row = $productsDAO->getResult()) {
                $productsFound[] = $row;
            }
            $response->rows = $productsFound;
            $response->success = TRUE;
        } catch (\PDOException $ex) {
            $response->setFailedMessage("Request data", "Unable to request the products (error '" . $ex->getCode() . "')");
        }
        
        // 3) Return JSON response
        return $response;
    }

    static protected function action_suggestions() {
        // 1) Read POST parameters */
        $request = new \Request();
        
        // 2) Request the rows matching the criterium from the database
        $productsDAO = new \app\model\ProductsDAO();
        $productsDAO->setNameAsFilter('%' . $request->criteria . '%');
        $productsDAO->setSortCriteria('name');
        $productsDAO->setLimit(0, 10);
        
        $response = new \Response();
        $previousSuggestion = '';
        $suggestions = array();
        try {
            while($row = $productsDAO->getResult()) {
                if ($row['name'] !== $previousSuggestion) {
                    $suggestions[]['label'] = $row['name'];
                    $previousSuggestion = $row['name'];
                }
            }
            $response->setResponse($suggestions);
        } catch (\PDOException $ex) {
            $response->setFailedMessage("Request suggestions", "Unable to request the products (error '" . $ex->getCode() . "')");
        }

        // 3) Return JSON response
        return $response;
    }

}
