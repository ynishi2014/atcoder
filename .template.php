<?php
[$N] = ints();
o($N);

function str(){return trim(fgets(STDIN));}
function ints(){return array_map('intval',explode(' ',trim(fgets(STDIN))));}
function int(){return intval(trim(fgets(STDIN)));}
function chmax(&$a,$b){if($a<$b){$a=$b;return 1;}return 0;}
function chmin(&$a,$b){if($a>$b){$a=$b;return 1;}return 0;}
function popcount($x){$c=0;while($x){$x&=$x-1;++$c;}return$c;}
function o(...$val){
    if(count($val)==1)$val=array_shift($val);
    echo debug_backtrace()[0]['line'].")";
    if(is_array($val)){
        if(count($val) == 0)echo "empty array";
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

function dfs($current, $from = -1, $d = 0){
  global $G, $distance;
  $distance[$current] = $d;
  foreach($G[$current] as $to){
    if($from != $to){
      dfs($to, $current, $d + 1);
    }
  }
}

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
      $G[$a][] = $b;
      if($both)$G[$b][] = $a;
    }else{
      list($a, $b, $d) = $values;
      $G[$a][] = [$b, $d];
      if($both)$G[$b][] = [$a, $d];
    }
  }
  return $G;
}
