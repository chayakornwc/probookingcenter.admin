<?php
#include
if(session_id()==''){
    session_start();
}

include_once('../unity/post_to_ws/post_to_ws.php');
include_once('../unity/php_script.php');

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
    $wsfolder	= '/login';      # กำหนด Folder
    $wsfile		= '/check_login.php';     # กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(
                            'user_name'         => $_REQUEST['user_name'],
                            'user_password'     => hash_password($_REQUEST['password']),
                            'method'            => 'GET'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    
    if($data_return['status'] == 200){
        $result = $data_return['results'][0];
        
        $_SESSION['login']['user_id']       = $result['user_id'];
        $_SESSION['login']['user_name']     = $result['user_name'];
        $_SESSION['login']['group_id']      = $result['group_id'];
        $_SESSION['login']['group_name']    = $result['group_name'];
        $_SESSION['login']['user_fname']    = $result['user_fname'];
        $_SESSION['login']['user_lname']    = $result['user_lname'];
        $_SESSION['login']['user_email']    = $result['user_email'];
        $_SESSION['login']['user_tel']      = $result['user_tel'];
        

        $_SESSION['login']['menu_1']       = $result['menu_1'];
        $_SESSION['login']['menu_2']       = $result['menu_2'];
        $_SESSION['login']['menu_3']       = $result['menu_3'];
        $_SESSION['login']['menu_4']       = $result['menu_4'];
        $_SESSION['login']['menu_5']       = $result['menu_5'];
        $_SESSION['login']['menu_6']       = $result['menu_6'];
        $_SESSION['login']['menu_7']       = $result['menu_7'];
        $_SESSION['login']['menu_8']       = $result['menu_8'];
        $_SESSION['login']['menu_9']       = $result['menu_9'];
        $_SESSION['login']['menu_10']       = $result['menu_10'];
        $_SESSION['login']['menu_11']       = $result['menu_11'];
        $_SESSION['login']['menu_12']       = $result['menu_12'];
        $_SESSION['login']['menu_13']       = $result['menu_13'];
        $_SESSION['login']['menu_14']       = $result['menu_14'];
        $_SESSION['login']['menu_15']       = $result['menu_15'];
        $_SESSION['login']['menu_16']       = $result['menu_16'];
        $_SESSION['login']['menu_17']       = $result['menu_17'];


        $set_data['status'] = 'TRUE';
        $set_data['url_menu'] = '../dashboard';
        
    }
    else{

         $set_data['status'] = 'FALSE';
         
    }

    echo json_encode($set_data);

}













?>