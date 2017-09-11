########### BEGIN 2017-08-25 10:22:14 ###############
########### BEGIN 2017-08-25 08:44:52 ###############
cd ./src
mkdir -p cc
chmod 777 cc
echo>cc/route_cc_company.php
chmod 777 cc/route_cc_company.php

cat<<EOF > cc/companyhelper.class.php
<?php
class Cc_CompanyHelper extends Base_dblayerHelper {

    public function __construct() {
        \$this->table_ = 'cc_company';
        \$this->colNames_ = 'cc_name, url, contact, address_1, address_2, city, state, zip, country, phone, phone_2, phone_cell, phone_fax, recorded_on';
        \$this->idcol_ = 'cc_company_id';
        parent::__construct();
    }

    public function getSelectSql( ) {
        \$sql=<<<ESQL
    SELECT cc_company.cc_company_id
	, cc_company.cc_name
	, cc_company.url
	, cc_company.contact
	, cc_company.address_1
	, cc_company.address_2
	, cc_company.city
	, cc_company.state
	, cc_company.zip
	, cc_company.country
	, cc_company.phone
	, cc_company.phone_2
	, cc_company.phone_cell
	, cc_company.phone_fax
	, cc_company.recorded_on
    FROM cc_company
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
        WHERE cc_company.cc_company_id=?
ESQL;
        \$rows = dbconn::exec(\$dbc, \$sql, [\$args['cc_company_id']]);
        \$data = [];
        foreach( \$rows as \$r) {
            \$data[] = \$r;
        }
        return \$data;
     }

    public function getByFk( \$dbc, \$args) {
        \$sql .=<<<ESQL
    SELECT cc_company.cc_company_id
	, cc_company.cc_name
	, cc_company.url
	, cc_company.contact
	, cc_company.address_1
	, cc_company.address_2
	, cc_company.city
	, cc_company.state
	, cc_company.zip
	, cc_company.country
	, cc_company.phone
	, cc_company.phone_2
	, cc_company.phone_cell
	, cc_company.phone_fax
	, cc_company.recorded_on
    FROM cc_company
        
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
        \$insertCols = explode(',', 'cc_name, url, contact, address_1, address_2, city, state, zip, country, phone, phone_2, phone_cell, phone_fax');
        foreach( \$insertCols as \$col) {
          \$col = trim(\$col);
          \$values[\$col] = getArrayVal(\$posted, \$col);
        }
        \$sql = <<<ESQL
    INSERT INTO cc_company ( cc_name
	, url
	, contact
	, address_1
	, address_2
	, city
	, state
	, zip
	, country
	, phone
	, phone_2
	, phone_cell
	, phone_fax )
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE cc_name = VALUES(cc_name)
	, url = VALUES(url)
	, contact = VALUES(contact)
	, address_1 = VALUES(address_1)
	, address_2 = VALUES(address_2)
	, city = VALUES(city)
	, state = VALUES(state)
	, zip = VALUES(zip)
	, country = VALUES(country)
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
                \$sql1 = "SELECT cc_company_id FROM cc_company WHERE cc_company.cc_company_id=?;";
                \$rows = dbconn::exec(\$dbc, \$sql1, [\$args]);
                \$id = (isset(\$rows[0])) ? \$rows[0] : null;
            }
        } catch (Exception \$ex) {
            error_log(sprintf("%s %s %s", \$ex->getFile(), \$ex->getLine(), \$ex->getMessage()));
        }
        return ['id' => \$id] ;
    }

    public function delete(\$dbc, \$ids) {
        \$sql = "DELETE FROM cc_company WHERE cc_company.cc_company_id=?";
        return dbconn::exec(\$dbc, \$sql, [\$args['cc_company_id']]);
    }
}
?>
EOF
chmod 777 cc/companyhelper.class.php

cat<<EOF > cc/companygetall.class.php
<?php
class Cc_CompanyGetAll extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_CompanyHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->getAll(\$dbc);
        return \$data;
    }

}

?>
EOF
chmod 777 cc/companygetall.class.php

echo "\$app->get('/company', new FileLoad(\$app, '', 'Cc_CompanyGetAll'))->setName('CcCompanyGetAll');" >> cc/route_cc_company.php

cat<<EOF > cc/companyget.class.php
<?php
class Cc_CompanyGet extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_CompanyHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->get(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/companyget.class.php

echo "\$app->get('/company/{cc_company_id}', new FileLoad(\$app, '', 'Cc_CompanyGet'))->setName('CcCompanyGet');" >> cc/route_cc_company.php

cat<<EOF > cc/companypost.class.php
<?php
class Cc_CompanyPost extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_CompanyHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->post(\$dbc, \$args, \$this->posted_);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/companypost.class.php

echo "\$app->post('/company', new FileLoad(\$app, '', 'Cc_CompanyPost'))->setName('CcCompanyPost');" >> cc/route_cc_company.php

cat<<EOF > cc/companydelete.class.php
<?php
class Cc_CompanyDelete extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new Cc_CompanyHelper();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->delete(\$dbc, \$args);
        return \$data;
    }

}
?>
EOF
chmod 777 cc/companydelete.class.php

echo "\$app->delete('/company', new FileLoad(\$app, '', 'Cc_CompanyDelete'))->setName('CcCompanyDelete');" >> cc/route_cc_company.php

