<?php

class BIT{
    // 注意: 1-origin
    public $tree;
    public $N;
    public function __construct($N){
        $this->tree = array_fill(0, $N+1, 0);
        $this->N = $N;
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
    public function nth($x){ // 1-origin O(logN * logN) // TODO:高速化
        $ng = 0;
        $ok = $this->N;
        while($ok - $ng > 1){
            $mid = ($ok+$ng)>>1;
            if($this->sum($mid)>=$x){
                $ok = $mid;
            }else{
                $ng = $mid;
            }
        }
        return $ok;
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
