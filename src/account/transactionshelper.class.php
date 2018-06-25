<?php

class Account_TransactionsHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'cc_transactions';
        $this->colNames_ = 'account_id, transaction_date, transaction_type, transaction_status, cedit, debit, recorded_on';
        $this->idcol_ = 'cctrans_id';
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
        SELECT cc_transactions.cctrans_id
            , cc_transactions.account_id
            , cc_transactions.transaction_date
            , cc_transactions.transaction_type
            , cc_transactions.transaction_status
            , cc_transactions.cedit
            , cc_transactions.debit
            , cc_transactions.recorded_on
        FROM cc_transactions
ESQL;
        return $sql;
    }

    public function getFkSql( ) {
        $sql=<<<ESQL
    INNER JOIN client_cc ON client_cc.clicc_id=cc_transactions.clicc_id
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
         WHERE cc_transactions.cctrans_id=?
ESQL;
        return $this->getData($dbc, $sql, [$args['cctrans_id']]);
    }

    public function getByFk( $dbc, $args) {
        $sql=$this->getSelectSql();
        $sql .= $this->getFkSql();
        $sql .= <<<ESQL
    WHERE cc_transactions.cctrans_id=?
ESQL;
        return $this->getData( $dbc, $sql, $args);
    }

    public function post( $dbc, $args, $posted) {
        $values = [];
        $values[$this->idcol_] = getArrayVal( $posted, $this->idcol_);
        $insertCols = [account_id
			, transaction_date
			, transaction_type
			, transaction_status
			, cedit
			, debit
			, recorded_on];
        foreach ($insertCols as $col) {
            $col = trim($col);
            $values[$col] = getArrayVal($posted, $col);
        }
        error_log(json_encode($values));
        $sql = <<<ESQL
    WITH parms AS (
        SELECT ?::integer as cctrans_id
			, ?::bigint as account_id
			, ?::date as transaction_date
			, ?::character varying as transaction_type
			, ?::character varying as transaction_status
			, ?::numeric as cedit
			, ?::numeric as debit
			, ?::timestamp without time zone as recorded_on
    ), upd AS (
        UPDATE cc_transactions
        SET account_id = parms.account_id
			, transaction_date = parms.transaction_date
			, transaction_type = parms.transaction_type
			, transaction_status = parms.transaction_status
			, cedit = parms.cedit
			, debit = parms.debit
			, recorded_on = parms.recorded_on
        FROM parms
        WHERE cc_transactions.cctrans_id = parms.cctrans_id
        RETURNING cc_transactions.cctrans_id
    ), ins AS (
        INSERT INTO client_accounts (account_id
			, transaction_date
			, transaction_type
			, transaction_status
			, cedit
			, debit
			, recorded_on)
        SELECT account_id
			, transaction_date
			, transaction_type
			, transaction_status
			, cedit
			, debit
			, recorded_on
        FROM parms
        WHERE NOT EXISTS (SELECT 1 FROM upd)
        RETURNING cc_transactions.cctrans_id
    )
    SELECT cctrans_id
			, account_id
			, transaction_date
			, transaction_type
			, transaction_status
			, cedit
			, debit
			, recorded_on
    FROM upd
    UNION ALL
    SELECT cctrans_id
			, account_id
			, transaction_date
			, transaction_type
			, transaction_status
			, cedit
			, debit
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
        $sql = "DELETE FROM cc_transactions WHERE cc_transactions.cctrans_id=?";
        return dbconn::exec($dbc, $sql, [$args['cctrans_id']]);
    }
}
?>
