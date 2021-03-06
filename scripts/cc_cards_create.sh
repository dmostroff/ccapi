########### BEGIN 2017-08-25 10:22:14 ###############
########### BEGIN 2017-08-25 08:44:52 ###############
cd ./src
mkdir -p cc
chmod 777 cc
echo>cc/route_cc_cards.php
chmod 777 cc/route_cc_cards.php

cat<<EOF > cc/cardshelper.class.php
<?php
class Cc_CardsHelper extends Base_dblayerHelper {

    public function __construct() {
        \$this->table_ = 'cc_cards';
        \$this->colNames_ = 'cc_company_id, card_name, version, annual_fee, first_year_free, recorded_on';
        \$this->idcol_ = 'cc_card_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        \$sql=<<<ESQL
    SELECT cc_cards.cc_card_id
	, cc_cards.cc_company_id
	, cc_cards.card_name
	, cc_cards.version
	, cc_cards.annual_fee
	, cc_cards.first_year_free
	, cc_cards.recorded_on
    FROM cc_cards
ESQL;
        return \$sql;
     }

    public function getFkSql( ) {
        \$sql=<<<ESQL
INNER JOIN cc_company ON cc_cards.cc_company_id=cc_company.cc_company_id
ESQL;
        return \$sql;
     }

    public function getAll( \$dbc) {
        \$sql=\$this->getSelectSql();
        \$rows = dbconn::exec(\$dbc, \$sql);
        return \$rows;
     }

    public function get( \$dbc, \$args) {
        \$sql=\$this->getSelectSql();
        \$sql .=<<<ESQL
        WHERE cc_cards.cc_card_id=?
ESQL;
        \$rows = dbconn::exec(\$dbc, \$sql, [\$args['cc_card_id']]);
        \$data = [];
        foreach( \$rows as \$r) {
            \$data[] = \$r;
        }
        return \$data;
     }

    public function getByFk( \$dbc, \$args) {
        \$sql .=<<<ESQL
    SELECT cc_cards.cc_card_id
	, cc_cards.cc_company_id
	, cc_cards.card_name
	, cc_cards.version
	, cc_cards.annual_fee
	, cc_cards.first_year_free
	, cc_cards.recorded_on
    FROM cc_cards
        INNER JOIN cc_company ON cc_cards.cc_company_id=cc_company.cc_company_id
    WHERE cc_company.cc_company_id=?
ESQL;
        \$rows = dbconn::exec(\$dbc, \$sql, \$args);
        \$data = [];
        foreach( \$rows as \$r) {
            \$data[] = \$r;
        }
        return \$data;
     }

    public function post( \$dbc, \$args, \$posted) {
        \$values = [];
        \$insertCols = explode(',', 'cc_company_id, card_name, version, annual_fee, first_year_free');
        foreach( \$insertCols as \$col) {
          \$col = trim(\$col);
          \$values[\$col] = getArrayVal(\$posted, \$col);
        }
        \$sql = <<<ESQL
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
        \$id = null;
        try {
//            error_log(\$sql);
//            error_log(print_r(\$values, 1));
            dbconn::exec(\$dbc, \$sql, \$values);
            if(1) {
                \$sql1 = "SELECT last_insert_id() as id;";
                \$rows = dbconn::exec(\$dbc, \$sql1);
                \$id = (isset(\$rows[0])) ? \$rows[0]['id'] : null;
            } else {
                \$sql1 = "SELECT cc_card_id FROM cc_cards WHERE cc_cards.cc_card_id=?;";
                \$rows = dbconn::exec(\$dbc, \$sql1, [\$args]);
                \$id = (isset(\$rows[0])) ? \$rows[0] : null;
            }
        } catch (Exception \$ex) {
            error_log(sprintf("%s %s %s", \$ex->getFile(), \$ex->getLine(), \$ex->getMessage()));
        }
        return ['id' => \$id] ;
    }

    public function delete(\$dbc, \$ids) {
        \$sql = "DELETE FROM cc_cards WHERE cc_cards.cc_card_id=?";
        return dbconn::exec(\$dbc, \$sql, [\$args['cc_card_id']]);
    }
}
?>
EOF
chmod 777 cc/cardshelper.class.php

cat<<EOF > cc/cardsgetall.class.php
<?php
class Cc_CardsGetAll extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_CardsHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->getAll(\$dbc);
        return \$data;
    }

}

?>
EOF
chmod 777 cc/cardsgetall.class.php

echo "\$app->get('/cards', new FileLoad(\$app, '', 'Cc_CardsGetAll'))->setName('CcCardsGetAll');" >> cc/route_cc_cards.php

cat<<EOF > cc/cardsget.class.php
<?php
class Cc_CardsGet extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_CardsHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->get(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/cardsget.class.php

echo "\$app->get('/cards/{cc_card_id}', new FileLoad(\$app, '', 'Cc_CardsGet'))->setName('CcCardsGet');" >> cc/route_cc_cards.php

cat<<EOF > cc/cardspost.class.php
<?php
class Cc_CardsPost extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_CardsHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->post(\$dbc, \$args, \$this->posted_);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/cardspost.class.php

echo "\$app->post('/cards', new FileLoad(\$app, '', 'Cc_CardsPost'))->setName('CcCardsPost');" >> cc/route_cc_cards.php

cat<<EOF > cc/cardsdelete.class.php
<?php
class Cc_CardsDelete extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_CardsHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->delete(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/cardsdelete.class.php

echo "\$app->delete('/cards', new FileLoad(\$app, '', 'Cc_CardsDelete'))->setName('CcCardsDelete');" >> cc/route_cc_cards.php

