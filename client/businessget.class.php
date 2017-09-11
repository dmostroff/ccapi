<?php
class Client_BusinessGet extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_BusinessHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->get($dbc, $args);
        return $data;
    }

}
?>
