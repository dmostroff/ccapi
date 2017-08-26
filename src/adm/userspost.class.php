<?php
class Adm_UsersPost extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Adm_UsersHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->post($dbc, $args, $this->posted_);
        return $data;
    }

}
?>
