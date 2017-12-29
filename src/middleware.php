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
require_once('authenticate.php');

class middleware {
    public function __invoke($request, $response, $next) {
        $retVal = false;
        $token = null;
        $uri = $request->getUri();
        if( 0 < strpos($uri, '/login')) {
            $retVal = true;
        } else {
            $ipAddress = $request->getAttribute('ip_address');
            error_log( "ipAddress [" . $ipAddress . "]");
            $ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
            error_log( "ipAddress (" . $ip . ")");
           if ($request->hasHeader('Authorization')) {
                $token1 = $request->getHeaderLine('Authorization');
                $token = preg_replace( '/^Bearer /', '', $token1);
                $retVal = Authenticate::validate_token($token);
                error_log( sprintf( "TOKEN: %s; result %s!", $token, $retVal));
            }
        }
        if( $retVal) {
            error_log( "Valid {$token}");
           $response = $next($request, $response); 
        } else {
            $ret = ['res' => [ 'rc' => -1, 'msg'=>'Invalid user'], 'data' => null, 'token' => $token];
            error_log( print_r($ret, 1));
            $response = $response->withStatus(200);
            $response = $response->withHeader('Content-type', 'application/json');
            $response->getBody()->write(json_encode($ret, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
        return $response;
    }
}
