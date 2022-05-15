<?php

class BIT{
    // 注意: 1-origin
    public $tree;
    public $N;
    public function __construct($N){
        $this->tree = array_fill(0, $N+1, 0);
        $this->N = $N;
        $bits = sprintf("%b", $N);
        $this->d = 2**(strlen($bits)-1);
    }
    public function sum($i){
        $sum = 0;
        $tree = $this->tree;
        while($i){
            $sum += $tree[$i];
            $i -= $i&-$i;
        }
        return $sum;
    }
    public function sumBetween($i, $j){
        return $this->sum($j) - $this->sum($i-1);
    }
    public function nth($x){
        $ans = 1;
        $d = $this->d;
        $tree = $this->tree;
        do{
          if($tree[$ans+$d]<$x){
            $x-=$tree[$ans+$d];
            $ans+=$d;
          }
          $d>>=1;
        }while($d);
        return $ans;
    }
    public function set($i, $x){
        $old = $this->sumBetween($i, $i);
        $this->add($i, $x-$old);
    }
    public function add($i, $x){
        $N = $this->N;
        $tree = &$this->tree;
        while($i <= $N){
            $tree[$i] += $x;
            $i += $i&-$i;
        }
    }
}
