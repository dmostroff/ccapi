<?php
class Client_PersonGet extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_PersonHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->get($dbc, $args);
        return (isset($data[0])) ? $data[0] : null;
    }

}
?>
