<?php
list($N) = ints();
o($N);

function str(){
  return trim(fgets(STDIN));
}
function ints(){
  return array_map("intval", explode(" ", trim(fgets(STDIN))));
}
function int(){
  return intval(trim(fgets(STDIN)));
}
function chmax(&$a,$b){if($a<$b){$a=$b;return 1;}return 0;}
function chmin(&$a,$b){if($a>$b){$a=$b;return 1;}return 0;}

function o(...$val){
    if(count($val)==1)$val = array_shift($val);
    $trace = debug_backtrace();
    echo $trace[0]['line'].")";
    if(is_array($val)){
        if(count($val) == 0){
            echo "empty array";
        }elseif(!is_array(current($val))){
            echo "array: ";
            echo implode(" ", addIndex($val))."\n";
        }else{
            echo "array:array\n";
            if(isCleanArray($val)){
                foreach($val as $row)echo implode(" ", addIndex($row))."\n";
            }else{
                foreach($val as $i => $row)echo "[".$i."] ".implode(" ", addIndex($row))."\n";
            }
        }
    }else{
        echo $val."\n";
    }
}
function addIndex($val){
    if(!isCleanArray($val)){
        $val = array_map(function($k, $v){return $k.":".$v;}, array_keys($val), $val);
    }
    return $val;
}
function isCleanArray($array){
    $clean = true;
    $i = 0;
    foreach($array as $k => $v){
        if($k != $i++)$clean = false;
    }
    return $clean;
}
/**
 * 座圧対象の配列を渡すと以下の値を返す
 * ・圧縮された配列
 * ・復元用のMap
 * ・圧縮用のMap
 **/
function atsu($array){
    $a = array_flip($array);
    $fuku = array_flip($a);
    sort($fuku);
    $atsu = array_flip($fuku);
    foreach($array as $i => $val)$array[$i] = $atsu[$val];
    return [$array, $fuku, $atsu];
}
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
//ビットカウント-1の数を数える
function popcount($x){
    $con = 0;
    while ($x) {
        $x &= $x-1;
        ++$con;
    }
    return $con;
}
