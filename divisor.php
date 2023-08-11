<?php

//約数
function divisor($n){
    for($i = ceil(sqrt($n)); $i >= 1; $i--){
        if($n % $i == 0){
            $f[] = $i;
            $f[] = $n / $i;
        }
    }
    sort($f);
    return array_values(array_unique($f));
}
