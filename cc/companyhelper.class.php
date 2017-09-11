<?php
class Cc_CompanyHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'cc_company';
        $this->colNames_ = 'cc_name, url, contact, address_1, address_2, city, state, country, phone, phone_2, phone_cell, phone_fax, recorded_on';
        $this->idcol_ = 'cc_company_id';
        parent::__construct();
    }
        
    public function getAll( $dbc) {
        $sql=<<<ESQL
        SELECT cc_company_id, cc_name
	, url
	, contact
	, address_1
	, address_2
	, city
	, state
	, country
	, phone
	, phone_2
	, phone_cell
	, phone_fax
	, recorded_on
        FROM cc_company
ESQL;
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
     }

    public function get( $dbc, $args) {
        $sql=<<<ESQL
        SELECT cc_company_id, cc_name
	, url
	, contact
	, address_1
	, address_2
	, city
	, state
	, country
	, phone
	, phone_2
	, phone_cell
	, phone_fax
	, recorded_on
        FROM cc_company
        WHERE cc_company_id = ?
ESQL;
        $rows = dbconn::exec($dbc, $sql, [$args['cc_company_id']]);
        return $rows;
     }

    public function post( $dbc, $args, $posted) {
        $values = [];
        $insertCols = explode(',', 'cc_name, url, contact, address_1, address_2, city, state, country, phone, phone_2, phone_cell, phone_fax');
        foreach( $insertCols as $col) {
          $col = trim($col);
          $values[$col] = getArrayVal($posted, $col);
        }
        $sql = <<<ESQL
    INSERT INTO cc_company ( cc_name
	, url
	, contact
	, address_1
	, address_2
	, city
	, state
	, country
	, phone
	, phone_2
	, phone_cell
	, phone_fax )
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE cc_name = VALUES(cc_name)
	, url = VALUES(url)
	, contact = VALUES(contact)
	, address_1 = VALUES(address_1)
	, address_2 = VALUES(address_2)
	, city = VALUES(city)
	, state = VALUES(state)
	, country = VALUES(country)
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
                $sql1 = "SELECT cc_company_id FROM cc_company WHERE cc_company_id = ?;";
                $rows = dbconn::exec($dbc, $sql1, [$args]);
                $id = (isset($rows[0])) ? $rows[0] : null;
            }
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return ['id' => $id] ;
    }

    public function delete($dbc, $ids) {
        $sql = "DELETE FROM cc_company WHERE cc_company_id = ?";
        return dbconn::exec($dbc, $sql, [$args['cc_company_id']]);
    }
}
?>
