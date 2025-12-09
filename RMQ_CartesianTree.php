<?php

/**
 * ノード1個を表すクラス（Cartesian Tree の節）
 */
class CartesianTreeNode
{
    /** @var int 元配列におけるインデックス */
    public int $index;

    /** @var mixed 値（数値でも文字列でも可） */
    public $value;

    /** @var CartesianTreeNode|null 左の子 */
    public ?CartesianTreeNode $left = null;

    /** @var CartesianTreeNode|null 右の子 */
    public ?CartesianTreeNode $right = null;

    /** @var CartesianTreeNode|null 親ノード（必要なければ未使用でもよい） */
    public ?CartesianTreeNode $parent = null;

    /**
     * @param int   $index 元配列でのインデックス
     * @param mixed $value 値
     */
    public function __construct(int $index, $value)
    {
        $this->index = $index;
        $this->value = $value;
    }
}

/**
 * Cartesian Tree 本体
 *
 * - 元配列の順序を中順（inorder）順序とし、
 * - 値に関してヒープ条件（最小 or 最大）を満たす2分木
 */
class CartesianTree
{
    /** @var CartesianTreeNode|null ルートノード */
    public ?CartesianTreeNode $root;

    public function __construct(?CartesianTreeNode $root = null)
    {
        $this->root = $root;
    }

    /**
     * 最小ヒープ条件（親 <= 子）を満たす Cartesian Tree を構築
     *
     * @param array $values 値の配列
     * @return self
     */
    public static function buildMin(array $values): self
    {
        return self::build($values, true);
    }

    /**
     * 最大ヒープ条件（親 >= 子）を満たす Cartesian Tree を構築
     *
     * @param array $values 値の配列
     * @return self
     */
    public static function buildMax(array $values): self
    {
        return self::build($values, false);
    }

    /**
     * O(n) スタックアルゴリズムで Cartesian Tree を構築
     *
     * @param array $values
     * @param bool  $isMinHeap true: 最小ヒープ, false: 最大ヒープ
     * @return self
     */
    private static function build(array $values, bool $isMinHeap): self
    {
        $n = count($values);
        if ($n === 0) {
            return new self(null);
        }

        /** @var CartesianTreeNode[] $stack */
        $stack = [];

        for ($i = 0; $i < $n; $i++) {
            $current = new CartesianTreeNode($i, $values[$i]);
            $last = null;

            // スタック上でヒープ条件に反するノードをまとめて current の左側に付ける
            while (!empty($stack) && self::compareNodeValue(end($stack), $current, $isMinHeap) > 0) {
                $last = array_pop($stack);
            }

            // current の左の子は、ヒープ条件を破ってポップされた最後のノード
            $current->left = $last;
            if ($last !== null) {
                $last->parent = $current;
            }

            // スタック上にまだノードがあれば、その右の子として current を接続
            if (!empty($stack)) {
                $top = end($stack);
                $top->right = $current;
                $current->parent = $top;
            }

            $stack[] = $current;
        }

        // スタックの底にあるノードがルート
        $root = null;
        while (!empty($stack)) {
            $root = array_pop($stack);
        }

        return new self($root);
    }

    /**
     * ノード同士の値を比較するヘルパー
     *
     * @param CartesianTreeNode $a
     * @param CartesianTreeNode $b
     * @param bool              $isMinHeap true: a > b なら正, false: a < b なら正
     * @return int 負: a < b, 0: 等しい, 正: a > b（条件に応じて反転）
     */
    private static function compareNodeValue(CartesianTreeNode $a, CartesianTreeNode $b, bool $isMinHeap): int
    {
        if ($a->value == $b->value) {
            return 0;
        }

        // 通常の大小
        $cmp = ($a->value < $b->value) ? -1 : 1;

        // 最大ヒープの場合は反転（親 >= 子）
        return $isMinHeap ? $cmp : -$cmp;
    }

    /**
     * 中順（inorder）走査で (index => value) 配列を返す
     *
     * @return array<int,mixed>
     */
    public function inorder(): array
    {
        $result = [];
        $this->inorderRecursive($this->root, $result);
        return $result;
    }

    /**
     * 先行順（preorder）走査で (index => value) 配列を返す
     *
     * @return array<int,mixed>
     */
    public function preorder(): array
    {
        $result = [];
        $this->preorderRecursive($this->root, $result);
        return $result;
    }

    /**
     * 後行順（postorder）走査で (index => value) 配列を返す
     *
     * @return array<int,mixed>
     */
    public function postorder(): array
    {
        $result = [];
        $this->postorderRecursive($this->root, $result);
        return $result;
    }

