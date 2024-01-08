<?php
[$N, $Q] = ints();
$uft = new UFT($N);
$ret = [];
for($i = 1; $i <= $Q; $i++){
    [$a, $b, $d] = ints();
    if($uft->unite($a, $b, $d))$ret[] = $i;
}
echo implode(" ", $ret);

class UFT{
  public $parent;
  public $size;
  public $rank;
  public $elements;
  public $weights;
  public function __construct($size){
    $this->size = array_fill(1, $size, 1);
    $this->rank = array_fill(1, $size, 1);
    $this->parent = array_fill(1, $size, 0);
    $this->weight = array_fill(1, $size, 0);
    //for($i = 1; $i <= $size; ++$i)$this->elements[$i] = [$i];
  }
  public function dump(){
    o("--------------");
    o($this->parent);
    o($this->size);
    o($this->rank);
  }
  public function getElements($i){
    return $this->elements[$this->getRoot($i)];
  }
  public function getRoot($i){
    if($this->parent[$i] == 0){
      return $i;
    }else{
      $r = $this->getRoot($this->parent[$i]);
      $this->weight[$i] = $this->weight[$this->parent[$i]]+$this->weight[$i];
      return $this->parent[$i] = $r;
    }
  }
  public function unite($i, $j, $x){
    $rootI = $this->getRoot($i);
    $rootJ = $this->getRoot($j);
    if($rootJ == $rootI)return $this->weight[$j]-$this->weight[$i] == $x;//元から同じグループ
    //if($this->rank[$rootI] > $this->rank[$rootJ])[$rootI, $rootJ] = [$rootJ, $rootI];//Rank(J)>Rank(I)に揃えておく
    $this->parent[$rootI] = $rootJ;
    $this->weight[$rootI] = $this->weight[$j]-$this->weight[$i]-$x;
    if($this->rank[$rootI] == $this->rank[$rootJ])$this->rank[$rootJ]++;
    //foreach($this->elements[$rootI] as $elem)$this->elements[$rootJ][] = $elem;
    $this->size[$rootJ]+=$this->size[$rootI];
    $this->size[$rootI] = '*';//不要な情報となるので潰す/デバッグ出力向けに*を入れている
    $this->rank[$rootI] = '*';//同上
    return true;
  }
  public function size($i){return $this->size[$this->getRoot($i)];}
  public function isUnion($i, $j){return $this->getRoot($i) == $this->getRoot($j);}
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
  $G = array_fill(1, $N, []);
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
