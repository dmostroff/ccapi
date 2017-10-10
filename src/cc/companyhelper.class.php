<?php
class Cc_CompanyHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'cc_company';
        $this->colNames_ = 'cc_name, url, contact, address_1, address_2, city, state, zip, country, phone, phone_2, phone_cell, phone_fax, recorded_on';
        $this->idcol_ = 'cc_company_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        $sql=<<<ESQL
    SELECT cc_company.cc_company_id
	, cc_company.cc_name
	, cc_company.url
	, cc_company.contact
	, cc_company.address_1
	, cc_company.address_2
	, cc_company.city
	, cc_company.state
	, cc_company.zip
	, cc_company.country
	, cc_company.phone
	, cc_company.phone_2
	, cc_company.phone_cell
	, cc_company.phone_fax
	, cc_company.recorded_on
    FROM cc_company
ESQL;
        return $sql;
     }

    public function getFkSql( ) {
        $sql=<<<ESQL

ESQL;
        return $sql;
     }

    public function getAll( $dbc) {
        $sql=$this->getSelectSql();
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
     }

    public function getByFk( $dbc, $args) {
        $sql .=<<<ESQL
    SELECT cc_company.cc_company_id
	, cc_company.cc_name
	, cc_company.url
	, cc_company.contact
	, cc_company.address_1
	, cc_company.address_2
	, cc_company.city
	, cc_company.state
	, cc_company.zip
	, cc_company.country
	, cc_company.phone
	, cc_company.phone_2
	, cc_company.phone_cell
	, cc_company.phone_fax
	, cc_company.recorded_on
    FROM cc_company
        
    WHERE 
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
        $insertCols = explode(',', 'cc_name, url, contact, address_1, address_2, city, state, zip, country, phone, phone_2, phone_cell, phone_fax');
        foreach( $insertCols as $col) {
          $col = trim($col);
          $values[$col] = getArrayVal($posted, $col);
        }
        if( 0 < $posted['cc_company_id']) {
            $values[$this->idcol_] = $posted[$this->idcol_];
            $id = $this->update( $dbc, $values);
        } else {
            $sql = <<<ESQL
    INSERT INTO cc_company ( cc_name
	, url
	, contact
	, address_1
	, address_2
	, city
	, state
	, zip
	, country
	, phone
	, phone_2
	, phone_cell
	, phone_fax )
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE cc_name = VALUES(cc_name)
	, url = VALUES(url)
	, contact = VALUES(contact)
	, address_1 = VALUES(address_1)
	, address_2 = VALUES(address_2)
	, city = VALUES(city)
	, state = VALUES(state)
	, zip = VALUES(zip)
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
                $sql1 = "SELECT last_insert_id() as id;";
                $rows = dbconn::exec($dbc, $sql1);
                $id = (isset($rows[0])) ? $rows[0]['id'] : null;
                if( $id == 0) {
                    $sql1 = "SELECT cc_company_id FROM cc_company WHERE cc_company.cc_company_id=?;";
                    $rows = dbconn::exec($dbc, $sql1, [$posted['cc_company_id']]);
                    $id = (isset($rows[0])) ? $rows[0]['cc_company_id'] : null;
                }
            } catch (Exception $ex) {
                error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
            }
        }
        return ['cc_company_id' => $id] ;
    }

    public function updateCompany( $dbc, $cc_company_id, $values ) {
        $values[] = $cc_company_id;
        $sql =<<<ESQL
        UPDATE cc_company
        SET cc_name = coalesce(?, cc_name)
            , url = ?
            , contact = ?
            , address_1 = ?
            , address_2 = ?
            , city = ?
            , state = ?
            , zip = ?
            , country = coalesce( ?, 'US')
            , phone = ?
            , phone_2 = ?
            , phone_cell = ?
            , phone_fax = ?
        WHERE cc_company_id = ?
ESQL;
        try {
            dbconn::exec($dbc, $sql, $values);
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return $cc_company_id;
    }
    
    public function delete($dbc, $ids) {
        $sql = "DELETE FROM cc_company WHERE cc_company.cc_company_id=?";
        return dbconn::exec($dbc, $sql, [$args['cc_company_id']]);
    }
}
?>
