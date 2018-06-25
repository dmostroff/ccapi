<?php
class Account_AccountPost extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Account_AccountHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        error_log(__METHOD__ . ':' . json_encode($this->posted_));
        $this->posted_['account_date'] = date( 'Y-m-d', strtotime($this->posted_['account_date']));
        $this->posted_['account'] = $this->accrypt();
        $this->posted_['cc_password'] = cryptutils::sslEncrypt($this->posted_['cc_password']);
        $rows = $this->helper_->post($dbc, $args, $this->posted_);
        $data = null;
        if( isset($rows[$this->helper_->idcol_])) {
            $data = $this->helper_->get($dbc, $rows);
        }
        return $data;
    }
    
    private function accrypt( ) {
        $accnum = preg_replace( '/[^\d]/', '', $this->posted_['account_num']);
        $accinfo = preg_replace( '/[^\d]/', '', $this->posted_['account_info']);
        $accdate = preg_replace( '/[^\d]/', '', $this->posted_['account_date']);
        $account = sprintf( "%s^%s^%s", $accnum, $accinfo, $accdate);
        $retStr = cryptutils::sslEncrypt ( $account);
        $dec = cryptutils::sslDecrypt( $retStr);
        error_log( $account);
        error_log( $retStr);
        error_log( $dec);
        error_log( $this->posted_['account_date']);
        return $retStr;
    }

}
?>
