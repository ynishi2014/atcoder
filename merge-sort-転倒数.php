<?php
$n = int();
$A = ints();

$c = 0;
$A = msort($A);
echo $c;

function msort($array){
    global $c;
    $size = count($array);
    if($size == 1){
        return $array;
    }else{
        $first = array_splice($array, 0, intdiv($size, 2));
        $last = $array;
        $first = msort($first);
        $last = msort($last);
        $i = 0; $j = 0;
        $fLen = count($first);
        $lLen = count($last);
        $ret = [];
        $last[] = 10**10;
        $first[] = 10**10;
        while($i < $fLen || $j < $lLen){
            if($first[$i] < $last[$j]){
                $c+=$j;
                $ret[] = $first[$i++];
            }else{
                $ret[] = $last[$j++];
            }
        }
        return $ret;
    }
}

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
