<?php
class Adm_SettingsHelper extends Base_dblayerHelper {

    public function __construct() {
        $this->table_ = 'adm_settings';
        $this->colNames_ = 'keyvalue';
        $this->idcol_ = 'prefix, keyname';
        parent::__construct();
    }
        
    public function getAll( $dbc) {
        $sql=<<<ESQL
        SELECT prefix, keyname, keyvalue
        FROM adm_settings
ESQL;
        $rows = dbconn::exec($dbc, $sql);
        return $rows;
     }

    public function get( $dbc, $args) {
        $sql=<<<ESQL
        SELECT prefix, keyname, keyvalue
        FROM adm_settings
        WHERE prefix = ? AND keyname = ?
ESQL;
        $rows = dbconn::exec($dbc, $sql, [$args['prefix'], $args['keyname']]);
        return $rows;
     }

    public function post( $dbc, $args, $posted) {
        $values = [];
        $insertCols = explode(',', 'prefix, keyname, keyvalue');
        foreach( $insertCols as $col) {
          $col = trim($col);
          $values[$col] = getArrayVal($posted, $col);
        }
        $sql = <<<ESQL
    INSERT INTO adm_settings ( prefix
	, keyname
	, keyvalue )
    VALUES(?,?,?)
    ON DUPLICATE KEY UPDATE prefix = VALUES(prefix)
	, keyname = VALUES(keyname)
	, keyvalue = VALUES(keyvalue)
	
ESQL;
        $id = null;
        try {
//            error_log($sql);
//            error_log(print_r($values, 1));
            dbconn::exec($dbc, $sql, $values);
            if(0) {
                $sql1 = "SELECT last_insert_id() as id;";
                $rows = dbconn::exec($dbc, $sql1);
                $id = (isset($rows[0])) ? $rows[0]['id'] : null;
            } else {
                $sql1 = "SELECT prefix, keyname FROM adm_settings WHERE prefix = ? AND keyname = ?;";
                $rows = dbconn::exec($dbc, $sql1, [$args]);
                $id = (isset($rows[0])) ? $rows[0] : null;
            }
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return ['id' => $id] ;
    }

    public function delete($dbc, $ids) {
        $sql = "DELETE FROM adm_settings WHERE prefix = ? AND keyname = ?";
        return dbconn::exec($dbc, $sql, [$args['prefix'], $args['keyname']]);
    }
}
?>
