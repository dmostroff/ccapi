<?php
class Cc_CompanyPost extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Cc_CompanyHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->post($dbc, $args, $this->posted_);
        return $data;
    }

}
?>
