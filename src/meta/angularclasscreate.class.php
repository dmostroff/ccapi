<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of metacreate
 *
 * @author DANO
 */
class Meta_ClassnameCreate extends Base_dblayer {

    public function invoke($request, $response, $args) {
        $dbc = $this->connect();
        $tablename = $args['table'];
        $baseDir = './src';
        // $baseDir = '/var/www/ccapi';
        try {
            $idCols = [];
            $cols = [];
            $insertCols = [];
            $autoIncCols = [];
            $rows = dbconn::exec($dbc, "SELECT COLUMN_NAME, COLUMN_KEY, EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? ORDER BY ORDINAL_POSITION", [$tablename]);
            foreach ($rows as $r) {
                if ($r['COLUMN_KEY'] == 'PRI') {
                    $idCols[] = $r['COLUMN_NAME'];
                } else {
                    $cols[] = $r['COLUMN_NAME'];
                }
                if( $r['EXTRA'] != 'auto_increment' && $r['EXTRA'] != 'on update CURRENT_TIMESTAMP') {
                    $insertCols[] = $r['COLUMN_NAME'];
                }
                if( $r['EXTRA'] == 'auto_increment' ) {
                    $autoIncCols[] = $r['COLUMN_NAME'];
                }
            }
            $sql = <<<EOT
SELECT REFERENCED_TABLE_NAME
    , concat(REFERENCED_TABLE_NAME, '.', REFERENCED_COLUMN_NAME) as FKCOL
    , concat(TABLE_NAME, '.', COLUMN_NAME, '=', REFERENCED_TABLE_NAME, '.', REFERENCED_COLUMN_NAME) AS FKEQ
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_NAME = ? AND REFERENCED_TABLE_NAME is not null ORDER BY CONSTRAINT_NAME, ORDINAL_POSITION
EOT;
            $fkrows = dbconn::exec($dbc, $sql, [$tablename]);
            $fks = [];
            $innerJoinFk = "";
            $whereFk = "";
            foreach ($fkrows as $r) {
                if (!isset( $fks[$r['REFERENCED_TABLE_NAME']])) {
                    $fks[$r['REFERENCED_TABLE_NAME']] = $r['FKCOL'];
                    $innerJoinFk .= "INNER JOIN " . $r['REFERENCED_TABLE_NAME'] . " ON " . $r['FKEQ'];
                    $whereFk = $r['FKCOL'] . '=?';
                } else {
                    $fks[$r['REFERENCED_TABLE_NAME']][] = $r['FKCOL'];
                    $innerJoinFk .= " AND " . $r['FKEQ'];
                    $whereFk = " AND " . $r['FKCOL'].'=?';
                }
            }
            $colNames = implode($cols, ", ");
            $idColNames = implode( $idCols, ', ');
            $dirName = explode( '_', $tablename)[0];
            $section = explode('_', $tablename)[1];
            $classnameBase = ucwords($tablename, "_");
            $routesFile = sprintf( "%s/route_%s.php", $dirName, $tablename);
            $res = "########### BEGIN " . date("Y-m-d h:i:s") . " ###############\n";
            $res .= "cd {$baseDir}\n";
            $res .= "mkdir -p {$dirName}\n";
            $res .= "chmod 777 {$dirName}\n";
            $res .= "echo>{$routesFile}\n";
            $res .= "chmod 777 {$routesFile}\n";
            $res .= "\n";

//            $classname = $classnameBase . "Base";
//            $filename = strtolower(str_replace( '_', '/', $classname)) . '.class.php';
//            $res .= "cat<<EOF > {$filename}\n";
//            $res .= "<?php\n";
//            $res .= $this->createBaseClass($classname, $tablename);
//            $res .= "\n? >\nEOF\n";
//            $res .= "\n";
//            
            $classname = $classnameBase . "Helper";
            $filename = strtolower(str_replace( '_', '/', $classname)) . '.class.php';
            $res .= "cat<<EOF > {$filename}\n";
            $res .= "<?php\n";
            $res .= $this->createHelperClass($classname, $tablename, $cols, $idCols, $insertCols, $autoIncCols, $innerJoinFk, $whereFk );
            $res .= "\n?>\nEOF\n";
            $res .= "chmod 777 {$filename}\n";
            $res .= "\n";

            $classname = $classnameBase . "GetAll";
            $filename = strtolower(str_replace( '_', '/', $classname)) . '.class.php';
            $res .= "cat<<EOF > {$filename}\n";
            $res .= "<?php\n";
            $res .= $this->createGetAllClass($classname, $tablename);
            $res .= "\n?>\nEOF\n";
            $res .= "chmod 777 {$filename}\n";
            $res .= "\n";

            $route = sprintf("\\\$app->get('/%s', new FileLoad(\\\$app, '', '%s'))->setName('%s');", $section, $classname, str_replace("_", "", $classname));
            $res .= sprintf( 'echo "%s" >> %s', $route, $routesFile) . "\n";
            $res .= "\n";

            $classname = $classnameBase . "Get";
            $filename = strtolower(str_replace( '_', '/', $classname)) . '.class.php';
            $res .= "cat<<EOF > {$filename}\n";
            $res .= "<?php\n";
            $res .= $this->createGetClass($classname, $tablename, $idCols);
            $res .= "\n?>\nEOF\n";
            $res .= "chmod 777 {$filename}\n";
            $res .= "\n";

            $idArgs = implode(array_map(function($x) { return "{" . $x . "}"; }, $idCols), '/');
            $route = sprintf("\\\$app->get('/%s/%s', new FileLoad(\\\$app, '', '%s'))->setName('%s');", $section, $idArgs, $classname, str_replace("_", "", $classname));
            $res .= sprintf( 'echo "%s" >> %s', $route, $routesFile) . "\n";
            $res .= "\n";

            $classname = $classnameBase . "Post";
            $filename = strtolower(str_replace( '_', '/', $classname)) . '.class.php';
            $res .= "cat<<EOF > {$filename}\n";
            $res .= "<?php\n";
            $res .= $this->createPostClass($classname, $tablename);
            $res .= "\n?>\nEOF\n";
            $res .= "chmod 777 {$filename}\n";
            $res .= "\n";

            $route = sprintf("\\\$app->post('/%s', new FileLoad(\\\$app, '', '%s'))->setName('%s');", $section, $classname, str_replace("_", "", $classname));
            $res .= sprintf( 'echo "%s" >> %s', $route, $routesFile) . "\n";
            $res .= "\n";

            $classname = $classnameBase . "Delete";
            $filename = strtolower(str_replace( '_', '/', $classname)) . '.class.php';
            $res .= "cat<<EOF > {$filename}\n";
            $res .= "<?php\n";
            $res .= $this->createDeleteClass($classname, $tablename);
            $res .= "\n?>\nEOF\n";
            $res .= "chmod 777 {$filename}\n";
            $res .= "\n";

            $route = sprintf("\\\$app->delete('/%s', new FileLoad(\\\$app, '', '%s'))->setName('%s');", $section, $classname, str_replace("_", "", $classname));
            $res .= sprintf( 'echo "%s" >> %s', $route, $routesFile) . "\n";
            $res .= "\n";

            $rc = 0;
            $msg = 'OK';
            $retStatus = 200;
        } catch (Exception $ex) {
            error_log(sprintf("%s %s] %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
            $rc = -1;
            $msg = $ex->getMessage();
            $retStatus = 400;
        }
        // $ret = ['res' => ['rc' => $rc, 'msg' => $msg], 'data' => $data];
        $r = $response->withStatus(200);
        $r = $r->withHeader('Content-type', 'text/plain');
        $r->getBody()->write($res);
        return $r;
    }

    private function createBaseClass($classname, $tablename) {
        $txt = <<<EOT
class {$classname} extends Base_dblayer {

    public function __construct(\$app) {
        \$this->helper_ = new {\$classname}Helper();
        parent::__construct(\$app);
    }

    public function invoke(\$request, \$response, \$args) {
        \$dbc = \$this->connect();
        try {
            \$data = \$this->run(\$dbc, \$args);
            \$rc = 0;
            \$msg = 'OK';
            \$retStatus = 200;
        } catch (Exception \$ex) {
            error_log(sprintf("%s %s] %s", \$ex->getFile(), \$ex->getLine(), \$ex->getMessage()));
            \$rc = -1;
            \$msg = \$ex->getMessage();
            \$retStatus = 400;
        }
        \$ret = ['res' => ['rc' => \$rc, 'msg' => \$msg], 'data' => \$data];
        return \$this->jsonResponse(\$ret, \$retStatus);
    }

    protected function run(\$dbc, \$args) {
        return null;
    }

}
EOT;
        return str_replace( '$', '\$', $txt);
    }

    private function createHelperClass($classname, $tablename, $cols, $idCols, $insertCols, $autoIncCols, $innerJoinFk, $whereFk) {
        $colNamesString = implode( ', ', $cols);
        $colNames = implode( "\n\t, ", $cols);
        $idColNames = implode(', ', $idCols);
        $insertColNames = implode( "\n\t, ", $insertCols);
        $insertColNamesString = implode( ', ', $insertCols);
        $idargs = implode( ', ', array_map( function($x) { return "\$args['" . $x . "']"; }, $idCols));
        $where = implode( ' AND ', array_map( function($x) { return $x . " = ?"; }, $idCols));
        $where = "{$tablename}." . implode( "=?\n\tAND {$tablename}.", $idCols) . '=?';
        $qs = substr(str_repeat('?,', count($insertCols)), 0, -1);
        $set = implode(', ', array_map(function($x) {
                    return $x . " = VALUES(" . $x . ")\n\t";
                }, $insertCols));
        $autoInc = (count($autoIncCols)>0) ? 1 : 0;
        $fullColNames = array_merge( $idCols, $cols);
        $allColNames = "{$tablename}." . implode( "\n\t, {$tablename}.", $fullColNames);
//        $fkJoin = {};
//        if( 0 < count($fks)) {
//            $whereFk .= implode( ' AND ', innerJoin = 
//        }
        $txt = <<<EOT
class {$classname} extends Base_dblayerHelper {

    public function __construct() {
        \$this->table_ = '{$tablename}';
        \$this->colNames_ = '{$colNamesString}';
        \$this->idcol_ = '{$idColNames}';
        parent::__construct();
    }

    public function getSelectSql( ) {
        \$sql=<<<ESQL
    SELECT {$allColNames}
    FROM {$tablename}
ESQL;
        return \$sql;
     }

    public function getFkSql( ) {
        \$sql=<<<ESQL
{$innerJoinFk}
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
        WHERE {$where}
ESQL;
        \$rows = dbconn::exec(\$dbc, \$sql, [{$idargs}]);
        \$data = [];
        foreach( \$rows as \$r) {
            \$data[] = \$r;
        }
        return \$data;
     }

    public function getByFk( \$dbc, \$args) {
        \$sql .=<<<ESQL
    SELECT {$allColNames}
    FROM {$tablename}
        {$innerJoinFk}
    WHERE {$whereFk}
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
        \$insertCols = explode(',', '{$insertColNamesString}');
        foreach( \$insertCols as \$col) {
          \$col = trim(\$col);
          \$values[\$col] = getArrayVal(\$posted, \$col);
        }
        \$sql = <<<ESQL
    INSERT INTO {$tablename} ( {$insertColNames} )
    VALUES({$qs})
    ON DUPLICATE KEY UPDATE {$set}
ESQL;
        \$id = null;
        try {
//            error_log(\$sql);
//            error_log(print_r(\$values, 1));
            dbconn::exec(\$dbc, \$sql, \$values);
            if({$autoInc }) {
                \$sql1 = "SELECT last_insert_id() as id;";
                \$rows = dbconn::exec(\$dbc, \$sql1);
                \$id = (isset(\$rows[0])) ? \$rows[0]['id'] : null;
            } else {
                \$sql1 = "SELECT {$idColNames} FROM {$tablename} WHERE {$where};";
                \$rows = dbconn::exec(\$dbc, \$sql1, [\$args]);
                \$id = (isset(\$rows[0])) ? \$rows[0] : null;
            }
        } catch (Exception \$ex) {
            error_log(sprintf("%s %s %s", \$ex->getFile(), \$ex->getLine(), \$ex->getMessage()));
        }
        return ['id' => \$id] ;
    }

    public function delete(\$dbc, \$ids) {
        \$sql = "DELETE FROM {$tablename} WHERE {$where}";
        return dbconn::exec(\$dbc, \$sql, [{$idargs}]);
    }
}
EOT;
        return str_replace( '$', '\$', $txt);
    }

    private function createGetAllClass($classname, $tablename) {
        $helperclassname = ucwords($tablename, "_") . "Helper";
        $txt = <<<EOT
class {$classname} extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new {$helperclassname}();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->getAll(\$dbc);
        return \$data;
    }

}

EOT;
        return str_replace( '$', '\$', $txt);
    }

