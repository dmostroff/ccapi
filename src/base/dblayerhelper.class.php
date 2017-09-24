<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base_dblayerHelper
 *
 * @author DANO
 */
class Base_dblayerHelper {

    public $table_ = "";
    public $colNames_ = "";
    public $idcol_ = [];
    public $cols_ = [];
    public $colCount_ = 0;

    public function __construct() {
        $this->cols_ = explode( ', ', $this->colNames_);
//        error_log( "sssssssssssssssssssssss");
//        error_log( print_r($this->colNames_, 1));
//        error_log( print_r($this->cols_, 1));
//        error_log( "sssssssssssssssssssssss");
        $this->colCount_ = count($this->cols_);
    }

    public function getAll($dbc) {
        $sql = "SELECT {$this->idcol_}, {$this->colNames_} FROM {$this->table_}";
        error_log($sql);
        $retVal = dbconn::exec($dbc, $sql);
        return $retVal;
    }

// getAll

    public function get($dbc, $id) {
        $sql = "SELECT {$this->idcol_}, {$this->colNames_} FROM {$this->table_}";
        error_log($sql);
        $sql .= " WHERE " . $this->idcol_ . " = ?";
        $rows = dbconn::exec($dbc, $sql, [$id]);
        $retVal = (isset($rows[0])) ? $rows[0] : null;
        return $retVal;
    }

    public function getId($dbc, $id) {
        $sql = <<<ESQL
        SELECT {$this->idcol_}
        FROM {$this->table_}
        WHERE {$this->idcol_} = ?";
ESQL;
        $rows = null;
        try {
            $rows = dbconn::exec($dbc, $sql, [$id]);
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", __FILE__, __METHOD__, $ex->getMessage()));
        }
        $idRet = (isset($rows[0])) ? $rows[0][$this->idcol_] : null;
        return $idRet;
    }

// getId

    public function post($dbc, $args, $vals) {
        $values = [];
        $cols = [];
        foreach ($this->cols_ as $col) {
            if (isset($vals[$col])) {
                $cols[] = $col;
                $values[$col] = $vals[$col];
            }
        }
        $colNames = implode(', ', $cols);
        $qs = substr(str_repeat('?,', count($cols)), 0, -1);
        $set = implode(', ', array_map(function($x) {
                    return $x . "= VALUES(" . $x . ")";
                }, $cols));
//        error_log( print_r($vals,1));
//        error_log("!!!!!!!!!!!!!!!!!!!!!!");
//        error_log( print_r($cols,1));
//        error_log( $colNames);
//        error_log( $qs);
//        error_log( $set);
        $sql = <<<ESQL
    INSERT INTO {$this->table_} ( {$colNames} )
    VALUES({$qs})
    ON DUPLICATE KEY UPDATE {$set}
ESQL;
        $id = null;
        try {
            error_log($sql);
            error_log(print_r($values, 1));
            dbconn::exec($dbc, $sql, $values);
            $sql1 = "SELECT last_insert_id();";
            $rows = dbconn::exec($dbc, $sql);
            $id = (isset($rows[0])) ? $rows[0][0] : null;
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
        }
        return $id;
    }

// post

    public function insert($dbc, $vals) {
        $qs = '? as ' . str_replace(', ', ', ? as ', $this->colNames_);
        $sql = <<<ESQL
    INSERT INTO {$this->table_} ( {$this->colNames_})
    VALUES({$qs})
ESQL;
        $rows = null;
        try {
            $rows = dbconn::exec($dbc, $sql, $vals);
        } catch (Exception $ex) {
            error_log(sprintf("%s %s %s", __FILE__, __METHOD__, $ex->getMessage()));
        }
        return $rows;
    }

// insert

    public function update($dbc, $vals) {
        $tcols = [];
        for( $ii=0;$ii<count($this->cols_); $ii++) {
            if( $this->cols_[$ii] != 'recorded_on') {
                $tcols[] = $this->cols_[$ii];
            }
        }
        $set = implode(', ', array_map(function($x) {
                    return $x . "= ?";
                }, $tcols));
        $cols = substr(str_repeat('?,', count($tcols)), -1);
        $sql = <<<ESQL
    UPDATE {$this->table_}
    SET {$set}
    WHERE {$this->idcol_} = ?
ESQL;
        $retVal = null;
        try {
            dbconn::exec($dbc, $sql, array_values($vals));
            $retVal = $vals[$this->idcol_];
        } catch (Exception $ex) {
            error_log(sprintf("!!!! %s %s %s", __FILE__, __METHOD__, $ex->getMessage()));
        }
//        error_log("@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@");
//        error_log( print_r($tcols, 1));
//        error_log( print_r($vals, 1));
//        error_log( print_r($retVal, 1));
//        error_log("@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@");
        return $retVal;
    }

// update

    public function delete($dbc, $id) {
        $sql = "DELETE FROM {$this->table_} WHERE {$this->idcol_} = ? RETURNING {$this->idcol_}";
        return dbconn::exec($dbc, $sql, [$id]);
    }
    
    public static function getAll_($dbc) {
        return;
    }
    
    public static function get_($dbc, $args) {
        return;
    }
    
    public static function post_($dbc, $args, $posted) {
        return;
    }
    
    public static function delete_($dbc, $args) {
        return;
    }

// delete
}
