<?php

final class BIT {
    // 注意: 1-origin
    public array $tree;
    public ?array $raw;   // set を高速化したい場合に使う（保持しない場合は null）
    public int $N;
    public int $d;        // N 以下で最大の 2 の冪
    public int $total = 0; // 配列合計（sumAll を O(1) に）

    // keepRaw=true にすると set(i,x) が速くなる（メモリを余分に消費）
    public function __construct(int $N, bool $keepRaw = false) {
        if ($N < 1) {
            $this->N = 0;
            $this->tree = [0];
            $this->raw  = $keepRaw ? [0] : null;
            $this->d = 0;
            return;
        }
        $this->N = $N;
        $this->tree = array_fill(0, $N + 1, 0); // index 0 は未使用
        $this->raw  = $keepRaw ? array_fill(0, $N + 1, 0) : null;

        // N 以下の最大の 2 の冪を求める（sprintf("%b",$N) より軽い）
        $this->d = 1;
        while (($this->d << 1) <= $N) $this->d <<= 1;
    }

    // O(n) で 1-indexed 配列から Fenwick 木を構築（$vals[1..N] を想定）
    public static function build(array $vals1Indexed, bool $keepRaw = false): self {
        // 整数キー最大値を N とする（疎配列でも動く）
        $N = 0;
        foreach ($vals1Indexed as $k => $_) {
            if (is_int($k) && $k > $N) $N = $k;
        }
        $bit = new self($N, $keepRaw);
        if ($N === 0) return $bit;

        // 生配列のコピーと total の計算
        $tree = $bit->tree; // 一旦ローカルに作って最後に代入
        for ($i = 1; $i <= $N; $i++) {
            $v = (int)($vals1Indexed[$i] ?? 0);
            $tree[$i] = $v;
            $bit->total += $v;
            if ($keepRaw) $bit->raw[$i] = $v;
        }
        // Fenwick の線形ビルド
        for ($i = 1; $i <= $N; $i++) {
            $j = $i + ($i & -$i);
            if ($j <= $N) {
                $tree[$j] += $tree[$i];
            }
        }
        $bit->tree = $tree;
        return $bit;
    }

    public function add(int $i, int $x): void {
        // 範囲チェックはしない（速度優先）: 1 <= $i <= $this->N で呼ぶこと
        $this->total += $x;
        if ($this->raw !== null) $this->raw[$i] += $x;

        $N = $this->N;
        while ($i <= $N) {
            $this->tree[$i] += $x;
            $i += $i & -$i;
        }
    }

    public function set(int $i, int $x): void {
        if ($this->raw !== null) {
            $delta = $x - $this->raw[$i];
        } else {
            // raw を持たない場合は従来通り 2 回の sum（logN を 2 回）
            $delta = $x - ($this->sum($i) - $this->sum($i - 1));
        }
        if ($delta !== 0) $this->add($i, $delta);
    }

    public function sum(int $i): int {
        $sum = 0;
        $tree = $this->tree; // copy-on-write。読み取りのみで高速
        while ($i > 0) {
            $sum += $tree[$i];
            $i -= $i & -$i;
        }
        return $sum;
    }

    public function sumBetween(int $l, int $r): int {
        if ($l > $r) return 0;
        return $this->sum($r) - $this->sum($l - 1);
    }

    public function sumAll(): int {
        return $this->total;
    }

    // 累積和が w 以上となる最小の index（w<=0 で 0、w>total で N+1）
    public function nth(int $w): int {
        if ($w <= 0) return 0;
        if ($w > $this->total) return $this->N + 1;

        $x = 0;
        $N = $this->N;
        $tree = $this->tree;
        for ($k = $this->d; $k > 0; $k >>= 1) {
            $nx = $x + $k;
            if ($nx <= $N && $tree[$nx] < $w) {
                $w -= $tree[$nx];
                $x = $nx;
            }
        }
        return $x + 1;
    }

    public function max(): int { return $this->nth($this->total); }
    public function min(): int { return $this->nth(1); }
}
