<?php

function Y_m_d($date){
	if($date == '0000-00-00 00:00:00'){
		return;
	}
	$date = explode('/',$date);

	if(strlen($date[0]) == 1){
		$date[0] = '0'.$date[0];
	}
	if(strlen($date[1]) == 1){
		$date[1] = '0'.$date[1];
	}
	$date = date_create($date[0].'-'.$date[1].'-'.$date[2]);
	
	$date = date_format($date,'Y-m-d');
	return $date;
}