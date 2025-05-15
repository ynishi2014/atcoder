<?php
$S = trim(fgets(STDIN));
print_r(manacher($S));

function manacher($S){
    $i = 0;
    $j = 0;
    $R = array_fill(0, strlen($S), 0);
    
    while ($i < strlen($S)) {
        while ($i - $j >= 0 && $i + $j < strlen($S) && $S[$i - $j] == $S[$i + $j]) {
            ++$j;
        }
        $R[$i] = $j;
        $k = 1;
        while ($i - $k >= 0 && $k + $R[$i - $k] < $j) {
            $R[$i + $k] = $R[$i - $k];
            ++$k;
        }
        $i += $k;
        $j -= $k;
    }
    
    return $R;
}
