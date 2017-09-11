########### BEGIN 2017-08-25 10:22:14 ###############
########### BEGIN 2017-08-25 08:44:50 ###############
cd ./src
mkdir -p adm
chmod 777 adm
echo>adm/route_adm_users.php
chmod 777 adm/route_adm_users.php

cat<<EOF > adm/usershelper.class.php
<?php
class Adm_UsersHelper extends Base_dblayerHelper {

    public function __construct() {
        \$this->table_ = 'adm_users';
        \$this->colNames_ = 'login, pwd, user_name, email, phone, phone_2, phone_cell, phone_fax, recorded_on';
        \$this->idcol_ = 'user_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        \$sql=<<<ESQL
    SELECT adm_users.user_id
	, adm_users.login
	, adm_users.pwd
	, adm_users.user_name
	, adm_users.email
	, adm_users.phone
	, adm_users.phone_2
	, adm_users.phone_cell
	, adm_users.phone_fax
	, adm_users.recorded_on
    FROM adm_users
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
        WHERE adm_users.user_id=?
ESQL;
        \$rows = dbconn::exec(\$dbc, \$sql, [\$args['user_id']]);
        \$data = [];
        foreach( \$rows as \$r) {
            \$data[] = \$r;
        }
        return \$data;
     }

    public function getByFk( \$dbc, \$args) {
        \$sql .=<<<ESQL
    SELECT adm_users.user_id
	, adm_users.login
	, adm_users.pwd
	, adm_users.user_name
	, adm_users.email
	, adm_users.phone
	, adm_users.phone_2
	, adm_users.phone_cell
	, adm_users.phone_fax
	, adm_users.recorded_on
    FROM adm_users
        
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
        \$insertCols = explode(',', 'login, pwd, user_name, email, phone, phone_2, phone_cell, phone_fax');
        foreach( \$insertCols as \$col) {
          \$col = trim(\$col);
          \$values[\$col] = getArrayVal(\$posted, \$col);
        }
        \$sql = <<<ESQL
    INSERT INTO adm_users ( login
	, pwd
	, user_name
	, email
	, phone
	, phone_2
	, phone_cell
	, phone_fax )
    VALUES(?,?,?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE login = VALUES(login)
	, pwd = VALUES(pwd)
	, user_name = VALUES(user_name)
	, email = VALUES(email)
	, phone = VALUES(phone)
	, phone_2 = VALUES(phone_2)
	, phone_cell = VALUES(phone_cell)
	, phone_fax = VALUES(phone_fax)
	
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
                \$sql1 = "SELECT user_id FROM adm_users WHERE adm_users.user_id=?;";
                \$rows = dbconn::exec(\$dbc, \$sql1, [\$args]);
                \$id = (isset(\$rows[0])) ? \$rows[0] : null;
            }
        } catch (Exception \$ex) {
            error_log(sprintf("%s %s %s", \$ex->getFile(), \$ex->getLine(), \$ex->getMessage()));
        }
        return ['id' => \$id] ;
    }

    public function delete(\$dbc, \$ids) {
        \$sql = "DELETE FROM adm_users WHERE adm_users.user_id=?";
        return dbconn::exec(\$dbc, \$sql, [\$args['user_id']]);
    }
}
?>
EOF
chmod 777 adm/usershelper.class.php

cat<<EOF > adm/usersgetall.class.php
<?php
class Adm_UsersGetAll extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Adm_UsersHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->getAll(\$dbc);
        return \$data;
    }

}

?>
EOF
chmod 777 adm/usersgetall.class.php

echo "\$app->get('/users', new FileLoad(\$app, '', 'Adm_UsersGetAll'))->setName('AdmUsersGetAll');" >> adm/route_adm_users.php

cat<<EOF > adm/usersget.class.php
<?php
class Adm_UsersGet extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Adm_UsersHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->get(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 adm/usersget.class.php

echo "\$app->get('/users/{user_id}', new FileLoad(\$app, '', 'Adm_UsersGet'))->setName('AdmUsersGet');" >> adm/route_adm_users.php

cat<<EOF > adm/userspost.class.php
<?php
class Adm_UsersPost extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Adm_UsersHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->post(\$dbc, \$args, \$this->posted_);
        return \$data;
    }

}
?>
EOF
chmod 777 adm/userspost.class.php

echo "\$app->post('/users', new FileLoad(\$app, '', 'Adm_UsersPost'))->setName('AdmUsersPost');" >> adm/route_adm_users.php

cat<<EOF > adm/usersdelete.class.php
<?php
class Adm_UsersDelete extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Adm_UsersHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->delete(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 adm/usersdelete.class.php

echo "\$app->delete('/users', new FileLoad(\$app, '', 'Adm_UsersDelete'))->setName('AdmUsersDelete');" >> adm/route_adm_users.php

