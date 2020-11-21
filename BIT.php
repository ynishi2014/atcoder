<?php

class BIT{
    // 注意: 1-origin
    public $tree;
    public $N;
    public function __construct($N){
        $this->tree = array_fill(0, $N+1, 0);
        $this->N = $N;
    }
    public function between($from, $to){//閉区間
        return $this->sum($to) - $this->sum($from-1);
    }
    public function sum($i){
        if($i == 0)return 0;
        return $this->tree[$i] + $this->sum($i-($i&-$i));
    }
    public function add($i, $x){
        if($i > $this->N)return;
        $this->tree[$i] += $x;
        $this->add($i+($i&-$i), $x);
    }
}
