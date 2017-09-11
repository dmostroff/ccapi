<?php
class Adm_SettingsGet extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Adm_SettingsHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->get($dbc, $args);
        return $data;
    }

}
?>
