<?php
$s = trim(fgets(STDIN));;
$str = new Str($s);

print_r($str->getSegLen());

/**
 * 文字列に関する処理をまとめる
 */
class Str{
  public $str = false;
  function __construct($str){
    $this->str = $str;
  }
  /**
   * 同じ文字の継続数を返す
   * in: "aabbccc"
   * out: [2,2,3]
   */
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
