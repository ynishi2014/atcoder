<?php
// 0オリジンに書き換えました

// UnionFindTree ---
$tree = new UFT(100);

// 0,1,2 ... を使う
echo $tree->getRoot(0);
$tree->unite(0, 1);

echo $tree->getRoot(0);
echo $tree->getRoot(1);
echo $tree->getRoot(2);
$tree->unite(0, 2);
echo $tree->getRoot(2);


class UFT {
  public $parent;
  public $size;
  public $rank;
  public $elements;

  public function __construct($size) {
    // 添字を 0〜$size-1 にする
    $this->size   = array_fill(0, $size, 1);
    $this->rank   = array_fill(0, $size, 1);
    // 親がいないことを -1 で表現（0オリジンなので0は使えるため）
    $this->parent = array_fill(0, $size, -1);
    // for($i = 0; $i < $size; ++$i)$this->elements[$i] = [$i];
  }

  public function dump() {
    o("--------------");
    o($this->parent);
    o($this->size);
    o($this->rank);
  }

  public function getElements($i) {
    return $this->elements[$this->getRoot($i)];
  }

  public function getRoot($i) {
    if ($this->parent[$i] == -1) {
      return $i;
    } else {
      return $this->getRoot($this->parent[$i]);
    }
  }

  public function unite($i, $j) {
    $rootI = $this->getRoot($i);
    $rootJ = $this->getRoot($j);
    if ($rootJ == $rootI) return false; // 元から同じグループ

    // Rank(J) > Rank(I) に揃えておく
    if ($this->rank[$rootI] > $this->rank[$rootJ]) list($rootI, $rootJ) = [$rootJ, $rootI];

    $this->parent[$rootI] = $rootJ;

    if ($this->rank[$rootI] == $this->rank[$rootJ]) {
      $this->rank[$rootJ]++;
    }

    // foreach($this->elements[$rootI] as $elem)$this->elements[$rootJ][] = $elem;
    $this->size[$rootJ] += $this->size[$rootI];
    $this->size[$rootI]  = '*'; // 不要な情報となるので潰す/デバッグ出力向けに*を入れている
    $this->rank[$rootI]  = '*'; // 同上
  }

  public function size($i) {
    return $this->size[$this->getRoot($i)];
  }

  public function isUnion($i, $j) {
    return $this->getRoot($i) == $this->getRoot($j);
  }
}
