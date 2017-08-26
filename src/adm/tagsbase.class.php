<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usersbase
 *
 * @author DANO
 */
class Adm_TagsBase extends Base_dblayer {

    public function __construct($app) {
        $this->helper_ = new Adm_TagHelper();
        parent::__construct($app);
    }

    public function invoke($request, $response, $args) {
        $dbc = $this->connect();
        try {
            $data = $this->run($dbc, $args);
            $rc = 0;
            $msg = 'OK';
            $retStatus = 200;
        } catch (Exception $ex) {
            error_log(sprintf("%s %s] %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
            $rc = -1;
            $msg = $ex->getMessage();
            $retStatus = 400;
        }
        $ret = ['res' => ['rc' => $rc, 'msg' => $msg], 'data' => $data];
        return $this->jsonResponse($ret, $retStatus);
    }

    protected function run($dbc, $args) {
        return null;
    }

}
