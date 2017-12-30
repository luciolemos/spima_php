<?php

namespace app\model;

class FournisseurDAO extends \DAO {

    protected function initDaoProperties() {
        $this->table = "fournisseurs";
    }

}
