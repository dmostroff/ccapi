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
        $sql .= " ORDER BY cc_name";
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
        if( 0 < $posted[$this->idcol_]) {
            $values[$this->idcol_] = $posted[$this->idcol_];
            $id = $this->update( $dbc, $values);
        } else {
            $sql = <<<ESQL
    WITH parms AS (
      SELECT ?::integer as {$this->idcol_}
        , ?::text as cc_name
	, ?::text as url
	, ?::text as contact
	, ?::text as address_1
	, ?::text as address_2
	, ?::text as city
	, ?::text as state
	, ?::text as zip
	, ?::text as country
        , regexp_replace( ?, '[^\d]', '', 'g') as phone
        , regexp_replace( ?, '[^\d]', '', 'g') as phone_2
        , regexp_replace( ?, '[^\d]', '', 'g') as phone_cell
        , regexp_replace( ?, '[^\d]', '', 'g') as phone_fax
    ), upd AS (
      UPDATE cc_company
      SET cc_name = parms.cc_name
	, url = parms.url
	, contact = parms.contact
	, address_1 = parms.contact
	, address_2 = parms.contact
	, city = parms.contact
	, state = parms.contact
	, zip = parms.contact
	, country = parms.contact
	, phone = parms.contact
	, phone_2 = parms.contact
	, phone_cell = parms.contact
	, phone_fax  = parms.contact
      FROM parms
      WHERE cc_company.{$this->idcol_} = parms.{$this->idcol_}
      RETURNING cc_company.{$this->idcol_}
    ), ins AS (
        INSERT INTO cc_company ( 
            cc_name
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
            , phone_fax
        )
        SELECT cc_name
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
            , phone_fax
        FROM parms
        RETURNING cc_company.{$this->idcol_}
    )
    SELECT {$this->idcol_}
    FROM upd
    UNION ALL
    SELECT {$this->idcol_}
    FROM ins
ESQL;
            $id = null;
            try {
    //            error_log($sql);
    //            error_log(print_r($values, 1));
                $rows = dbconn::exec($dbc, $sql, $values);
                $id = (isset($rows[0])) ? $rows[0][$this->idcol_] : null;
            } catch (Exception $ex) {
                error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
            }
        }
        return [$this->idcol_ => $id] ;
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
