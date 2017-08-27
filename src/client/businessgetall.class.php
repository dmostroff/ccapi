<?php
class Client_BusinessGetAll extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_BusinessHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->getAll($dbc);
        return $data;
    }

}

?>
