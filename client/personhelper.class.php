<?php
class Client_PersonHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'client_person';
        $this->colNames_ = 'last_name, first_name, middle_name, dob, gender, ssn, mmn, email, pwd, phone, phone_2, phone_cell, phone_fax, phone_official, recorded_on';
        $this->idcol_ = 'client_id';
        parent::__construct();
    }
        
    public function getAll( $dbc) {
        $sql=<<<ESQL
        SELECT client_id, last_name
	, first_name
	, middle_name
	, dob
	, gender
	, ssn
	, mmn
	, email
	, pwd
	, phone
	, phone_2
	, phone_cell
	, phone_fax
	, phone_official
	, recorded_on
        FROM client_person
ESQL;
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
     }

    public function get( $dbc, $args) {
        $sql=<<<ESQL
        SELECT client_id, last_name
	, first_name
	, middle_name
	, dob
	, gender
	, ssn
	, mmn
	, email
	, pwd
	, phone
	, phone_2
	, phone_cell
	, phone_fax
	, phone_official
	, recorded_on
        FROM client_person
        WHERE client_id = ?
ESQL;
        $rows = dbconn::exec($dbc, $sql, [$args['client_id']]);
        return $rows;
     }

    public function post( $dbc, $args, $posted) {
        $values = [];
        $insertCols = explode(',', 'last_name, first_name, middle_name, dob, gender, ssn, mmn, email, pwd, phone, phone_2, phone_cell, phone_fax, phone_official');
        foreach( $insertCols as $col) {
          $col = trim($col);
          $values[$col] = getArrayVal($posted, $col);
        }
        $sql = <<<ESQL
    INSERT INTO client_person ( last_name
	, first_name
	, middle_name
	, dob
	, gender
	, ssn
	, mmn
	, email
	, pwd
	, phone
	, phone_2
	, phone_cell
	, phone_fax
	, phone_official )
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE last_name = VALUES(last_name)
	, first_name = VALUES(first_name)
	, middle_name = VALUES(middle_name)
	, dob = VALUES(dob)
	, gender = VALUES(gender)
	, ssn = VALUES(ssn)
	, mmn = VALUES(mmn)
	, email = VALUES(email)
	, pwd = VALUES(pwd)
	, phone = VALUES(phone)
	, phone_2 = VALUES(phone_2)
	, phone_cell = VALUES(phone_cell)
	, phone_fax = VALUES(phone_fax)
	, phone_official = VALUES(phone_official)
	
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
                $sql1 = "SELECT client_id FROM client_person WHERE client_id = ?;";
                $rows = dbconn::exec($dbc, $sql1, [$args]);
                $id = (isset($rows[0])) ? $rows[0] : null;
            }
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return ['id' => $id] ;
    }

    public function delete($dbc, $ids) {
        $sql = "DELETE FROM client_person WHERE client_id = ?";
        return dbconn::exec($dbc, $sql, [$args['client_id']]);
    }
}
?>
