<?php

// ノードクラス：各ノードは値、ランダムな優先度、左右の子を持つ
class TreapNode {
  public $value;
  public $priority;
  public $left;
  public $right;

  public function __construct($value) {
    $this->value = $value;
    // 優先度はランダムな値（mt_rand() を利用）
    $this->priority = mt_rand();
    $this->left = null;
    $this->right = null;
  }
}

// 平衡性を保つトレイプによる順序付き集合クラス
class OrderedSet implements IteratorAggregate {
  private $root;
  private $cmp; // 値の比較関数。デフォルトは数値や文字列の比較
  private $count;

  public function __construct(callable $cmp = null) {
    $this->root = null;
    $this->count = 0;
    if ($cmp === null) {
      $this->cmp = function ($a, $b) {
        if ($a == $b) return 0;
        return ($a < $b) ? -1 : 1;
      };
    } else {
      $this->cmp = $cmp;
    }
  }

  // IteratorAggregate インターフェイスの実装（中順走査でソート済みの順番にアクセス可能）
  public function getIterator() {
    $result = [];
    $this->inorderTraversal($this->root, $result);
    return new ArrayIterator($result);
  }

  // 中順走査（inorder traversal）
  private function inorderTraversal($node, &$result) {
    if ($node === null) return;
    $this->inorderTraversal($node->left, $result);
    $result[] = $node->value;
    $this->inorderTraversal($node->right, $result);
  }

  // 要素数を返す
  public function size() {
    return $this->count;
  }

  // 値が存在するかどうかを調べる（単純な二分探索木の探索）
  public function contains($value) {
    $node = $this->root;
    $cmp = $this->cmp;
    while ($node !== null) {
      $d = $cmp($value, $node->value);
      if ($d === 0) {
        return true;
      } elseif ($d < 0) {
        $node = $node->left;
      } else {
        $node = $node->right;
      }
    }
    return false;
  }

  // 値を挿入（重複する値は挿入しない）
  public function insert($value) {
    if ($this->contains($value)) {
      return false;
    }
    $this->root = $this->insertNode($this->root, $value);
    $this->count++;
    return true;
  }

  // 再帰的にノードを挿入し、必要に応じて回転で平衡性を保つ
  private function insertNode($node, $value) {
    if ($node === null) {
      return new TreapNode($value);
    }
    $cmp = $this->cmp;
    if ($cmp($value, $node->value) < 0) {
      $node->left = $this->insertNode($node->left, $value);
      if ($node->left->priority < $node->priority) {
        $node = $this->rotateRight($node);
      }
    } else { // ($value > $node->value) – 重複は既にチェックしているので
      $node->right = $this->insertNode($node->right, $value);
      if ($node->right->priority < $node->priority) {
        $node = $this->rotateLeft($node);
      }
    }
    return $node;
  }

  // 右回転（rotate right）
  private function rotateRight($node) {
    $left = $node->left;
    $node->left = $left->right;
    $left->right = $node;
    return $left;
  }

  // 左回転（rotate left）
  private function rotateLeft($node) {
    $right = $node->right;
    $node->right = $right->left;
    $right->left = $node;
    return $right;
  }

  // 指定した値を削除。存在すれば true、存在しなければ false
  public function erase($value) {
    if (!$this->contains($value)) {
      return false;
    }
    $this->root = $this->eraseNode($this->root, $value);
    $this->count--;
    return true;
  }

  // 再帰的にノードを削除（両側の子が存在する場合は優先度に基づいて回転しながら削除）
  private function eraseNode($node, $value) {
    if ($node === null) return null;
    $cmp = $this->cmp;
    if ($cmp($value, $node->value) < 0) {
      $node->left = $this->eraseNode($node->left, $value);
    } elseif ($cmp($value, $node->value) > 0) {
      $node->right = $this->eraseNode($node->right, $value);
    } else {
      // 削除するノードが見つかった場合
      if ($node->left === null) {
        return $node->right;
      } elseif ($node->right === null) {
        return $node->left;
      } else {
        // 両方の子がある場合、優先度の小さいほう側に回転して削除を続行
        if ($node->left->priority < $node->right->priority) {
          $node = $this->rotateRight($node);
          $node->right = $this->eraseNode($node->right, $value);
        } else {
          $node = $this->rotateLeft($node);
          $node->left = $this->eraseNode($node->left, $value);
        }
      }
    }
    return $node;
  }

  // lower_bound: 指定した値以上となる最小の値を返す（なければ null）
  public function lower_bound($value) {
    $node = $this->root;
    $result = null;
    $cmp = $this->cmp;
    while ($node !== null) {
      if ($cmp($node->value, $value) >= 0) {
        $result = $node->value;
        $node = $node->left;
      } else {
        $node = $node->right;
      }
    }
    return $result;
  }

  // upper_bound: 指定した値より大きい最小の値を返す（なければ null）
  public function upper_bound($value) {
    $node = $this->root;
    $result = null;
    $cmp = $this->cmp;
    while ($node !== null) {
      if ($cmp($node->value, $value) > 0) {
        $result = $node->value;
        $node = $node->left;
      } else {
        $node = $node->right;
      }
    }
    return $result;
  }

  public function prev_bound($value) {
    $node = $this->root;
    $result = null;
    $cmp = $this->cmp;
    while ($node !== null) {
      // 現在のノードの値が $value より小さいなら候補に更新し、
      // もっと大きな値を求めるため右の子に進む
      if ($cmp($node->value, $value) < 0) {
        $result = $node->value;
        $node = $node->right;
      } else {
        // $node->value が $value 以上なら、左側に小さい値があるかもしれないので左に進む
        $node = $node->left;
      }
    }
    return $result;
  }
}

$set = new OrderedSet();

fscanf(STDIN, "%d%d", $L, $Q);
$set->insert(0);
$set->insert($L);
for ($i = 0; $i < $Q; $i++) {
  fscanf(STDIN, "%d%d", $c, $x);
  if ($c == 1) {
    $set->insert($x);
  } else {
    echo $set->lower_bound($x) - $set->prev_bound($x), "\n";
  }
}
