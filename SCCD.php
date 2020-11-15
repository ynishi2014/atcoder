<?php
// https://atcoder.jp/contests/arc030/submissions/18114800
class StrongConnectionDecomposition{
    public $G; // 順方向グラフ
    public $Gr; // 逆方向グラフ
    public $n; // ノード数
    public $done; // 一回目のDFS
    public $startArray; // 2回目のDFSの開始順
    public $group; // i番目の要素の所属先
    public $GG = []; // 強連結集合のDAG
    public $tsort; // 強連結集合のトポロジカルソート
    function __construct($n){
        $this->G = array_fill(1, $n, []);
        $this->Gr = array_fill(1, $n, []);
        $this->n = $n;
        $this->group = array_fill(1, $n, -1);
    }
    function solve(){
        for($i = 1; $i <= $this->n; $i++){
            $this->dfs($i);
        }
        $num = 1;
        foreach(array_reverse($this->startArray) as $start){
            $this->dfs2($start, $num++);
        }
        foreach($this->G as $from => $toArray){
            foreach($toArray as $to){
                if($this->group[$from]!=$this->group[$to]){
                    if(!isset($this->GG[$this->group[$from]][$this->group[$to]])){
                        $this->GG[$this->group[$from]][$this->group[$to]] = true;
                    }
                }
            }
        }
        foreach($this->GG as $i => $row)$this->GG[$i] = array_keys($this->GG[$i]);
        $this->tsort = TSORT::sort($this->GG);
    }
    function dfs($current){
        if(isset($this->done[$current]))return;
        $this->done[$current] = true;
        foreach($this->G[$current] as $next){
            $this->dfs($next);
        }
        $this->startArray[] = $current;
    }
    function dfs2($current, $num){
        if($this->group[$current] != -1)return;
        $this->group[$current] = $num;
        foreach($this->Gr[$current] as $next){
            $this->dfs2($next, $num);
        }
    }
    function connect($i, $j){
        $this->G[$i][] = $j;
        $this->Gr[$j][] = $i;
    }
}
class TSORT{
    static function sort($G){
        global $__G__, $ans;
        $__G__ = $G;
        $ans = [];
        for($i = 1; $i <= count($G); $i++)TSORT::visit($i);
        return array_reverse($ans);
    }
    static function visit($i){
        global $__G__, $visited, $ans;
        if(!isset($visited[$i])){
            $visited[$i] = true;
            foreach($__G__[$i]??[] as $next){
                TSORT::visit($next);
            }
            $ans[] = $i;
        }
    }
}
