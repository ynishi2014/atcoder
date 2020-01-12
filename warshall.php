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
    for($i = 1; $i <= $this->n; $i++){
      for($j = 1; $j <= $this->n; $j++){
        $this->d[$i][$j] = PHP_INT_MAX;
      }  
    }
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
    for($k = 1; $k <= $this->n; $k++){
      for($i = 1; $i <= $this->n; $i++){
        for($j = 1; $j <= $this->n; $j++){
          if($this->d[$i][$j] > $this->d[$i][$k] + $this->d[$k][$j]){
            $this->d[$i][$j] = $this->d[$i][$k] + $this->d[$k][$j];
          }
        }
      }
    }
    return $this->d;
  }
}
