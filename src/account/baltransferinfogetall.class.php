<?php
class Account_BaltransferinfoGetAll extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Account_BaltransferinfoHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->getAll($dbc);
        return $data;
    }

}

?>
