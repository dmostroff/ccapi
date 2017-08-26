<?php

if(!class_exists ('cryptutils')) {
Class cryptutils {

  const checkVar = "CRYPT_CHECK";

  const defaultKeyBase64 = "VXS6nVbsmy+sY55jelwjoW+qDTYt/iMi3Vne01nDCwk=";


  public static function defaultKey() {
    return(base64_decode(self::defaultKeyBase64));
  }


  public function randstr() {
    $random = substr(md5(rand()),0,10);
    return(preg_replace("/\|/", '#', $random));
  }


  public static function rawEncrypt($plain_text, $ikey = null) {
    $key = $ikey ? $ikey : self::defaultKey();
    //$encrypted_data = mcrypt_ecb(MCRYPT_RIJNDAEL_256, $key, $plain_text, MCRYPT_ENCRYPT);
    //return($encrypted_data);
    $td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
    $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td, $key, $iv);
    $encrypted_data = mcrypt_generic($td, $plain_text);
    mcrypt_module_close($td);
    return($encrypted_data);
  }

  public static function rawDecrypt($encrypted_text, $ikey = null) {
    $key = $ikey ? $ikey : self::defaultKey();
    //$decrypted_data = mcrypt_ecb(MCRYPT_RIJNDAEL_256, $key, $encrypted_text, MCRYPT_DECRYPT);
    //return($decrypted_data);
    $td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
    $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td, $key, $iv);
    $decrypted_data = mdecrypt_generic($td, $encrypted_text);
    mcrypt_module_close($td);
    return($decrypted_data);
  }

  public static function strEncrypt($str, $key = null) {
    if($str === null) {
      $text =  "-1|" . self::randstr() . "|" . self::checkVar . "|" . $str;
    } else {
      $text = strlen($str) . "|" . self::randstr() . "|" . self::checkVar . "|" . $str;
    }
   return(base64_encode(self::rawEncrypt($text, $key)));
  }

  public static function strDecrypt($str, $key = null) {
   $raw = self::rawDecrypt(base64_decode($str), $key);
   $res = explode('|', $raw);
    if(count($res) < 4 || $res[2] != self::checkVar) {
     return(null);
    }
    // find data or return null for special encoding
    if($res[0] < 0) {
      return(null);
    }
   $pos = 0;
   for($i = 0; $i < 3; $i++) {
     $pos = strpos($raw, '|', $pos) + 1;
   }
   //echo "raw: $raw <br>";
   return(substr($raw, $pos, $res[0]));
  }

 public static function strEncryptExpires($str, $delta, $key=null) {
   $now = time();
   $expires = $now + $delta;
   //echo "expires-now: $now, expires: $expires<br>";
   return(self::strEncrypt($expires . "|$str", $key));
 }

 public function strDecryptExpires($str, $key = null) {
   $raw = self::strDecrypt($str, $key);
   //echo "raw from expires: $raw<br>";
   $res = explode('|', $raw);
    if(count($res) < 2) {
      error_log("Expire type string has no time string was: $str");
      return(null);
    }
    $now = time();
    if($res[0] + 0 < time()) {
      error_log("Expired encrypted string received now: $now, string: {$res[0]}");
      return(null);
    }

    return(substr($raw, strpos($raw,'|') + 1));
 }

}


} // end of exists check