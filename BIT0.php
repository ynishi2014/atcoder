<?php

class BIT {
    // 注意: 0-origin
    public $tree;
    public $N;
    public $d;

    public function __construct($N){
        // 最も高いビットを計算
        $bits = sprintf("%b", max(1, $N));
        $this->d = 1;
        while ($this->d << 1 <= $N){
            $this->d <<= 1;
        }
        // 0からN-1までのインデックスで初期化
        $this->tree = array_fill(0, $N, 0);
        $this->N = $N;
        // $this->raw = array_fill(0, $N, 0); // 必要に応じてコメント解除
    }

    // BIT の最大値を取得
    public function max(){
        return $this->nth($this->sumAll());
    }

    // BIT の最小値を取得
    public function min(){
        return $this->nth(1);
    }

    // 全ての要素の合計を取得
    public function sumAll(){
        return $this->sum($this->N - 1);
    }

    // インデックス 0 から i までの合計を取得
    public function sum($i){
        $sum = 0;
        while($i >= 0){
            $sum += $this->tree[$i];
            $i = ($i & ($i + 1)) - 1;
        }
        return $sum;
    }

    // インデックス i から j までの合計を取得
    public function sumBetween($i, $j){
        return $this->sum($j) - ($i > 0 ? $this->sum($i - 1) : 0);
    }

    // w 番目の要素のインデックスを取得
    public function nth(int $w){
        if($w <= 0) return -1; // 無効な値の場合
        $x = -1;
        $N = $this->N;
        $tree = $this->tree;
        for($k = $this->d; $k > 0; $k >>= 1){
            if(($x + $k) < $N && $tree[$x + $k] < $w){
                $w -= $tree[$x + $k];
                $x += $k;
            }
        }
        return $x + 1;
    }

    // インデックス i の値を x に設定
    public function set($i, $x){
        $old = $this->sumBetween($i, $i);
        $this->add($i, $x - $old);
        // $this->raw[$i] += $x - $old; // 必要に応じてコメント解除
    }

    // インデックス i に値 x を加算
    public function add($i, $x){
        while($i < $this->N){
            $this->tree[$i] += $x;
            $i = $i | ($i + 1);
        }
    }
}
