<?php
$atsuArray = atsu([1,100,2,10,100]);
print_r($atsuArray);

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
