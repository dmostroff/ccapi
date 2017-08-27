<?php
class Cc_ActionGetAll extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Cc_ActionHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->getAll($dbc);
        return $data;
    }

}

?>
