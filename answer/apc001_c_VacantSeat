<?php
$n = intval(fgets(STDIN));
$minRet = ask(0);
$maxRet = ask($n-1);
$min = 0;
$max = $n-1;
while($min!=$max){
    $mid = intdiv($max+$min, 2);
    $midRet = ask($mid);
    if(($midRet != $minRet) != ($mid - $min) % 2){
        $max = $mid;
        $maxRet = $midRet;
    }else{
        $min = $mid;
        $minRet = $midRet;
    }
}

function ask($a){
    //return dummyResponder($a);//通信相手のエミュレータ
    echo $a,"\n";
    flush();
    $in = trim(fgets(STDIN));
    if($in == "Vacant"){
        exit;
    }elseif($in == "Male"){
        return 1;
    }else{
        return -1;
    }
}
function dummyResponder($a){
    echo $a,"\n";
    return [1,-1,1,-1,1,-1,0,1,-1,1,-1][$a];
}

