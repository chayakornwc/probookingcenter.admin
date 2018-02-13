<?php
 #include

include_once('../unity/post_to_ws/post_to_ws.php');
//include_once('../unity/php_script.php');



function hash_password($password){

	$password = trim($password);
	
	$password = substr(md5($password),0,20);

	return $password;


}

function d_m_y($date){
	
	if($date == '0000-00-00 00:00:00'){
		return;
	}
	$date = date('j/n/Y',strtotime($date));

	return $date;
}

function m_d_y($date){
	if($date == '0000-00-00 00:00:00' || $date == ''){
		return;
	}
	$date =  date('m/d/Y',strtotime($date));
	
	return $date;
}

function base64_encode_dev_gun($p){
	
	for($i = 0; $i < 13; $i++){
		$p = base64_encode($p);
	}

	return $p;
}
function base64_decode_dev_gun($p){
	
	for($i = 0; $i < 13; $i++){
		$p = base64_decode($p);
	}

	return $p;
}
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


function dateRange($first, $last, $step = '+1 day', $format = 'd/m/Y' ) { 

	$dates = array();
	$current = strtotime($first);
	$last = strtotime($last);

	while( $current <= $last ) { 

		$dates[] = date($format, $current);
		$current = strtotime($step, $current);
	}

	return $dates;
}

function paginate_list($data_all , $current_page , $page_limit){

	// $current_page นับแบบ array หน้า 1 เริ่มที่ 0
	$count_page =  no_negative( ceil($data_all / $page_limit) - 1 );

	$first_page 	= 0;
	$pre_page 		= no_negative( $current_page-1 );
	$next_page 		= no_greater_than( ($current_page + 1) , $count_page );
	$last_page 		= $count_page;

	$button_disable_first 		= '';
	$button_disable_pre 		= '';
	if($current_page <= $first_page ){
		$button_disable_pre 	= 'disabled_click';
		$button_disable_first 	= 'disabled disabled_click';
	}

	$button_disable_next = '';
	$button_disable_last = '';
	if( $current_page >= $last_page ){
		$button_disable_next 	= 'disabled_click';
		$button_disable_last 	= 'disabled_click';
	}


	$paginate_html = '<ul class="pagination" style="visibility: visible;">
						<li class="prev '.$button_disable_first.'">
							<a class="'.$button_disable_first.'" href="javascript:;" title="First" onclick="PAGE_SELECT(\''.intval($first_page).'\');">
								<i class="fa fa-angle-double-left"></i>
							</a>
						</li>
						<li class="prev '.$button_disable_pre.'">
							<a class="'.$button_disable_pre.'" href="javascript:;" title="Prev" onclick="PAGE_SELECT(\''.intval($pre_page).'\');">
								<i class="fa fa-angle-left"></i>
							</a>
						</li>';
	$loop_max 		= 0;
	$start_page 	= $current_page - 5;
	$end_page 		= no_greater_than(($current_page + 5)+abs($start_page) , $count_page);

	$start_page 	= no_negative($start_page);

	for($i=$start_page;$i<=$end_page;$i++){

		$is_active = '';

		if(intval($i) == $current_page){
			$is_active = 'active';
		}

		$paginate_html .= '	<li class="'.$is_active.'">
								<a href="javascript:;" onclick="PAGE_SELECT(\''.intval($i).'\');">'.intval($i+1).'</a>
							</li>
							';
		$loop_max++;
		if($loop_max >= 11){
			break;
		}
	}

	$paginate_html .= '<li class="next '.$button_disable_next.'">
									<a class="'.$button_disable_next.'" href="javascript:;" title="Next" onclick="PAGE_SELECT(\''.intval($next_page).'\');">
										<i class="fa fa-angle-right"></i>
							</a>
						</li>
						<li class="next '.$button_disable_last.'">
							<a  href="javascript:;"  class="'.$button_disable_last.'" title="Last" onclick="PAGE_SELECT(\''.intval($last_page).'\');">
								<i class="fa fa-angle-double-right"></i>
							</a>
						</li>
					</ul>';

	return $paginate_html;
}

