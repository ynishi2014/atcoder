<?php
//https://atcoder.jp/contests/abc073/submissions/8379970
fscanf(STDIN, "%d%d%d", $N, $M, $R);
$rArray = array_map("intval", explode(" ", trim(fgets(STDIN))));

$wf = new Wf($N);
for($i = 0; $i < $M; $i++){
  fscanf(STDIN, "%d%d%d", $a, $b, $c);
  $wf->connect($a, $b, $c);
}
$d = $wf->solve();

echo dfs($rArray, -1);
function dfs($rArray, $current){
  global $d;
  if(!$rArray)return 0;
  $min = 10**10;
  foreach($rArray as $i => $p){
    $array = $rArray;
    unset($array[$i]);
    $dist = dfs($array, $p);
    if($current != -1){
      $dist += $d[$current][$p];
    }
    if($dist < $min){
      $min = $dist;
    }
  }
  return $min;
}

/**
 * Warshall–Floyd
 * Attention: 1-origin
 **/
class Wf{
  public $d;
  public $n;
  function __construct($n)	{
    $this->n = $n;
    for($i = 1; $i <= $this->n; $i++){
      for($j = 1; $j <= $this->n; $j++){
        $this->d[$i][$j] = 10**18;
      }  
    }
  }
  function connect($a, $b, $c){
    $this->d[$a][$b] = $c;
    $this->d[$b][$a] = $c;
  }
  function solve(){
    for($k = 1; $k <= $this->n; $k++){
      for($i = 1; $i <= $this->n; $i++){
        for($j = 1; $j <= $this->n; $j++){
          if($this->d[$i][$j] > $this->d[$i][$k] + $this->d[$k][$j]){
            $this->d[$i][$j] = $this->d[$i][$k] + $this->d[$k][$j];
          }
        }
      }
    }
    return $this->d;
  }
}
