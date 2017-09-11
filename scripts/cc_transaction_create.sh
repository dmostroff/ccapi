########### BEGIN 2017-08-25 10:22:14 ###############
########### BEGIN 2017-08-25 08:44:53 ###############
cd ./src
mkdir -p cc
chmod 777 cc
echo>cc/route_cc_transaction.php
chmod 777 cc/route_cc_transaction.php

cat<<EOF > cc/transactionhelper.class.php
<?php
class Cc_TransactionHelper extends Base_dblayerHelper {

    public function __construct() {
        \$this->table_ = 'cc_transaction';
        \$this->colNames_ = 'clicc_id, transaction_date, transaction_type, transaction_status, cedit, debit, recorded_on';
        \$this->idcol_ = 'cctrans_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        \$sql=<<<ESQL
    SELECT cc_transaction.cctrans_id
	, cc_transaction.clicc_id
	, cc_transaction.transaction_date
	, cc_transaction.transaction_type
	, cc_transaction.transaction_status
	, cc_transaction.cedit
	, cc_transaction.debit
	, cc_transaction.recorded_on
    FROM cc_transaction
ESQL;
        return \$sql;
     }

    public function getFkSql( ) {
        \$sql=<<<ESQL
INNER JOIN client_cc ON cc_transaction.clicc_id=client_cc.clicc_id
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
        WHERE cc_transaction.cctrans_id=?
ESQL;
        \$rows = dbconn::exec(\$dbc, \$sql, [\$args['cctrans_id']]);
        \$data = [];
        foreach( \$rows as \$r) {
            \$data[] = \$r;
        }
        return \$data;
     }

    public function getByFk( \$dbc, \$args) {
        \$sql .=<<<ESQL
    SELECT cc_transaction.cctrans_id
	, cc_transaction.clicc_id
	, cc_transaction.transaction_date
	, cc_transaction.transaction_type
	, cc_transaction.transaction_status
	, cc_transaction.cedit
	, cc_transaction.debit
	, cc_transaction.recorded_on
    FROM cc_transaction
        INNER JOIN client_cc ON cc_transaction.clicc_id=client_cc.clicc_id
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
        \$insertCols = explode(',', 'clicc_id, transaction_date, transaction_type, transaction_status, cedit, debit');
        foreach( \$insertCols as \$col) {
          \$col = trim(\$col);
          \$values[\$col] = getArrayVal(\$posted, \$col);
        }
        \$sql = <<<ESQL
    INSERT INTO cc_transaction ( clicc_id
	, transaction_date
	, transaction_type
	, transaction_status
	, cedit
	, debit )
    VALUES(?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE clicc_id = VALUES(clicc_id)
	, transaction_date = VALUES(transaction_date)
	, transaction_type = VALUES(transaction_type)
	, transaction_status = VALUES(transaction_status)
	, cedit = VALUES(cedit)
	, debit = VALUES(debit)
	
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
                \$sql1 = "SELECT cctrans_id FROM cc_transaction WHERE cc_transaction.cctrans_id=?;";
                \$rows = dbconn::exec(\$dbc, \$sql1, [\$args]);
                \$id = (isset(\$rows[0])) ? \$rows[0] : null;
            }
        } catch (Exception \$ex) {
            error_log(sprintf("%s %s %s", \$ex->getFile(), \$ex->getLine(), \$ex->getMessage()));
        }
        return ['id' => \$id] ;
    }

    public function delete(\$dbc, \$ids) {
        \$sql = "DELETE FROM cc_transaction WHERE cc_transaction.cctrans_id=?";
        return dbconn::exec(\$dbc, \$sql, [\$args['cctrans_id']]);
    }
}
?>
EOF
chmod 777 cc/transactionhelper.class.php

cat<<EOF > cc/transactiongetall.class.php
<?php
class Cc_TransactionGetAll extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_TransactionHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->getAll(\$dbc);
        return \$data;
    }

}

?>
EOF
chmod 777 cc/transactiongetall.class.php

echo "\$app->get('/transaction', new FileLoad(\$app, '', 'Cc_TransactionGetAll'))->setName('CcTransactionGetAll');" >> cc/route_cc_transaction.php

cat<<EOF > cc/transactionget.class.php
<?php
class Cc_TransactionGet extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_TransactionHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->get(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/transactionget.class.php

echo "\$app->get('/transaction/{cctrans_id}', new FileLoad(\$app, '', 'Cc_TransactionGet'))->setName('CcTransactionGet');" >> cc/route_cc_transaction.php

cat<<EOF > cc/transactionpost.class.php
<?php
class Cc_TransactionPost extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_TransactionHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->post(\$dbc, \$args, \$this->posted_);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/transactionpost.class.php

echo "\$app->post('/transaction', new FileLoad(\$app, '', 'Cc_TransactionPost'))->setName('CcTransactionPost');" >> cc/route_cc_transaction.php

cat<<EOF > cc/transactiondelete.class.php
<?php
class Cc_TransactionDelete extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_TransactionHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->delete(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/transactiondelete.class.php

echo "\$app->delete('/transaction', new FileLoad(\$app, '', 'Cc_TransactionDelete'))->setName('CcTransactionDelete');" >> cc/route_cc_transaction.php

