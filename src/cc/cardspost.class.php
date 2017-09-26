<?php
class Cc_CardsPost extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Cc_CardsHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $rows = $this->helper_->post($dbc, $args, $this->posted_);
        $data = null;
        if( isset($rows['cc_card_id'])) {
            $data = $this->helper_->get($dbc, $rows);
        }
        return $data;
    }

}
?>
