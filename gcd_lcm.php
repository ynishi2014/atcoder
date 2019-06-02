<?php

//配列の最大公約数
function gcdAll($array){
    $gcd = $array[0];
    for($i = 1; $i < count($array); $i++){
        $gcd = gcd($gcd, $array[$i]);
    }
    return $gcd;
}

//最大公約数
function gcd($m, $n){
    if(!$n)return $m;
    return gcd($n, $m % $n);
}

//配列の最小公倍数
function lcmAll($array){
    $lcm = $array[0];
    for($i = 1; $i < count($array); $i++){
        $lcm = lcm($lcm, $array[$i]);
    }
    return $lcm;
}

//最小公倍数
function lcm($a, $b) {
    return $a / gcd($a, $b) * $b;
}
