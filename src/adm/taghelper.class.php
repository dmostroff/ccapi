<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of taghelper
 *
 * @author DANO
 */
class Adm_TagHelper extends Base_dblayerHelper {
    //put your code here
    public function __construct() {
        $this->table_ = "adm_tags";
        $this->colNames_ = "description";
        $this->idcol_ = 'prefix, tag';
        parent::__construct();
    }

    public function getTagsAll( $dbc) {
        $sql = "SELECT prefix, tag, description FROM adm_tags";
        return dbconn::exec($dbc, $sql);
    }
    
    public function getTagsByPrefix( $dbc, $prefix) {
        $sql = "SELECT prefix, tag, description FROM adm_tags WHERE prefix = ?";
        $rows = dbconn::exec($dbc, $sql, [$prefix]);
        return $rows;
    }
    
    public function getTag( $dbc, $prefix, $tag) {
        $sql = "SELECT prefix, tag, description FROM adm_tags WHERE prefix = ? AND tag = ?";
        $rows = dbconn::exec($dbc, $sql, [$prefix, $tag]);
        return (isset($rows[0])) ? $rows[0] : null;
    }
    
    public function post($dbc, $args, $vals) {
        $sql =<<<ESQL
INSERT INTO adm_tags (prefix, tag, description)
VALUES(?,?,?)
   ON DUPLICATE KEY UPDATE description = VALUES(description)
ESQL;
        try {
            $rows = dbconn::exec($dbc, $sql, $vals);
        } catch( Exception $ex) {
            error_log(sprintf( "%s %s %s", __FILE__, __METHOD__, $ex->getMessage()));
        }
        return $rows;
    } // post
    
    public function delete( $dbc, $args, $posted) {
        $sql = "DELETE FROM adm_tags WHERE prefix = ? AND tag = ?";
        $values = [$posted['prefix'], $posted['tag']];
        $rows = dbconn::exec($dbc, $sql, $values);
        error_log( print_r($values,1));
        error_log( print_r($rows,1));
        return $rows;
    } // delete
    
}
