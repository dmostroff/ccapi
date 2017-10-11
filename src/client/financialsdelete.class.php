<?php
class Client_FinancialsDelete extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_FinancialsHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->delete($dbc, $args, $this->posted_);
        return $data;
    }

}
?>
