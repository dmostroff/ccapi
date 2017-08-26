<?php
class Cc_CompanyDelete extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Cc_CompanyHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->delete($dbc, $args);
        return $data;
    }

}
?>
