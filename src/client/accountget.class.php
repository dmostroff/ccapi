<?php
class Client_AccountGet extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_AccountHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->get($dbc, $args);
        $data['annual_fee'] = round($data['annual_fee'],2);
        $data['credit_limit'] = round($data['credit_limit'],2);
        $data = $this->helper_->account_decrypt( $data);
        return $data;
    }

}
?>
