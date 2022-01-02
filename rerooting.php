<?php
// https://github.com/key-moon/Library/blob/master/src/Algorithm/rerooting.csx をPHPに写経したものです
// 使用例: https://atcoder.jp/contests/abc220/submissions/28287768

class ReRooting{
  public int $nodeCount;
  public $adjacents;
  public $indexForAdjacents;
  public $res;
  public $dp;
  public $identity;
  public $operate;
  public $operateNode;
  public function __construct(int $nodeCount, array $edges, $identity, $operate, $operateNode){
    $this->nodeCount = $nodeCount;
    $this->identity = $identity;
    $this->operate = $operate;
    $this->operateNode = $operateNode;
    $adjacents = array_fill(0, $nodeCount, []);
    $indexForAdjacents = array_fill(0, $nodeCount, []);
    foreach($edges as [$f, $t]){
      $indexForAdjacents[$f][] = count($adjacents[$t]);
      $indexForAdjacents[$t][] = count($adjacents[$f]);
      $adjacents[$f][] = $t;
      $adjacents[$t][] = $f;
    }
    $this->dp = array_fill(0, $nodeCount, []);
    $this->res = array_fill(0, $nodeCount, false);
    $this->adjacents = $adjacents;
    $this->indexForAdjacents = $indexForAdjacents;
    if($nodeCount > 1)$this->initialize();
    elseif($nodeCount == 1)$this->res[0] = $operateNode($this->identity, 0);
  }
  public function query($node){return $this->res[$node];}
  public function initialize():void{
    #region speedUP
    $dp = $this->dp;
    $res = $this->res;
    $adjacents = $this->adjacents;
    $indexForAdjacents = $this->indexForAdjacents;
    $operate = $this->operate;
    $operateNode = $this->operateNode;
    $identity = $this->identity;
    #endregion

    #region InitOrderedTree
    $parents = array_fill(0, $this->nodeCount, 0);
    $order = array_fill(0, $this->nodeCount, 0);
    $index = 0;
    $stack = [0];
    $parents[0] = -1;
    while($stack){
      $node = array_pop($stack);
      $order[$index++] = $node;
      foreach($this->adjacents[$node] as $adjacent){
        if($adjacent == $parents[$node])continue;
        $stack[] = $adjacent;
        $parents[$adjacent] = $node;
      }
    }
    #endregion

    #region fromLeaf
    for($i = count($order) - 1; $i >= 1; --$i){
      $node = $order[$i];
      $parent = $parents[$node];
      $accum = $identity;
      $parentIndex = -1;
      foreach($adjacents[$node] as $j => $adjacent){
        if($adjacent == $parent){
          $parentIndex = $j;
          continue;
        }
        $accum = $operate($accum, $dp[$node][$j]);
      }
      $dp[$parent][$indexForAdjacents[$node][$parentIndex]] = $operateNode($accum, $node);
    }
    #endregion

    #region toLeaf
    foreach($order as $node){
      $accum = $identity;
      $accumFromTail = array_fill(0, count($adjacents[$node]), $identity);
      for($j = count($accumFromTail) - 1; $j >= 1; --$j)$accumFromTail[$j - 1] = $operate($dp[$node][$j], $accumFromTail[$j]);
      for($j = 0; $j < count($accumFromTail); ++$j){
        $dp[$adjacents[$node][$j]][$indexForAdjacents[$node][$j]] = $operateNode($operate($accum, $accumFromTail[$j]), $node);
        $accum = $operate($accum, $dp[$node][$j]);
      }
      $res[$node] = $operateNode($accum, $node);
    }
    #endregion
    $this->res = $res;
    $this->dp = $dp;
  }
}
