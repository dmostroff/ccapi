<?php
class Client_FinancialsHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'client_financials';
        $this->colNames_ = 'client_id, annual_income, credit_line, valid_from, valid_to, recorded_on';
        $this->idcol_ = 'financial_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        $sql=<<<ESQL
    SELECT client_financials.financial_id
	, client_financials.client_id
	, client_financials.annual_income
	, client_financials.credit_line
	, client_financials.valid_from
	, client_financials.valid_to
	, client_financials.recorded_on
    FROM client_financials
ESQL;
        return $sql;
     }

    public function getFkSql( ) {
        $sql=<<<ESQL
INNER JOIN client_person ON client_financials.client_id=client_person.client_id
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
    SELECT client_financials.financial_id
	, client_financials.client_id
	, client_financials.annual_income
	, client_financials.credit_line
	, client_financials.valid_from
	, client_financials.valid_to
	, client_financials.recorded_on
    FROM client_financials
        INNER JOIN client_person ON client_financials.client_id=client_person.client_id
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
        $insertCols = explode(',', 'client_id, annual_income, credit_line, valid_from, valid_to');
        foreach( $insertCols as $col) {
          $col = trim($col);
          $values[$col] = getArrayVal($posted, $col);
        }
        $sql = <<<ESQL
    INSERT INTO client_financials ( client_id
	, annual_income
	, credit_line
	, valid_from
	, valid_to )
    VALUES(?,?,?,?,?)
    ON DUPLICATE KEY UPDATE client_id = VALUES(client_id)
	, annual_income = VALUES(annual_income)
	, credit_line = VALUES(credit_line)
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
                $sql1 = "SELECT financial_id FROM client_financials WHERE client_financials.financial_id=?;";
                $rows = dbconn::exec($dbc, $sql1, [$args]);
                $id = (isset($rows[0])) ? $rows[0] : null;
            }
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return ['id' => $id] ;
    }

    public function delete($dbc, $ids) {
        $sql = "DELETE FROM client_financials WHERE client_financials.financial_id=?";
        return dbconn::exec($dbc, $sql, [$args['financial_id']]);
    }
}
?>
