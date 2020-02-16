<?php
/**
 * 1のビットの数を高速にカウントします
 */
function popcount($x){
    $con = 0;
    while ($x) {
        $x &= $x-1;
        ++$con;
    }
    return $con;
}
