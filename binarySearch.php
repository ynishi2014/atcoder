<?php

//昇順に整列した配列に対する操作
//$key以上の値のうち、一番左の値のIndexを返す
function lower_bound($array, $key){
    $min = -1;
    $max = count($array);
    while($max - $min > 1){
        $c = intdiv($min + $max, 2);
        if($array[$c] >= $key){
            $max = $c;
        }else{
            $min = $c;
        }
    }
    return $max;
}


//二分探索　target以下で最大の値の場所を返す
function bsl($nums, $target) {
    $size = count($nums);
    $min = 0; 
    $max = $size - 1;
    while(true){
        $center = floor(($min + $max) / 2);
        $centerValue = $nums[$center];
        if($target == $centerValue){
            return $center;
        }elseif($target > $centerValue){
            $min = $center + 1;
        }else{
            $max = $center;
        }
        if($min == $max){
            $center = floor(($min + $max) / 2);
            $centerValue = $nums[$center];
            if($centerValue == $target){
                return $center;
            }elseif($centerValue < $target){
                return $center;
            }else{
                return $center-1;
            }
        }
    }
}

//二分探索　要素の場所を返す　存在しなければ-1を返す
function bsr($nums, $target) {
    $size = count($nums);
    $min = 0; 
    $max = $size - 1;
    while($max >= $min){
        $center = floor(($min + $max) / 2);
        $centerValue = $nums[$center];
        if($target < $centerValue){
            $max = $center - 1;
        }elseif($target > $centerValue){
            $min = $center + 1;
        }else{
            return $center;
        }
    }
    return -1;
}
