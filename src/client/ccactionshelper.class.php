<?php

class Client_CcActionsHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'cc_actions';
        $this->colNames_ = 'account_id, ccaction, action_type, action_status, due_date, details, recorded_on';
        $this->idcol_ = 'ccaction_id';
        parent::__construct();
    }

    public function getData( $dbc, $sql, $values) {
        $rows = dbconn::exec($dbc, $sql, $values);
        $data = [];
        foreach( $rows as $r) {
            $data[] = $r;
        }
        return $data;
    }

    public function getSelectSql( ) {
        $sql=<<<ESQL
        SELECT cc_actions.ccaction_id
			, cc_actions.account_id
			, cc_actions.ccaction
			, cc_actions.action_type
			, cc_actions.action_status
			, cc_actions.due_date
			, cc_actions.details
			, cc_actions.recorded_on
        FROM cc_actions
ESQL;
        return $sql;
    }

    public function getFkSql( ) {
        $sql=<<<ESQL
    INNER JOIN client_cc ON client_cc.clicc_id=cc_actions.clicc_id
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
         WHERE cc_actions.ccaction_id=?
ESQL;
        return $this->getData($dbc, $sql, [$args['ccaction_id']]);
    }

    public function getByFk( $dbc, $args) {
        $sql=$this->getSelectSql();
        $sql .= $this->getFkSql();
        $sql .= <<<ESQL
    WHERE cc_actions.ccaction_id=?
ESQL;
        return $this->getData( $dbc, $sql, $args);
    }

    public function post( $dbc, $args, $posted) {
        $values = [];
        $values[$this->idcol_] = getArrayVal( $posted, $this->idcol_);
        $insertCols = [account_id
			, ccaction
			, action_type
			, action_status
			, due_date
			, details
			, recorded_on];
        foreach ($insertCols as $col) {
            $col = trim($col);
            $values[$col] = getArrayVal($posted, $col);
        }
        error_log(json_encode($values));
        $sql = <<<ESQL
    WITH parms AS (
        SELECT ?::integer as ccaction_id
			, ?::bigint as account_id
			, ?::text as ccaction
			, ?::character varying as action_type
			, ?::character varying as action_status
			, ?::date as due_date
			, ?::text as details
			, ?::timestamp without time zone as recorded_on
    ), upd AS (
        UPDATE cc_actions
        SET account_id = parms.account_id
			, ccaction = parms.ccaction
			, action_type = parms.action_type
			, action_status = parms.action_status
			, due_date = parms.due_date
			, details = parms.details
			, recorded_on = parms.recorded_on
        FROM parms
        WHERE cc_actions.ccaction_id = parms.ccaction_id
        RETURNING cc_actions.ccaction_id
    ), ins AS (
        INSERT INTO client_accounts (account_id
			, ccaction
			, action_type
			, action_status
			, due_date
			, details
			, recorded_on)
        SELECT account_id
			, ccaction
			, action_type
			, action_status
			, due_date
			, details
			, recorded_on
        FROM parms
        WHERE NOT EXISTS (SELECT 1 FROM upd)
        RETURNING cc_actions.ccaction_id
    )
    SELECT ccaction_id
			, account_id
			, ccaction
			, action_type
			, action_status
			, due_date
			, details
			, recorded_on
    FROM upd
    UNION ALL
    SELECT ccaction_id
			, account_id
			, ccaction
			, action_type
			, action_status
			, due_date
			, details
			, recorded_on
    FROM ins
ESQL;
        $id = null;
        try {
//            error_log( print_r($dbc, 1));
//            error_log($sql);
            error_log(print_r($values, 1));
            $rows = dbconn::exec($dbc, $sql, $values);
            $id = (isset($rows[0])) ? $rows[0][$this->idcol_] : $values[$this->idcol_];
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return [$this->idcol_ => $id];
    }

    public function delete($dbc, $args, $posted) {
        $sql = "DELETE FROM cc_actions WHERE cc_actions.ccaction_id=?";
        return dbconn::exec($dbc, $sql, [$args['ccaction_id']]);
    }
}
?>