<?php

for($i = 1; $i <= $N; $i++){
  for($j = 1; $j <= $N; $j++){
    $d[$i][$j] = 10**10;
  }  
}
 
for($i = 0; $i < $M; $i++){
  fscanf(STDIN, "%d%d%d", $a, $b, $c);
  $d[$a][$b] = $c;
  $d[$b][$a] = $c;
}
 
for($k = 1; $k <= $N; $k++){
  for($i = 1; $i <= $N; $i++){
    for($j = 1; $j <= $N; $j++){
      if($d[$i][$j] > $d[$i][$k] + $d[$k][$j]){
        $d[$i][$j] = $d[$i][$k] + $d[$k][$j];
      }
    }
  }
}
