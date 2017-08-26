<?php

// echo __FILE__;

function getArrayVal($array, $member, $defval=null) {
    if (isset($array) && isset($array[$member])) {
        return $array[$member];
    } else {
        return $defval;
    }
}

function getObjVal($obj, $member, $defval = null) {
    if (!$obj || !is_object($obj) || !property_exists($obj, $member)) {
        return($defval);
    } else {
        return($obj->$member);
    }
}

if (!class_exists('utils')) {

    class utils {

        const iniFile = '/etc/ostent/ccapi.cfg';

        public static function read_ini_file($inifile, $process_sections = false) {
            try {
                return parse_ini_file($inifile, $process_sections);
            } catch (Exception $ex) {
                error_log(sprintf("Cannot read '%s' : %s", $inifile, $process_sections));
                throw $ex;
            }
        }

        public static function read_ini_section($inifile, $section) {
            $iniData = self::read_ini_file($inifile, TRUE);
            if (isset($iniData[$section])) {
                return $iniData[$section];
            } else {
                return [];
            }
        }

        function getArrayVal($array, $member, $defval=null) {
            if (isset($array) && isset($array[$member])) {
                return $array[$member];
            } else {
                return $defval;
            }
        }

//return a value of a property of object
        function getObjVal($obj, $member, $defval = null) {
            if (!$obj || !is_object($obj) || !property_exists($obj, $member)) {
                return($defval);
            } else {
                return($obj->$member);
            }
        }

    }

}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

