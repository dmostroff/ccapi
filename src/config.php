<?php
class Config {
  // const CFG_INI_FILENAME = '/etc/ostent/ccapi.ini';
  const CFG_INI_FILENAME = '/etc/ostent/cardpoints.ini';
  
  const CFG_COMMON_DIR = '../src/common';
  static function cfgvars() {
    return utils::read_ini_file(self::CFG_INI_FILENAME, TRUE);
//    return utils::fetch_cfginfo(self::CFG_INI_FILENAME);
  }
  static function cfgval($vname) {
    return utils::fetch_cfginfo(self::CFG_INI_FILENAME, $vname, true);
  }
}
$commonDir = Config::CFG_COMMON_DIR;
