<?php

function tsort($G){
    global $__G__, $ans;
    $__G__ = $G;
    $ans = [];
    for($i = 0; $i < count($G); $i++)visit($i);
    return $ans;
}
function visit($i){
    global $__G__, $visited, $ans;
    if(!isset($visited[$i])){
        $visited[$i] = true;
        foreach($__G__[$i]??[] as $next){
            visit($next);
        }
        $ans[] = $i;
    }
}
