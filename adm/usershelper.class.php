<?php
class Adm_UsersHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'adm_users';
        $this->colNames_ = 'login, pwd, user_name, email, phone, phone_2, phone_cell, phone_fax, recorded_on';
        $this->idcol_ = 'user_id';
        parent::__construct();
    }
        
    public function getAll( $dbc) {
        $sql=<<<ESQL
        SELECT user_id, login
	, pwd
	, user_name
	, email
	, phone
	, phone_2
	, phone_cell
	, phone_fax
	, recorded_on
        FROM adm_users
ESQL;
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
     }

    public function get( $dbc, $args) {
        $sql=<<<ESQL
        SELECT user_id, login
	, pwd
	, user_name
	, email
	, phone
	, phone_2
	, phone_cell
	, phone_fax
	, recorded_on
        FROM adm_users
        WHERE user_id = ?
ESQL;
        $rows = dbconn::exec($dbc, $sql, [$args['user_id']]);
        return $rows;
     }

    public function post( $dbc, $args, $posted) {
        $values = [];
        $insertCols = explode(',', 'login, pwd, user_name, email, phone, phone_2, phone_cell, phone_fax');
        foreach( $insertCols as $col) {
          $col = trim($col);
          $values[$col] = getArrayVal($posted, $col);
        }
        $sql = <<<ESQL
    INSERT INTO adm_users ( login
	, pwd
	, user_name
	, email
	, phone
	, phone_2
	, phone_cell
	, phone_fax )
    VALUES(?,?,?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE login = VALUES(login)
	, pwd = VALUES(pwd)
	, user_name = VALUES(user_name)
	, email = VALUES(email)
	, phone = VALUES(phone)
	, phone_2 = VALUES(phone_2)
	, phone_cell = VALUES(phone_cell)
	, phone_fax = VALUES(phone_fax)
	
ESQL;
        $id = null;
        try {
//            error_log($sql);
//            error_log(print_r($values, 1));
            dbconn::exec($dbc, $sql, $values);
            if(1) {
                $sql1 = "SELECT last_insert_id() as id;";
                $rows = dbconn::exec($dbc, $sql1);
                $id = (isset($rows[0])) ? $rows[0]['id'] : null;
            } else {
                $sql1 = "SELECT user_id FROM adm_users WHERE user_id = ?;";
                $rows = dbconn::exec($dbc, $sql1, [$args]);
                $id = (isset($rows[0])) ? $rows[0] : null;
            }
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return ['id' => $id] ;
    }

    public function delete($dbc, $ids) {
        $sql = "DELETE FROM adm_users WHERE user_id = ?";
        return dbconn::exec($dbc, $sql, [$args['user_id']]);
    }
}
?>
