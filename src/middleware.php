<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of middleware
 *
 * @author DANO
 */
class middleware {
    public function __invoke($request, $response, $next) {
        $retVal = false;
        $uri = $request->getUri();
        if( 0 < preg_match('/login/', $uri)) {
            $retVal = true;
        } else {
            error_log( 'header?');
            if ($request->hasHeader('Authorization')) {
                $token = $request->getHeaderLine('Authorization');
                error_log( 'TOKEN: ' . $token);

                $dbc = dbconn::connect(Config::CFG_INI_FILENAME);
                $sql = "SELECT f_login_validate(?) as tokenvalid";
                $values = [$token];
                try {
                    error_log( $sql);
                    $rows = dbconn::exec($dbc, $sql, $values);
                    error_log( json_encode($rows));
                    if( isset($rows[0]) && $rows[0]['tokenvalid']) {
                        $retVal = (1 == $rows[0]['tokenvalid']) ? true : false;
                    }
                } catch (Exception $ex) {
                    error_log(sprintf("%s %d] %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
                }
            }
        }
        if( $retVal) {
           $response = $next($request, $response); 
        } else {
            $ret = [ 'rc' => -1, 'msg'=>'Invalid user'];
            $response = $response->withStatus(401);
            $response = $response->withHeader('Content-type', 'application/json');
            $response->getBody()->write(json_encode($ret, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
        return $response;
    }
}
