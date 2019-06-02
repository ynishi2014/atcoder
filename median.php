<?php

//Median
function median($values){
	sort($values);
	if(count($values) % 2 == 0){
		return ($values[(count($values)/2)-1]+$values[((count($values)/2))])/2;
	}else{
		return $values[floor(count($values)/2)];
	}
}
