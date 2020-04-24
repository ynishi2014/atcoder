<?php
echo lis([1,2,3,4,4,3], false, true);

/**
 * 単調増加列
 * @param $A 配列
 * @param $kougi true：広義 false：狭義
 * @param $inv true：減少列
 **/
function lis($A, $kougi = false, $inv = false){
    if($inv)foreach($A as $i => $a)$A[$i] = -$a;
    $dp = array_fill(0, count($A)+1, PHP_INT_MAX);
    $dp[0] = PHP_INT_MIN;
    $longest = 0;
    foreach($A as $a){
        $b = lower_bound($dp, $a+$kougi);
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
