########### BEGIN 2017-08-25 10:22:14 ###############
########### BEGIN 2017-08-25 08:44:51 ###############
cd ./src
mkdir -p cc
chmod 777 cc
echo>cc/route_cc_action.php
chmod 777 cc/route_cc_action.php

cat<<EOF > cc/actionhelper.class.php
<?php
class Cc_ActionHelper extends Base_dblayerHelper {

    public function __construct() {
        \$this->table_ = 'cc_action';
        \$this->colNames_ = 'clicc_id, ccaction, action_type, action_status, due_date, details, recorded_on';
        \$this->idcol_ = 'ccaction_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        \$sql=<<<ESQL
    SELECT cc_action.ccaction_id
	, cc_action.clicc_id
	, cc_action.ccaction
	, cc_action.action_type
	, cc_action.action_status
	, cc_action.due_date
	, cc_action.details
	, cc_action.recorded_on
    FROM cc_action
ESQL;
        return \$sql;
     }

    public function getFkSql( ) {
        \$sql=<<<ESQL
INNER JOIN client_cc ON cc_action.clicc_id=client_cc.clicc_id
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
        WHERE cc_action.ccaction_id=?
ESQL;
        \$rows = dbconn::exec(\$dbc, \$sql, [\$args['ccaction_id']]);
        \$data = [];
        foreach( \$rows as \$r) {
            \$data[] = \$r;
        }
        return \$data;
     }

    public function getByFk( \$dbc, \$args) {
        \$sql .=<<<ESQL
    SELECT cc_action.ccaction_id
	, cc_action.clicc_id
	, cc_action.ccaction
	, cc_action.action_type
	, cc_action.action_status
	, cc_action.due_date
	, cc_action.details
	, cc_action.recorded_on
    FROM cc_action
        INNER JOIN client_cc ON cc_action.clicc_id=client_cc.clicc_id
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
        \$insertCols = explode(',', 'clicc_id, ccaction, action_type, action_status, due_date, details');
        foreach( \$insertCols as \$col) {
          \$col = trim(\$col);
          \$values[\$col] = getArrayVal(\$posted, \$col);
        }
        \$sql = <<<ESQL
    INSERT INTO cc_action ( clicc_id
	, ccaction
	, action_type
	, action_status
	, due_date
	, details )
    VALUES(?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE clicc_id = VALUES(clicc_id)
	, ccaction = VALUES(ccaction)
	, action_type = VALUES(action_type)
	, action_status = VALUES(action_status)
	, due_date = VALUES(due_date)
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
                \$sql1 = "SELECT ccaction_id FROM cc_action WHERE cc_action.ccaction_id=?;";
                \$rows = dbconn::exec(\$dbc, \$sql1, [\$args]);
                \$id = (isset(\$rows[0])) ? \$rows[0] : null;
            }
        } catch (Exception \$ex) {
            error_log(sprintf("%s %s %s", \$ex->getFile(), \$ex->getLine(), \$ex->getMessage()));
        }
        return ['id' => \$id] ;
    }

    public function delete(\$dbc, \$ids) {
        \$sql = "DELETE FROM cc_action WHERE cc_action.ccaction_id=?";
        return dbconn::exec(\$dbc, \$sql, [\$args['ccaction_id']]);
    }
}
?>
EOF
chmod 777 cc/actionhelper.class.php

cat<<EOF > cc/actiongetall.class.php
<?php
class Cc_ActionGetAll extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_ActionHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->getAll(\$dbc);
        return \$data;
    }

}

?>
EOF
chmod 777 cc/actiongetall.class.php

echo "\$app->get('/action', new FileLoad(\$app, '', 'Cc_ActionGetAll'))->setName('CcActionGetAll');" >> cc/route_cc_action.php

cat<<EOF > cc/actionget.class.php
<?php
class Cc_ActionGet extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_ActionHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->get(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/actionget.class.php

echo "\$app->get('/action/{ccaction_id}', new FileLoad(\$app, '', 'Cc_ActionGet'))->setName('CcActionGet');" >> cc/route_cc_action.php

cat<<EOF > cc/actionpost.class.php
<?php
class Cc_ActionPost extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_ActionHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->post(\$dbc, \$args, \$this->posted_);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/actionpost.class.php

echo "\$app->post('/action', new FileLoad(\$app, '', 'Cc_ActionPost'))->setName('CcActionPost');" >> cc/route_cc_action.php

cat<<EOF > cc/actiondelete.class.php
<?php
class Cc_ActionDelete extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_ActionHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->delete(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/actiondelete.class.php

echo "\$app->delete('/action', new FileLoad(\$app, '', 'Cc_ActionDelete'))->setName('CcActionDelete');" >> cc/route_cc_action.php

