<?php
class Account_TransactionsPost extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Account_TransactionsHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->post($dbc, $args, $this->posted_);
        return $data;
    }

}
?>
