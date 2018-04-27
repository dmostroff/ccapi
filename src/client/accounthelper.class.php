<?php

class Client_AccountHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'client_accounts';
        $this->colNames_ = 'client_id, name, cc_card_id, account, account_info, cc_login, cc_password, cc_status, annual_fee, credit_limit, addtional_card, open_date, close_date, notes, recorded_on';
        $this->idcol_ = 'account_id';
        parent::__construct();
    }

    public function getSelectSql() {
        $sql = <<<ESQL
    SELECT {$this->table_}.{$this->idcol_}
	, client_accounts.client_id
        , client_person.first_name
        , client_person.middle_name
        , client_person.last_name
	, client_accounts.name
	, client_accounts.cc_card_id
        , cc_cards.card_name
	, client_accounts.account
	, client_accounts.account_info
	, client_accounts.cc_login
	, client_accounts.cc_password
	, client_accounts.cc_status
        , tg.description as cc_status_desc
	, client_accounts.annual_fee
	, client_accounts.credit_limit
	, client_accounts.addtional_card
        , client_accounts.open_date
        , client_accounts.close_date
        , client_accounts.notes
	, client_accounts.recorded_on
    FROM client_accounts
        INNER JOIN client_person ON client_person.client_id=client_accounts.client_id
        LEFT OUTER JOIN cc_cards ON cc_cards.cc_card_id=client_accounts.cc_card_id
        LEFT OUTER JOIN adm_tags tg ON tg.prefix = 'CARDSTATUS' AND tg.tag = client_accounts.cc_status
ESQL;
        return $sql;
    }
    
    public function getFkSql() {
        $sql = <<<ESQL
INNER JOIN client_person ON client_accounts.client_id=client_person.client_id
ESQL;
        return $sql;
    }

    public function get($dbc) {
        $sql = $this->getSelectSql();
        $sql .= sprintf( " WHERE %s.%s = ?", $this->table_, $this->idcol_);
        $rows = dbconn::exec($dbc, $sql, [$args[$this->idcol_]]);
        $retVal = (isset($rows[0])) ? $rows[0] : null;
        return $retVal;
    }

    public function getAll($dbc) {
        $sql = $this->getSelectSql();
        $sql .= " ORDER BY 1";
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
    }

    public function getByFk($dbc, $args) {
        $sql = <<<ESQL
    SELECT client_accounts.account_id
	, client_accounts.client_id
	, coalesce(client_accounts.name
            , trim(concat(trim(concat(client_person.first_name, ' ', client_person.middle_name), ' ', client_person.last_name))
               ) as name
	, client_accounts.cc_card_id
        , cc_cards.card_name
	, client_accounts.account
	, client_accounts.account_info
	, client_accounts.cc_login
	, client_accounts.cc_password
	, client_accounts.cc_status
	, client_accounts.annual_fee
	, client_accounts.credit_limit
	, client_accounts.addtional_card
        , client_accounts.open_date
        , client_accounts.close_date
        , client_accounts.notes
	, client_accounts.recorded_on
        , client_person.first_name
        , client_person.middle_name
        , client_person.last_name
    FROM client_accounts
        INNER JOIN client_person ON client_person.client_id=client_accounts.client_id
        LEFT OUTER JOIN cc_cards ON cc_cards.cc_card_id=client_accounts.cc_card_id
    WHERE client_person.client_id=?
ESQL;
        $rows = dbconn::exec($dbc, $sql, $args);
        $data = [];
        foreach ($rows as $r) {
            $data[] = $r;
        }
        return $data;
    }

    public function post($dbc, $args, $posted) {
        $values = [];
        $values[$this->idcol_] = getArrayVal( $posted, $this->idcol_);
        $insertCols = ['client_id', 'name', 'cc_card_id', 'account', 'account_info'
            , 'cc_login', 'cc_password', 'cc_status'
            , 'annual_fee', 'credit_limit', 'addtional_card', 'open_date', 'close_date', 'notes'];
        foreach ($insertCols as $col) {
            $col = trim($col);
            $values[$col] = getArrayVal($posted, $col);
        }
        error_log(json_encode($values));
        $sql = <<<ESQL
    WITH parms AS (
        SELECT ?::integer as account_id
        , coalesce(?,0)::bigint as client_id
	, ?::text as name
	, ?::bigint as cc_card_id
	, ?::text as account
	, ?::text as account_info
	, ?::text as cc_login
	, ?::text as cc_password
	, ?::text as cc_status
        , regexp_replace( ?, '[^\d|\.]', '', 'g')::numeric as annual_fee
        , regexp_replace( ?, '[^\d|\.]', '', 'g')::numeric as credit_limit
	, substr(?, 1,1)::text as addtional_card
        , ?::date as open_date
        , ?::date as close_date
	, ?::text as notes
    ), upd AS (
        UPDATE client_accounts
        SET client_id = parms.client_id
            , name = parms.name
            , cc_card_id = parms.cc_card_id
            , account = parms.account
            , account_info = parms.account_info
            , cc_login = parms.cc_login
            , cc_password = parms.cc_password
            , cc_status = parms.cc_status
            , annual_fee = parms.annual_fee
            , credit_limit = parms.credit_limit
            , addtional_card = parms.addtional_card
            , open_date = parms.open_date
            , close_date = parms.close_date
            , notes = parms.notes
        FROM parms
        WHERE client_accounts.account_id = parms.account_id
        RETURNING client_accounts.account_id
    ), ins AS (
        INSERT INTO client_accounts ( 
            client_id
            , name
            , cc_card_id
            , account
            , account_info
            , cc_login
            , cc_password
            , cc_status
            , annual_fee
            , credit_limit
            , addtional_card
            , open_date
            , close_date
            , notes    
        )
        SELECT client_id
            , name
            , cc_card_id
            , account
            , account_info
            , cc_login
            , cc_password
            , cc_status
            , annual_fee
            , credit_limit
            , addtional_card
            , open_date
            , close_date
            , notes    
        FROM parms
        WHERE NOT EXISTS (SELECT 1 FROM upd)
        RETURNING client_accounts.account_id
    )
    SELECT account_id
    FROM upd
    UNION ALL
    SELECT account_id
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
    
    public static function account_decrypt( $data) {
        error_log( sprintf("%s %d] %s", __METHOD__, __LINE__, print_r($data,1)));
        $account = cryptutils::sslDecrypt($data['account']);
        error_log( sprintf("%s %d] %s", __METHOD__, __LINE__, $account));
        list($accnum, $accinfo, $accdate) = explode( '^', $account);
        $data['account_num'] = $accnum;
        $data['account_info'] = $accinfo;
        $data['account_date'] = date("Y-m-d H:i:s", strtotime($accdate));
        $data['cc_password'] = cryptutils::sslDecrypt($data['cc_password']);
        return $data;
    }

}

?>
