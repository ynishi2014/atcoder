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
    for($i = 1; $i <= $n; $i++){
      $this->distance[$i] = $this->inf;
      //$this->from[$i] = 0;
    }
  }
  function connect($from, $to, $cost){
    $this->graph[$from][] = [$to, $cost];
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
      if(!isset($G[$f]))continue;
      foreach($G[$f] as list($t, $dist)){
        $new = $distance[$f] + $dist;
        if($distance[$t] > $new){
          $distance[$t] = $new;
          //$tfrom[$t] = $f;
          $pq->insert($t, -$new);
        }
      }
    }
    $this->distance = $distance;
    //$this->from = $tfrom;
  }
}
