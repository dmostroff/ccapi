<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base_dblayer
 *
 * @author DANO
 */
class Base_dblayer {

    protected $app_;
    public $dbc_;
    protected $helper_ = null;
    protected $requiresContentType_ = true;
    protected $token_ = null;

    public function __construct($app) {
        $this->app_ = $app;
    }
    
    public static function isJson( $contentType) {
        if( preg_match( "#application/json#", $contentType)) {
            return true;
        } else {
            return false;
        }
    }

    //put your code here
    public function __invoke($request, $response, $args) {
        $this->response_ = $response;

        // is there an API version either in the HEADER or in the route path
        if ($request->hasHeader('APIVersion')) {
            $this->apiversion_ = $request->getHeaderLine('APIVersion');
        }
        if (isset($args['apivers'])) {
            $this->apiversion_ = $args['apivers'];
        }
        if ($request->hasHeader('Authorization')) {
            $token1 = $request->getHeaderLine('Authorization');
            $this->token_ = preg_replace( '/^Bearer /', '', $token1);
        }

        if ($request->getMethod() == 'POST'
            || 
            $request->getMethod() == 'DELETE'
            ) {
            if ($this->requiresContentType_ && !self::isJson($request->getContentType())) {
//                $this->logger_->addError('Auth: Content-Type of request is not application/json'
//                        , [ 'content-type' => $request->getContentType()]);
                $ret = ['Auth: Content-Type of request is not application/json'];
                return $this->jsonResponse($ret, 400);
            }
            // ...read the POST'ed data
            $this->posted_ = $request->getParsedBody();
//            error_log( print_r($this->posted_, 1));
//            error_log( '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');
//            $this->logger_->addDebug('parsed body is', [ 'parsedBody' => $this->posted_]);
            if ($this->requiresPostedData() && (!isset($this->posted_) || empty($this->posted_))) {
//                $this->logger_->addError('authenticate: POSTed is missing data');
                $ret = ['authenticate: POSTed is missing data'];
                return $this->jsonResponse($ret, 400);
            }
        }
//    $this->logger_->addDebug('muser is', (array)$this->muser_);
        try {
            $ret = $this->invoke($request, $response, $args);
        } catch (Exception $ex) {
            if ($ex->getMessage() == 'doReturn') {
                return(false);
            } else {
                throw $ex;
            }
        }
        return($ret);
    }


    public function invoke($request, $response, $args) {
        try {
            $data = $this->run($args);
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
    
    public function exec($dbc, $qry, $args) {
        return $this->execdb($dbc, $qry, $args);
    }

    public function execdb($dbc, $qry, $args) {
        $result = null;
        dbconn::exec($dbc, "BEGIN");
        try {
            $result = dbconn::exec($dbc, $qry, $args);
            dbconn::exec($dbc, "COMMIT");
        } catch (Exception $ex) {
            error_log(sprintf("%s %d] %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
            dbconn::exec($dbc, "ROLLBACK");
        }
        return $result;
    }

    public function connect() {
        return dbconn::connect(Config::CFG_INI_FILENAME);
    }

    public function connectdbc() {
        $this->dbc_ = dbconn::connect(Config::CFG_INI_FILENAME);
    }

    public function execdbc($qry, $args) {
        return $this->execdb($this->dbc_, $qry, $args);
    }
    protected function requiresPostedData() {
        return true;
    }
    //---------------------------------------------------------------------------
    protected function jsonResponse($ret, $respStatus = 200, $response = null) {
        if (empty($response)) {
            $response = $this->response_;
        }
        $r = $response->withStatus($respStatus);
        $r = $r->withHeader('Content-type', 'application/json');
        if( isset($this->token_)) {
            $r = $r->withHeader('Authorization', $this->token_);
        }
        $r->getBody()->write(json_encode($ret, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        return $r;
    }

}
