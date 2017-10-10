<?php
class Client_PersonGet extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_PersonHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        return $this->helper_->get($dbc, $args);
    }

}
?>
