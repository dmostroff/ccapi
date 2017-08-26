<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of autoload
 *
 * @author DANO
 */
spl_autoload_register(function ($class) {
    $fullpath = "";
    $classes_from_common = [
        'utils'
        , 'curlutils'
        , 'dbconn'
        , 'cryptutils'
    ];
    if (in_array($class, $classes_from_common)) {
        require_once '../src/common/' . $class . '.class.php';
        return;
    }
    error_log( $class);
    try {
        $file = explode("_", $class);
        if ($file[0] && $file[1]) {
            $fullpath = '../src/' . strtolower($file[0]) . '/' . strtolower($file[1]) . '.class.php';
            require_once "$fullpath";
        }
    } catch (Exception $e) {
        error_log("spl_autoload: failed to load \"$class\" (\$fullpath is \"$fullpath\")");
        throw $e;
    }
});