function no_negative($number){
	if( $number < 0){
		return 0;
	}else{
		return $number;
	}
}

function no_greater_than($number , $greater){

	if($greater <= $number){
		return intval($greater);
	}else{
		return intval($number);
	}

}
function html_status_payment($s){
	if($s == 0){ #รอตรวจสอบ
		$html = '<div class="label bg-warning">รอตรวจสอบ</div>';
	}
	else if($s == 1){#ผ่านการตรวจสอบ
		$html = '<div class="label bg-success">ผ่านการตรวจสอบ</div>';
	}
	else if($s == 9){#ไม่ผ่านการตรวจสอบ
		$html = '<div class="label bg-danger-dark">ไม่ผ่านการตรวจสอบ</div>';
	}
	else{
		$html = '';
	}
	return $html;
}
function html_status_book($s){
	if($s == 0){ #จอง
		$html = '<div class="label bg-green-light">จอง</div>';
	}
	else if($s == 5){#รอที่นั่ง
		$html = '<div class="label bg-yellow">W/L</div>';
	}
	else if($s == 10){#แจ้ง invoice
		$html = '<div class="label bg-pink-light">แจ้ง Invoice</div>';
	}
	else if($s == 20){#ชำระเงินมัดจำไม่ครบ
	//	$html = '<div class="label bg-primary-light">มัดจำบางส่วน</div>';
		$html = '<div class="label bg-primary-light">DEP(PT)</div>';
	}
	else if($s == 25){#ชำระเงินมัดจำครบ
	//	$html = '<div class="label bg-primary-dark">มัดจำเต็มจำนวน</div>';
		$html = '<div class="label bg-primary-dark">DEP</div>';
	}
	else if($s == 30){#ชำระเงินเต็มจำนวนไม่ครบ
	//	$html = '<div class="label bg-success-light">Full payment บางส่วน</div>';
		$html = '<div class="label bg-success-light">FP(PT)</div>';
	}
	else if($s == 35){#ชำระเงินเต็มจำนวนครบ
	//	$html = '<div class="label bg-success">Full payment เต็มจำนวน</div>';
		$html = '<div class="label bg-success">FP</div>';
	}
	else if($s == 40 ){#ยกเลิกการจอง
		$html = '<div class="label bg-danger-dark">CXL</div>';
	}
	else{
		$html = '';
	}
	return $html;
}

function text_status_book($s){
	if($s == 0){ #
		$html = 'จอง';
	}
	else if($s == 5){#
		$html = 'รอที่นั่ง';
	}
	else if($s == 10){#
		$html = 'แจ้ง Invoice';
	}
	else if($s == 20){#ชำระเงินมัดจำไม่ครบ
		$html = 'ชำระ มัดจำ(Deposite)บางส่วน';
	}
	else if($s == 25){#ชำระเงินมัดจำครบ
		$html = 'ชำระ มัดจำ(Deposite)เต็มจำนวน';
	}
	else if($s == 30){#ชำระเงินเต็มจำนวนไม่ครบ
		$html = 'ชำระ เต็มจำนวน(Full payment)บางส่วน';
	}
	else if($s == 35){#ชำระเงินเต็มจำนวนครบ
		$html = 'ชำระ เต็มจำนวน(Full payment)เต็มจำนวน';
	}
	else if($s == 40 ){#ยกเลิกการจอง
		$html = 'ยกเลิกการจอง';
	}
	else{
		$html = '';
	}
	return $html;
}
function text_status_book_report($s){
	if($s == 0){ #
		$html = 'จอง';
	}
	else if($s == 5){#
		$html = 'รอที่นั่ง';
	}
	else if($s == 10){#
		$html = 'แจ้ง Invoice';
	}
	else if($s == 20){#ชำระเงินมัดจำไม่ครบ
		$html = 'DEP(PT)';
	}
	else if($s == 25){#ชำระเงินมัดจำครบ
		$html = 'DEP';
	}
	else if($s == 30){#ชำระเงินเต็มจำนวนไม่ครบ
		$html = 'FP(PT)';
	}
	else if($s == 35){#ชำระเงินเต็มจำนวนครบ
		$html = 'FP';
	}
	else if($s == 40 ){#ยกเลิกการจอง
		$html = 'CXL';
	}
	else{
		$html = '';
	}
	return $html;
}
function text_status_period_report($s){
	if($s == 1){ #
		$html = 'เปิดจอง';
	}
	else if($s == 2){#
		$html = 'เต็ม';
	}
	else if($s == 3){#
		$html = 'ปิดทัวร์';
	}
	else if($s == 9){#ชำระเงินมัดจำไม่ครบ
		$html = 'ระงับการใช้งาน';
	}
	else if($s == 10){#ชำระเงินมัดจำครบ
		$html = 'ตัดตั๋ว';
	}
	else{
		$html = '';
	}
	return $html;
}
function html_status_period($s){
	if($s == 1){ #เปิดจอง
		$html = '<div class="label bg-green-light">เปิดจอง</div>';
	}
	else if($s == 2){#เต็ม
		$html = '<div class="label bg-danger-dark">เต็ม</div>';
	}
	else if($s == 3){#ปิดทัวร์
		$html = '<div class="label bg-warning-dark">ปิดทัวร์</div>';
	}
	else if($s == 9){#ลบ
		$html = '<div class="label bg-danger">ระงับการใช้งาน</div>';
	}
	else if($s == 10){#ตัดตั๋ว
		$html = '<div class="label bg-danger">ตัดตั๋ว</div>';
	}
	else{
		$html = '';
	}
	return $html;
}



