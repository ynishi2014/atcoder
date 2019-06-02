<?php

define("MOD", 10**9+7);

//echo addm(10**9+6,100);
//echo subm(1,2);
//echo mulm(10**9+6, 2);
echo mulm(divm(1,4),4);

function addm($a, $b){
    return ($a + $b) % MOD;
}

function subm($a, $b){
    return ($a + MOD - $b) % MOD;
}

function mulm($a, $b){
    return ($a * $b) % MOD;
}

function divm($a, $b){
    return mulm($a, powerm($b, MOD - 2));
}

function powerm($a, $b){
    if($b == 0)return 1;
    if($b == 1)return $a;
    if($b & 1)return (powerm($a, $b>>1)**2 % MOD) * $a % MOD;
    return powerm($a, $b>>1)**2 % MOD;
}
