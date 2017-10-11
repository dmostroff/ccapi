<?php
class Cc_ActionDelete extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Cc_ActionHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->delete($dbc, $args, $this->posted_);
        return $data;
    }

}
?>
