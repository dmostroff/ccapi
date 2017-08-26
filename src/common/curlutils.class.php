<?php

if (!class_exists('curlutils')) {

    class curlutils {

        const HTTP_OK = 200;
        const HEADER_XML = "Content-Type: application/xml";
        const USER_AGENT = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.65 Safari/537.36";

        public static $certificate = null;
        public static $cert_pw = null;
        public static $cert_check = true;
        public static $finalOptions = [];

        public static function httpOK($http_status) {
            return(intval($http_status / 100) * 100 == curlutils::HTTP_OK);
        }

        public static function setTimeouts($ch) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        }

        public static function getCerr($ch) {
            if ($errno = curl_errno($ch)) {
                $error_message = ($errno);
                return("({$errno})");
            }
        }

        private static function failure($msg) {
            if (php_sapi_name() == 'cli') {
                $msg .= "\n";
            }
            error_log("Failed while $msg");
        }

        public static function applyFinalOption($ch) {
            foreach (self::$finalOptions as $opt) {
                curl_setopt($ch, $opt[0], $opt[1]);
            }
        }

        public static function credCpost($credtype, $cred, $url, $fields, $what) {
            return(curlutils::postWithHeaders($url, $fields, array("Authorization: $credtype $cred"), $what));
        }

        public static function credPostWithHeaders($credtype, $cred, $url, $fields, $headers, $what) {
            if (!$headers) {
                $headers = array();
            }
            $headers[] = "Authorization: $credtype $cred";
            return(curlutils::postWithHeaders($url, $fields, $headers, $what));
        }

        public static function post($url, $fields, $what = null) {
            return(curlutils::postWithHeaders($url, $fields, null, $what));
        }

        public static function postWithHeaders($url, $fields, $headers, $what) {
            $ch = curl_init();
            curlutils::setTimeouts($ch);
            if ($headers && count($headers) > 0) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, n2po_curl::USER_AGENT);
            if (self::$certificate) {
                curl_setopt($ch, CURLOPT_SSLCERT, self::$certificate);
                curl_setopt($ch, CURLOPT_SSLCERTPASSWD, self::$cert_pw);
            }
            if (!self::$cert_check) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            }
            curlutils::applyFinalOption($ch);
            $res = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($res === FALSE || !curlutils::httpOK($http_status)) {
                $curlerr = curlutils::getCerr($ch);
                curlutils::failure("$what. http response: $http_status. curl: $curlerr");
            }
            return(array($http_status, $res));
        }

        public static function credCget($credtype, $cred, $url, $what) {
            return(curlutils::getWithHeaders($url, array("Authorization: $credtype $cred"), $what));
        }

        public static function credCgetWithHeaders($credtype, $cred, $url, $headers, $what) {
            if (!$headers) {
                $headers = array();
            }
            $headers[] = "Authorization: $credtype $cred";
            return(curlutils::getWithHeaders($url, $headers, $what));
        }

        public static function get($url, $what = null, $headercbfunc = null) {
            return(curlutils::getWithHeaders($url, null, $what, $headercbfunc));
        }

        public static function getWithHeaders($url, $headers, $what, $headercbfunc = null) {
            $ch = curl_init();
            curlutils::setTimeouts($ch);
            if ($headers && count($headers) > 0) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, n2po_curl::USER_AGENT);
            if (self::$certificate) {
                curl_setopt($ch, CURLOPT_SSLCERT, self::$certificate);
                curl_setopt($ch, CURLOPT_SSLCERTPASSWD, self::$cert_pw);
            }
            if (!self::$cert_check) {
                error_log("n2po_curl: no foreign cert check");
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            }
            if (isset($headercbfunc)) {
                curl_setopt($ch, CURLOPT_HEADERFUNCTION, $headercbfunc);
            }
            //curl_setopt($ch, CURLOPT_VERBOSE, true);
            curlutils::applyFinalOption($ch);
            $res = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($res === FALSE || !n2po_curl::httpOK($http_status)) {
                $curlerr = curlutils::getCerr($ch);
                curlutils::failure("$what. http response: $http_status. curl: $curlerr");
            }
            return(array($http_status, $res));
        }

        public static function setCertificate($certfile, $certpw) {
            self::$certificate = $certfile;
            self::$cert_pw = $certpw;
        }

        public static function setCertCheck($value) {
            self::$cert_check = $value;
        }

        public static function setFinalOptions($options) {
            self::$finalOptions = $options;
        }

    }

}