function select_option_airline($status = 'all'){
	#WS  AIRLINE
	$wsserver   = URL_WS;
	$wsfolder	= '/manage_airline'; //กำหนด Folder
	$wsfile		= '/select_list_airline.php'; //กำหนด File
	$url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
	$data 		= array(    
								'method'        => 'GET'
							);


	$data_return =	json_decode( post_to_ws($url,$data),true );


	
	if($data_return['status'] == 200){
		$result = $data_return['results'];
		
		
		$option_airline = '';
		foreach($result as $key => $value){
			
			if($status == 'all'){
				$disabled = '';
				if($value['status'] == 2){
					$disabled = 'disabled';
				}

				$option_airline .= '<option value="'.$value['air_id'].'" '.$disabled.'>'.$value['air_name'].'</option>';
			}
			else if($status == $value['status']){

				$option_airline .= '<option value="'.$value['air_id'].'">'.$value['air_name'].'</option>';

			}


			
		}

	}
	else{
		$option_airline = '<option value="">ไม่พบข้อมูล</option>';
	}

	return  $option_airline;

}

function select_option_country($status = 'all',$country_id = ''){

	#WS  AIRLINE
    $wsserver   = URL_WS;
    $wsfolder	= '/dropdownlist'; //กำหนด Folder
    $wsfile		= '/select_list_country.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );
	
	if($data_return['status'] == 200){
		$result = $data_return['results'];

		$option_country = '';
		foreach($result as $key => $value){
			$selected= '';
			if($country_id == $value['country_id']){
				$selected= 'selected';
			}

			if($status == 'all'){
				$disabled = '';
				if($value['status'] == 2){
					$disabled = 'disabled';
				}
				$option_country .= '<option value="'.$value['country_id'].'" '.$disabled.' deposit="'.intval($value['country_deposit']).'" '.$selected.'>'.$value['country_name'].'</option>';

			}
			else if($status == $value['status']){

				$option_country .= '<option value="'.$value['country_id'].'" deposit="'.intval($value['country_deposit']).'" '.$selected.'>'.$value['country_name'].'</option>';

			}
		}

	}
	else{
		$option_country = '<option value="">ไม่พบข้อมูล</option>';
	}
	return $option_country;
}
function select_option_series($status = 'all',$ser_id = ''){

	#WS  AIRLINE
    $wsserver   = URL_WS;
    $wsfolder	= '/dropdownlist'; //กำหนด Folder
    $wsfile		= '/select_list_series.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );
	
	if($data_return['status'] == 200){
		$result = $data_return['results'];

		$option_series = '';
		foreach($result as $key => $value){
			$selected= '';
			if($ser_id == $value['ser_id']){
				$selected= 'selected';
			}

		
				$option_series .= '<option value="'.$value['ser_id'].'" '.$selected.'>'.$value['ser_code'].'</option>';

		
		}

	}
	else{
		$option_series = '<option value="">ไม่พบข้อมูล</option>';
	}
	return $option_series;
}
function select_option_user($status = 'all',$user_id = ''){

	#WS  AIRLINE
    $wsserver   = URL_WS;
    $wsfolder	= '/dropdownlist'; //กำหนด Folder
    $wsfile		= '/select_list_user.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );
	
	if($data_return['status'] == 200){
		$result = $data_return['results'];

		$option_user = '';
		foreach($result as $key => $value){
			$selected= '';
			if($country_id == $value['user_id']){
				$selected= 'selected';
			}

		
				$option_user .= '<option value="'.$value['user_id'].'" '.$selected.'>'.$value['username'].'</option>';

		
		}

	}
	else{
		$option_user = '<option value="">ไม่พบข้อมูล</option>';
	}
	return $option_user;
}

