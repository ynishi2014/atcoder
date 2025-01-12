<?php
define('MOD', 998244353);
//define('MOD', 1000000007);
define('ROOT', 15311432); // 3^119 mod 998244353
define('ROOT_INV', 469870224); // inverse of ROOT
define('ROOT_PW', 1 << 23); // 2^23
define('REMOVEZERO', false);

[$N, $K] = ints();
$f = array_fill(0, $N+2, 0);
for($i = 0; $i < $K; $i++){
    [$l, $r] = ints();
    for($j = $l; $j <=$r; $j++){
        $f[$j]++;
    }
}

$D = fpsdiv([1], fpssub([1],$f), $N+5);
echo $D[$N-1]??0;

// mod演算を行う関数
function mod($x) {
    $x %= MOD;
    return $x < 0 ? $x + MOD : $x;
}

// mod逆元を計算する関数（フェルマーの小定理を使用）
function modinv($a) {
    if ($a == 0) {
        throw new InvalidArgumentException("逆元が存在しません（a = 0）。");
    }
    return modpow($a, MOD - 2);
}

// modべき乗を計算する関数
function modpow($a, $b) {
    $res = 1;
    $a = mod($a);
    while ($b > 0) {
        if ($b % 2 == 1) {
            $res = mod($res * $a);
        }
        $a = mod($a * $a);
        $b >>= 1;
    }
    return $res;
}

// 加算
function fpsadd($A, $B) {
    $keys = array_unique(array_merge(array_keys($A), array_keys($B)));
    $C = [];
    foreach ($keys as $key) {
        $sum = mod(($A[$key] ?? 0) + ($B[$key] ?? 0));
        if ($sum) $C[$key] = $sum;
    }
    return $C;
}

// 減算
function fpssub($A, $B) {
    $keys = array_unique(array_merge(array_keys($A), array_keys($B)));
    $C = [];
    foreach ($keys as $key) {
        $sum = mod(($A[$key] ?? 0) - ($B[$key] ?? 0));
        if ($sum) $C[$key] = $sum;
    }
    return $C;
}

// スカラー倍
function fpsmulsc($A, $c) {
    $c = mod($c);
    foreach ($A as $key => $a) {
        $A[$key] = mod($a * $c);
    }
    return $A;
}

// 乗算（次数を n に制限可能）
function fpsmultiply($A, $B, $n = null) {
    $C = [];
    foreach ($A as $i => $a) {
        foreach ($B as $j => $b) {
            $k = $i + $j;
            if ($n !== null && $k >= $n) continue; // 次数を超えた場合はスキップ
            $C[$k] = ($C[$k] ?? 0) + ($a * $b);
            $C[$k] %= MOD;
        }
    }
    // 不要な0を削除
    if(REMOVEZERO){
        foreach ($C as $key => $value) {
            if ($value == 0) {
                unset($C[$key]);
            }
        }
    }
    
    return $C;
}

// 除算
function fpsdiv($A, $B, $n) {
    $invB = fpsinv($B, $n); // Bの逆数を計算
    return fpsmultiply_ntt($A, $invB, $n);
}

// 逆数（O(N log N)） - ニュートン法とNTTを使用
function fpsinv($A, $n) {
    if (!isset($A[0]) || $A[0] == 0) {
        throw new InvalidArgumentException("逆数を計算できません（A[0] が定義されていないか0です）。");
    }

    // 初期化: R_1 = 1 / A_0
    $R = [modinv($A[0])];
    $m = 1;

    while ($m < $n) {
        $m = min($m * 2, $n);

        // A truncated to 2m
        $A_truncated = array_slice($A, 0, $m, true);

        // Compute A * R using NTT-based multiplication, mod x^m
        $AR = fpsmultiply_ntt($A_truncated, $R, $m);
        
        // 2 - A * R mod x^m
        $C = [];
        for ($i = 0; $i < $m; $i++) {
            $C[$i] = ($i == 0 ? 2 : 0) - ($AR[$i] ?? 0);
            $C[$i] = mod($C[$i]);
        }
        
        // Update R = R * C mod x^m using NTT-based multiplication
        $R_new = fpsmultiply_ntt($R, $C, $m);
        
        // Truncate R_new to m
        $R = array_slice($R_new, 0, $m, true);
    }

    // Truncate R to n
    return array_slice($R, 0, $n, true);
}

