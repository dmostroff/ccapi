<?php

class Cc_CardsHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'cc_cards';
        $this->colNames_ = 'cc_company_id, card_name, version, annual_fee, first_year_free, recorded_on';
        $this->idcol_ = 'cc_card_id';
        parent::__construct();
    }

    public function getSelectSql() {
        $sql = <<<ESQL
    SELECT cc_cards.cc_card_id
	, cc_cards.cc_company_id
        , cc_company.cc_name
	, cc_cards.card_name
	, cc_cards.version
	, cc_cards.annual_fee
	, cc_cards.first_year_free
	, cc_cards.recorded_on
    FROM cc_cards
        INNER JOIN cc_company ON cc_cards.cc_company_id=cc_company.cc_company_id
ESQL;
        return $sql;
    }

    public function getFkSql() {
        $sql = <<<ESQL
INNER JOIN cc_company ON cc_cards.cc_company_id=cc_company.cc_company_id
ESQL;
        return $sql;
    }

    public function getAll($dbc) {
        $sql = $this->getSelectSql();
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
    }

    public function getByFk($dbc, $args) {
        $sql =<<<ESQL
    SELECT cc_cards.cc_card_id
	, cc_cards.cc_company_id
        , cc_company.cc_name
	, cc_cards.card_name
	, cc_cards.version
	, cc_cards.annual_fee
	, cc_cards.first_year_free
	, cc_cards.recorded_on
    FROM cc_cards
        INNER JOIN cc_company ON cc_cards.cc_company_id=cc_company.cc_company_id
    WHERE cc_company.cc_company_id=?
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
        $insertCols = explode(',', 'cc_company_id, card_name, version, annual_fee, first_year_free');
        foreach ($insertCols as $col) {
            $col = trim($col);
            $values[$col] = getArrayVal($posted, $col);
        }
        if (0 < $posted['cc_card_id']) {
            $values[$this->idcol_] = $posted[$this->idcol_];
            $id = $this->update($dbc, $values);
        } else {
            $sql = <<<ESQL
    INSERT INTO cc_cards ( cc_company_id
	, card_name
	, version
	, annual_fee
	, first_year_free )
    VALUES(?,?,?,?,?)
    ON DUPLICATE KEY UPDATE cc_company_id = VALUES(cc_company_id)
	, card_name = VALUES(card_name)
	, version = VALUES(version)
	, annual_fee = VALUES(annual_fee)
	, first_year_free = VALUES(first_year_free)
	
ESQL;
            $id = null;
            try {
//            error_log($sql);
//            error_log(print_r($values, 1));
                dbconn::exec($dbc, $sql, $values);
                $sql1 = "SELECT last_insert_id() as id;";
                $rows = dbconn::exec($dbc, $sql1);
                $id = (isset($rows[0])) ? $rows[0]['id'] : null;
            } catch (Exception $ex) {
                error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
            }
        }
        return ['cc_card_id' => $id];
    }

    public function delete($dbc, $ids) {
        $sql = "DELETE FROM cc_cards WHERE cc_cards.cc_card_id=?";
        return dbconn::exec($dbc, $sql, [$args['cc_card_id']]);
    }

}

?>
