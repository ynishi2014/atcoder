<?php
$K = int();
$S = str();
$T = str();
if(abs(strlen($S)-strlen($T))>$K){
    echo "No";exit;
}

echo levenshteinDistance($S, $T) <= $K ? "Yes" : "No";

function levenshteinDistance($str1, $str2) {
    global $K;
    $len1 = strlen($str1);
    $len2 = strlen($str2);

    // 距離を格納する2次元配列を初期化
    $dp = array();

    // 初期化: 空文字列からの変換コスト
    for ($i = 0; $i <= min($len1, $K); $i++) {
        $dp[$i][0] = $i;
    }
    for ($j = 0; $j <= min($len2, $K); $j++) {
        $dp[0][$j] = $j;
    }

    // 動的計画法で距離を計算
    for ($i = 1; $i <= $len1; $i++) {
        $dpi = &$dp[$i];
        $dpim = &$dp[$i-1];
        $sim = $str1[$i-1];
        for ($j = max(1, $i-$K), $J =  min($len2, $i+$K); $j <= $J; $j++) {
            // 文字が一致する場合はコスト0、一致しない場合はコスト1
            $cost = ($sim === $str2[$j - 1]) ? 0 : 1;

            // 挿入、削除、置換の最小コストを計算
            $dpi[$j] = min(
                ($dpim[$j]??1000000) + 1,      // 削除
                ($dpi[$j - 1]??1000000) + 1,      // 挿入
                ($dpim[$j - 1]??1000000) + $cost // 置換
            );
        }
        unset($dp[$i-1]);
    }
    // 最終的な距離を返す
    return $dp[$len1][$len2];
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
function atsu1($array){$a=array_flip($array);$fuku=array_flip($a);sort($fuku);array_unshift($fuku,0);unset($fuku[0]);$atsu=array_flip($fuku);foreach($array as $i=>$val)$array[$i]=$atsu[$val];return [$array, $fuku, $atsu];}
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
      $a--; $b--;
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
