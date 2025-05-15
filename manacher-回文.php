<?php
$S = trim(fgets(STDIN));
print_r(manacher($S));

// 直径(回文の長さ)を返す
function manacher($S){
    $S = implode("#", str_split($S));
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
    foreach($R as $i => $r){
        if($i%2==1){
            $D[$i]=$r;
        }else{
            $D[$i]=intdiv($r-1,2)*2+1;
        }
    }
    return $D;
}
