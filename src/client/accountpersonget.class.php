<?php
class Client_AccountPersonGet extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_AccountHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->getByFk($dbc, $args);
        return $data;
    }

}
?>
