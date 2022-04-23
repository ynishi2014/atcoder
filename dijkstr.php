<?php
/**
 * Dijkstra
 * Attention: 1-origin
 **/
class Di{
  public $pq;
  public $distance;
  public $G;
  public $from;
  public $inf = 10**18;
  function __construct($n) {
    $this->distance = array_fill(1, $n, $this->inf);
//    $this->from = array_fill(1,$n,0);
    $this->G = array_fill(1,$n,[]);
  }
  function connect($from, $to, $cost){
    if(isset($this->G[$from][$to])){
      $this->G[$from][$to] = min($this->G[$from][$to], $cost);
    }else{
      $this->G[$from][$to] = $cost;
    }
  }
  function solve($from){
    $pq = new SplPriorityQueue();
    $distance = $this->distance;
    $tfrom = $this->from;
    $G = $this->G;
    $pq->insert($from, 0);
    $distance[$from] = 0;
    while($pq->count()){
      $f = $pq->extract();
      if(!isset($map[$f])){
        $map[$f] = true;
        foreach($G[$f] as $t => $dist){
          $new = $distance[$f] + $dist;
          if($distance[$t] > $new){
            $distance[$t] = $new;
            //$tfrom[$t] = $f;
            $pq->insert($t, -$new);
          }
        }
      }
    }
    $this->distance = $distance;
    //$this->from = $tfrom;
  }
}
