########### BEGIN 2017-08-25 10:22:14 ###############
########### BEGIN 2017-08-25 08:44:50 ###############
cd ./src
mkdir -p adm
chmod 777 adm
echo>adm/route_adm_settings.php
chmod 777 adm/route_adm_settings.php

cat<<EOF > adm/settingshelper.class.php
<?php
class Adm_SettingsHelper extends Base_dblayerHelper {

    public function __construct() {
        \$this->table_ = 'adm_settings';
        \$this->colNames_ = 'keyvalue';
        \$this->idcol_ = 'prefix, keyname';
        parent::__construct();
    }

    public function getSelectSql( ) {
        \$sql=<<<ESQL
    SELECT adm_settings.prefix
	, adm_settings.keyname
	, adm_settings.keyvalue
    FROM adm_settings
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
        WHERE adm_settings.prefix=?
	AND adm_settings.keyname=?
ESQL;
        \$rows = dbconn::exec(\$dbc, \$sql, [\$args['prefix'], \$args['keyname']]);
        \$data = [];
        foreach( \$rows as \$r) {
            \$data[] = \$r;
        }
        return \$data;
     }

    public function getByFk( \$dbc, \$args) {
        \$sql .=<<<ESQL
    SELECT adm_settings.prefix
	, adm_settings.keyname
	, adm_settings.keyvalue
    FROM adm_settings
        
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
        \$insertCols = explode(',', 'prefix, keyname, keyvalue');
        foreach( \$insertCols as \$col) {
          \$col = trim(\$col);
          \$values[\$col] = getArrayVal(\$posted, \$col);
        }
        \$sql = <<<ESQL
    INSERT INTO adm_settings ( prefix
	, keyname
	, keyvalue )
    VALUES(?,?,?)
    ON DUPLICATE KEY UPDATE prefix = VALUES(prefix)
	, keyname = VALUES(keyname)
	, keyvalue = VALUES(keyvalue)
	
ESQL;
        \$id = null;
        try {
//            error_log(\$sql);
//            error_log(print_r(\$values, 1));
            dbconn::exec(\$dbc, \$sql, \$values);
            if(0) {
                \$sql1 = "SELECT last_insert_id() as id;";
                \$rows = dbconn::exec(\$dbc, \$sql1);
                \$id = (isset(\$rows[0])) ? \$rows[0]['id'] : null;
            } else {
                \$sql1 = "SELECT prefix, keyname FROM adm_settings WHERE adm_settings.prefix=?
	AND adm_settings.keyname=?;";
                \$rows = dbconn::exec(\$dbc, \$sql1, [\$args]);
                \$id = (isset(\$rows[0])) ? \$rows[0] : null;
            }
        } catch (Exception \$ex) {
            error_log(sprintf("%s %s %s", \$ex->getFile(), \$ex->getLine(), \$ex->getMessage()));
        }
        return ['id' => \$id] ;
    }

    public function delete(\$dbc, \$ids) {
        \$sql = "DELETE FROM adm_settings WHERE adm_settings.prefix=?
	AND adm_settings.keyname=?";
        return dbconn::exec(\$dbc, \$sql, [\$args['prefix'], \$args['keyname']]);
    }
}
?>
EOF
chmod 777 adm/settingshelper.class.php

cat<<EOF > adm/settingsgetall.class.php
<?php
class Adm_SettingsGetAll extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Adm_SettingsHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->getAll(\$dbc);
        return \$data;
    }

}

?>
EOF
chmod 777 adm/settingsgetall.class.php

echo "\$app->get('/settings', new FileLoad(\$app, '', 'Adm_SettingsGetAll'))->setName('AdmSettingsGetAll');" >> adm/route_adm_settings.php

cat<<EOF > adm/settingsget.class.php
<?php
class Adm_SettingsGet extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Adm_SettingsHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->get(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 adm/settingsget.class.php

echo "\$app->get('/settings/{prefix}/{keyname}', new FileLoad(\$app, '', 'Adm_SettingsGet'))->setName('AdmSettingsGet');" >> adm/route_adm_settings.php

cat<<EOF > adm/settingspost.class.php
<?php
class Adm_SettingsPost extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Adm_SettingsHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->post(\$dbc, \$args, \$this->posted_);
        return \$data;
    }

}
?>
EOF
chmod 777 adm/settingspost.class.php

echo "\$app->post('/settings', new FileLoad(\$app, '', 'Adm_SettingsPost'))->setName('AdmSettingsPost');" >> adm/route_adm_settings.php

cat<<EOF > adm/settingsdelete.class.php
<?php
class Adm_SettingsDelete extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Adm_SettingsHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->delete(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 adm/settingsdelete.class.php

echo "\$app->delete('/settings', new FileLoad(\$app, '', 'Adm_SettingsDelete'))->setName('AdmSettingsDelete');" >> adm/route_adm_settings.php

