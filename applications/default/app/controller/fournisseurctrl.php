<?php

namespace app\controller;

use app\model\FournisseurDAO;

class FournisseurCtrl extends \AppController {

    static protected function action_lister() {
        $fournisseurDAO = new FournisseurDAO();
        $fournisseurs = array();
        while ($row = $fournisseurDAO->getResult()) {
            $fournisseurs[] = $row;
        }
        /* Réponse retournée au contrôleur principal */
        $response = new \Response();
        $response->rows = $fournisseurs;
        $response->success = true;        
        return $response;
    }

    static protected function action_enregistrer() {
        /* Lecture des données de la requête HTTP */
        $request = new \Request();
        $row = $request->getValuesAsMap('id', 'nom', 'adresse', 'code_postal', 'ville');
        /* Enregistrement des données en Base de données */
        $fournisseurDAO = new FournisseurDAO();
        $result = $fournisseurDAO->store($row);
        /* Réponse retournée au contrôleur principal */
        $response = new \Response();
        if ($result) {
            $response->setSuccessMessage('Operação sobre registro', 'realizada com sucesso.');
        } else {
            $response->setFailedMessage('Novo registro', "Falha na criação do registro.");
        }
        return $response;
    }

    static protected function action_supprimer() {
        /* Lecture des données de la requête HTTP */
        $request = new \Request();
        $rowID = $request->id;
        /* Suppression du fournisseur en Base de données */
        $fournisseurDAO = new FournisseurDAO();
        $result = $fournisseurDAO->remove($rowID);
        /* Réponse retournée au contrôleur principal */
        $response = new \Response();
        if ($result) {
            $response->setSuccessMessage('Exclusão de registro!', 'Exclusão realizada com sucesso.');
        } else {
            $response->setFailedMessage('Exclusão de registro!', 'Exclusão nâo realizada.');
        }
        return $response;
    }

}
