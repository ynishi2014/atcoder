<?php
// https://atcoder.jp/contests/abc284/tasks/abc284_f
$N = int();
$S = str();
for($i = 0; $i < 26; $i++){
  $dict[chr(ord('a')+$i)] = rand(0, 2**31);
}
$A = 2**31-29871; // もっと大きい値を使いたいが、2個かけ合わせて2*63以上になると壊れるので我慢
$MOD = 2**31-12397; // 同上
$tmp = 1;
for($i = 0; $i < $N; $i++){
  $RUI[] = $tmp = $tmp * $A % $MOD;
}
// iがNの場合の正順と逆順のハッシュ値を求める
$baseVal = 0;
for($i = 0; $i < $N; $i++)$baseVal = ($baseVal + $dict[$S[$i]]) * $A % $MOD;
$revVal = 0;
for($i = 0; $i < $N; $i++)$revVal = ($revVal + $dict[$S[2*$N-1-$i]]) * $A % $MOD;

// 一致していたらiはN
if($baseVal==$revVal){
  echo substr($S,0,$N),"\n";;
  echo $N,"\n";
  exit;
}

// 1文字ずつずらして判定する
for($i = 0; $i < $N; $i++){
  $baseVal -= $RUI[$i] * $dict[$S[$N-$i-1]]; // 正順の前半パートの末尾を削る
  $baseVal += $RUI[$i] * $dict[$S[2*$N-$i-1]]; // 正順の後半パートに追加する
  $baseVal %= $MOD;
  $baseVal += $MOD;
  $baseVal %= $MOD;
  
  $revVal -= $RUI[$N-1] * $dict[$S[2*$N-$i-1]] % $MOD; // 逆順の末尾を削る
  $revVal *= $A; // 1文字ずらす
  $revVal %= $MOD;
  $revVal += $RUI[0] * $dict[$S[$N-$i-1]]; // 逆順の先頭に追加する
  $revVal %= $MOD;
  $revVal += $MOD;
  $revVal %= $MOD;
  if($baseVal == $revVal){
    echo substr($S, 0, $N-1-$i).substr($S, 2*$N-1-$i),"\n";
    echo $N-$i-1;
    exit;
  }
}
echo -1;

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
