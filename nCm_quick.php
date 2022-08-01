<?php
define("MOD", 10**9+7);

/**
 * とても高速なnCm関数です
 *  prepareModで事前計算しておき、
 *  nCmを呼び出しましょう。
 *  AtCoderの2秒制約ならN=10^6ぐらいまで通ります
 */

function prepareMod($n){
  global $f, $invf;
  $f[0] = $invf[0] = $invf[1] = 1;
  for($i = 1; $i <= $n; $i++)$f[$i] = $f[$i-1]*$i%MOD;
  for($i = 2; $i <= $n; $i++)$invf[$i] = (MOD - intdiv(MOD, $i))*$invf[MOD%$i]%MOD;
  for($i = 2; $i <= $n; $i++)$invf[$i] = $invf[$i]*$invf[$i-1]%MOD;
}
function nCm($n, $m){
  global $f, $invf;
  if($n < 0 || $n - $m < 0 || $m < 0)return 0;
  return $f[$n] * $invf[$n-$m] % MOD * $invf[$m] % MOD;
}
function nHm($n, $m){
  global $f, $invf;
  return nCm($n+$m-1, $m);
}
