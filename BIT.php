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
    public function add($i, $x){
        $N = $this->N;
        $tree = &$this->tree;
        while($i <= $N){
            $tree[$i] += $x;
            $i += $i&-$i;
        }
    }
}
