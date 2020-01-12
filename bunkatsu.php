<?php
fscanf(STDIN, "%d%d", $n, $m); //nをm分割する
echo bunkatsu($n,$m);

function bunkatsu($n, $m){
  $dp = array_fill(0,$m+1,array_fill(0,$n+1,0));
  $dp[0][0] = 1;
  for($i = 1; $i <= $m; $i++){
    for($j = 0; $j <= $n; $j++){
      if($j - $i >= 0){
        $dp[$i][$j] = ($dp[$i-1][$j] + $dp[$i][$j-$i]) % (10**9+7);
      }else{
        $dp[$i][$j] = $dp[$i-1][$j];
      }
    }
  }
  return $dp[$m][$n];
}