function select_option_com_agency($status = 'all',$user_id = ''){
	
       #Agency Company name
	   $wsserver   = URL_WS;
	   $wsfolder	= '/agency'; //กำหนด Folder
	   $wsfile		= '/get_com_agen.php'; //กำหนด File
	   $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
	   $data 		= array(    
								   'method'        => 'GET'
							   );
   
	
	   $data_return =	json_decode( post_to_ws($url,$data),true );
   
   
	   if($data_return['status'] == 200){
   
		   $result = $data_return['results'];
		   $option_company_agency = '';
   
		   foreach($result as $key => $value){
   
			   $selected_company_agency = '';
			   if($agency_company_id == $value['agen_com_id']){
				   $selected_company_agency = 'selected';
			   }
   
			   $option_company_agency .= '<option value="'.$value['agen_com_id'].'" '.$selected_company_agency.'>'.$value['agen_com_name'].'</option>';
		   }
   
	   }
	   else{
		   $option_company_agency = '<option value="">ไม่พบข้อมูล</option>';
	   }
		return $option_company_agency;
	}

function select_option_agent($status = 'all',$agen_id = ''){

	#WS  AIRLINE
    $wsserver   = URL_WS;
    $wsfolder	= '/dropdownlist'; //กำหนด Folder
    $wsfile		= '/select_list_agent.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );
	
	if($data_return['status'] == 200){
		$result = $data_return['results'];

		$option_agent = '';
		foreach($result as $key => $value){
			$selected= '';
			if($country_id == $value['agen_id']){
				$selected= 'selected';
			}

		
				$option_agent .= '<option value="'.$value['agen_id'].'" '.$selected.'>'.$value['agenname'].'</option>';

		
		}

	}
	else{
		$option_agent = '<option value="">ไม่พบข้อมูล</option>';
	}
	return $option_agent;
}
function select_option_bankbook($status = 'all',$agen_id = ''){

	#WS  AIRLINE
    $wsserver   = URL_WS;
    $wsfolder	= '/dropdownlist'; //กำหนด Folder
    $wsfile		= '/select_list_bankbook.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );
	
	if($data_return['status'] == 200){
		$result = $data_return['results'];

		$option_bankbook = '';
		foreach($result as $key => $value){
			$selected= '';
		
				$option_bankbook .= '<option value="'.$value['bankbook_id'].'" '.$selected.'>'.$value['bankbookname'].'</option>';

		
		}

	}
	else{
		$option_bankbook = '<option value="">ไม่พบข้อมูล</option>';
	}
	return $option_bankbook;
}







