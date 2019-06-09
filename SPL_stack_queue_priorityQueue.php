<?php
//Stack -- スタック --配列を使えば十分ではある
$s = new SplStack();
$s->push(1);
$s->push(2);
echo $s->pop();
echo $s->pop();
echo "\n";
//12

//Queue -- キュー
$q = new SplQueue();
$q->enqueue(1);
$q->enqueue(2);
echo $q->dequeue();
echo $q->dequeue();
echo "\n";
//21

//https://php.net/manual/ja/class.splpriorityqueue.php
//PriorityQueue -- 値を取り出す
$pq = new SplPriorityQueue();
$pq->insert("One",1);
$pq->insert("Three",3);
$pq->insert("Two",2);
echo $pq->extract(),"\n";
echo $pq->extract(),"\n";
echo $pq->extract(),"\n";
//Three
//Two
//One

//PriorityQueue -- 優先度を取り出す
$pq = new SplPriorityQueue();
$pq->setExtractFlags(SplPriorityQueue::EXTR_PRIORITY);
$pq->insert("One",1);
$pq->insert("Three",3);
$pq->insert("Two",2);
echo $pq->extract(),"\n";
echo $pq->extract(),"\n";
echo $pq->extract(),"\n";
//3
//2
//1

//PriorityQueue -- 両方取り出す
$pq = new SplPriorityQueue();
$pq->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
$pq->insert("One",1);
$pq->insert("Three",3);
$pq->insert("Two",2);
echo json_encode($pq->extract()),"\n";
echo json_encode($pq->extract()),"\n";
echo json_encode($pq->extract()),"\n";
//{"data":"Three","priority":3}
//{"data":"Two","priority":2}
//{"data":"One","priority":1}

//DoubleLinkedList -- deque -- 双方向リスト
$dqueue = new SplDoublyLinkedList();

$dqueue->push(1); // 1
$dqueue->push(2); //1 2
$dqueue->unshift(3); //3 1 2
$dqueue->unshift(4); //4 3 1 2
echo $dqueue->pop(); //4 3 1 (2)
echo $dqueue->shift(); //(4) 3 1
echo $dqueue->shift(); //(3) 1
echo $dqueue->shift(); //(1)
