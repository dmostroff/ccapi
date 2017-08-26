<?php
class Client_AddressHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'client_address';
        $this->colNames_ = 'client_id, address_type, address_1, address_2, city, state, country, valid_from, valid_to, recorded_on';
        $this->idcol_ = 'address_id';
        parent::__construct();
    }
        
    public function getAll( $dbc) {
        $sql=<<<ESQL
        SELECT address_id, client_id
	, address_type
	, address_1
	, address_2
	, city
	, state
	, country
	, valid_from
	, valid_to
	, recorded_on
        FROM client_address
ESQL;
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
     }

    public function get( $dbc, $args) {
        $sql=<<<ESQL
        SELECT address_id, client_id
	, address_type
	, address_1
	, address_2
	, city
	, state
	, country
	, valid_from
	, valid_to
	, recorded_on
        FROM client_address
        WHERE address_id = ?
ESQL;
        $rows = dbconn::exec($dbc, $sql, [$args['address_id']]);
        return $rows;
     }

    public function post( $dbc, $args, $posted) {
        $values = [];
        $insertCols = explode(',', 'client_id, address_type, address_1, address_2, city, state, country, valid_from, valid_to');
        foreach( $insertCols as $col) {
          $col = trim($col);
          $values[$col] = getArrayVal($posted, $col);
        }
        $sql = <<<ESQL
    INSERT INTO client_address ( client_id
	, address_type
	, address_1
	, address_2
	, city
	, state
	, country
	, valid_from
	, valid_to )
    VALUES(?,?,?,?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE client_id = VALUES(client_id)
	, address_type = VALUES(address_type)
	, address_1 = VALUES(address_1)
	, address_2 = VALUES(address_2)
	, city = VALUES(city)
	, state = VALUES(state)
	, country = VALUES(country)
	, valid_from = VALUES(valid_from)
	, valid_to = VALUES(valid_to)
	
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
                $sql1 = "SELECT address_id FROM client_address WHERE address_id = ?;";
                $rows = dbconn::exec($dbc, $sql1, [$args]);
                $id = (isset($rows[0])) ? $rows[0] : null;
            }
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return ['id' => $id] ;
    }

    public function delete($dbc, $ids) {
        $sql = "DELETE FROM client_address WHERE address_id = ?";
        return dbconn::exec($dbc, $sql, [$args['address_id']]);
    }
}
?>
