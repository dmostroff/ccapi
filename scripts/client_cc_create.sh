########### BEGIN 2017-08-25 10:22:14 ###############
########### BEGIN 2017-08-25 08:44:54 ###############
cd ./src
mkdir -p client
chmod 777 client
echo>client/route_client_cc.php
chmod 777 client/route_client_cc.php

cat<<EOF > client/cchelper.class.php
<?php
class Client_CcHelper extends Base_dblayerHelper {

    public function __construct() {
        \$this->table_ = 'client_cc';
        \$this->colNames_ = 'client_id, name, ccnumber, expdate, ccv, cc_login, cc_password, cc_company_id, cc_status, annual_fee, credit_limit, addtional_card, recorded_on';
        \$this->idcol_ = 'clicc_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        \$sql=<<<ESQL
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
        return \$sql;
     }

    public function getFkSql( ) {
        \$sql=<<<ESQL
INNER JOIN client_person ON client_cc.client_id=client_person.client_id
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
        WHERE client_cc.clicc_id=?
ESQL;
        \$rows = dbconn::exec(\$dbc, \$sql, [\$args['clicc_id']]);
        \$data = [];
        foreach( \$rows as \$r) {
            \$data[] = \$r;
        }
        return \$data;
     }

    public function getByFk( \$dbc, \$args) {
        \$sql .=<<<ESQL
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
        \$rows = dbconn::exec(\$dbc, \$sql, \$args);
        \$data = [];
        foreach( \$rows as \$r) {
            \$data[] = \$r;
        }
        return \$data;
     }

    public function post( \$dbc, \$args, \$posted) {
        \$values = [];
        \$insertCols = explode(',', 'client_id, name, ccnumber, expdate, ccv, cc_login, cc_password, cc_company_id, cc_status, annual_fee, credit_limit, addtional_card');
        foreach( \$insertCols as \$col) {
          \$col = trim(\$col);
          \$values[\$col] = getArrayVal(\$posted, \$col);
        }
        \$sql = <<<ESQL
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
                \$sql1 = "SELECT clicc_id FROM client_cc WHERE client_cc.clicc_id=?;";
                \$rows = dbconn::exec(\$dbc, \$sql1, [\$args]);
                \$id = (isset(\$rows[0])) ? \$rows[0] : null;
            }
        } catch (Exception \$ex) {
            error_log(sprintf("%s %s %s", \$ex->getFile(), \$ex->getLine(), \$ex->getMessage()));
        }
        return ['id' => \$id] ;
    }

    public function delete(\$dbc, \$ids) {
        \$sql = "DELETE FROM client_cc WHERE client_cc.clicc_id=?";
        return dbconn::exec(\$dbc, \$sql, [\$args['clicc_id']]);
    }
}
?>
EOF
chmod 777 client/cchelper.class.php

cat<<EOF > client/ccgetall.class.php
<?php
class Client_CcGetAll extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_CcHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->getAll(\$dbc);
        return \$data;
    }

}

?>
EOF
chmod 777 client/ccgetall.class.php

echo "\$app->get('/cc', new FileLoad(\$app, '', 'Client_CcGetAll'))->setName('ClientCcGetAll');" >> client/route_client_cc.php

cat<<EOF > client/ccget.class.php
<?php
class Client_CcGet extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_CcHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->get(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 client/ccget.class.php

echo "\$app->get('/cc/{clicc_id}', new FileLoad(\$app, '', 'Client_CcGet'))->setName('ClientCcGet');" >> client/route_client_cc.php

cat<<EOF > client/ccpost.class.php
<?php
class Client_CcPost extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_CcHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->post(\$dbc, \$args, \$this->posted_);
        return \$data;
    }

}
?>
EOF
chmod 777 client/ccpost.class.php

echo "\$app->post('/cc', new FileLoad(\$app, '', 'Client_CcPost'))->setName('ClientCcPost');" >> client/route_client_cc.php

cat<<EOF > client/ccdelete.class.php
<?php
class Client_CcDelete extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_CcHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->delete(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 client/ccdelete.class.php

echo "\$app->delete('/cc', new FileLoad(\$app, '', 'Client_CcDelete'))->setName('ClientCcDelete');" >> client/route_client_cc.php

