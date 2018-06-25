<?php
class Account_AccountDelete extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Account_AccountHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        error_log('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');
        error_log(print_r($this->posted_,1));
        $data = $this->helper_->delete($dbc, $args, $this->posted_);
        return $data;
    }

}
?>
