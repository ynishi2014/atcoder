<?php
function op($a, $b){return $a | $b;}

//$seg = new SegTree($array);

class SegTree{
  public $tree;
  public $bits;
  function __construct($n){
    if(is_array($n)){
      $array = $n;
      $n = count($array);
      $this->bits = max(1, ceil(log($n)/log(2)));
      for($i = $n, $I = 2**$this->bits; $i < $I; ++$i){
        $array[] = 0;
      }
      $this->tree[0] = $array;
      $this->rollupAll();
    }else{
      $this->bits = max(1, ceil(log($n)/log(2)));
      for($i = 0; $i < $this->bits; ++$i){
        $this->tree[$i] = array_fill(0, 2**($this->bits-$i), 0);
      }
    }
  }
  function rollupAll(){
    for($i = 1; $i < $this->bits; ++$i){
      $treei = &$this->tree[$i];
      $treeim = $this->tree[$i-1];
      for($j = 0, $J = 2**($this->bits - $i + 1); $j < $J; $j+=2){
        $treei[] = op($treeim[$j], $treeim[$j+1]);
      }
    }
  }
  function set($i, $amount){
    $this->tree[0][$i] = $amount;
    for($j = 1, $J = $this->bits; $j < $J; ++$j){
      $this->tree[$j][$i>>$j] = op($this->tree[$j-1][$i>>$j<<1], $this->tree[$j-1][($i>>$j<<1) + 1]);
    }
  }
  function get($f, $t){
    $sum = 0;
    $tree = $this->tree;
    $bits = $this->bits;
    $i = 0;
    while($f!=$t){
      $treei = $tree[$i];
      if(($f >> $i) & 1 == 1){
        $sum = op($sum, $treei[$f>>$i]);
        $f+=1<<$i;
      }
      if(($t >> $i) & 1 == 1){
        $sum = op($sum, $treei[($t>>$i)-1]);
        $t-=1<<$i;
      }
      ++$i;
    }
    return $sum;
  }
}