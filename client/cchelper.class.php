<?php
class Client_CcHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'client_cc';
        $this->colNames_ = 'client_id, name, mispar, exp_date, mispar_v, cc_login, cc_password, cc_company_id, cc_status, annual_fee, credit_limit, addtional_card, recorded_on';
        $this->idcol_ = 'clicc_id';
        parent::__construct();
    }
        
    public function getAll( $dbc) {
        $sql=<<<ESQL
        SELECT clicc_id, client_id
	, name
	, mispar
	, exp_date
	, mispar_v
	, cc_login
	, cc_password
	, cc_company_id
	, cc_status
	, annual_fee
	, credit_limit
	, addtional_card
	, recorded_on
        FROM client_cc
ESQL;
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
     }

    public function get( $dbc, $args) {
        $sql=<<<ESQL
        SELECT clicc_id, client_id
	, name
	, mispar
	, exp_date
	, mispar_v
	, cc_login
	, cc_password
	, cc_company_id
	, cc_status
	, annual_fee
	, credit_limit
	, addtional_card
	, recorded_on
        FROM client_cc
        WHERE clicc_id = ?
ESQL;
        $rows = dbconn::exec($dbc, $sql, [$args['clicc_id']]);
        return $rows;
     }

    public function post( $dbc, $args, $posted) {
        $values = [];
        $insertCols = explode(',', 'client_id, name, mispar, exp_date, mispar_v, cc_login, cc_password, cc_company_id, cc_status, annual_fee, credit_limit, addtional_card');
        foreach( $insertCols as $col) {
          $col = trim($col);
          $values[$col] = getArrayVal($posted, $col);
        }
        $sql = <<<ESQL
    INSERT INTO client_cc ( client_id
	, name
	, mispar
	, exp_date
	, mispar_v
	, cc_login
	, cc_password
	, cc_company_id
	, cc_status
	, annual_fee
	, credit_limit
	, addtional_card )
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE client_id = VALUES(client_id)
	, name = VALUES(name)
	, mispar = VALUES(mispar)
	, exp_date = VALUES(exp_date)
	, mispar_v = VALUES(mispar_v)
	, cc_login = VALUES(cc_login)
	, cc_password = VALUES(cc_password)
	, cc_company_id = VALUES(cc_company_id)
	, cc_status = VALUES(cc_status)
	, annual_fee = VALUES(annual_fee)
	, credit_limit = VALUES(credit_limit)
	, addtional_card = VALUES(addtional_card)
	
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
                $sql1 = "SELECT clicc_id FROM client_cc WHERE clicc_id = ?;";
                $rows = dbconn::exec($dbc, $sql1, [$args]);
                $id = (isset($rows[0])) ? $rows[0] : null;
            }
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return ['id' => $id] ;
    }

    public function delete($dbc, $ids) {
        $sql = "DELETE FROM client_cc WHERE clicc_id = ?";
        return dbconn::exec($dbc, $sql, [$args['clicc_id']]);
    }
}
?>
