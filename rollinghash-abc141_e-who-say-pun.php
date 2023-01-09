<?php
// https://atcoder.jp/contests/abc141/tasks/abc141_e
$N = int();
$S = str();
for($i = 0; $i < 26; $i++){
  $dict[chr(ord('a')+$i)] = rand(0, 2**31);
}
$A = 2**31-29871; // もっと大きい値を使いたいが、2個かけ合わせて2*63以上になると壊れるので我慢
$MOD = 2**31-12397; // 同上
$tmp = 1;

//$A = 10; $dict["A"]=1; $dict["a"]=1; $dict["b"]=2;

for($i = 0; $i < $N; $i++){
  $RUI[] = $tmp = $tmp * $A % $MOD;
}

for($i = 1; $i < $N; $i *= 2);
$ng = $i;
$ok = 0;
while($ng - $ok > 1){
  $mid = ($ng+$ok)/2;
  if(isOK($mid)){
    $ok = $mid;
  }else{
    $ng = $mid;
  }
}
echo $ok,"\n";

function isOK($len){
  global $N, $S, $A, $MOD, $RUI, $dict;
  if($len == 0)return true;
  if($len > $N/2)return false;
  $tmp = 0;
  for($i = 0; $i < $len; $i++){
    $tmp*=$A;
    $tmp+=$dict[$S[$i]];
    $tmp%=$MOD;
  }
  $ary[] = $tmp;
  for($i = $len; $i < $N; $i++){
    $tmp*=$A;
    $tmp+=$dict[$S[$i]];
    $tmp-=$dict[$S[$i-$len]]*$RUI[$len-1];
    $tmp%=$MOD;$tmp+=$MOD;$tmp%=$MOD;
    $ary[] = $tmp;
  }
  foreach($ary as $i => $v){
    if($i >= $len){
      $map[$ary[$i-$len]] = true;
    }
    if(isset($map[$v])){
      return true;
    }
  }
  return false;
}

exit;

// iがNの場合の正順と逆順のハッシュ値を求める
$baseVal = 0;
for($i = 0; $i < $N; $i++)$baseVal = ($baseVal + $dict[$S[$i]]) * $A % $MOD;


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
function isqrt($n):int{$res=intval(sqrt($n))+1;while($res*$res>$n)$res--;return $res;}
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
