<?php
$primes = era(10000);
$factor = factor(10000, $primes);
var_dump($factor);
 
function factor($n, $primes){
  $map = [];
  if($n == 1)return [];
  while(true){
    if($primes[$n] <= 1){
      if(!isset($map[$n])){
        $map[$n] = 0;
      }
      $map[$n]++;
      return $map;
    }else{
      if(!isset($map[$primes[$n]])){
        $map[$primes[$n]] = 0;
      }
      $map[$primes[$n]]++;
      $n /= $primes[$n];
    }
  }
}
function era($n){
  $primes = array_fill(1, $n, 0);
  for($i = 2; $i <= $n; $i++){
    if($primes[$i] == 0){
      for($j = $i * 2; $j <= $n; $j+=$i){
        $primes[$j] = $i;
      }
    }
  }
  return $primes;
}
