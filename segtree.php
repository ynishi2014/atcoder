<?php
//$segMax = new SegTree($P, "max", 0);
//$segMin = new SegTree($P, "min", 10**10);
//$segMax = new SegTree($P, function($a, $b){return max($a, $b);}, 0);
//$segMin = new SegTree($P, function($a, $b){return min($a, $b);}, 10**10);

class SegTree{
  public $tree;
  public $bits;
  public $zero;
  public $op;
  function __construct($n, callable $op = null, $zero = 0){
    $this->op = $op;
    $this->zero = $zero;
    if(is_array($n)){
      $array = $n;
      $n = count($array);
      $this->bits = max(1, ceil(log($n+1)/log(2)));
      for($i = $n, $I = 2**$this->bits; $i < $I; ++$i){
        $array[] = $zero;
      }
      $this->tree[0] = $array;
      $this->rollupAll();
    }else{
      $this->bits = max(1, ceil(log($n)/log(2)));
      for($i = 0; $i < $this->bits; ++$i){
        $this->tree[$i] = array_fill(0, 2**($this->bits-$i), $zero);
      }
    }
  }
  function rollupAll(){
    $op = $this->op;
    for($i = 1; $i < $this->bits; ++$i){
      $treei = &$this->tree[$i];
      $treeim = $this->tree[$i-1];
      for($j = 0, $J = 2**($this->bits - $i + 1); $j < $J; $j+=2){
        $treei[] = $op($treeim[$j], $treeim[$j+1]);
      }
    }
  }
  function set($i, $amount){
    $tree = &$this->tree;
    $tree[0][$i] = $amount;
    $op = $this->op;
    for($j = 1, $J = $this->bits; $j < $J; ++$j){
      $ij = $i >> $j;$treejm = $tree[$j-1];
      $tree[$j][$ij] = $op($treejm[$ij<<1], $treejm[($ij<<1) + 1]);
    }
  }
  function get($f, $t){
    $sum = $this->zero;
    $tree = $this->tree;
    $op = $this->op;
    $i = 0;
    while($f!=$t){
      $treei = $tree[$i];
      if(($f >> $i) & 1){
        $sum = $op($sum, $treei[$f>>$i]);
        $f+=1<<$i;
      }
      if(($t >> $i) & 1){
        $sum = $op($sum, $treei[($t>>$i) - 1]);
        $t-=1<<$i;
      }
      ++$i;
    }
    return $sum;
  }
}
