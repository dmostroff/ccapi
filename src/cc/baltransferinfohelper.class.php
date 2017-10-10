<?php
class Cc_BaltransferinfoHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'cc_baltransferinfo';
        $this->colNames_ = 'client_id, clicc_id, due_date, total, credit_line, recorded_on';
        $this->idcol_ = 'bal_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        $sql=<<<ESQL
    SELECT cc_baltransferinfo.bal_id
	, cc_baltransferinfo.client_id
	, cc_baltransferinfo.clicc_id
	, cc_baltransferinfo.due_date
	, cc_baltransferinfo.total
	, cc_baltransferinfo.credit_line
	, cc_baltransferinfo.recorded_on
    FROM cc_baltransferinfo
ESQL;
        return $sql;
     }

    public function getFkSql( ) {
        $sql=<<<ESQL
INNER JOIN client_person ON cc_baltransferinfo.client_id=client_person.client_idINNER JOIN client_cc ON cc_baltransferinfo.clicc_id=client_cc.clicc_id
ESQL;
        return $sql;
     }

    public function getAll( $dbc) {
        $sql=$this->getSelectSql();
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
     }

    public function getByFk( $dbc, $args) {
        $sql =<<<ESQL
    SELECT cc_baltransferinfo.bal_id
	, cc_baltransferinfo.client_id
	, cc_baltransferinfo.clicc_id
	, cc_baltransferinfo.due_date
	, cc_baltransferinfo.total
	, cc_baltransferinfo.credit_line
	, cc_baltransferinfo.recorded_on
    FROM cc_baltransferinfo
        INNER JOIN client_person ON cc_baltransferinfo.client_id=client_person.client_idINNER JOIN client_cc ON cc_baltransferinfo.clicc_id=client_cc.clicc_id
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
        $insertCols = explode(',', 'client_id, clicc_id, due_date, total, credit_line');
        foreach( $insertCols as $col) {
          $col = trim($col);
          $values[$col] = getArrayVal($posted, $col);
        }
        $sql = <<<ESQL
    INSERT INTO cc_baltransferinfo ( client_id
	, clicc_id
	, due_date
	, total
	, credit_line )
    VALUES(?,?,?,?,?)
    ON DUPLICATE KEY UPDATE client_id = VALUES(client_id)
	, clicc_id = VALUES(clicc_id)
	, due_date = VALUES(due_date)
	, total = VALUES(total)
	, credit_line = VALUES(credit_line)
	
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
                $sql1 = "SELECT bal_id FROM cc_baltransferinfo WHERE cc_baltransferinfo.bal_id=?;";
                $rows = dbconn::exec($dbc, $sql1, [$args]);
                $id = (isset($rows[0])) ? $rows[0] : null;
            }
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return ['id' => $id] ;
    }

    public function delete($dbc, $ids) {
        $sql = "DELETE FROM cc_baltransferinfo WHERE cc_baltransferinfo.bal_id=?";
        return dbconn::exec($dbc, $sql, [$args['bal_id']]);
    }
}
?>
