########### BEGIN 2017-08-25 10:22:14 ###############
########### BEGIN 2017-08-25 08:44:55 ###############
cd ./src
mkdir -p client
chmod 777 client
echo>client/route_client_financials.php
chmod 777 client/route_client_financials.php

cat<<EOF > client/financialshelper.class.php
<?php
class Client_FinancialsHelper extends Base_dblayerHelper {

    public function __construct() {
        \$this->table_ = 'client_financials';
        \$this->colNames_ = 'client_id, annual_income, credit_line, valid_from, valid_to, recorded_on';
        \$this->idcol_ = 'financial_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        \$sql=<<<ESQL
    SELECT client_financials.financial_id
	, client_financials.client_id
	, client_financials.annual_income
	, client_financials.credit_line
	, client_financials.valid_from
	, client_financials.valid_to
	, client_financials.recorded_on
    FROM client_financials
ESQL;
        return \$sql;
     }

    public function getFkSql( ) {
        \$sql=<<<ESQL
INNER JOIN client_person ON client_financials.client_id=client_person.client_id
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
        WHERE client_financials.financial_id=?
ESQL;
        \$rows = dbconn::exec(\$dbc, \$sql, [\$args['financial_id']]);
        \$data = [];
        foreach( \$rows as \$r) {
            \$data[] = \$r;
        }
        return \$data;
     }

    public function getByFk( \$dbc, \$args) {
        \$sql .=<<<ESQL
    SELECT client_financials.financial_id
	, client_financials.client_id
	, client_financials.annual_income
	, client_financials.credit_line
	, client_financials.valid_from
	, client_financials.valid_to
	, client_financials.recorded_on
    FROM client_financials
        INNER JOIN client_person ON client_financials.client_id=client_person.client_id
    WHERE client_person.client_id=?
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
        \$insertCols = explode(',', 'client_id, annual_income, credit_line, valid_from, valid_to');
        foreach( \$insertCols as \$col) {
          \$col = trim(\$col);
          \$values[\$col] = getArrayVal(\$posted, \$col);
        }
        \$sql = <<<ESQL
    INSERT INTO client_financials ( client_id
	, annual_income
	, credit_line
	, valid_from
	, valid_to )
    VALUES(?,?,?,?,?)
    ON DUPLICATE KEY UPDATE client_id = VALUES(client_id)
	, annual_income = VALUES(annual_income)
	, credit_line = VALUES(credit_line)
	, valid_from = VALUES(valid_from)
	, valid_to = VALUES(valid_to)
	
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
                \$sql1 = "SELECT financial_id FROM client_financials WHERE client_financials.financial_id=?;";
                \$rows = dbconn::exec(\$dbc, \$sql1, [\$args]);
                \$id = (isset(\$rows[0])) ? \$rows[0] : null;
            }
        } catch (Exception \$ex) {
            error_log(sprintf("%s %s %s", \$ex->getFile(), \$ex->getLine(), \$ex->getMessage()));
        }
        return ['id' => \$id] ;
    }

    public function delete(\$dbc, \$ids) {
        \$sql = "DELETE FROM client_financials WHERE client_financials.financial_id=?";
        return dbconn::exec(\$dbc, \$sql, [\$args['financial_id']]);
    }
}
?>
EOF
chmod 777 client/financialshelper.class.php

cat<<EOF > client/financialsgetall.class.php
<?php
class Client_FinancialsGetAll extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_FinancialsHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->getAll(\$dbc);
        return \$data;
    }

}

?>
EOF
chmod 777 client/financialsgetall.class.php

echo "\$app->get('/financials', new FileLoad(\$app, '', 'Client_FinancialsGetAll'))->setName('ClientFinancialsGetAll');" >> client/route_client_financials.php

cat<<EOF > client/financialsget.class.php
<?php
class Client_FinancialsGet extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_FinancialsHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->get(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 client/financialsget.class.php

echo "\$app->get('/financials/{financial_id}', new FileLoad(\$app, '', 'Client_FinancialsGet'))->setName('ClientFinancialsGet');" >> client/route_client_financials.php

cat<<EOF > client/financialspost.class.php
<?php
class Client_FinancialsPost extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_FinancialsHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->post(\$dbc, \$args, \$this->posted_);
        return \$data;
    }

}
?>
EOF
chmod 777 client/financialspost.class.php

echo "\$app->post('/financials', new FileLoad(\$app, '', 'Client_FinancialsPost'))->setName('ClientFinancialsPost');" >> client/route_client_financials.php

cat<<EOF > client/financialsdelete.class.php
<?php
class Client_FinancialsDelete extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_FinancialsHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->delete(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 client/financialsdelete.class.php

echo "\$app->delete('/financials', new FileLoad(\$app, '', 'Client_FinancialsDelete'))->setName('ClientFinancialsDelete');" >> client/route_client_financials.php

