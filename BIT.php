<?php

class BIT{
    // 注意: 1-origin
    public $tree;
    public $N;
    public function __construct($N){
        $bits = sprintf("%b", $N);
        $this->d = 2**(strlen($bits)-1);
        $this->tree = array_fill(0, $N+1, 0);
        $this->N = $N;
    }
    public function max(){return $this->nth($this->sum($this->N));}
    public function min(){return $this->nth(1);}
    public function sumAll(){return $this->sum($this->N);}
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
    public function nth(int $w){
        if($w <= 0)return 0;
        $x = 0;
        $N = $this->N;
        $tree = $this->tree;
        for($k = $this->d; $k>0; $k>>=1){
            if($x+$k <= $N && $tree[$x+$k] < $w){
                $w-=$tree[$x+$k];
                $x+=$k;
            }
        }
        return $x+1;
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
