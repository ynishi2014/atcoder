<?php

function lis($A){
    $dp = array_fill(0, count($A)+1, PHP_INT_MAX);
    $dp[0] = 0;
    $longest = 0;
    foreach($A as $a){
        $b = lower_bound($dp, $a);
        $dp[$b] = $a;
        $longest = max($longest, $b);
    }
    return $longest;
}
