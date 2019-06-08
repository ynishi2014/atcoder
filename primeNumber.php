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
