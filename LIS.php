<?php

function lis($A){
    $dp = array_fill(0, count($A)+1, PHP_INT_MAX);
    $dp[0] = PHP_INT_MIN;
    $longest = 0;
    foreach($A as $a){
        $b = lower_bound($dp, $a);
        $dp[$b] = $a;
        $longest = max($longest, $b);
    }
    return $longest;
}

function lower_bound($array, $key){
  $min = -1;
  $max = count($array);
  while($max - $min > 1){
    $c = intdiv($min + $max, 2);
    if($array[$c] >= $key){
      $max = $c;
    }else{
      $min = $c;
    }
  }
  return $max;
}
