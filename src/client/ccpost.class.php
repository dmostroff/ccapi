<?php
class Client_CcPost extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_CcHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->post($dbc, $args, $this->posted_);
        return $data;
    }

}
?>
