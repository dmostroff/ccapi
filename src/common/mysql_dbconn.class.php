<?php
echo __FILE__;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dbconn
 *
 * @author DANO
 */
class mysql_dbconn {

    public static $CONFIG = '/etc/ostent/config.ini';

//put your code here
    public static function connect($prefix) {
        static $conn;
        if (!isset($conn)) {
            $config = parse_ini_file(dbcon::$CONFIG);
            $conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['dbname']);
        }
        if( $conn == false) {
            return mysqli_connect_error();
        }
        return $conn;
    }

}
