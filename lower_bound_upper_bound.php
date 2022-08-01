<?php
$array = [1,2,2,3,4];
$key = 2;
echo lower_bound($array, $key),"\n";
echo upper_bound($array, $key),"\n";
echo upper_bound($array, $key)-lower_bound($array, $key),"\n";

/**
 * $key以上になる一番左のIndexを返す
 */
function lower_bound($array, $key){
  $min = -1;
  $max = count($array);
  while($max - $min > 1){
    $c = $min + $max >> 1;
    if($array[$c] >= $key){
      $max = $c;
    }else{
      $min = $c;
    }
  }
  return $max;
}
/**
 * $keyを超える一番左のIndexを返す 
 */
function upper_bound($array, $key){
  $min = -1;
  $max = count($array);
  while($max - $min > 1){
    $c = $min + $max >> 1;
    if($array[$c] > $key){
      $max = $c;
    }else{
      $min = $c;
    }
  }
  return $max;
}
