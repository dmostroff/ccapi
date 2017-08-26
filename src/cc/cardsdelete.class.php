<?php
class Cc_CardsDelete extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Cc_CardsHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->delete($dbc, $args);
        return $data;
    }

}
?>
