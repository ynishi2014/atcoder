<?php
list($n, $m) = array_map("intval", explode(" ", trim(fgets(STDIN))));
$di = new Di($n);
for($i = 0; $i < $m; $i++){
  list($from, $to, $d) = array_map("intval", explode(" ", trim(fgets(STDIN))));
  $di->connect($from, $to, $d);
}
for($i = 2; $i <= $n; $i++){
  $di->connect($i, $i - 1, 0);
}
$di->solve(1);
$distance = $di->distance[$n];
if($distance == $di->inf){
  echo -1;
}else{
  echo $distance;
}

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
