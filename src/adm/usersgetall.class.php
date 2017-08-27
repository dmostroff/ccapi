<?php
class Adm_UsersGetAll extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Adm_UsersHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->getAll($dbc);
        return $data;
    }

}

?>
