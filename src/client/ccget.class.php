<?php
class Client_CcGet extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_CcHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->get($dbc, $args);
        return $data;
    }

}
?>
