<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cccompanycards
 *
 * @author DANO
 */
class Cc_CompanyCards  extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Cc_CardsHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->getByFk($dbc, $args);
        return $data;
    }

}
?>