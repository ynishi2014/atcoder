<?php

//二分探索　target以下で最大の値を返す
function bsl($nums, $target) {
    $size = count($nums);
    $min = 0; 
    $max = $size - 1;
    while(true){
        $center = floor(($min + $max) / 2);
        $centerValue = $nums[$center];
        if($target == $centerValue){
            return $centerValue;
        }elseif($target > $centerValue){
            $min = $center + 1;
        }else{
            $max = $center;
        }
        if($min == $max){
            $center = floor(($min + $max) / 2);
            $centerValue = $nums[$center];
            if($centerValue == $target){
                return $nums[$center];
            }elseif($centerValue < $target){
                return $nums[$center];
            }else{
                return $nums[$center-1] ?? -10**18;
            }
        }
    }
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

//二分探索　target以上で最小の値を返す
function bsr($nums, $target) {
    $size = count($nums);
    $min = 0; 
    $max = $size - 1;
    while(true){
        $center = floor(($min + $max) / 2);
        $centerValue = $nums[$center];
        if($target == $centerValue){
            return $centerValue;
        }elseif($target > $centerValue){
            $min = $center + 1;
        }else{
            $max = $center;
        }
        if($min == $max){
            $center = floor(($min + $max) / 2);
            $centerValue = $nums[$center];
            if($centerValue == $target){
                return $nums[$center];
            }elseif($centerValue < $target){
                return $nums[$center+1] ?? 10**18;
            }else{
                return $nums[$center];
            }
        }
    }
}

//二分探索　要素の場所を返す　存在しなければ-1を返す
function bs($nums, $target) {
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