$thai_day_arr=array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");   
$thai_month_arr=array(   
    "0"=>"",   
    "1"=>"มกราคม",   
    "2"=>"กุมภาพันธ์",   
    "3"=>"มีนาคม",   
    "4"=>"เมษายน",   
    "5"=>"พฤษภาคม",   
    "6"=>"มิถุนายน",    
    "7"=>"กรกฎาคม",   
    "8"=>"สิงหาคม",   
    "9"=>"กันยายน",   
    "10"=>"ตุลาคม",   
    "11"=>"พฤศจิกายน",   
    "12"=>"ธันวาคม"                    
);   
$thai_month_arr_short=array(   
    "0"=>"",   
    "1"=>"ม.ค.",   
    "2"=>"ก.พ.",   
    "3"=>"มี.ค.",   
    "4"=>"เม.ย.",   
    "5"=>"พ.ค.",   
    "6"=>"มิ.ย.",    
    "7"=>"ก.ค.",   
    "8"=>"ส.ค.",   
    "9"=>"ก.ย.",   
    "10"=>"ต.ค.",   
    "11"=>"พ.ย.",   
    "12"=>"ธ.ค."                    
);   
function thai_date_and_time($time){   // 19 ธันวาคม 2556 เวลา 10:10:43
    global $thai_day_arr,$thai_month_arr;   
    $thai_date_return =date("j",$time);
    $thai_date_return.=" ".$thai_month_arr[date("n",$time)];   
    $thai_date_return.= " ".(date("Y",$time)+543);   
    $thai_date_return.= " เวลา ".date("H:i:s",$time);
    return $thai_date_return;   
} 
function thai_date_and_time_short($time){   // 19  ธ.ค. 2556 10:10:4
    global $thai_day_arr,$thai_month_arr_short;   
    $thai_date_return =date("j",$time);
    $thai_date_return.="&nbsp;&nbsp;".$thai_month_arr_short[date("n",$time)];   
    $thai_date_return.= " ".(date("Y",$time)+543);   
    $thai_date_return.= " ".date("H:i:s",$time);
    return $thai_date_return;   
} 
function thai_date_short($time){   // 19  ธ.ค. 2556
    global $thai_day_arr,$thai_month_arr_short;   
    $thai_date_return =date("j",$time);
    $thai_date_return.=" ".$thai_month_arr_short[date("n",$time)];   
    $thai_date_return.= " ".(date("Y",$time)+543);   
    return $thai_date_return;   
} 
function thai_date_fullmonth($time){   // 19 ธันวาคม 2556
    global $thai_day_arr,$thai_month_arr;   
    $thai_date_return =date("j",$time);
    $thai_date_return.=" ".$thai_month_arr[date("n",$time)];   
    $thai_date_return.= " ".(date("Y",$time)+543);   
    return $thai_date_return;   
} 
function thai_date_short_number($time){   // 19-12-56
    global $thai_day_arr,$thai_month_arr;   
    $thai_date_return =date("d",$time);
    $thai_date_return.="-".date("m",$time);   
    $thai_date_return.= "-".substr((date("Y",$time)+543),-2);   
    return $thai_date_return;   
} 








// งก์ชั่นแปลงค่าเงินตัวเลขเป็นตัวอักษร

function Convert($amount_number)
{
    $amount_number = number_format($amount_number, 2, ".","");
    $pt = strpos($amount_number , ".");
    $number = $fraction = "";
    if ($pt === false) 
        $number = $amount_number;
    else
    {
        $number = substr($amount_number, 0, $pt);
        $fraction = substr($amount_number, $pt + 1);
    }
    
    $ret = "";
    $baht = ReadNumber ($number);
    if ($baht != "")
        $ret .= $baht . "บาท";
    
    $satang = ReadNumber($fraction);
    if ($satang != "")
        $ret .=  $satang . "สตางค์";
    else 
        $ret .= "ถ้วน";
    return $ret;
}

function ReadNumber($number)
{
    $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
    $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
    $number = $number + 0;
    $ret = "";
    if ($number == 0) return $ret;
    if ($number > 1000000)
    {
        $ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
        $number = intval(fmod($number, 1000000));
    }
    
    $divider = 100000;
    $pos = 0;
    while($number > 0)
    {
        $d = intval($number / $divider);
        $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : 
            ((($divider == 10) && ($d == 1)) ? "" :
            ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
        $ret .= ($d ? $position_call[$pos] : "");
        $number = $number % $divider;
        $divider = $divider / 10;
        $pos++;
    }
    return $ret;
}




?>