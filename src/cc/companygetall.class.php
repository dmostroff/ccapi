<?php
class Cc_CompanyGetAll extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Cc_CompanyHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->getAll($dbc);
        return $data;
    }

}

?>
