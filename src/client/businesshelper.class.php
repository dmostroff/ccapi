<?php
class Client_BusinessHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'client_business';
        $this->colNames_ = 'client_id, business_name, address_id, revenue, num_of_years, num_of_employees, valid_from, valid_to, recorded_on';
        $this->idcol_ = 'pbiz_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        $sql=<<<ESQL
    SELECT client_business.pbiz_id
	, client_business.client_id
	, client_business.business_name
	, client_business.address_id
	, client_business.revenue
	, client_business.num_of_years
	, client_business.num_of_employees
	, client_business.valid_from
	, client_business.valid_to
	, client_business.recorded_on
    FROM client_business
ESQL;
        return $sql;
     }

    public function getFkSql( ) {
        $sql=<<<ESQL
INNER JOIN client_person ON client_business.client_id=client_person.client_id
ESQL;
        return $sql;
     }

    public function getAll( $dbc) {
        $sql=$this->getSelectSql();
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
     }

    public function get( $dbc, $args) {
        $sql=$this->getSelectSql();
        $sql .=<<<ESQL
        WHERE client_business.pbiz_id=?
ESQL;
        $rows = dbconn::exec($dbc, $sql, [$args['pbiz_id']]);
        $data = [];
        foreach( $rows as $r) {
            $data[] = $r;
        }
        return $data;
     }

    public function getByFk( $dbc, $args) {
        $sql =<<<ESQL
    SELECT client_business.pbiz_id
	, client_business.client_id
	, client_business.business_name
	, client_business.address_id
	, client_business.revenue
	, client_business.num_of_years
	, client_business.num_of_employees
	, client_business.valid_from
	, client_business.valid_to
	, client_business.recorded_on
    FROM client_business
        INNER JOIN client_person ON client_business.client_id=client_person.client_id
    WHERE client_person.client_id=?
ESQL;
        $rows = dbconn::exec($dbc, $sql, $args);
        $data = [];
        foreach( $rows as $r) {
            $data[] = $r;
        }
        return $data;
     }

    public function post( $dbc, $args, $posted) {
        $values = [];
        $insertCols = explode(',', 'client_id, business_name, address_id, revenue, num_of_years, num_of_employees, valid_from, valid_to');
        foreach( $insertCols as $col) {
          $col = trim($col);
          $values[$col] = getArrayVal($posted, $col);
        }
        $sql = <<<ESQL
    INSERT INTO client_business ( client_id
	, business_name
	, address_id
	, revenue
	, num_of_years
	, num_of_employees
	, valid_from
	, valid_to )
    VALUES(?,?,?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE client_id = VALUES(client_id)
	, business_name = VALUES(business_name)
	, address_id = VALUES(address_id)
	, revenue = VALUES(revenue)
	, num_of_years = VALUES(num_of_years)
	, num_of_employees = VALUES(num_of_employees)
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
                $sql1 = "SELECT pbiz_id FROM client_business WHERE client_business.pbiz_id=?;";
                $rows = dbconn::exec($dbc, $sql1, [$args]);
                $id = (isset($rows[0])) ? $rows[0] : null;
            }
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return ['id' => $id] ;
    }

    public function delete($dbc, $ids) {
        $sql = "DELETE FROM client_business WHERE client_business.pbiz_id=?";
        return dbconn::exec($dbc, $sql, [$args['pbiz_id']]);
    }
}
?>
