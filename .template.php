<?php
$n = str();
o($n);

function str(){
  return trim(fgets(STDIN));
}
function ints(){
  return array_map("intval", explode(" ", trim(fgets(STDIN))));
}
function int(){
  return intval(trim(fgets(STDIN)));
}

function o(...$val){
    if(count($val)==1)$val = array_shift($val);
    $trace = debug_backtrace();
    echo $trace[0]['line'].")";
    if(is_array($val)){
        if(count($val) == 0){
            echo "empty array";
        }elseif(!is_array(current($val))){
            echo "array:";
            echo implode(" ", $val)."\n";
        }else{
            echo "array:array\n";
            foreach($val as $row){
                echo implode(" ", $row)."\n";
            }
        }
    }else{
        echo $val."\n";
    }
}
