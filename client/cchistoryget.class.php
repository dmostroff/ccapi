<?php
class Client_CchistoryGet extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_CchistoryHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->get($dbc, $args);
        return $data;
    }

}
?>
