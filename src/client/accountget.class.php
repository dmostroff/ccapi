<?php
class Client_AccountGet extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Client_AccountHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $data = $this->helper_->get($dbc, $args);
        if( isset($data)) {
            $data['annual_fee'] = round($data['annual_fee'],2);
            $data['credit_limit'] = round($data['credit_limit'],2);
            $cc_password = null;
            if( isset($data['cc_password'])) {
                $cc_password = cryptutils::sslDecrypt($data['cc_password']);
            }
            $data['cc_password'] = $cc_password;
            $data = $this->helper_->account_decrypt( $data);
            error_log( print_r($data, 1));
        }
        return $data;
    }
}
?>
