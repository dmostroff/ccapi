<?php
class Account_BaltransferinfoPost extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Account_BaltransferinfoHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->post($dbc, $args, $this->posted_);
        return $data;
    }

}
?>
