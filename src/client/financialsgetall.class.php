<?php
class Client_FinancialsGetAll extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_FinancialsHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->getAll($dbc);
        return $data;
    }

}

?>
