<?php

class Client_AddressHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'client_address';
        $this->colNames_ = 'client_id, address_type, address_1, address_2, city, state, zip, country, valid_from, valid_to, recorded_on';
        $this->idcol_ = 'address_id';
        parent::__construct();
    }

    public function getSelectSql() {
        $sql = <<<ESQL
    SELECT client_address.address_id
	, client_address.client_id
	, client_address.address_type
	, client_address.address_1
	, client_address.address_2
	, client_address.city
	, client_address.state
	, client_address.zip
	, client_address.country
	, client_address.valid_from
	, client_address.valid_to
	, client_address.recorded_on
    FROM client_address
ESQL;
        return $sql;
    }

    public function getFkSql() {
        $sql = <<<ESQL
INNER JOIN client_person ON client_address.client_id=client_person.client_id
ESQL;
        return $sql;
    }

    public function getAll($dbc) {
        $sql = $this->getSelectSql();
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
    }

    public function get($dbc, $args) {
        $sql = $this->getSelectSql();
        $sql .=<<<ESQL
        WHERE client_address.address_id=?
ESQL;
        $rows = dbconn::exec($dbc, $sql, [$args['address_id']]);
        $data = [];
        foreach ($rows as $r) {
            $data[] = $r;
        }
        return $data;
    }

    public function getByFk($dbc, $args) {
        $sql = <<<ESQL
    SELECT client_address.address_id
	, client_address.client_id
	, client_address.address_type
	, client_address.address_1
	, client_address.address_2
	, client_address.city
	, client_address.state
	, client_address.zip
	, client_address.country
	, client_address.valid_from
	, client_address.valid_to
	, client_address.recorded_on
    FROM client_address
        INNER JOIN client_person ON client_address.client_id=client_person.client_id
    WHERE client_person.client_id=?
ESQL;
        $rows = dbconn::exec($dbc, $sql, $args);
        $data = [];
        foreach ($rows as $r) {
            $data[] = $r;
        }
        return $data;
    }

    public function post($dbc, $args, $posted) {
        $values = [];
        $insertCols = explode(',', 'client_id, address_type, address_1, address_2, city, state, zip, country, valid_from, valid_to');
        foreach ($insertCols as $col) {
            $col = trim($col);
            $values[$col] = getArrayVal($posted, $col);
        }
        if (isset($posted[$this->idcol_])) {
            $values['client_id'] = $posted['client_id'];
//            error_log(json_encode([__FILE__, __METHOD__, $values]));
            $id = $this->update($dbc, $values);
//            error_log(json_encode([__FILE__, __METHOD__, $id]));
        } else {
            $sql = <<<ESQL
    INSERT INTO client_address ( client_id
	, address_type
	, address_1
	, address_2
	, city
	, state
	, zip
	, country
	, valid_from
	, valid_to )
    VALUES(?,?,?,?,?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE client_id = VALUES(client_id)
	, address_type = VALUES(address_type)
	, address_1 = VALUES(address_1)
	, address_2 = VALUES(address_2)
	, city = VALUES(city)
	, state = VALUES(state)
	, zip = VALUES(zip)
	, country = VALUES(country)
	, valid_from = VALUES(valid_from)
	, valid_to = VALUES(valid_to)
	
ESQL;
            $id = null;
            try {
//            error_log($sql);
//            error_log(print_r($values, 1));
                dbconn::exec($dbc, $sql, $values);
                $sql1 = "SELECT last_insert_id() as id;";
                $rows = dbconn::exec($dbc, $sql1);
                $id = (isset($rows[0])) ? $rows[0]['id'] : null;
            } catch (Exception $ex) {
                error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
            }
        }
        return ['id' => $id];
    }

    public function delete($dbc, $args) {
        $sql = "DELETE FROM client_address WHERE client_address.address_id=?";
        return dbconn::exec($dbc, $sql, [$args[$this->idcol_]]);
    }

}

?>
