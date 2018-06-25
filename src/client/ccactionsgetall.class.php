<?php
class Client_CcActionsGetAll extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_CcActionsHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->getAll($dbc);
        return $data;
    }

}

?>
