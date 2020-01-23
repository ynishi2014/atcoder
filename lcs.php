<?php
//https://atcoder.jp/contests/dp/submissions/9687959
$a = str();
$b = str();
 
echo lcstr($a, $b);

function lcstr($str1, $str2){
    global $dp,$dpf;
    lcs($str1, $str2);
    $i = strlen($str1) - 1;
    $j = strlen($str2) - 1;
    $ret = '';
    while($i >= 0 && $j >= 0){
        switch($dpf[$i][$j]){
            case 1:
                $ret .= $str1[$i];
                $i--;$j--;
                break;
            case 2:
                $i--;
                break;
            case 3:
                $j--;
                break;
        }
    }
    return strrev($ret);
}

function lcs($str1, $str2){
    global $dp,$dpf;
    $l1 = strlen($str1);
    $l2 = strlen($str2);
    $dp = array_fill(-1, $l1+1, array_fill(-1, $l2+1, 0));
    for($i = 0; $i < $l1; $i++){
        $dpb = $dp[$i-1];
        $dpi = &$dp[$i];
        $si = $str1[$i];
        $dpfi = [];
        for($j = 0; $j < $l2; $j++){
            if($si == $str2[$j]){
                $dpi[$j] = $dpb[$j-1] + 1;
                $dpfi[$j] = 1;//斜めからきた
            }elseif($dpb[$j] > $dpi[$j-1]){
                $dpi[$j] = $dpb[$j];
                $dpfi[$j] = 2;//上からきた
            }else{
                $dpi[$j] = $dpi[$j-1];
                $dpfi[$j] = 3;//左からきた
            }
        }
        $dpf[] = $dpfi;
    }
    return $dp[$l1 - 1][$l2 - 1];
}
