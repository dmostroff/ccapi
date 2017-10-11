<?php
class Client_AddressDelete extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_AddressHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->delete($dbc, $args, $this->posted_);
        return $data;
    }

}
?>
