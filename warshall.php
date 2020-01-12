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
    $array = array_fill(1, $n, PHP_INT_MAX);
    $this->d = array_fill(1, $n, $array);
    for($i = 1; $i <= $this->n; $i++){
        $this->d[$i][$i] = 0;
    }
  }
  function connect($a, $b, $c){
    $this->d[$a][$b] = $c;
  }
  function connect2($a, $b, $c){
    $this->d[$a][$b] = $c;
    $this->d[$b][$a] = $c;
  }
  function solve(){
    $d = &$this->d;
    $n = $this->n;
    for($k = 1; $k <= $n; $k++){
      for($i = 1; $i <= $n; $i++){
        for($j = 1; $j <= $n; $j++){
          if($d[$i][$j] > $d[$i][$k] + $d[$k][$j]){
            $d[$i][$j] = $d[$i][$k] + $d[$k][$j];
          }
        }
      }
    }
    return $d;
  }
}
