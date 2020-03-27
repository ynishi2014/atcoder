<?php
/**
 * Warshall–Floyd
 * Attention: 1-origin
 **/
class Wf{
  public $d;
  public $n;
  function __construct($n)	{
    $this->n = $n;
    $array = array_fill(1, $n, 10**18);
    $this->d = array_fill(1, $n, $array);
    for($i = 1; $i <= $this->n; $i++){
        $this->d[$i][$i] = 0;
    }
  }
  function connect($a, $b = false, $c = 1){
    if(is_array($a))list($a, $b, $c) = $a;
    $this->d[$a][$b] = $c;
  }
  function connect2($a, $b = false, $c = 1){
    if(is_array($a))list($a, $b, $c) = $a;
    $this->d[$a][$b] = $c;
    $this->d[$b][$a] = $c;
  }
  function solve(){
    $d = $this->d;
    $n = $this->n;
    for($k = 1; $k <= $n; ++$k){
      $dk = $d[$k];
      for($i = 1; $i <= $n; ++$i){
        $di = &$d[$i];
        for($j = 1; $j <= $n; ++$j){
          if($di[$j] > $di[$k] + $dk[$j]){
            $di[$j] = $di[$k] + $dk[$j];
          }
        }
      }
    }
    $this->d = $d;
    return $d;
  }
}
