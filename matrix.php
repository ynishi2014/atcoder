<?php
define("MOD", 10**9+7);
[$N, $M, $K] = ints();
$A = ints();
$G = loadGraph($N, $M, true);

// 1/2　と 1/2M　を事前に計算しておく
$inv2 = divm(1, 2);
$inv2M = divm(1, 2*$M);

// 1回分の変換行列を作る
$map = array_fill(0, $N, array_fill(0, $N, 0));
for($i = 0; $i < $N; $i++){ // 元の成分
  $map[$i][$i]+=$inv2+mulm($M-count($G[$i+1]), $inv2M);
}
for($i = 0; $i < $N; $i++){ // 渡される成分
  foreach($G[$i+1] as $next){
    $map[$i][$next-1]+=$inv2M;
  }
}
for($i = 0; $i < $N; $i++){
  for($j = 0; $j < $N; $j++){
    $map[$i][$j] %= MOD;
  }
}

// 行列累乗する
$dp = matpow($map, 29);

// 元の状態を行列にしておく
$AA = array2mat($A);

// 行列累乗したものから必要なものを掛ける
foreach(str_split(strrev(sprintf("%b", $K))) as $i => $flag){
  if($flag)$AA = matmul($dp[$i], $AA);
}

// 結果出力
foreach($AA as $a){
  echo $a[0],"\n";
}

/**
 * 配列を縦行列にする
 **/
function array2mat($array){
  foreach($array as $a){
    $AA[] = [$a];
  }
  return $AA;
}

/**
 * 行列累乗する
 */
function matpow($map, $n){
  $dp[0] = $map;
  for($i = 1; $i <= $n; $i++){
    $dp[$i] = matmul($dp[$i-1],$dp[$i-1]);
  }
  return $dp;
}

/**
 * 行列の2乗
 */
function matpow2($map){
  return matmul($map, $map);
}

/**
 * 行列の掛け算
 */
function matmul($map1, $map2){
  $N = count($map1);
  $M = count($map2[0]);
  $O = count($map2);
  $ret = array_fill(0, $N, array_fill(0, $M, 0));
  for($i = 0; $i < $N; $i++){
    $map1i = $map1[$i];
    for($j = 0; $j < $M; $j++){
      $tmp = 0;
      for($k = 0; $k < $O; ++$k){
        $tmp += ($map1i[$k] * $map2[$k][$j]) % MOD;
      }
      $ret[$i][$j] = $tmp % MOD;
    }
  }
  return $ret;
}

//足し算
function addm($a, $b){
    return ($a + $b) % MOD;
}

//引き算
function subm($a, $b){
    return ($a + MOD - $b) % MOD;
}

//掛け算
function mulm($a, $b){
    return ($a * $b) % MOD;
}

//割り算
function divm($a, $b){
    return mulm($a, powerm($b, MOD - 2));
}

//累乗
function powerm($a, $b){
    if($b == 0)return 1;
    if($b & 1)return (powerm($a, $b>>1)**2 % MOD) * $a % MOD;
    return powerm($a, $b>>1)**2 % MOD;
}

//階乗
function factorialm($a){
    if($a == 0)return 1;
    return factorialm($a - 1) * $a % MOD;
}

//順列
function nPm($n, $m){
    if($m == 0)return 1;
    return nPm($n, $m - 1) * ($n - $m + 1) % MOD;
}

//組み合わせ
function nCm($n, $m){
    $m = min($m, $n-$m);
    return divm(nPm($n, $m), factorialm($m));
}

//逆元の導出・法が素数でなくても動作する
function modinv($a, $m){
    $b = $m; $u = 1; $v = 0;
    while($b){
        $t = intdiv($a, $b);
        $a -= $t * $b; [$a, $b] = [$b, $a];
        $u -= $t * $v; [$u, $v] = [$v, $u];
    }
    $u %= $m;
    if($u < 0)$u+=$m;
    return $u;
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
