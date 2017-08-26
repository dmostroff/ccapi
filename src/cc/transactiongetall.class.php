<?php
class Cc_TransactionGetAll extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Cc_TransactionHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->getAll($dbc);
        return $data;
    }

}
?>
