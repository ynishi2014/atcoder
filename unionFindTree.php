<?php
//1オリジンになっています

//UnionFindTree --- 
$tree = new UFT(100);

echo $tree->getRoot(1);
$tree->unite(1,2);

echo $tree->getRoot(1);
echo $tree->getRoot(2);
echo $tree->getRoot(3);
$tree->unite(1,3);
echo $tree->getRoot(3);


class UFT{
  public $parent;
  public $size;
  public $rank;
  public $elements;
  public function __construct($size){
    $this->size = array_fill(1, $size, 1);
    $this->rank = array_fill(1, $size, 1);
    $this->parent = array_fill(1, $size, 0);
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
      return $this->getRoot($this->parent[$i]);
    }
  }
  public function unite($i, $j){
    $rootI = $this->getRoot($i);
    $rootJ = $this->getRoot($j);
    if($rootJ == $rootI)return false;//元から同じグループ
    if($this->rank[$rootI] > $this->rank[$rootJ])list($rootI, $rootJ) = [$rootJ, $rootI];//Rank(J)>Rank(I)に揃えておく
    $this->parent[$rootI] = $rootJ;
    if($this->rank[$rootI] == $this->rank[$rootJ]){
        $this->rank[$rootJ]++;
    }
    //foreach($this->elements[$rootI] as $elem)$this->elements[$rootJ][] = $elem;
    $this->size[$rootJ]+=$this->size[$rootI];
    $this->size[$rootI] = '*';//不要な情報となるので潰す/デバッグ出力向けに*を入れている
    $this->rank[$rootI] = '*';//同上
  }
  public function size($i){return $this->size[$this->getRoot($i)];}
  public function isUnion($i, $j){return $this->getRoot($i) == $this->getRoot($j);}
}
