<?php
class Client_AccountPost extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_AccountHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $rows = $this->helper_->post($dbc, $args, $this->posted_);
        $data = null;
        if( isset($rows[$this->helper_->idcol_])) {
            $data = $this->helper_->get($dbc, $rows);
        }
        return $data;
    }

}
?>