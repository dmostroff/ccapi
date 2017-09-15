<?php
class Adm_UsersGet extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Adm_UsersHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->get($dbc, $args);
        if( isset($data)) {
            unset($data['pwd']);
        }
        return $data;
    }

}
?>
