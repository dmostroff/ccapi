########### BEGIN 2017-08-25 10:22:14 ###############
########### BEGIN 2017-08-25 08:44:56 ###############
cd ./src
mkdir -p client
chmod 777 client
echo>client/route_client_person.php
chmod 777 client/route_client_person.php

cat<<EOF > client/personhelper.class.php
<?php
class Client_PersonHelper extends Base_dblayerHelper {

    public function __construct() {
        \$this->table_ = 'client_person';
        \$this->colNames_ = 'last_name, first_name, middle_name, dob, gender, ssn, mmn, email, pwd, phone, phone_2, phone_cell, phone_fax, phone_official, recorded_on';
        \$this->idcol_ = 'client_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        \$sql=<<<ESQL
    SELECT client_person.client_id
	, client_person.last_name
	, client_person.first_name
	, client_person.middle_name
	, client_person.dob
	, client_person.gender
	, client_person.ssn
	, client_person.mmn
	, client_person.email
	, client_person.pwd
	, client_person.phone
	, client_person.phone_2
	, client_person.phone_cell
	, client_person.phone_fax
	, client_person.phone_official
	, client_person.recorded_on
    FROM client_person
ESQL;
        return \$sql;
     }

    public function getFkSql( ) {
        \$sql=<<<ESQL

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
        WHERE client_person.client_id=?
ESQL;
        \$rows = dbconn::exec(\$dbc, \$sql, [\$args['client_id']]);
        \$data = [];
        foreach( \$rows as \$r) {
            \$data[] = \$r;
        }
        return \$data;
     }

    public function getByFk( \$dbc, \$args) {
        \$sql .=<<<ESQL
    SELECT client_person.client_id
	, client_person.last_name
	, client_person.first_name
	, client_person.middle_name
	, client_person.dob
	, client_person.gender
	, client_person.ssn
	, client_person.mmn
	, client_person.email
	, client_person.pwd
	, client_person.phone
	, client_person.phone_2
	, client_person.phone_cell
	, client_person.phone_fax
	, client_person.phone_official
	, client_person.recorded_on
    FROM client_person
        
    WHERE 
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
        \$insertCols = explode(',', 'last_name, first_name, middle_name, dob, gender, ssn, mmn, email, pwd, phone, phone_2, phone_cell, phone_fax, phone_official');
        foreach( \$insertCols as \$col) {
          \$col = trim(\$col);
          \$values[\$col] = getArrayVal(\$posted, \$col);
        }
        \$sql = <<<ESQL
    INSERT INTO client_person ( last_name
	, first_name
	, middle_name
	, dob
	, gender
	, ssn
	, mmn
	, email
	, pwd
	, phone
	, phone_2
	, phone_cell
	, phone_fax
	, phone_official )
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE last_name = VALUES(last_name)
	, first_name = VALUES(first_name)
	, middle_name = VALUES(middle_name)
	, dob = VALUES(dob)
	, gender = VALUES(gender)
	, ssn = VALUES(ssn)
	, mmn = VALUES(mmn)
	, email = VALUES(email)
	, pwd = VALUES(pwd)
	, phone = VALUES(phone)
	, phone_2 = VALUES(phone_2)
	, phone_cell = VALUES(phone_cell)
	, phone_fax = VALUES(phone_fax)
	, phone_official = VALUES(phone_official)
	
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
                \$sql1 = "SELECT client_id FROM client_person WHERE client_person.client_id=?;";
                \$rows = dbconn::exec(\$dbc, \$sql1, [\$args]);
                \$id = (isset(\$rows[0])) ? \$rows[0] : null;
            }
        } catch (Exception \$ex) {
            error_log(sprintf("%s %s %s", \$ex->getFile(), \$ex->getLine(), \$ex->getMessage()));
        }
        return ['id' => \$id] ;
    }

    public function delete(\$dbc, \$ids) {
        \$sql = "DELETE FROM client_person WHERE client_person.client_id=?";
        return dbconn::exec(\$dbc, \$sql, [\$args['client_id']]);
    }
}
?>
EOF
chmod 777 client/personhelper.class.php

cat<<EOF > client/persongetall.class.php
<?php
class Client_PersonGetAll extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_PersonHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->getAll(\$dbc);
        return \$data;
    }

}

?>
EOF
chmod 777 client/persongetall.class.php

echo "\$app->get('/person', new FileLoad(\$app, '', 'Client_PersonGetAll'))->setName('ClientPersonGetAll');" >> client/route_client_person.php

cat<<EOF > client/personget.class.php
<?php
class Client_PersonGet extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_PersonHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->get(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 client/personget.class.php

echo "\$app->get('/person/{client_id}', new FileLoad(\$app, '', 'Client_PersonGet'))->setName('ClientPersonGet');" >> client/route_client_person.php

cat<<EOF > client/personpost.class.php
<?php
class Client_PersonPost extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_PersonHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->post(\$dbc, \$args, \$this->posted_);
        return \$data;
    }

}
?>
EOF
chmod 777 client/personpost.class.php

echo "\$app->post('/person', new FileLoad(\$app, '', 'Client_PersonPost'))->setName('ClientPersonPost');" >> client/route_client_person.php

cat<<EOF > client/persondelete.class.php
<?php
class Client_PersonDelete extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Client_PersonHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->delete(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 client/persondelete.class.php

echo "\$app->delete('/person', new FileLoad(\$app, '', 'Client_PersonDelete'))->setName('ClientPersonDelete');" >> client/route_client_person.php

