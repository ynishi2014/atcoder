<?php
$n = int();
o([1=>2,3=>4]);
o([1,2]);
o([[1,2],2=>[3,5=>4]]);

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
            echo "array:";
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
