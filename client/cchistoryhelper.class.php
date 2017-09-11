<?php
class Client_CchistoryHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'client_cchistory';
        $this->colNames_ = 'clicc_id, ccevent, ccevent_amt, details, recorded_on';
        $this->idcol_ = 'cchist_id';
        parent::__construct();
    }
        
    public function getAll( $dbc) {
        $sql=<<<ESQL
        SELECT cchist_id, clicc_id
	, ccevent
	, ccevent_amt
	, details
	, recorded_on
        FROM client_cchistory
ESQL;
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
     }

    public function get( $dbc, $args) {
        $sql=<<<ESQL
        SELECT cchist_id, clicc_id
	, ccevent
	, ccevent_amt
	, details
	, recorded_on
        FROM client_cchistory
        WHERE cchist_id = ?
ESQL;
        $rows = dbconn::exec($dbc, $sql, [$args['cchist_id']]);
        return $rows;
     }

    public function post( $dbc, $args, $posted) {
        $values = [];
        $insertCols = explode(',', 'clicc_id, ccevent, ccevent_amt, details');
        foreach( $insertCols as $col) {
          $col = trim($col);
          $values[$col] = getArrayVal($posted, $col);
        }
        $sql = <<<ESQL
    INSERT INTO client_cchistory ( clicc_id
	, ccevent
	, ccevent_amt
	, details )
    VALUES(?,?,?,?)
    ON DUPLICATE KEY UPDATE clicc_id = VALUES(clicc_id)
	, ccevent = VALUES(ccevent)
	, ccevent_amt = VALUES(ccevent_amt)
	, details = VALUES(details)
	
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
                $sql1 = "SELECT cchist_id FROM client_cchistory WHERE cchist_id = ?;";
                $rows = dbconn::exec($dbc, $sql1, [$args]);
                $id = (isset($rows[0])) ? $rows[0] : null;
            }
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return ['id' => $id] ;
    }

    public function delete($dbc, $ids) {
        $sql = "DELETE FROM client_cchistory WHERE cchist_id = ?";
        return dbconn::exec($dbc, $sql, [$args['cchist_id']]);
    }
}
?>
