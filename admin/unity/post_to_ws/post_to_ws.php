<?php
	//error_reporting (E_WARNING);
	require_once('config.php');
	function post_to_ws($url ,array $data , $timeout=300,$way=0)
	{
		

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		//curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_VERBOSE, true);
		//curl_setopt($curl, CURLOPT_PORT, 8000);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		$RESULT = curl_exec($curl);


		curl_close($curl);
		return $RESULT;
	}


?>