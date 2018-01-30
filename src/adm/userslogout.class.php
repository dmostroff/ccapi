<?php
class Adm_UsersLogout extends Base_dblayer {

    public function __construct() {
        $this->helper_ = new Adm_UsersHelper();
    }

    public function run($args) {
        Authenticate::release_token($this->token_);
//        $dbc = $this->connect();
//        $sql = <<<ESQL
//    DELETE FROM adm_sessions
//    WHERE login = ?
//        AND token = ?
//ESQL;
//        $values = [$this->posted_['login'], $this->posted_['token']];
//        $data = [];
//        $rows = dbconn::exec($dbc, $sql, $values);
//        if( isset($rows[0])) {
//            $data = $rows[0];
//        }
//        $sql = <<<ESQL
//    SELECT * FROM adm_sessions
//    WHERE login = ?
//        AND token = ?
//ESQL;
//        $values = [$this->posted_['login'], $this->posted_['token']];
//        $data = ['deleted'];
//        $rows = dbconn::exec($dbc, $sql, $values);
//        if( isset($rows[0])) {
//            $data = $rows[0];
//        }
        error_log( "Token released " . $this->token_ . ":" . json_encode($args));
        return [$this->token_];
    }

}
?>