    /**
     * ツリー構造を文字列で簡易的に可視化（デバッグ用）
     *
     * @return string
     */
    public function prettyPrint(): string
    {
        return $this->prettyPrintRecursive($this->root, 0);
    }

    // ==========================
    // 内部実装（再帰ヘルパー）
    // ==========================

    private function inorderRecursive(?CartesianTreeNode $node, array &$result): void
    {
        if ($node === null) {
            return;
        }
        $this->inorderRecursive($node->left, $result);
        $result[$node->index] = $node->value;
        $this->inorderRecursive($node->right, $result);
    }

    private function preorderRecursive(?CartesianTreeNode $node, array &$result): void
    {
        if ($node === null) {
            return;
        }
        $result[$node->index] = $node->value;
        $this->preorderRecursive($node->left, $result);
        $this->preorderRecursive($node->right, $result);
    }

    private function postorderRecursive(?CartesianTreeNode $node, array &$result): void
    {
        if ($node === null) {
            return;
        }
        $this->postorderRecursive($node->left, $result);
        $this->postorderRecursive($node->right, $result);
        $result[$node->index] = $node->value;
    }

    private function prettyPrintRecursive(?CartesianTreeNode $node, int $depth): string
    {
        if ($node === null) {
            return '';
        }

        $indent = str_repeat('  ', $depth);
        $s = $indent . "({$node->index}: {$node->value})\n";
        if ($node->left !== null) {
            $s .= $this->prettyPrintRecursive($node->left, $depth + 1);
        }
        if ($node->right !== null) {
            $s .= $this->prettyPrintRecursive($node->right, $depth + 1);
        }
        return $s;
    }
}

/**
 * Cartesian Tree を用いた Range Minimum Query 構造
 *
 * - 元配列 values から最小ヒープ Cartesian Tree を構築
 * - Euler Tour + 深さ配列を作り、そこに Sparse Table を載せる
 * - RMQ クエリは O(1)
 */
class RMQCartesianTree
{
    /** @var array<int,mixed> 元配列 */
    private array $values;

    /** @var int 要素数 */
    private int $n;

    /** @var CartesianTree 木構造（最小ヒープ） */
    private CartesianTree $tree;

    /**
     * Euler Tour（各ステップで訪れたノードの index）
     * @var int[]
     */
    private array $euler = [];

    /**
     * Euler Tour に対応する深さ
     * @var int[]
     */
    private array $depth = [];

    /**
     * 各ノード index ごとの Euler Tour における最初の出現位置
     * @var int[]
     */
    private array $firstOccurrence = [];

    /**
     * Sparse Table: $st[k][i] = euler 配列上の位置（インデックス）
     * k: 区間長 2^k
     * @var array<int,array<int,int>>
     */
    private array $st = [];

    /**
     * log2 テーブル: $log2[i] = floor(log2(i))
     * @var int[]
     */
    private array $log2 = [];

    /**
     * @param array<int,mixed> $values RMQ 対象の配列
     */
    public function __construct(array $values)
    {
        $this->values = array_values($values);
        $this->n = count($this->values);

        if ($this->n === 0) {
            throw new InvalidArgumentException('RMQCartesianTree: 空配列は扱えません');
        }

        // 1. 最小ヒープ Cartesian Tree を構築
        $this->tree = CartesianTree::buildMin($this->values);

        if ($this->tree->root === null) {
            throw new RuntimeException('CartesianTree の root が null です');
        }

        // 2. Euler Tour + depth + firstOccurrence を構築
        $this->buildEulerTour();

        // 3. Sparse Table と log2 テーブルを構築
        $this->buildSparseTable();
    }

    /**
     * 区間 [l, r] の最小値のインデックスを返す
     *
     * @param int $l 0-based, inclusive
     * @param int $r 0-based, inclusive
     * @return int 最小値のインデックス
     */
    public function rmqIndex(int $l, int $r): int
    {
        if ($l < 0 || $r < 0 || $l >= $this->n || $r >= $this->n) {
            throw new OutOfBoundsException('rmqIndex: クエリ範囲が配列外です');
        }
        if ($l > $r) {
            [$l, $r] = [$r, $l];
        }

        // RMQ(A,l,r) は Cartesian Tree 上で LCA(ノード l, ノード r) の index
        return $this->lcaIndex($l, $r);
    }

    /**
     * 区間 [l, r] の最小値そのものを返す
     *
     * @param int $l 0-based, inclusive
     * @param int $r 0-based, inclusive
     * @return mixed
     */
    public function rmqValue(int $l, int $r)
    {
        $idx = $this->rmqIndex($l, $r);
        return $this->values[$idx];
    }

