<?php
$array = ['a','b','c','d'];
do{
    var_dump(implode(",",$array));
}while(next_permutation($array));

function next_permutation(&$a){
    $n = count($a);
    for($i = $n - 2; $i >= 0; $i--)if($a[$i] < $a[$i+1])break;
    if($i < 0){
        reverse($a, 0, $n);
        return false;
    }
    $k = $i;
    for($i = $n - 1; $i >= $k + 1; $i--)if($a[$k] < $a[$i])break;
    $l = $i;
    list($a[$l], $a[$k]) = [$a[$k], $a[$l]];
    reverse($a, $k + 1, $n - ($k + 1));
    return true;
}
function reverse(&$a, $start, $size){
    $end = $start + $size - 1;
    while($start < $end){
        list($a[$start++], $a[$end--]) = [$a[$end], $a[$start]];
    }
}
