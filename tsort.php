<?php

class TSORT{
    static function sort($G){
        global $__G__, $ans;
        $__G__ = $G;
        $ans = [];
        for($i = 1; $i <= count($G); $i++)TSORT::visit($i);
        return array_reverse($ans);
    }
    static function visit($i){
        global $__G__, $visited, $ans;
        if(!isset($visited[$i])){
            $visited[$i] = true;
            foreach($__G__[$i]??[] as $next){
                TSORT::visit($next);
            }
            $ans[] = $i;
        }
    }
}