    //put your code here
    private function createGetClass($classname, $tablename, $idCols) {
        $helperclassname = ucwords($tablename, "_") . "Helper";
        $txt = <<<EOT
class {$classname} extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new {$helperclassname}();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->get(\$dbc, \$args);
        return \$data;
    }

}
EOT;
        return str_replace( '$', '\$', $txt);
    }

    private function createPostClass($classname, $tablename) {
        $helperclassname = ucwords($tablename, "_") . "Helper";
        $txt = <<<EOT
class {$classname} extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new {$helperclassname}();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->post(\$dbc, \$args, \$this->posted_);
        return \$data;
    }

}
EOT;
        return str_replace( '$', '\$', $txt);
    }

    private function createDeleteClass($classname, $tablename) {
        $helperclassname = ucwords($tablename, "_") . "Helper";
        $txt = <<<EOT
class {$classname} extends Base_dblayer {

    public function __construct() {
        \$this->helper_ = new {$helperclassname}();
    }

    public function run(\$args) {
        \$dbc = \$this->connect();
        \$data = \$this->helper_->delete(\$dbc, \$args);
        return \$data;
    }

}
EOT;
        return str_replace( '$', '\$', $txt);
    }
    
    private function createRoutes($tablename, $idCols) {
        $section = explode('_', $tablename)[1];
        $idArgs = implode(array_map(function($x) { return "{" . $x . "}"; }, $idCols), '/');
        $getclassname = str_replace("_", "_Get", ucwords($tablename, "_"));
        $getname = str_replace("_", "", $getclassname);
        $getallclassname = str_replace("_", "_GetAll", ucwords($tablename, "_"));
        $getallname = str_replace("_", "", $getallclassname);
        $postclassname = str_replace("_", "_Post", ucwords($tablename, "_"));
        $postname = str_replace("_", "", $postclassname);
        $deleteclassname = str_replace("_", "_Delete", ucwords($tablename, "_"));
        $deletename = str_replace("_", "", $deleteclassname);
        $txt = <<<EOT
\$app->get('/$section'                 , new FileLoad( \$app, "", '{$getallclassname}'))->setName('{$getallname}');
\$app->get('/$section/{$idArgs}'       , new FileLoad( \$app, "", '{$getclassname}'))->setName('{$getname}');
\$app->post('/$section'                , new FileLoad( \$app, "", '{$postclassname}'))->setName('{$postname}');
\$app->delete('/$section'              , new FileLoad( \$app, "", '{$deleteclassname}'))->setName('{$deletename}');
EOT;
        return str_replace( '$', '\$', $txt);
        
    }

}
