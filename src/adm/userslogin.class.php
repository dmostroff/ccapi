<?php
// require_once "../authenticate.php";

class Adm_UsersLogin extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Adm_UsersHelper();
    }

    public function invoke($request, $response, $args) {
        $data = null;
        try {
            $data = $this->run($args);
            if (isset($data) && isset($data['token'])) {
                $rc = 0;
                $msg = 'OK';
            } else {
                $rc = -1;
                $msg = 'Invalid login';
            }
            $retStatus = 200;
        } catch (Exception $ex) {
            error_log(sprintf("%s %s] %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
            $rc = -1;
            $msg = $ex->getMessage();
            $retStatus = 400;
        }
        $ret = ['res' => ['rc' => $rc, 'msg' => $msg], 'data' => $data];
        return $this->jsonResponse($ret, $retStatus);
    }

    public function run($args) {
        $dbc = $this->connect();
        $sql = $this->helper_->getSelectSql();
        $sql .=<<<ESQL
    WHERE login = ?        
ESQL;
        $values = [$this->posted_['login']];
        $rows = dbconn::exec($dbc, $sql, $values);
        $data = null;
        if (isset($rows[0])) {
            $data = $rows[0];
//            error_log( "=====================");
//            error_log( $data['pwd']);
//            error_log( $this->posted_['pwd']);
//            error_log(password_verify($this->posted_['pwd'], $data['pwd']));
//            error_log( "=====================");
            if (password_verify($this->posted_['pwd'], $data['pwd'])) {
                unset($data['pwd']);
                $token = Authenticate::generate_token();
                Authenticate::persist_token($token, $data);
                $data['token'] = $this->token_ = $token;
                $data['validate'] = Authenticate::validate_token($this->token_);
                error_log(print_r($data,1));
            } else {
                $this->token_ = null;
            }
            $data['posted'] = $this->posted_;
        } else {
            $data['token'] = null;
        }
        return $data;
    }

}

?>
