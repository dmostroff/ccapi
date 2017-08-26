<?php
class FileLoad {
  private $loaded = false;
  private $filename = null;
  private $classname = null;
  private $instance = null;
  private $app = null;
  public function __construct(&$app, $filename, $classname) {
    $this->app = $app ;
    $this->filename = $filename;
    $this->classname = $classname;
  }
  
  public function __invoke($a1 = null, $a2 = null, $a3 = null, $a4 = null, $a5 = null, $a6 = null, $a7 = null) {
    if(!$this->loaded) {
      $this->loaded = true;
      //error_log("including {$this->filename}");
      if($this->filename) {
        require_once($this->filename);
      }
      //error_log("Instantiating {$this->classname}");
      // $logger = $this->app->getContainer()['logger'];
      $this->instance = new $this->classname($this->app);
                                        
    }                      
    return($this->instance->__invoke($a1, $a2, $a3, $a4, $a5, $a6, $a7));
  }
}
