########### BEGIN 2017-08-25 10:22:14 ###############
########### BEGIN 2017-08-25 08:44:55 ###############
cd ./src
mkdir -p client
chmod 777 client
echo>client/route_client_cchistory.php
chmod 777 client/route_client_cchistory.php

cat<<EOF > client/cchistoryhelper.class.php
<?php
class Client_CchistoryHelper extends Base_dblayerHelper {

    public function __construct() {
        \$this->table_ = 'client_cchistory';
        \$this->colNames_ = 'clicc_id, ccevent, ccevent_amt, details, recorded_on';
        \$this->idcol_ = 'cchist_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        \$sql=<<<ESQL
    SELECT client_cchistory.cchist_id
	, client_cchistory.clicc_id
	, client_cchistory.ccevent
	, client_cchistory.ccevent_amt
	, client_cchistory.details
	, client_cchistory.recorded_on
    FROM client_cchistory
ESQL;
        return \$sql;
     }

    public function getFkSql( ) {
        \$sql=<<<ESQL
INNER JOIN client_cc ON client_cchistory.clicc_id=client_cc.clicc_id
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
        WHERE client_cchistory.cchist_id=?
ESQL;
        \$rows = dbconn::exec(\$dbc, \$sql, [\$args['cchist_id']]);
        \$data = [];
        foreach( \$rows as \$r) {
            \$data[] = \$r;
        }
        return \$data;
     }

    public function getByFk( \$dbc, \$args) {
        \$sql .=<<<ESQL
    SELECT client_cchistory.cchist_id
	, client_cchistory.clicc_id
	, client_cchistory.ccevent
	, client_cchistory.ccevent_amt
	, client_cchistory.details
	, client_cchistory.recorded_on
    FROM client_cchistory
        INNER JOIN client_cc ON client_cchistory.clicc_id=client_cc.clicc_id
    WHERE client_cc.clicc_id=?
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
        \$insertCols = explode(',', 'clicc_id, ccevent, ccevent_amt, details');
        foreach( \$insertCols as \$col) {
          \$col = trim(\$col);
          \$values[\$col] = getArrayVal(\$posted, \$col);
        }
        \$sql = <<<ESQL
    INSERT INTO client_cchistory ( clicc_id
	, ccevent
	, ccevent_amt
	, details )
    VALUES(?,?,?,?)
    ON DUPLICATE KEY UPDATE clicc_id = VALUES(clicc_id)
	, ccevent = VALUES(ccevent)
	, ccevent_amt = VALUES(ccevent_amt)
	, details = VALUES(details)
	
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
                \$sql1 = "SELECT cchist_id FROM client_cchistory WHERE client_cchistory.cchist_id=?;";
                \$rows = dbconn::exec(\$dbc, \$sql1, [\$args]);
                \$id = (isset(\$rows[0])) ? \$rows[0] : null;
            }
        } catch (Exception \$ex) {
            error_log(sprintf("%s %s %s", \$ex->getFile(), \$ex->getLine(), \$ex->getMessage()));
        }
        return ['id' => \$id] ;
    }

    public function delete(\$dbc, \$ids) {
        \$sql = "DELETE FROM client_cchistory WHERE client_cchistory.cchist_id=?";
        return dbconn::exec(\$dbc, \$sql, [\$args['cchist_id']]);
    }
}
?>
EOF
chmod 777 client/cchistoryhelper.class.php

cat<<EOF > client/cchistorygetall.class.php
<?php
class Client_CchistoryGetAll extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_CchistoryHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->getAll(\$dbc);
        return \$data;
    }

}

?>
EOF
chmod 777 client/cchistorygetall.class.php

echo "\$app->get('/cchistory', new FileLoad(\$app, '', 'Client_CchistoryGetAll'))->setName('ClientCchistoryGetAll');" >> client/route_client_cchistory.php

cat<<EOF > client/cchistoryget.class.php
<?php
class Client_CchistoryGet extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_CchistoryHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->get(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 client/cchistoryget.class.php

echo "\$app->get('/cchistory/{cchist_id}', new FileLoad(\$app, '', 'Client_CchistoryGet'))->setName('ClientCchistoryGet');" >> client/route_client_cchistory.php

cat<<EOF > client/cchistorypost.class.php
<?php
class Client_CchistoryPost extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_CchistoryHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->post(\$dbc, \$args, \$this->posted_);
        return \$data;
    }

}
?>
EOF
chmod 777 client/cchistorypost.class.php

echo "\$app->post('/cchistory', new FileLoad(\$app, '', 'Client_CchistoryPost'))->setName('ClientCchistoryPost');" >> client/route_client_cchistory.php

cat<<EOF > client/cchistorydelete.class.php
<?php
class Client_CchistoryDelete extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_CchistoryHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->delete(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 client/cchistorydelete.class.php

echo "\$app->delete('/cchistory', new FileLoad(\$app, '', 'Client_CchistoryDelete'))->setName('ClientCchistoryDelete');" >> client/route_client_cchistory.php

