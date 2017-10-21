<?php
class Adm_UsersLogin extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Adm_UsersHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $sql = $this->helper_->getSelectSql( );
        $sql .=<<<ESQL
    WHERE login = ?        
ESQL;
        $values = [$this->posted_['login']];
        $rows = dbconn::exec($dbc, $sql, $values);
        $data = null;
        if( isset($rows[0])) {
            $data = $rows[0];
            if( password_verify( $this->posted_['pwd'], $data['pwd'])) {
                unset($data['pwd']);
                $length = 20;
                $token = bin2hex(random_bytes($length));
                if(isset($this->token_)) {
                    $sqlLogin = "SELECT f_login( ?, ?) as valid_login";
                    $valLogin = [ $data['login'], $this->token_];
                    $retVal = dbconn::exec($dbc, $sqlLogin, $valLogin);
                    if( isset($retVal[0])) {
                        if($retVal[0]['valid_login'] == 0) {
                            $this->token_ = $token;
                        }
                    }
                } else {
                    $this->token_ = $token;
                }
              $data['posted'] = $this->posted_;
            } else {
                $data['token'] = null;
            }
        }
        return $data;
    }

}
?>
