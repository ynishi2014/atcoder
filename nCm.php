<?php
define("MOD", 10**9+7);
fscanf(STDIN, "%d%d", $n, $k);
 
nCm($n);//パスカルの三角形を構築
for($i=1; $i <= $k; $i++){
  echo nCm($n-$k+1, $i) * nCm($k-1, $i-1) % MOD, "\n";
}
 
function nCm($n, $m = 0){
  static $pascal;
  if(!isset($pascal)){
    $pascal[0][0] = 1;
    for($j = 1; $j <= $n; $j++){
      for($i = 0; $i <= $j; $i++){
        $pascal[$j][$i] = (($pascal[$j-1][$i-1] ?? 0) + ($pascal[$j-1][$i] ?? 0)) % MOD;
      }
    }
  }
  return $pascal[$n][$m];
}
