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
      SELECT ?::integer as client_id
        , ?::text as last_name
        , ?::text as first_name
        , ?::text as middle_name
        , ?::date as dob
        , ?::text as gender
        , regexp_replace( ?, '[^\d]', '', 'g') as ssn
        , ?::text as mmn
        , ?::text as email
        , ?::text as pwd
        , regexp_replace( ?, '[^\d]', '', 'g') as phone
        , regexp_replace( ?, '[^\d]', '', 'g') as phone_2
        , regexp_replace( ?, '[^\d]', '', 'g') as phone_cell
        , regexp_replace( ?, '[^\d]', '', 'g') as phone_fax
        , ?::text as recorded_on
    ), upd AS (
      UPDATE client_person
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
      RETURNING client_person.*
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
    FROM parms
    WHERE NOT EXISTS ( SELECT 1 from upd)
    RETURNING *
    )
    SELECT upd.*
    FROM upd
    UNION ALL
    SELECT ins.*
    FROM ins
ESQL;
        $rows = [];
        try {
//                error_log($sql);
//                error_log(print_r($values, 1));
            $rows = dbconn::exec($dbc, $sql, $values);
//            $id = (isset($rows[0])) ? $rows[0][$this->idcol_] : $posted[$this->idcol_];
        } catch (Exception $ex) {
            error_log(sprintf("!!! %s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return $rows;
    }

    public function delete($dbc, $ids, $posted) {
        $sql = "DELETE FROM client_person WHERE client_person.client_id=?";
        return dbconn::exec($dbc, $sql, [$args['client_id']]);
    }

}

?>
