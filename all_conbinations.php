<?php

all_conbinations(30, 8);

function all_conbinations($max, $count, $previous = 0, $array = []){
    if($count == 0){
        //echo implode(" ", $array),"\n";
    }else{
        for($i = $previous + 1, $I = $max-$count+1; $i <= $I; ++$i){
            $array[] = $i;
            all_conbinations($max, $count-1, $i, $array);
            array_pop($array);
        }
    }
}
