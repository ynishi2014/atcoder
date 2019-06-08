<?php
//1オリジンになっています

//UnionFindTree --- 
$tree = new UnionFindTree(100);

echo $tree->findRoot(1);
$tree->unite(1,2);

echo $tree->findRoot(1);
echo $tree->findRoot(2);
echo $tree->findRoot(3);
$tree->unite(1,3);
echo $tree->findRoot(3);


class UFT{
    public $parent;
    public $size;
    public $rank;
    public function __construct($size){
        for($i = 1; $i <= $size; $i++){
            $this->size[$i] = 1;
            $this->rank[$i] = 1;
            $this->parent[$i] = 0;
        }
    }
    public function dump(){
        o("--------------");
        o($this->parent);
        o($this->size);
        o($this->rank);
    }
    public function getRoot($i){
        if($this->parent[$i] == 0){
            return $i;
        }else{
            return $this->getRoot($this->parent[$i]);
        }
    }
    public function unite($i, $j){
        $rootI = $this->getRoot($j);
        $rootJ = $this->getRoot($i);
        if($rootJ == $rootI)return false;//元から同じグループ
        if($this->rank[$rootI] > $this->rank[$rootJ])list($rootI, $rootJ) = [$rootJ, $rootI];//Rank(J)>Rank(I)に揃えておく
        $this->parent[$rootI] = $rootJ;
        if($this->rank[$rootI] == $this->rank[$rootJ]){
            $this->rank[$rootJ]++;
        }
        $this->size[$rootJ]+=$this->size[$rootI];
        $this->size[$rootI] = '*';//不要な情報となるので潰す/デバッグ出力向けに*を入れている
        $this->rank[$rootI] = '*';//同上
    }
    public function size($i){return $this->size[$this->getRoot($i)];}
    public function isUnion($i, $j){return $this->getRoot($i) == $this->getRoot($j);}
}
