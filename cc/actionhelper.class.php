<?php
class Cc_ActionHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'cc_action';
        $this->colNames_ = 'clicc_id, ccaction, action_type, action_status, due_date, details, recorded_on';
        $this->idcol_ = 'ccaction_id';
        parent::__construct();
    }
        
    public function getAll( $dbc) {
        $sql=<<<ESQL
        SELECT ccaction_id, clicc_id
	, ccaction
	, action_type
	, action_status
	, due_date
	, details
	, recorded_on
        FROM cc_action
ESQL;
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
     }

    public function get( $dbc, $args) {
        $sql=<<<ESQL
        SELECT ccaction_id, clicc_id
	, ccaction
	, action_type
	, action_status
	, due_date
	, details
	, recorded_on
        FROM cc_action
        WHERE ccaction_id = ?
ESQL;
        $rows = dbconn::exec($dbc, $sql, [$args['ccaction_id']]);
        return $rows;
     }

    public function post( $dbc, $args, $posted) {
        $values = [];
        $insertCols = explode(',', 'clicc_id, ccaction, action_type, action_status, due_date, details');
        foreach( $insertCols as $col) {
          $col = trim($col);
          $values[$col] = getArrayVal($posted, $col);
        }
        $sql = <<<ESQL
    INSERT INTO cc_action ( clicc_id
	, ccaction
	, action_type
	, action_status
	, due_date
	, details )
    VALUES(?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE clicc_id = VALUES(clicc_id)
	, ccaction = VALUES(ccaction)
	, action_type = VALUES(action_type)
	, action_status = VALUES(action_status)
	, due_date = VALUES(due_date)
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
                $sql1 = "SELECT ccaction_id FROM cc_action WHERE ccaction_id = ?;";
                $rows = dbconn::exec($dbc, $sql1, [$args]);
                $id = (isset($rows[0])) ? $rows[0] : null;
            }
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return ['id' => $id] ;
    }

    public function delete($dbc, $ids) {
        $sql = "DELETE FROM cc_action WHERE ccaction_id = ?";
        return dbconn::exec($dbc, $sql, [$args['ccaction_id']]);
    }
}
?>
