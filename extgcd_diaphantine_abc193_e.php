<?php
//https://atcoder.jp/contests/abc193/submissions/20572210
$T = intval(fgets(STDIN));
while($T--) {
    [$X, $Y, $P, $Q] = array_map("intval", explode(" ", trim(fgets(STDIN))));
    $ans = PHP_INT_MAX;
    for($i = $X; $i < $X + $Y; $i++){
        for($j = $P; ($i == $X) ? ($j < $P + $Q) : ($j == $P); $j++){
            $list = diophantine(($X + $Y) * 2, -($P + $Q), $j - $i);
            if($list[0] !== 0) {
                $ans = min($ans, $i + ($X + $Y) * 2 * $list[1]);
            }
        }
    }
    if($ans === PHP_INT_MAX){
        echo "infinity\n";
    }else{
        echo $ans,"\n";
    }
}
function extGCD(int $a, int $b, &$p, &$q) : int{
  if($b == 0){ $p = 1; $q = 0; return $a; }
  $d = extGCD($b, $a%$b, $q, $p);
  $q -= intdiv($a, $b) * $p;
  return $d;
}
function gcdExt($a, $b){
  if(function_exists("gmp_gcdext")){
    return gmp_gcdext($a, $b);
  }else{
    $g = extGCD($a, $b, $s, $t);
    return ["g" => $g, "s" => $s, "t" => $t];
  }
}
function diophantine($a, $b, $c){
    global $buff;
    if(!isset($buff[$a][$b])){
        $gcd = gcdExt($a, $b);
        $buff[$a][$b] = $gcd;
    }else{
        $gcd = $buff[$a][$b];
    }
 
    $g = (int)$gcd["g"];
    $s = (int)$gcd["s"];
    $t = (int)$gcd["t"];
    if($c % $g !== 0){
        return [0,0,0,0];
    }
    $c2 = intdiv($c, $g);
    $x = $s * $c2;
    $z = $t * $c2;
    $w = -intdiv($b, $g);
    $y = intdiv($a, $g);
    if($x < 0){
        $bi = intdiv(-$x + abs($w) - 1, $w);
        $x += $bi * $w;
        $z += $bi * $y;
    }
    if($z < 0){
        $bi = intdiv(-$z + abs($y) - 1, $y);
        $x += $bi * $w;
        $z += $bi * $y;
    }
    if($x >= abs($w) && $z >= abs($y)){
        $bi = min(intdiv($x, $w), intdiv($z, $y));
        $x -= $w * $bi;
        $z -= $y * $bi;
    }
    return [$w, $x, $y, $z];
}
 
