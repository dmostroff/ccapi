<?php

echo __FILE__;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dbconntest
 *
 * @author DANO
 */
class Test_dbconntest {

    private $app_;

    public function __construct($app) {
        $this->app_ = $app;
    }

    public function __invoke($request, $response, $args) {
        $this->run();
    }

    public function run() {
        //put your code here
        $iniFile = Config::CFG_INI_FILENAME;
        $dbc = dbconn::connect($iniFile);
        $tags = dbconn::exec($dbc, "SELECT * from adm_tags");
        foreach ($tags as $tag) {
            echo sprintf("%s: %s = %s<hr color=gold>", $tag['prefix'], $tag['tag'], $tag['description']);
        }
        echo print_r($tags, 1);
        echo __METHOD__;
    }

}
