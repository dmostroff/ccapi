<?php
class Client_AddressPersonGetAll extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_AddressHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->getByFk($dbc, $args);
        return $data;
    }

}

?>
