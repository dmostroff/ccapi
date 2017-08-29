<?php

class Cc_CompanyGetDetail extends Base_dblayer {

    protected $CardsHelper_;

    public function __construct() {
        $this->helper_ = new Cc_CompanyHelper();
        $this->CardsHelper_ = new Cc_CardsHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->get($dbc, $args);
        if (isset($data)) {
            $data['cards'] = $this->CardsHelper_->getByFk($dbc, [$args[$this->helper_->idcol_]]);
        }
        return $data;
    }

}

?>
