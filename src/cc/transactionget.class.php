<?php
class Cc_TransactionGet extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Cc_TransactionHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->get($dbc, $args);
        return $data;
    }

}
?>
