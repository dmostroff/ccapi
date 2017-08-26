<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mypdo
 *
 * @author DANO
 */
class MyPDO extends PDO
{
    public static $CONFIG = '/etc/ostent/config.ini';
    public function __construct($cfile = null)
    {
        if( !isset($cfile)) {
            $cfile = myPDO::$CONFIG;
        }
        if (!$settings = parse_ini_file($cfile, TRUE)) throw new exception('Unable to open ' . $cfile . '.');
        
        $dns = $settings['database']['driver'] .
        ':host=' . $settings['database']['host'] .
        ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '') .
        ';dbname=' . $settings['database']['schema'];
        
        parent::__construct($dns, $settings['database']['username'], $settings['database']['password']);
    }
}
?>
