<?php

final class BIT {
    // 注意: 0-origin（外部 API）／内部は 1-origin Fenwick（tree[0] 未使用）
    public array $tree;
    public ?array $raw;   // set を高速化したい場合に使う（保持しない場合は null, 0-origin）
    public int $N;        // 要素数
    public int $d;        // N 以下で最大の 2 の冪
    public int $total = 0; // 配列合計（sumAll を O(1) に）

    // keepRaw=true にすると set(i,x) が速くなる（メモリを余分に消費）
    public function __construct(int $N, bool $keepRaw = false) {
        if ($N < 1) {
            $this->N = 0;
            $this->tree = [0];               // 内部 1-origin: index 0 は未使用
            $this->raw  = $keepRaw ? [] : null; // 0 要素
            $this->d = 0;
            return;
        }
        $this->N = $N;
        $this->tree = array_fill(0, $N + 1, 0);      // 内部 1-origin: index 1..N を使用
        $this->raw  = $keepRaw ? array_fill(0, $N, 0) : null; // 外部 0-origin: index 0..N-1

        // N 以下の最大の 2 の冪を求める
        $this->d = 1;
        while (($this->d << 1) <= $N) $this->d <<= 1;
    }

    // O(n) で 0-indexed 配列から Fenwick 木を構築（$vals[0..N-1] を想定）
    public static function build(array $vals0Indexed, bool $keepRaw = false): self {
        // 整数キー最大値を N-1 とする（疎配列でも動く）
        $maxK = -1;
        foreach ($vals0Indexed as $k => $_) {
            if (is_int($k) && $k > $maxK) $maxK = $k;
        }
        $N = $maxK + 1;
        $bit = new self($N, $keepRaw);
        if ($N === 0) return $bit;

        // 生配列のコピーと total の計算（内部 tree は 1-origin に詰める）
        $tree = $bit->tree; // 一旦ローカルに作って最後に代入
        for ($i = 0; $i < $N; $i++) {
            $v = (int)($vals0Indexed[$i] ?? 0);
            $tree[$i + 1] = $v;
            $bit->total += $v;
            if ($keepRaw) $bit->raw[$i] = $v;
        }
        // Fenwick の線形ビルド（内部 1-origin）
        for ($i = 1; $i <= $N; $i++) {
            $j = $i + ($i & -$i);
            if ($j <= $N) {
                $tree[$j] += $tree[$i];
            }
        }
        $bit->tree = $tree;
        return $bit;
    }

    // A[i] += x （0 <= i < N）
    public function add(int $i, int $x): void {
        // 範囲チェックはしない（速度優先）: 0 <= $i < $this->N で呼ぶこと
        $this->total += $x;
        if ($this->raw !== null) $this->raw[$i] += $x;

        $N = $this->N;
        $i++; // 内部 1-origin
        while ($i <= $N) {
            $this->tree[$i] += $x;
            $i += $i & -$i;
        }
    }

    // A[i] = x （0 <= i < N）
    public function set(int $i, int $x): void {
        if ($this->raw !== null) {
            $delta = $x - $this->raw[$i];
        } else {
            // raw を持たない場合は従来通り 2 回の sum（logN を 2 回）
            $delta = $x - ($this->sum($i) - $this->sum($i - 1));
        }
        if ($delta !== 0) $this->add($i, $delta);
    }

    // prefix sum: sum A[0..i]（i<0 なら 0）
    public function sum(int $i): int {
        if ($i < 0) return 0;
        $sum = 0;
        $tree = $this->tree; // copy-on-write。読み取りのみで高速
        $i++; // 内部 1-origin
        while ($i > 0) {
            $sum += $tree[$i];
            $i -= $i & -$i;
        }
        return $sum;
    }

    // 区間和: sum A[l..r]（l>r なら 0）
    public function sumBetween(int $l, int $r): int {
        if ($l > $r) return 0;
        return $this->sum($r) - $this->sum($l - 1);
    }

    public function sumAll(): int {
        return $this->total;
    }

    // 累積和が w 以上となる最小の 0-origin index（w<=0 で -1、w>total で N）
    public function nth(int $w): int {
        if ($w <= 0) return -1;
        if ($w > $this->total) return $this->N;

        $x = 0; // 内部 1-origin での走査基準（返り値は 0-origin で $x）
        $N = $this->N;
        $tree = $this->tree;
        for ($k = $this->d; $k > 0; $k >>= 1) {
            $nx = $x + $k;
            if ($nx <= $N && $tree[$nx] < $w) {
                $w -= $tree[$nx];
                $x = $nx;
            }
        }
        return $x; // 0-origin index
    }

    public function max(): int { return $this->nth($this->total); }
    public function min(): int { return $this->nth(1); }
}
