<?php
class Adm_SettingsDelete extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Adm_SettingsHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->delete($dbc, $args);
        return $data;
    }

}
?>
