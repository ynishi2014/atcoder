<?php
$code = <<<'CODE'
#include<bits/stdc++.h>
using namespace std;
const int mod=1e9+7;
int add(int x,int y){return(x+=y)<mod?x:x-mod;}
int sub(int x,int y){return(x-=y)>= 0?x:x+mod;}
int dp[1<<21];
int n,a[21][21];
int main(){
  scanf("%d",&n);
  for(int i=0;i<n;++i){
    for(int j=0;j<n;++j){
      scanf("%d",&a[i][j]);
    }
  }
  dp[0]=1;
  for(int b=1;b<(1<<n);++b){
    int bc=__builtin_popcount(b)-1;
    for(int i=0;i<n;++i){
      if((b>>i&1)&&a[bc][i]){
        dp[b]=add(dp[b],dp[b^(1<<i)]);
      }
    }
  }
  printf("%d\n",dp[(1<<n)-1]);
}
CODE;
file_put_contents("A.cpp", $code);
while($line = fgets(STDIN))$lines[] = $line;
file_put_contents("in", implode("\n", $lines));
exec("g++  A.cpp -o a.out");
exec("./a.out < in", $a, $b);
echo implode("\n", $a);
