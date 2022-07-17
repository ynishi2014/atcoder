<?php

class Trie{// BinaryTrie
  public $bitlen;
  public $fmt;
  public $next = 1;
  public function __construct($bitlen = 63, $maxQuery = 2*10**5){
    $this->bitlen = $bitlen;
    $this->fmt = "%0{$bitlen}b";
    $this->cnt = new SPLFixedArray($maxQuery*($bitlen-1));
    $this->p0 =  new SPLFixedArray($maxQuery*($bitlen-1));
    $this->p1 =  new SPLFixedArray($maxQuery*($bitlen-1));
  }
  public function add($k, $v){
    $node = 0;
    $cnt = $this->cnt;
    $p0 = $this->p0;
    $p1 = $this->p1;
    $next = &$this->next;
    $str = sprintf($this->fmt, $k);
    for($i = 0, $I = $this->bitlen; $i < $I; ++$i){
      $cnt[$node] = $cnt[$node] + $v;
      if($str[$i] == '0'){
        if($p0[$node] == 0){
          $p0[$node] = $next++;
        }
        $node = $p0[$node];
      }else{
        if($p1[$node] == 0){
          $p1[$node] = $next++;
        }
        $node = $p1[$node];
      }
    }
    $cnt[$node] = $cnt[$node] + $v;
  }
  public function insert($k){$this->add($k, 1);}
  public function delete($k){$this->add($k, -1);}
  public function lower_bound($k){return $this->count_lower($k);}
  public function upper_bound($k){return $this->count_lower($k+1);}
  public function nth($pos){// 1-origin
    $node = 0;
    $num = 0;
    $cnt = $this->cnt;
    $p0 = $this->p0;
    $p1 = $this->p1;
    $len = $this->bitlen;
    for($i = 0; $i < $len ; ++$i){
      $num <<= 1;
      if(!$p0[$node] || $pos > $cnt[$p0[$node]]){
        if($p0[$node]){
          $pos-= $cnt[$p0[$node]];
        }
        $node = $p1[$node];
        ++$num;
      }else{
        $node = $p0[$node];
      }
    }
    return $num; 
  }
  public function count($k = false){
    $bits = sprintf($this->fmt, $k);
    $node = 0;
    $p0 = $this->p0;
    $p1 = $this->p1;
    $cnt = $this->cnt;
    $str = sprintf($this->fmt, $k);
    for($i = 0, $I = $this->bitlen; $i < $I; ++$i){
      if($str[$i] == '0'){
        $node = $p0[$node];
        if(!$node)return 0;
      }else{
        $node = $p1[$node];
        if(!$node)return 0;
      }
    }
    return $cnt[$node];
  }
  public function count_all(){
    return $this->cnt[0];
  }
  public function count_upper($k){
    return $this->count_all() - $this->count_lower($k+1);
  }
  public function count_lower($k){
    $bits = sprintf($this->fmt, $k);
    $node = 0;
    $c = 0;
    $p0 = $this->p0;
    $p1 = $this->p1;
    $cnt = $this->cnt;
    $str = sprintf($this->fmt, $k);
    for($i = 0, $I = $this->bitlen; $i < $I; ++$i){
      if($str[$i] == '0'){
        $node = $p0[$node];
        if(!$node)break;
      }else{
        if($p0[$node])$c+=$cnt[$p0[$node]];
        $node = $p1[$node];
        if(!$node)break;
      }
    }
    return $c;
  }
  public function max(){
    return $this->nth($this->cnt[0]);
  }
  public function min(){
    return $this->nth(1);
  }
  public function popMax(){
    $max = $this->nth($this->cnt[0]);
    $this->add($max, -1);
    return $max;
  }
  public function popMin(){
    $min = $this->nth(1);
    $this->add($min, -1);
    return $min;
  }
  public function dump(){
    for($i = 1, $I = $this->count_all(); $i <= $I; ){
      $count = $this->count($this->nth($i));
      o($i.":".$this->nth($i).":".$count);
      $i+=$count;
    }
  }
}
