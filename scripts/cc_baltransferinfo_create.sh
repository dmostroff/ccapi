########### BEGIN 2017-08-25 10:22:14 ###############
########### BEGIN 2017-08-25 08:44:51 ###############
cd ./src
mkdir -p cc
chmod 777 cc
echo>cc/route_cc_baltransferinfo.php
chmod 777 cc/route_cc_baltransferinfo.php

cat<<EOF > cc/baltransferinfohelper.class.php
<?php
class Cc_BaltransferinfoHelper extends Base_dblayerHelper {

    public function __construct() {
        \$this->table_ = 'cc_baltransferinfo';
        \$this->colNames_ = 'client_id, clicc_id, due_date, total, credit_line, recorded_on';
        \$this->idcol_ = 'bal_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        \$sql=<<<ESQL
    SELECT cc_baltransferinfo.bal_id
	, cc_baltransferinfo.client_id
	, cc_baltransferinfo.clicc_id
	, cc_baltransferinfo.due_date
	, cc_baltransferinfo.total
	, cc_baltransferinfo.credit_line
	, cc_baltransferinfo.recorded_on
    FROM cc_baltransferinfo
ESQL;
        return \$sql;
     }

    public function getFkSql( ) {
        \$sql=<<<ESQL
INNER JOIN client_person ON cc_baltransferinfo.client_id=client_person.client_idINNER JOIN client_cc ON cc_baltransferinfo.clicc_id=client_cc.clicc_id
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
        WHERE cc_baltransferinfo.bal_id=?
ESQL;
        \$rows = dbconn::exec(\$dbc, \$sql, [\$args['bal_id']]);
        \$data = [];
        foreach( \$rows as \$r) {
            \$data[] = \$r;
        }
        return \$data;
     }

    public function getByFk( \$dbc, \$args) {
        \$sql .=<<<ESQL
    SELECT cc_baltransferinfo.bal_id
	, cc_baltransferinfo.client_id
	, cc_baltransferinfo.clicc_id
	, cc_baltransferinfo.due_date
	, cc_baltransferinfo.total
	, cc_baltransferinfo.credit_line
	, cc_baltransferinfo.recorded_on
    FROM cc_baltransferinfo
        INNER JOIN client_person ON cc_baltransferinfo.client_id=client_person.client_idINNER JOIN client_cc ON cc_baltransferinfo.clicc_id=client_cc.clicc_id
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
        \$insertCols = explode(',', 'client_id, clicc_id, due_date, total, credit_line');
        foreach( \$insertCols as \$col) {
          \$col = trim(\$col);
          \$values[\$col] = getArrayVal(\$posted, \$col);
        }
        \$sql = <<<ESQL
    INSERT INTO cc_baltransferinfo ( client_id
	, clicc_id
	, due_date
	, total
	, credit_line )
    VALUES(?,?,?,?,?)
    ON DUPLICATE KEY UPDATE client_id = VALUES(client_id)
	, clicc_id = VALUES(clicc_id)
	, due_date = VALUES(due_date)
	, total = VALUES(total)
	, credit_line = VALUES(credit_line)
	
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
                \$sql1 = "SELECT bal_id FROM cc_baltransferinfo WHERE cc_baltransferinfo.bal_id=?;";
                \$rows = dbconn::exec(\$dbc, \$sql1, [\$args]);
                \$id = (isset(\$rows[0])) ? \$rows[0] : null;
            }
        } catch (Exception \$ex) {
            error_log(sprintf("%s %s %s", \$ex->getFile(), \$ex->getLine(), \$ex->getMessage()));
        }
        return ['id' => \$id] ;
    }

    public function delete(\$dbc, \$ids) {
        \$sql = "DELETE FROM cc_baltransferinfo WHERE cc_baltransferinfo.bal_id=?";
        return dbconn::exec(\$dbc, \$sql, [\$args['bal_id']]);
    }
}
?>
EOF
chmod 777 cc/baltransferinfohelper.class.php

cat<<EOF > cc/baltransferinfogetall.class.php
<?php
class Cc_BaltransferinfoGetAll extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_BaltransferinfoHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->getAll(\$dbc);
        return \$data;
    }

}

?>
EOF
chmod 777 cc/baltransferinfogetall.class.php

echo "\$app->get('/baltransferinfo', new FileLoad(\$app, '', 'Cc_BaltransferinfoGetAll'))->setName('CcBaltransferinfoGetAll');" >> cc/route_cc_baltransferinfo.php

cat<<EOF > cc/baltransferinfoget.class.php
<?php
class Cc_BaltransferinfoGet extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_BaltransferinfoHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->get(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/baltransferinfoget.class.php

echo "\$app->get('/baltransferinfo/{bal_id}', new FileLoad(\$app, '', 'Cc_BaltransferinfoGet'))->setName('CcBaltransferinfoGet');" >> cc/route_cc_baltransferinfo.php

cat<<EOF > cc/baltransferinfopost.class.php
<?php
class Cc_BaltransferinfoPost extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_BaltransferinfoHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->post(\$dbc, \$args, \$this->posted_);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/baltransferinfopost.class.php

echo "\$app->post('/baltransferinfo', new FileLoad(\$app, '', 'Cc_BaltransferinfoPost'))->setName('CcBaltransferinfoPost');" >> cc/route_cc_baltransferinfo.php

cat<<EOF > cc/baltransferinfodelete.class.php
<?php
class Cc_BaltransferinfoDelete extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_BaltransferinfoHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->delete(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/baltransferinfodelete.class.php

echo "\$app->delete('/baltransferinfo', new FileLoad(\$app, '', 'Cc_BaltransferinfoDelete'))->setName('CcBaltransferinfoDelete');" >> cc/route_cc_baltransferinfo.php

