<?php

function tsort(){
    global $N, $ans;
    for($i = 1; $i <= $N; $i++)visit($i);
    return $ans;
}
function visit($i){
    global $G, $visited, $ans;
    if(!isset($visited[$i])){
        $visited[$i] = true;
        foreach($G[$i] as $next){
            visit($next);
        }
        $ans[] = $i;
    }
}
