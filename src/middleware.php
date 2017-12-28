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
require_once "authenticate.php";

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
                $token = $request->getHeaderLine('Authorization');
                error_log( 'TOKEN0: ' . $token);
                $token = preg_replace( '/^Bearer /', '', $token);
                error_log( 'TOKEN1: ' . $token);
                $retVal = Authorization::validate_token($token);
            }
        }
        if( $retVal) {
           $response = $next($request, $response); 
        } else {
            $ret = ['res' => [ 'rc' => -1, 'msg'=>'Invalid user'], 'data' => $token];
            $response = $response->withStatus(200);
            $response = $response->withHeader('Content-type', 'application/json');
            $response->getBody()->write(json_encode($ret, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
        return $response;
    }
}
