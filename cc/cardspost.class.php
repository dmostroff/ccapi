<?php
class Cc_CardsPost extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Cc_CardsHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->post($dbc, $args, $this->posted_);
        return $data;
    }

}
?>
