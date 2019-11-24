<?php
$s = trim(fgets(STDIN));;
$str = new Str($s);

print_r($str->getSegLen());

class Str{
  public $str = false;
  function __construct($str){
    $this->str = $str;
  }
  function getSegLen(){
    $c = 1;
    for($i = 0, $I = strlen($this->str) - 1; $i < $I; $i++){
      if($this->str[$i] != $this->str[$i+1]){
        $len[] = $c;
        $c = 0;
      }
      $c++;
    }
    if($c)$len[] = $c;
    return $len;
  }
}
