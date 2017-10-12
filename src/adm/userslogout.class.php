<?php
class Adm_UsersLogout extends Base_dblayer {

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
        $data = null;
        if( isset($this->posted_['token'])) {
            $data = ['logut' => 'ok'
                , 'login' => $this->posted_['login']
                , 'token' => $this->posted_['token']
           ];
        }
//        $rows = dbconn::exec($dbc, $sql, $values);
//        if( isset($rows[0])) {
//            $data = $rows[0];
//            if( password_verify( $this->posted_['pwd'], $data['pwd'])) {
//                unset($data['pwd']);
//                $length = 20;
//                $token = bin2hex(random_bytes($length));
//                $data['token'] = $token;
//                $data['posted'] = $this->posted_;
//            } else {
//                $data['token'] = null;
//            }
//        }
        return $data;
    }

}
?>
