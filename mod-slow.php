<?php

define("MOD", 10**9+7);

//echo addm(10**9+6,100);
//echo subm(1,2);
//echo mulm(10**9+6, 2);
//echo mulm(divm(1,4),4);

//足し算
function addm($a, $b){
    return ($a + $b) % MOD;
}

//引き算
function subm($a, $b){
    return ($a + MOD - $b) % MOD;
}

//掛け算
function mulm($a, $b){
    return ($a * $b) % MOD;
}

//割り算
function divm($a, $b){
    return mulm($a, powerm($b, MOD - 2));
}

//累乗
function powerm($a, $b){
    if($b == 0)return 1;
    if($b & 1)return (powerm($a, $b>>1)**2 % MOD) * $a % MOD;
    return powerm($a, $b>>1)**2 % MOD;
}

//階乗
function factorialm($a){
    if($a == 0)return 1;
    return factorialm($a - 1) * $a % MOD;
}

//順列
function nPm($n, $m){
    if($m == 0)return 1;
    return nPm($n, $m - 1) * ($n - $m + 1) % MOD;
}

//組み合わせ
function nCm($n, $m){
    $m = min($m, $n-$m);
    return divm(nPm($n, $m), factorialm($m));
}

//逆元の導出・法が素数でなくても動作する
function modinv($a, $m){
    $b = $m; $u = 1; $v = 0;
    while($b){
        $t = intdiv($a, $b);
        $a -= $t * $b; [$a, $b] = [$b, $a];
        $u -= $t * $v; [$u, $v] = [$v, $u];
    }
    $u %= $m;
    if($u < 0)$u+=$m;
    return $u;
}

