<?php

class LCA{
  function __construct($tree){
    $this->tree = $tree;
    $this->N = count($tree);
    $this->distance = array_fill(1, $this->N, 0);
    $this->parent[0] = array_fill(0, $this->N+1, 0);
    $this->dfs(-1, 1);
    $this->level = 1;
    while((1 << ($this->level+1)) <= count($tree))$this->level++;
    for($i = 1; $i <= $this->level; $i++){ // 1+...+2**17 = 262143
      for($j = 0; $j <= $this->N; $j++){
        $this->parent[$i][$j] = $this->parent[$i-1][$this->parent[$i-1][$j]];
      }
    }
  }
  function dfs($f, $c){
    foreach($this->tree[$c] as $n){
      if($n==$f)continue;
      $this->distance[$n] = $this->distance[$c]+1;
      $this->parent[0][$n] = $c;
      $this->dfs($c, $n);
    }
  }
  function distance($f, $t){
    $c = $this->query($f, $t);
    return $this->distance[$f]+$this->distance[$t]-$this->distance[$c]*2;
  }
  function query($f, $t){
    $df = $this->distance[$f];
    $dt = $this->distance[$t];
    if($df>$dt){
      $f = $this->goUp($f, $df-$dt);
    }elseif($dt>$df){
      $t = $this->goUp($t, $dt-$df);
    }
    if($f==$t)return $f;
    for($i = $this->level; $i >= 0; $i--){
      if($this->parent[$i][$f]!=$this->parent[$i][$t]){
        $f = $this->parent[$i][$f];
        $t = $this->parent[$i][$t];
      }
    }
    return $this->parent[0][$f];
  }
  function goUp($p, $dist){
    $i = 0;
    while($dist){
      if($dist&1){
        $p = $this->parent[$i][$p];
      }
      $dist >>= 1;
      $i++;
    }
    return $p;
  }
}
