<?php
  fscanf(STDIN, "%d%d", $N, $Q);
  function compare($a1, $a2){
    echo "? ".$a1." ".$a2."\n";
    $s = trim(fgets(STDIN));
    return $s;
  } 
  $a = 'A';
  for($i = 0; $i < $N; $i++){
    $abc[] = $a++;
  }
  if($N == 26){
    $sorted = [];
    $sorted[] = array_shift($abc);
    while($abc){
      $a = array_shift($abc);
      $min = 0;
      $max = count($sorted);
      while(true){
        $mid = floor(($min+$max) / 2);
        if(compare($sorted[$mid], $a) == "<"){
          $min = $mid+1;
        }else{
          $max = $mid;
        }
        if($max == $min){
          array_splice($sorted, $max, 0, [$a]);
          break;
        }
      }
    }
    echo "! ".implode("", $sorted),"\n";
  }else{
    $candidate = [];
    for($i = 0; $i < 5 ** 5; $i++){
      $ii = $i;
      for($j = 0; $j < 5; $j++){
        $d[$j] = $ii % 5;
        $ii/=5;
      }
      if(count(array_unique($d)) == 5){
        $candidate[] = $d;
      }
    }
    $comp = [];
    for($i = 0; $i < 5; $i++){
      for($j = $i+1; $j < 5; $j++){
        $comp[] = [$i, $j];
      }
    }
    while(count($candidate) > 1){
      $min = 120;
      foreach($comp as $i => $c){
        $max = maxCand($candidate, $c);
        if($max < $min){
          $min = $max;
          $query = $c;
          $pos = $i;
        }
      }
      unset($comp[$pos]);
      $ans =  compare($abc[$query[0]], $abc[$query[1]]);
      $candidate = filterByComp($candidate, $query, $ans);
    }
    echo "! ";
    foreach($candidate[0] as $c){
      echo $abc[$c];
    }

  }
  function maxCand($cand, $cmp){
    $a=$b=0;
    foreach($cand as $c){
      $a+=array_search($cmp[0], $c) < array_search($cmp[1], $c);
      $b+=array_search($cmp[0], $c) > array_search($cmp[1], $c);
    }
    $max = max($a,$b);
    return $max;
  }
  function filterByComp($cand, $cmp, $ans){
    $filtered = [];
    foreach($cand as $i => $c){
      if($ans == ">"){
        if(array_search($cmp[1], $c) < array_search($cmp[0], $c)){
          $filtered[] = $c;
        }
      }else{
        if(array_search($cmp[0], $c) < array_search($cmp[1], $c)){
          $filtered[] = $c;
        }
      }
    }
    return $filtered;
  }
