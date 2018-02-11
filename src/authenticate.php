<?php
define('TOKTTL', 240*60) ;
define('CHECK_SRC_IP', false) ;
class Authenticate {
    const KEYPREFIX_ = "OSTENT:TOKEN";
  public static function generate_token() {
    return hash('sha1', 'random - ' . bin2hex(openssl_random_pseudo_bytes(1024)) . ' - numbers!') ;
  }
  public static function has_admin_to($tok, $clientid) {
    // ...how we store it
    $systok = self::KEYPREFIX_ . ':' . $tok;
    // ...can we find it in Redis?
    $red = new Redis() ;
    $red->connect('localhost') ;
    if($red->exists($systok)) {
      $attrs = $red->hGetAll($systok) ;
      if(isset($attrs)) {
        // ...for now only allow admins to do this
        return (0==$attrs['level'] || 10==$attrs['level']) ;
      }
    }
    return false ;
  }
  public static function persist_token($tok, $appattrs) {
    // ...how we store it
    $systok = self::KEYPREFIX_ . ':' . $tok;
    // ..what we store on SYSTEM level
    $sysattrs = array(
                  '_created'=>date('c')
                 ,'_remote_addr'=>$_SERVER['REMOTE_ADDR']
                 ,'_http_x_forwarded_for'=>isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : ''
                 ) ;
    // ...store it in Redis with the associated info
    $red = new Redis() ;
    $red->connect('localhost') ;
    $red->delete($systok) ;
    foreach($sysattrs as $k=>$v) {
      $red->hSet($systok, $k, $v) ;
    }
    foreach($appattrs as $k=>$v) {
      $red->hSet($systok, $k, $v) ;
    }
    $red->expire($systok, TOKTTL) ;
  }
  public static function release_token($tok) {
    // ...how we store it
    $systok = self::KEYPREFIX_ . ':' . $tok;
    // ...can we find it in Redis?
    $red = new Redis() ;
    $red->connect('localhost') ;
    $red->delete($systok) ;
  }
  // ...also vivifies the token (i.e. resets TTL to max)
  public static function validate_token($tok, $check_src_ip=CHECK_SRC_IP) {
    // ...how we stored it
    $systok = self::KEYPREFIX_ . ':' . $tok;
    // ...can we find it in Redis?
//    error_log( $systok);
    $red = new Redis() ;
    $red->connect('localhost') ;
    if($red->exists($systok)) {
      $attrs = $red->hGetAll($systok) ;
      if(isset($attrs)) {
        $we_likey = false ;
        if(!$check_src_ip) {
          $we_likey = true ;
        }
        else {
          // ...we should probably get these passed in to us...
          $we_likey =  ($attrs['_remote_addr']===$_SERVER['REMOTE_ADDR'])
                    && ($attrs['_http_x_forwarded_for']===(isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : ''))
                    ;
        }
        if($we_likey) {
          $red->expire($systok, TOKTTL) ;
          return true ;
        }
      }
    }
    return false ;
  }
  public static function fetch_app_attrs($tok) {
    // ...how we stored it
    $systok = self::KEYPREFIX_ . ':' . $tok;
    // ...can we find it in Redis?
    $red = new Redis() ;
    $red->connect('localhost') ;
    if($red->exists($systok)) {
      $attrs = $red->hGetAll($systok) ;
      if(isset($attrs)) {
        // ...strip out SYSTEM attrs from token
        unset($attrs['_created']) ;
        unset($attrs['_remote_addr']) ;
        unset($attrs['_http_x_forwarded_for']) ;
        return $attrs ;
      }
    }
    return null ;
  }
}