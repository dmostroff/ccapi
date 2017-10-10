<?php

class Client_CcHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'client_cc';
        $this->colNames_ = 'client_id, name, ccnumber, expdate, ccv, cc_login, cc_password, cc_company_id, cc_status, annual_fee, credit_limit, addtional_card, recorded_on';
        $this->idcol_ = 'clicc_id';
        parent::__construct();
    }

    public function getSelectSql() {
        $sql = <<<ESQL
    SELECT client_cc.clicc_id
	, client_cc.client_id
	, client_cc.name
	, client_cc.ccnumber
	, client_cc.expdate
	, client_cc.ccv
	, client_cc.cc_login
	, client_cc.cc_password
	, client_cc.cc_company_id
	, client_cc.cc_status
	, client_cc.annual_fee
	, client_cc.credit_limit
	, client_cc.addtional_card
	, client_cc.recorded_on
    FROM client_cc
ESQL;
        return $sql;
    }

    public function getFkSql() {
        $sql = <<<ESQL
INNER JOIN client_person ON client_cc.client_id=client_person.client_id
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
    SELECT client_cc.clicc_id
	, client_cc.client_id
	, client_cc.name
	, client_cc.ccnumber
	, client_cc.expdate
	, client_cc.ccv
	, client_cc.cc_login
	, client_cc.cc_password
	, client_cc.cc_company_id
	, client_cc.cc_status
	, client_cc.annual_fee
	, client_cc.credit_limit
	, client_cc.addtional_card
	, client_cc.recorded_on
    FROM client_cc
        INNER JOIN client_person ON client_cc.client_id=client_person.client_id
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
        $insertCols = explode(',', 'client_id, name, ccnumber, expdate, ccv, cc_login, cc_password, cc_company_id, cc_status, annual_fee, credit_limit, addtional_card');
        foreach ($insertCols as $col) {
            $col = trim($col);
            $values[$col] = getArrayVal($posted, $col);
        }
        if (isset($posted[$this->idcol_])) {
            $values[$this->idcol_] = $posted[$this->idcol_];
            $id = $this->update($dbc, $values);
        } else {
            $sql = <<<ESQL
    INSERT INTO client_cc ( client_id
	, name
	, ccnumber
	, expdate
	, ccv
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
	, ccnumber = VALUES(ccnumber)
	, expdate = VALUES(expdate)
	, ccv = VALUES(ccv)
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
                if (1) {
                    $sql1 = "SELECT last_insert_id() as id;";
                    $rows = dbconn::exec($dbc, $sql1);
                    $id = (isset($rows[0])) ? $rows[0]['id'] : null;
                } else {
                    $sql1 = "SELECT clicc_id FROM client_cc WHERE client_cc.clicc_id=?;";
                    $rows = dbconn::exec($dbc, $sql1, [$args]);
                    $id = (isset($rows[0])) ? $rows[0] : null;
                }
            } catch (Exception $ex) {
                error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
            }
        }
        return ['id' => $id];
    }

    public function delete($dbc, $ids) {
        $sql = "DELETE FROM client_cc WHERE client_cc.clicc_id=?";
        return dbconn::exec($dbc, $sql, [$args['clicc_id']]);
    }

}

?>
