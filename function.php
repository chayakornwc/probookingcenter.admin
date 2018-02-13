<?php
	require_once('config.php');

	error_reporting (E_WARNING);	
	function connect_db(){

		$server    = $GLOBALS['server'];
		$user_name = $GLOBALS['user_name'];
		$pwd       = $GLOBALS['pwd'];
		$db        = $GLOBALS['db'];
		
		$CON       = mysqli_connect($server,$user_name,$pwd,$db);

		if(mysqli_connect_errno()){
			echo mysqli_connect_error();
		}
		mysqli_set_charset($CON,"utf8");


		$GLOBALS['CON'] = $CON;
	}


connect_db();


	function disconect_db(){
		mysqli_close($GLOBALS['CON']);
	}



	function return_object( $code , $data = array() ){

	$http_codes = array(
						100 => 'Continue',
						101 => 'Switching Protocols',
						102 => 'Processing',
						200 => 'OK',
						201 => 'Created',
						202 => 'Accepted',
						203 => 'Non-Authoritative Information',
						204 => 'No Content',
						205 => 'Reset Content',
						206 => 'Partial Content',
						207 => 'Multi-Status',
						300 => 'Multiple Choices',
						301 => 'Moved Permanently',
						302 => 'Found',
						303 => 'See Other',
						304 => 'Not Modified',
						305 => 'Use Proxy',
						306 => 'Switch Proxy',
						307 => 'Temporary Redirect',
						400 => 'Bad Request',
						401 => 'Unauthorized',
						402 => 'Payment Required',
						403 => 'Forbidden',
						404 => 'Not Found',
						405 => 'Method Not Allowed',
						406 => 'Not Acceptable',
						407 => 'Proxy Authentication Required',
						408 => 'Request Timeout',
						409 => 'Conflict',
						410 => 'Gone',
						411 => 'Length Required',
						412 => 'Precondition Failed',
						413 => 'Request Entity Too Large',
						414 => 'Request-URI Too Long',
						415 => 'Unsupported Media Type',
						416 => 'Requested Range Not Satisfiable',
						417 => 'Expectation Failed',
						418 => 'I\'m a teapot',
						422 => 'Unprocessable Entity',
						423 => 'Locked',
						424 => 'Failed Dependency',
						425 => 'Unordered Collection',
						426 => 'Upgrade Required',
						449 => 'Retry With',
						450 => 'Blocked by Windows Parental Controls',
						500 => 'Internal Server Error',
						501 => 'Not Implemented',
						502 => 'Bad Gateway',
						503 => 'Service Unavailable',
						504 => 'Gateway Timeout',
						505 => 'HTTP Version Not Supported',
						506 => 'Variant Also Negotiates',
						507 => 'Insufficient Storage',
						509 => 'Bandwidth Limit Exceeded',
						510 => 'Not Extended'
	);

	$data_return = array();
	$data_return["status"] = $code;
	$data_return["message"] = $http_codes[$code];
	//$data_return["information"] = "more information https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html";
	$data_return["results"] = $data;
	
	return $data_return;

}

function set_limit($offset , $limit){

	$str_limit = 'LIMIT ';
	if( is_numeric($offset) && is_numeric($limit)){

		$start_row = $offset * $limit;

		$str_limit .= strval($start_row).','.$limit;

	}else{
		$str_limit = '';
	}

	return $str_limit;
}

?>
