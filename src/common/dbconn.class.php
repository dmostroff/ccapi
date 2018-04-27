<?php

// echo __FILE__;

//require_once( '../src/common/utils.class.php');
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
if (!class_exists('dbconn')) {

    class dbconn {

        private $cfgfile;

        function __construct($cfgfile) {
            $this->cfgfile = $cfgfile;
        }

        public static function connect($cfgfile, $section = 'database') {
            if (!$dbsettings = utils::read_ini_section($cfgfile, $section)) {
                throw new exception(sprintf("Unable to open %s", $cfgfile));
            }

            // error_log(print_r($dbsettings, 1));
            $dbsettings['driver'] = utils::getArrayVal($dbsettings, 'driver', 'pqsql');
            $dbsettings['host'] = utils::getArrayVal($dbsettings, 'host', 'localhost');
            $dbsettings['port'] = utils::getArrayVal($dbsettings, 'port', '5432');


            $dns = sprintf("%s:host=%s;port=%s;dbname=%s"
                    , $dbsettings['driver']
                    , $dbsettings['host']
                    , $dbsettings['port']
                    , $dbsettings['dbname']
            );
            // error_log( $dns);
            return new PDO($dns, $dbsettings['username'], $dbsettings['password']);
        }

        public static function exec($dbc, $sql, $args = null) {

            $pstmt = $dbc->prepare($sql);
            if (FALSE === $pstmt) {
// ...no workie
                error_log(print_r($dbc->errorInfo(), 1));
                throw new Exception("dbi::exec(...) failed! (parse phase)");
            }
// ...bind args (if any)
            if (isset($args) && count($args) > 0) {
                $tmpfld = 0;
                foreach ($args as $v) {
                    if (FALSE === $v) {
                        $pstmt->bindValue(++$tmpfld, $v, PDO::PARAM_BOOL);
                    } else {
                        $pstmt->bindValue(++$tmpfld, $v);
                    }
                }
            }
            if (FALSE === $pstmt->execute()) {
                error_log(print_r($pstmt->errorInfo(), 1));
                $pstmt->closeCursor();
                throw new Exception("dbi::pbef(...) failed! (execute phase)");
            }
            $tmpa = $pstmt->fetchAll(PDO::FETCH_ASSOC);
            $pstmt->closeCursor();
            return $tmpa;
        }

    }

}
    