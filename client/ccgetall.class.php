<?php
class Client_CcGetAll extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_CcHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->getAll($dbc);
        return $data;
    }

}
?>
