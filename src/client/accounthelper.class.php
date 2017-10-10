<?php

class Client_AccountHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'client_accounts';
        $this->colNames_ = 'client_id, name, cc_card_id, account, account_info, cc_login, cc_password, cc_status, annual_fee, credit_limit, addtional_card, recorded_on';
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
	, client_accounts.annual_fee
	, client_accounts.credit_limit
	, client_accounts.addtional_card
	, client_accounts.recorded_on
    FROM client_accounts
        INNER JOIN client_person ON client_person.client_id=client_accounts.client_id
        LEFT OUTER JOIN cc_cards ON cc_cards.cc_card_id=client_accounts.cc_card_id
ESQL;
        return $sql;
    }

    public function getFkSql() {
        $sql = <<<ESQL
INNER JOIN client_person ON client_accounts.client_id=client_person.client_id
ESQL;
        return $sql;
    }

    public function getAll($dbc) {
        $sql = $this->getSelectSql();
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
    }

    public function getByFk($dbc, $args) {
        $sql = <<<ESQL
    SELECT client_accounts.account_id
	, client_accounts.client_id
	, client_accounts.name
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
        $insertCols = explode(',', 'client_id, name, cc_card_id, account, account_info, cc_login, cc_password, cc_status, annual_fee, credit_limit, addtional_card');
        foreach ($insertCols as $col) {
            $col = trim($col);
            $values[$col] = getArrayVal($posted, $col);
        }
        if (isset($posted[$this->idcol_])) {
            $values[$this->idcol_] = $posted[$this->idcol_];
            $id = $this->update($dbc, $values);
        } else {
            $sql = <<<ESQL
    INSERT INTO client_accounts ( client_id
	, name
	, cc_card_id
	, account
	, account_info
	, cc_login
	, cc_password
	, cc_status
	, annual_fee
	, credit_limit
	, addtional_card )
    VALUES(?,?,?,?,?,?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE client_id = VALUES(client_id)
	, name = VALUES(name)
	, cc_card_id = VALUES(cc_card_id)
	, account = VALUES(account)
	, account_info = VALUES(account_info)
	, cc_login = VALUES(cc_login)
	, cc_password = VALUES(cc_password)
	, cc_status = VALUES(cc_status)
	, annual_fee = VALUES(annual_fee)
	, credit_limit = VALUES(credit_limit)
	, addtional_card = VALUES(addtional_card)
	
ESQL;
            $id = null;
            try {
            error_log($sql);
            error_log(print_r($values, 1));
                dbconn::exec($dbc, $sql, $values);
                $sql1 = "SELECT last_insert_id() as id;";
                $rows = dbconn::exec($dbc, $sql1);
                $id = (isset($rows[0])) ? $rows[0]['id'] : null;
            } catch (Exception $ex) {
                error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
            }
        }
        return [$this->idcol_ => $id];
    }

    public function delete($dbc, $args) {
        $sql = "DELETE FROM client_accounts WHERE client_accounts.account_id=?";
        return dbconn::exec($dbc, $sql, [$args[$this->idcol_]]);
    }

}

?>