    /**
     * 区間 [l, r] の最小値とそのインデックスを両方返す
     *
     * @param int $l
     * @param int $r
     * @return array{index:int,value:mixed}
     */
    public function rmq(int $l, int $r): array
    {
        $idx = $this->rmqIndex($l, $r);
        return [
            'index' => $idx,
            'value' => $this->values[$idx],
        ];
    }

    /**
     * LCA(配列インデックス i, j) のノード index を返す
     *
     * @param int $i
     * @param int $j
     * @return int
     */
    private function lcaIndex(int $i, int $j): int
    {
        $pos1 = $this->firstOccurrence[$i];
        $pos2 = $this->firstOccurrence[$j];

        if ($pos1 > $pos2) {
            [$pos1, $pos2] = [$pos2, $pos1];
        }

        // Euler Tour 上の区間 [pos1, pos2] で depth が最小となる位置を Sparse Table で取得
        $len = $pos2 - $pos1 + 1;
        $k = $this->log2[$len];

        $idx1 = $this->st[$k][$pos1];
        $idx2 = $this->st[$k][$pos2 - (1 << $k) + 1];

        $bestPos = ($this->depth[$idx1] <= $this->depth[$idx2]) ? $idx1 : $idx2;

        // bestPos は euler 配列中の位置なので、そこに記録されているノード index を返す
        return $this->euler[$bestPos];
    }

    /**
     * Euler Tour を構築
     * - euler[]  : 訪れたノードの index
     * - depth[]  : 各ステップにおける深さ
     * - firstOccurrence[index] : 各ノード index の最初の出現位置
     */
    private function buildEulerTour(): void
    {
        $this->euler = [];
        $this->depth = [];
        $this->firstOccurrence = [];

        $this->dfsEuler($this->tree->root, 0);
    }

    /**
     * 再帰 DFS による Euler Tour 構築
     *
     * @param CartesianTreeNode|null $node
     * @param int                    $d   現在の深さ
     */
    private function dfsEuler(?CartesianTreeNode $node, int $d): void
    {
        if ($node === null) {
            return;
        }

        $idx = $node->index;

        // 最初に訪れたタイミングを Euler Tour に追加
        $pos = count($this->euler);
        $this->euler[] = $idx;
        $this->depth[] = $d;

        if (!array_key_exists($idx, $this->firstOccurrence)) {
            $this->firstOccurrence[$idx] = $pos;
        }

        // 左の子へ
        if ($node->left !== null) {
            $this->dfsEuler($node->left, $d + 1);
            // 子から戻ってきたときに親をもう一度 Euler Tour に追加
            $this->euler[] = $idx;
            $this->depth[] = $d;
        }

        // 右の子へ
        if ($node->right !== null) {
            $this->dfsEuler($node->right, $d + 1);
            // 子から戻ってきたときに親をもう一度 Euler Tour に追加
            $this->euler[] = $idx;
            $this->depth[] = $d;
        }
    }

    /**
     * depth 配列に対する Sparse Table と log2 テーブルを構築
     */
    private function buildSparseTable(): void
    {
        $m = count($this->euler); // Euler Tour の長さ

        if ($m === 0) {
            throw new RuntimeException('Euler Tour が空です');
        }

        // log2 テーブル構築: log2[i] = floor(log2(i))
        $this->log2 = array_fill(0, $m + 1, 0);
        for ($i = 2; $i <= $m; $i++) {
            $this->log2[$i] = $this->log2[intdiv($i, 2)] + 1;
        }

        $K = $this->log2[$m]; // 最大の k

        // Sparse Table 初期化
        $this->st = [];
        $this->st[0] = array_fill(0, $m, 0);

        // k = 0 のとき、長さ 1 の区間なので、その位置自体を入れる
        for ($i = 0; $i < $m; $i++) {
            $this->st[0][$i] = $i; // euler[i] の位置
        }

        // k >= 1 のテーブル構築
        for ($k = 1; $k <= $K; $k++) {
            $len = 1 << $k;
            $this->st[$k] = array_fill(0, $m - $len + 1, 0);

            for ($i = 0; $i + $len <= $m; $i++) {
                $leftPos = $this->st[$k - 1][$i];
                $rightPos = $this->st[$k - 1][$i + ($len >> 1)];

                // depth が小さい方を採用
                $this->st[$k][$i] = ($this->depth[$leftPos] <= $this->depth[$rightPos])
                    ? $leftPos
                    : $rightPos;
            }
        }
    }
}

$values = [5, 2, 8, 1, 3, 7, 6];

$rmq = new RMQCartesianTree($values);

// 区間 [1, 4] = {2, 8, 1, 3} の最小値
$result = $rmq->rmq(1, 4);
// $result['index'] は 3
// $result['value'] は 1

echo "RMQ(1,4) index = {$result['index']}, value = {$result['value']}\n";
