<?php
class Client_CchistoryPost extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_CchistoryHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->post($dbc, $args, $this->posted_);
        return $data;
    }

}
?>
