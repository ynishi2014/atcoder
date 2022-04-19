<?php

[$H, $W] = ints();
$graph = array_fill(0, $H*$W+2, []);
for($y = 0; $y < $H; $y++)$S[] = str_split(str());
$ballCount = 0;
$baseScore = 0;
for($y = 0; $y < $H; $y++){
  for($x = 0; $x < $W; $x++){
    if($S[$y][$x] == "o"){
      $graph[0][$y*$W+$x+2] = [0, 1];
      $ballCount++;
      $baseScore+=10000-$x-$y;
    }
    if($x>0){
      if($S[$y][$x] != "#" && $S[$y][$x-1] != "#"){
        $graph[$y*$W+($x-1)+2][$y*$W+$x+2] = [0,10000];
      }
    }
    if($y>0){
      if($S[$y][$x] != "#" && $S[$y-1][$x] != "#"){
        $graph[($y-1)*$W+$x+2][$y*$W+$x+2] = [0,10000];
      }
    }
    if($S[$y][$x] != "#"){
      $graph[$y*$W+$x+2][1] = [10000-($y+$x), 1];
    }
  }
}
echo $baseScore-minCost(0,1,$ballCount);

function minCost($from, $goal, $cap){
    global $graph, $pathArray;
    $cost = 0;
    while($ret = getBestPath($from, $goal)){
        [$path, $capacity, $c] = $ret;
        $flow = min($cap, $capacity);
        $cap-=$flow;
        $cost+=$flow*$c;
        for($i = 0; $i < count($path) - 1; $i++){
            $graph[$path[$i]][$path[$i+1]][1]-=$flow;
            if(!isset($graph[$path[$i+1]][$path[$i]])){
                $graph[$path[$i+1]][$path[$i]] = [-$graph[$path[$i]][$path[$i+1]][0], 0]; // 逆辺がなければ張る
            }
            if($graph[$path[$i]][$path[$i+1]][1] == 0){
                unset($graph[$path[$i]][$path[$i+1]]); // 容量ゼロなら消す
            }
            $graph[$path[$i+1]][$path[$i]][1]+=$flow; // 逆辺の容量を増やす
        }
        
        if($cap == 0)return $cost;
    }
}
function getBestPath($from, $goal){
    global $distance, $graph;
    $pq = new SPLPriorityQueue();
    $pq->insert($from, 0);
    $cap[$from] = PHP_INT_MAX;
    $cost[$from] = 0;
    $path[$from] = [$from];
    while($pq->count()){
        $node = $pq->extract();
        foreach($graph[$node] as $next => [$cost2, $cap2]){
            if(($cost[$next]??PHP_INT_MAX) > $cost[$node] + $cost2){
                $cap[$next] = min($cap[$node], $cap2);
                $cost[$next] = $cost[$node] + $cost2;
                $p = $path[$node];
                $p[] = $next;
                $path[$next] = $p;
                $pq->insert($next, -$cost[$next]);
            }
        }
    }
    if(!isset($path[$goal])){
        throw new Exception("");
    }
    return [$path[$goal], $cap[$goal], $cost[$goal]];
}


function str(){return trim(fgets(STDIN));}
function ints($n = false){
  if($n===false){
    $str = trim(fgets(STDIN));if($str == '')return [];
    return array_map('intval',explode(' ',$str));
  }else{$ret = [];for($i = 0; $i < $n; $i++)foreach(array_map('intval',explode(' ',trim(fgets(STDIN)))) as $j => $v)$ret[$j][] = $v;return $ret;}
}
function int(){return intval(trim(fgets(STDIN)));}
function chmax(&$a,$b){if($a<$b){$a=$b;return 1;}return 0;}
function chmin(&$a,$b){if($a>$b){$a=$b;return 1;}return 0;}
function popcount($x){$c=0;while($x){$x&=$x-1;++$c;}return$c;}
function swap(&$a,&$b){$tmp=$a;$a=$b;$b=$tmp;}
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
function median($a){sort($a);$n=count($a);return $n%2?$a[($n-1)/2]:($a[$n/2-1]+$a[$n/2])/2;}
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
        $G[$b][] = $a;
        $G[$a][] = $b;
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
