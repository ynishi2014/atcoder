<?php
  /**
   * 029 - Long Bricks（★5）
   * https://atcoder.jp/contests/typical90/tasks/typical90_ac
   */
  [$w,$n] = array_map('intval', explode(' ', trim(fgets(STDIN))));
  $N=1;
  while ($N <= $w) {
    $N *= 2;
  }
  $data = array_fill(0, $N*2, 0);
  $lazy = array_fill(0, $N*2, false);
  
  ob_start();
  while($n--){
    [$l,$r] = array_map('intval', explode(' ', trim(fgets(STDIN))));
    $max = query($l,$r+1);
    update($l,$r+1,$max+1);
    echo $max+1,"\n";
  }

  function gindex($L, $R){ global $N;
    $lm = intdiv($L, $L&-$L) >> 1;
    $rm = intdiv($R, $R&-$R) >> 1;
    $ret = [];
    while($L<$R){
      if($R <= $rm)$ret[] = $R;
      if($L <= $lm&&$L>0)$ret[] = $L;
      $L>>=1;$R>>=1;
    }
    while($L){
      $ret[] = $L;
      $L>>=1;
    }
    return $ret;
  }
  function propagate($ids){ global $lazy,$data;
    for($j = count($ids)-1; $j >= 0; --$j){
      $i = $ids[$j];
      $lazyim = $lazy[$i-1]; 
      if($lazyim===false)continue;
      $lazy[($i<<1)-1] = $lazyim;
      $data[($i<<1)-1] = $lazyim;
      $lazy[$i<<1] = $lazyim;
      $data[$i<<1] = $lazyim;
      $lazy[$i-1] = false;
    }
  }
  function update($l, $r, $x){ global $N, $data, $lazy;
    $l+=$N;$r+=$N;
    $ids = gindex($l, $r);
    propagate($ids);
    while($l < $r){
      if($r&1){ --$r; $lazy[$r-1] = $x; $data[$r-1] = $x;}
      if($l&1){ $lazy[$l-1] = $x; $data[$l-1] = $x; ++$l;}
      $l>>=1;$r>>=1;
    }
    foreach($ids as $id)$data[$id-1] = max($data[($id<<1)-1], $data[($id<<1)]);
  }
  function query($l, $r){ global $N, $data;
    $l+=$N;$r+=$N;
    propagate(gindex($l,$r));
    $s = 0;
    while($l < $r){
      if($r&1){--$r; $s = max($s, $data[$r-1]);}
      if($l&1){$s = max($s, $data[$l-1]); ++$l;}
      $l >>= 1; $r >>= 1;
    }
    return $s;
  }
