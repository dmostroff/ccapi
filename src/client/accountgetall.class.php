<?php
class Client_AccountGetAll extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_AccountHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $rows = $this->helper_->getAll($dbc);
        $data = [];
        foreach( $rows as $row) {
            $data[] = $this->helper_->account_decrypt( $row);
        }
        error_log( __METHOD__ . ',' . __LINE__ . ';' . json_encode($data));
        return $data;
    }

}

?>
