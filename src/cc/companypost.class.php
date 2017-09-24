<?php
class Cc_CompanyPost extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Cc_CompanyHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $rows = $this->helper_->post($dbc, $args, $this->posted_);
//        error_log( '###########################');
//        error_log(print_r($rows, 1));
        $data = $rows;
        if( isset($rows['cc_company_id'])) {
            $data = $this->helper_->get($dbc, $rows);
        }
        return $data;
    }

}
?>
