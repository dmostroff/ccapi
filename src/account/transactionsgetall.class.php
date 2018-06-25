<?php
class Account_TransactionsGetAll extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Account_TransactionsHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->getAll($dbc);
        return $data;
    }

}

?>
