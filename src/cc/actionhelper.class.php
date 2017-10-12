<?php
class Cc_ActionHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'cc_action';
        $this->colNames_ = 'clicc_id, ccaction, action_type, action_status, due_date, details, recorded_on';
        $this->idcol_ = 'ccaction_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        $sql=<<<ESQL
    SELECT cc_action.ccaction_id
	, cc_action.clicc_id
	, cc_action.ccaction
	, cc_action.action_type
	, cc_action.action_status
	, cc_action.due_date
	, cc_action.details
	, cc_action.recorded_on
    FROM cc_action
ESQL;
        return $sql;
     }

    public function getFkSql( ) {
        $sql=<<<ESQL
INNER JOIN client_cc ON cc_action.clicc_id=client_cc.clicc_id
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
        WHERE cc_action.ccaction_id=?
ESQL;
        $rows = dbconn::exec($dbc, $sql, [$args['ccaction_id']]);
        $data = [];
        foreach( $rows as $r) {
            $data[] = $r;
        }
        return $data;
     }

    public function getByFk( $dbc, $args) {
        $sql =<<<ESQL
    SELECT cc_action.ccaction_id
	, cc_action.clicc_id
	, cc_action.ccaction
	, cc_action.action_type
	, cc_action.action_status
	, cc_action.due_date
	, cc_action.details
	, cc_action.recorded_on
    FROM cc_action
        INNER JOIN client_cc ON cc_action.clicc_id=client_cc.clicc_id
    WHERE client_cc.clicc_id=?
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
        $insertCols = explode(',', 'clicc_id, ccaction, action_type, action_status, due_date, details');
        foreach( $insertCols as $col) {
          $col = trim($col);
          $values[$col] = getArrayVal($posted, $col);
        }
        if( $posted[$this->idcols_] > 0) {
            $values[$this->idcols_] = $posted[$this->idcols_];
            $this->update($dbc, $values);
        } else {
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
                $sql1 = "SELECT ccaction_id FROM cc_action WHERE cc_action.ccaction_id=?;";
                $rows = dbconn::exec($dbc, $sql1, [$args]);
                $id = (isset($rows[0])) ? $rows[0] : null;
            } catch (Exception $ex) {
                error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
            }
        }
        return ['id' => $id] ;
    }

}
?>
