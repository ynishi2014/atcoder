<?php
//最長共通部分列問題 
$str1 = "aaaaaaa";
$str2 = "abcdabc";

echo lcs($str1, $str2);
function lcs($str1, $str2){
    for($i = 0; $i < strlen($str1); $i++){
        for($j = 0; $j < strlen($str2); $j++){
            if($str1[$i] == $str2[$j]){
                $dp[$i][$j] = ($dp[$i-1][$j-1]??0) + 1;
            }else{
                $dp[$i][$j] = max($dp[$i-1][$j]??0, $dp[$i][$j-1]??0);
            }
        }
    }
    return $dp[strlen($str1) - 1][strlen($str2) - 1];
}

function lcstr($str1, $str2){
    $l1 = strlen($str1);
    $l2 = strlen($str2);
    for($i = 0; $i < $l1; $i++){
        for($j = 0; $j < $l2; $j++){
            if($str1[$i] == $str2[$j]){
                $dp[$i][$j] = ($dp[$i-1][$j-1]??'') . $str1[$i];
            }else{
                if(strlen($dp[$i-1][$j]??'') > strlen($dp[$i][$j-1]??'')){
                    $dp[$i][$j] = $dp[$i-1][$j]??'';
                }else{
                    $dp[$i][$j] = $dp[$i][$j-1]??'';
                }
            }
        }
    }
    return $dp[$l1 - 1][$l2 - 1];
}
