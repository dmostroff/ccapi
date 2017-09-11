<?php
class Client_PersonDelete extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_PersonHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->delete($dbc, $args);
        return $data;
    }

}
?>
