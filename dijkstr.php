<?php

/**
 * Dijkstra
 * Attention: 1-origin
 **/
class Di{
  public $pq;
  public $distance;
  public $graph;
  public $from;
  public $inf = 10**18;
  function __construct($n) {
    $this->pq = new SplPriorityQueue();
    for($i = 1; $i <= $n; $i++){
      $this->distance[$i] = $this->inf;
      $this->from[$i] = 0;
    }
  }
  function connect($from, $to, $cost){
    $this->graph[$from][] = [$to, $cost];
  }
  function solve($from){
    $this->pq->insert($from, 0);
    $this->distance[$from] = 0;
    while($this->pq->count()){
      $f = $this->pq->extract();
      if(!isset($this->graph[$f]))continue;
      foreach($this->graph[$f] as list($t, $dist)){
        $new = $this->distance[$f] + $dist;
        if($this->distance[$t] > $new){
          $this->distance[$t] = $new;
          $this->from[$t] = $f;
          $this->pq->insert($t, -$new);
        }
      }
    }
  }
}
