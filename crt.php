<?php
$n = intval(fgets(STDIN));
for($i = 0; $i < $n; $i++){
  [$X, $Y, $P, $Q] = array_map("intval", explode(" ", trim(fgets(STDIN))));
  $m1 = ($X+$Y)*2;
  $m2 = $P+$Q;
  $min = PHP_INT_MAX;
  for($j = $X; $j < $X+$Y;$j++){
    for($k = $P; $k < $P+$Q; $k++){
      $ret = crt([$j, $k],[$m1, $m2]);
      if($ret[1] != 0 && $ret[0] < $min){
        $min = $ret[0];
      }
    }
  }
  if($min != PHP_INT_MAX){
    echo $min,"\n";
  }else{
    echo "infinity\n";
  }
  
}
//print_r(crt([1,2], [3,5]));
//print_r(inv_gcd(100,529));

function crt(array $r, array $m) : array{
  $n = count($r);
  $r0 = 0; $m0 = 1;
  for($i = 0; $i < $n; $i++){
    $r1 = mod($r[$i], $m[$i]); $m1 = $m[$i];
    if($m0 < $m1){
      [$r0, $r1] = [$r1, $r0];
      [$m0, $m1] = [$m1, $m0];
    }
    if($m0 % $m1 == 0){
      if($r0 % $m1 != $r1)return [0,0];
      continue;
    }
    [$g, $im] = inv_gcd($m0, $m1);
    $u1 = intdiv($m1, $g);
    if(($r1-$r0)%$g)return [0,0];
    $x = intdiv($r1-$r0, $g) % $u1 * $im % $u1;

    $r0 += $x * $m0;
    $m0 *= $u1;
    if($r0 < 0){
      $r0 += $m0;
    }  
  }
  return [$r0, $m0];
}
function mod($a, $m){
  return ($a % $m + $m) % $m;
}
function inv_gcd($a, $b){
  static $memo;
  if(isset($memo[$a][$b])){
    return $memo[$a][$b];
  }
  $a = mod($a, $b);
  if($a == 0)return [$b, 0];
  $s = $b; $t = $a;
  $m0 = 0; $m1 = 1;
  while($t){
    $u = intdiv($s, $t);
    $s -= $t * $u;
    $m0 -= $m1 * $u;
    $tmp = $s;
    $s = $t;
    $t = $tmp;
    $tmp = $m0;
    $m0 = $m1;
    $m1 = $tmp;
  }
  if($m0 < 0)$m0 += intdiv($b, $s);
  $memo[$a][$b] = [$s, $m0];
  return [$s, $m0];
}
