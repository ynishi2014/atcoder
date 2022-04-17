<?php
[$N, $G, $E] = ints();
$P = ints();
[$A, $B] = ints($E);

$graph = array_fill(1, $N, []);
for($i = 0; $i < $E; $i++){
    $graph[$A[$i]][$B[$i]] = 1;
    $graph[$B[$i]][$A[$i]] = 1;
}
for($i = 0; $i < $G; $i++){
    $graph[$P[$i]][$N] = 1;
//    $graph[$N][$P[$i]] = 1;
}

echo maxFlow(0, $N), "\n";

//https://atcoder.jp/contests/abc010/submissions/18064988

function maxFlow($from, $goal){
    global $done, $graph;
    $flow = 0;
    while($ret = dfs(-1, $from, $goal)){
        [$path, $capacity] = $ret;
        $flow += $capacity;
        for($i = 0; $i < count($path) - 1; $i++){
            $graph[$path[$i]][$path[$i+1]]-=$capacity;
            if($graph[$path[$i]][$path[$i+1]] == 0){
                unset($graph[$path[$i]][$path[$i+1]]); // 容量ゼロなら消す
            }
            if(!isset($graph[$path[$i+1]][$path[$i]])){
                $graph[$path[$i+1]][$path[$i]] = 0; // 逆辺がなければ張る
            }
            $graph[$path[$i+1]][$path[$i]]+=$capacity; // 逆辺の容量を増やす
        }
        $done = [0=>true];
    }
    return $flow;
}
function dfs($from, $current, $goal, $path = [], $min = 10**18){
    global $done, $graph;
    $path[] = $current;
    if($current == $goal)return [$path, $min];
    foreach($graph[$current] as $to => $capacity){
        if(!isset($done[$to])){
            $done[$to] = true;
            $ret = dfs($current, $to, $goal, $path, min($min, $capacity));
            if($ret){
                return $ret;
            }
        }
    }
    return false;
}

function str(){return trim(fgets(STDIN));}
function ints($n = false){
  if($n===false){
    return array_map('intval',explode(' ',trim(fgets(STDIN))));
  }else{$ret = [];for($i = 0; $i < $n; $i++)foreach(array_map('intval',explode(' ',trim(fgets(STDIN)))) as $j => $v)$ret[$j][] = $v;return $ret;}
}
function int(){return intval(trim(fgets(STDIN)));}
function chmax(&$a,$b){if($a<$b){$a=$b;return 1;}return 0;}
function chmin(&$a,$b){if($a>$b){$a=$b;return 1;}return 0;}
function popcount($x){$c=0;while($x){$x&=$x-1;++$c;}return$c;}
function o(...$val){
    if(count($val)==1)$val=array_shift($val);
    echo debug_backtrace()[0]['line'].")";
    if(is_array($val)){
        if(count($val) == 0)echo "empty array\n";
        elseif(!is_array(current($val)))echo "array: ",implode(" ", addIndex($val)),"\n";
        else{
            echo "array:array\n";
            if(isCleanArray($val))foreach($val as $row)echo implode(" ", addIndex($row)),"\n";
            else foreach($val as $i => $row)echo "[".$i."] ".implode(" ", addIndex($row)),"\n";
        }
    }else echo $val."\n";
}
function addIndex($val){if(!isCleanArray($val)){$val = array_map(function($k, $v){return $k.":".$v;}, array_keys($val), $val);}return $val;}
function isCleanArray($array){$clean=true;$i = 0;foreach($array as $k=>$v){if($k != $i++)$clean = false;}return $clean;}
// 座圧対象の配列 -> [圧縮された配列,復元用のMap,圧縮用のMap]
function atsu($array){$a = array_flip($array);$fuku=array_flip($a);sort($fuku);$atsu = array_flip($fuku);foreach($array as $i=>$val)$array[$i]=$atsu[$val];return [$array, $fuku, $atsu];}
function gcdAll($array){$gcd=$array[0];for($i=1,$I=count($array);$i<$I;++$i){$gcd=gcd($gcd,$array[$i]);}return $gcd;}
function gcd($m, $n){if(!$n)return $m;return gcd($n, $m % $n);}
function lcmAll($array){$lcm=$array[0];for($i=1,$I=count($array);$i<$I;++$i){$lcm=lcm($lcm,$array[$i]);}return $lcm;}
function lcm($a, $b) {return $a / gcd($a, $b) * $b;}
function loadTree($N = false){
  if($N === false)$N = $GLOBALS['N'];
  return loadGraph($N, $N-1);
}
function loadGraph($N = false, $M = false, $both = true){
  if($N === false)$N = $GLOBALS['N'];
  if($M === false)$M = $GLOBALS['M'];
  $G = array_fill(1, $N, []);
  for($i = 0; $i < $M; $i++){
    $values = ints();
    if(count($values) == 2){
      list($a, $b) = $values;
      if($both == 0){
        $G[$a][] = $b;
      }elseif($both == 1){
        $G[$a][] = $b;
        $G[$b][] = $a;
      }else{
        $G[$b][] = $a;
      }
    }else{
      list($a, $b, $d) = $values;
      if($both == 0){
        $G[$a][] = [$b, $d];
      }elseif($both == 1){
        $G[$a][] = [$b, $d];
        $G[$b][] = [$a, $d];
      }else{
        $G[$b][] = [$a, $d];
      }
    }
  }
  return $G;
}