// 微分
function fpsdiff($A) {
    $B = [];
    foreach ($A as $i => $a) {
        if ($i > 0) $B[$i - 1] = mod($i * $a);
    }
    return $B;
}

// 積分
function fpsint($A) {
    $B = [0]; // 積分定数（任意の値を設定可能）
    foreach ($A as $i => $a) {
        $B[$i + 1] = mod($a * modinv($i + 1));
    }
    return $B;
}

// 指数関数
function fpsexp($A, $n) {
    $R = [1]; // 初期値
    $B = [1]; // 現在の項
    for ($i = 1; $i < $n; $i++) {
        $B = fpsmultiply($B, $A, $n);
        $B = fpsmulsc($B, modinv($i));
        $R = fpsadd($R, $B);
    }
    return $R;
}

// 対数関数
function fpslog($A, $n) {
    if (!isset($A[0]) || $A[0] != 1) {
        throw new InvalidArgumentException("対数を計算するには A[0] が1である必要があります。");
    }
    $B = fpsdiff($A);
    $invA = fpsinv($A, $n);
    $C = fpsmultiply($B, $invA, $n);
    return fpsint($C);
}

// べき乗
function fpspow($A, $k, $n) {
    $logA = fpslog($A, $n);
    $kLogA = fpsmulsc($logA, $k);
    return fpsexp($kLogA, $n);
}


// NTTを実装する関数
function ntt(&$a, $invert) {
    $n = count($a);
    // ビット反転順に並べ替え
    for ($i = 1, $j = 0; $i < $n; $i++) {
        $bit = $n >> 1;
        while ($j >= $bit) {
            $j -= $bit;
            $bit >>= 1;
        }
        if ($j < $bit) {
            $j += $bit;
        }
        if ($i < $j) {
            list($a[$i], $a[$j]) = array($a[$j], $a[$i]);
        }
    }

    // NTTのメインループ
    for ($len = 2; $len <= $n; $len <<= 1) {
        $wlen = $invert ? modpow(ROOT_INV, MOD / $len) : modpow(ROOT, MOD / $len);
        for ($i = 0; $i < $n; $i += $len) {
            $w = 1;
            for ($j = 0; $j < $len / 2; $j++) {
                $u = $a[$i + $j];
                $v = mod($a[$i + $j + $len / 2] * $w);
                $a[$i + $j] = mod($u + $v);
                $a[$i + $j + $len / 2] = mod($u - $v);
                $w = mod($w * $wlen);
            }
        }
    }

    if ($invert) {
        $n_inv = modinv($n);
        for ($i = 0; $i < $n; $i++) {
            $a[$i] = mod($a[$i] * $n_inv);
        }
    }
}

// 高速な乗算（NTTを使用） - 修正版
function fpsmultiply_ntt($A, $B, $n = null) {
    // ポリゴンのサイズを調整
    $deg_A = count($A) > 0 ? max(array_keys($A)) : 0;
    $deg_B = count($B) > 0 ? max(array_keys($B)) : 0;
    if ($n === null) {
        $n = $deg_A + $deg_B + 1;
    }
    $size = 1;
    while ($size < $n) {
        $size <<= 1;
    }
    $size <<= 1; // 次のべき乗

    // ゼロパディング
    $fa = array_fill(0, $size, 0);
    $fb = array_fill(0, $size, 0);
    foreach ($A as $k => $v) {
        if ($k < $size) {
            $fa[$k] = $v;
        }
    }
    foreach ($B as $k => $v) {
        if ($k < $size) {
            $fb[$k] = $v;
        }
    }

    // NTT変換
    ntt($fa, false);
    ntt($fb, false);

    // 係数の掛け合わせ
    for ($i = 0; $i < $size; $i++) {
        $fa[$i] = mod($fa[$i] * $fb[$i]);
    }

    // 逆NTT
    ntt($fa, true);

    // 結果の切り捨て
    $C = [];
    for ($i = 0; $i < $n && $i < count($fa); $i++) {
        $c = mod($fa[$i]);
        if ($c !== 0) {
            $C[$i] = $c;
        }
    }
    return $C;
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
  $G = array_fill(0, $N, []);
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

