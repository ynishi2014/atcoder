<?php

//エラトステネスの篩　計算量はO(NloglogN)なので、ほぼ定数。素敵。
function era($n){
    for($i = 2; $i <= $n; $i++){
        $map[$i] = true;
    }
    $rootn = sqrt($n);
    $c = 0;
    for($i = 2; $i <= $rootn; $i++){
        if(isset($map[$i])){
            for($j = $i*2; $j <= $n; $j+=$i){
                $c++;
                unset($map[$j]);
            }
        }
    }
    return array_keys($map);
}

//素数判定　-- 1回の判定であれば、エラトステネスの篩を使わないほうが速い
function isPrime($n){
    $sqrtn = sqrt($n)+1;
    for($i = 2; $i < $sqrtn; $i++){
        if($n % $i == 0)return false;
    }
    return true;
}

//素因数分解 - 連想配列で返す(因数の値 => 因数の個数)
function factor($M){
    $rootM = $M**0.5+1;
    for($j = 2; $j <= $rootM; $j++){
        if($M%$j == 0){
            @$map[$j]++;
            $M/=$j;
            $rootM = $M**0.5+1;
            $j--;
        }
    }
    if($M>1){
        @$map[$M]++;
    }
    return $map;
}

//素因数分解 - 素因数を昇順に列挙
function factor($M){
    $f = [];
    $rootM = $M**0.5+1;
    for($j = 2; $j <= $rootM; $j++){
        if($M%$j == 0){
            $f[] = $j;
            $M/=$j;
            $rootM = $M**0.5+1;
            $j--;
        }
    }
    if($M>1){
        $f[] = $M;
    }
    return $f;
}

