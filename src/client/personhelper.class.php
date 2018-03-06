<?php

class Client_PersonHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'client_person';
        $this->colNames_ = 'last_name, first_name, middle_name, dob, gender, ssn, mmn, email, pwd, phone, phone_2, phone_cell, phone_fax, recorded_on';
        $this->idcol_ = 'client_id';
        parent::__construct();
    }

    public function getSelectSql() {
        $sql = <<<ESQL
    SELECT client_person.client_id
	, client_person.last_name
	, client_person.first_name
	, client_person.middle_name
	, client_person.dob
	, client_person.gender
	, client_person.ssn
	, client_person.mmn
	, client_person.email
	, client_person.pwd
	, client_person.phone
	, client_person.phone_2
	, client_person.phone_cell
	, client_person.phone_fax
	, client_person.recorded_on
    FROM client_person
ESQL;
        return $sql;
    }

    public function getFkSql() {
        $sql = <<<ESQL

ESQL;
        return $sql;
    }

    public function getAll($dbc) {
        $sql = $this->getSelectSql();
        $sql .= " ORDER BY 1";
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
    }

    public function getByFk($dbc, $args) {
        $sql .=<<<ESQL
    SELECT client_person.client_id
	, client_person.last_name
	, client_person.first_name
	, client_person.middle_name
	, client_person.dob
	, client_person.gender
	, client_person.ssn
	, client_person.mmn
	, client_person.email
	, client_person.pwd
	, client_person.phone
	, client_person.phone_2
	, client_person.phone_cell
	, client_person.phone_fax
	, client_person.recorded_on
    FROM client_person
        
    WHERE 
ESQL;
        $rows = dbconn::exec($dbc, $sql, $args);
        $data = [];
        foreach ($rows as $r) {
            $data[] = $r;
        }
        return $data;
    }

    public function post($dbc, $args, $posted) {
        $id = getArrayVal($posted, $this->idcol_);
        $values = [$id];
        $insertCols = explode(',', $this->colNames_);
        foreach ($insertCols as $col) {
            $col = trim($col);
            $values[$col] = getArrayVal($posted, $col);
        }
        $sql = <<<ESQL
    WITH parms AS (
      SELECT ? as client_id
        , ? as last_name
	, ? as first_name
	, ? as middle_name
	, ? as dob
	, ? as gender
	, ? as ssn
	, ? as mmn
	, ? as email
	, ? as pwd
	, ? as phone
	, ? as phone_2
	, ? as phone_cell
	, ? as phone_fax
    ), upd AS (
      UPDATE client_paerson
      SET last_name = parms.last_name
	, first_name = parms.first_name
	, middle_name = parms.middle_name
	, dob = parms.dob
	, gender = parms.gender
	, ssn = parms.ssn
	, mmn = parms.mmn
	, email = parms.email
	, pwd = parms.pwd
	, phone = parms.phone
	, phone_2 = parms.phone_2
	, phone_cell = parms.phone_cell
	, phone_fax = parms.phone_fax
      FROM parms
      WHERE client_person.client_id = parms.client_id
      RETURNING *
    ), ins AS (
     INSERT INTO client_person ( 
        last_name
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
    )
    SELECT last_name
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
    FROM parms
    WHERE NOT EXISTS ( SELECT 1 from upd)
    RETURNING *
    )
    SELECT upd.client_id
    FROM upd
    UNION ALL
    SELECT ins.client_id
    FROM ins
ESQL;
        $id = null;
        try {
    //            error_log($sql);
    //            error_log(print_r($values, 1));
            $rows = dbconn::exec($dbc, $sql, $values);
            $id = (isset($rows[0])) ? $rows[0][$this->idcol_] : $posted[$this->idcol_];
        } catch (Exception $ex) {
            error_log(sprintf("!!! %s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return ['client_id' => $id];
    }

    public function delete($dbc, $ids, $posted) {
        $sql = "DELETE FROM client_person WHERE client_person.client_id=?";
        return dbconn::exec($dbc, $sql, [$args['client_id']]);
    }

}

?>
