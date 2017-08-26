<?php
class Cc_BaltransferinfoGet extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Cc_BaltransferinfoHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->get($dbc, $args);
        return $data;
    }

}
?>
