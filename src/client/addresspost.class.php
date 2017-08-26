<?php
class Client_AddressPost extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_AddressHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->post($dbc, $args, $this->posted_);
        return $data;
    }

}
?>
