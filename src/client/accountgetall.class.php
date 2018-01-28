<?php
class Client_AccountGetAll extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_AccountHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->getAll($dbc);
        error_log( __METHOD__ . ',' . __LINE__ . ';' . json_encode($data));
        return $data;
    }

}

?>
