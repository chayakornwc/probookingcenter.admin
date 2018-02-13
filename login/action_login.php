<?php
#include
if(session_id()==''){
    session_start();
}

include_once('../admin/unity/post_to_ws/post_to_ws.php');
include_once('../admin/unity/php_script.php');

#REQUEST

#SET DATA RETURN

$set_data = array();



#METHOD

if($_REQUEST['method'] == 1){//login


    // ตรว สอบ การ ตอง ทัวร์ ตรงนี้
         $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	= '/login';      # กำหนด Folder
    $wsfile		= '/update_cancel_booking.php';     # กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(
                            'method'            => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

      // update prefix
         $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	= '/login';      # กำหนด Folder
    $wsfile		= '/update_prefix.php';     # กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(
                            'method'            => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

     // update close period
         $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	= '/login';      # กำหนด Folder
    $wsfile		= '/update_close_period.php';     # กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(
                            'method'            => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    


    //


    #WS
    $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	= '/f_login_agency';      # กำหนด Folder
    $wsfile		= '/check_login_agency.php';     # กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(
                            'username'         => $_REQUEST['user_name'],
                            'password'     => hash_password($_REQUEST['password']),
                            'method'            => 'GET'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    
    if($data_return['status'] == 200){
        $result = $data_return['results'][0];
        
        $_SESSION['login']['agen_id']       = $result['agen_id'];
        $_SESSION['login']['agen_user_name']     = $result['agen_user_name'];
        $_SESSION['login']['agen_fname']      = $result['agen_fname'];
        $_SESSION['login']['agen_lname']    = $result['agen_lname'];
        $_SESSION['login']['agen_email']    = $result['agen_email'];
        $_SESSION['login']['agen_tel']    = $result['agen_tel'];
        $_SESSION['login']['agency_company_id']    = $result['agency_company_id'];
        $_SESSION['login']['agen_com_name']    = $result['agen_com_name'];
        



        $set_data['status'] = 'TRUE';
        $set_data['url_menu'] = '../home';
        
    }
    else{

         $set_data['status'] = 'FALSE';
         
    }

    echo json_encode($set_data);

}













?>