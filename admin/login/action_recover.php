<?php
#include
if(session_id()==''){
    session_start();
}

include_once('../unity/post_to_ws/post_to_ws.php');
include_once('../unity/php_script.php');
include_once('../unity/php_send_email.php');

#REQUEST

#SET DATA RETURN

$set_data = array();



#METHOD

if($_REQUEST['method'] == 1){//login

    #WS
    $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	= '/login';      # กำหนด Folder
    $wsfile		= '/check_recover_email.php';     # กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(
                            'user_email'         => $_REQUEST['user_email'],
                            'method'            => 'GET'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );


    if($data_return['status'] == 200){

        $result = $data_return['results'][0];

        $user_id        = $result['user_id'];
        $email          = $result['user_email'];
        $user_name      = $result['user_name'];
        $user_fullname  = $result['user_fname'].' '.$result['user_lname'];
        
        send_email_reset_password($email,$user_id,$user_name,$user_fullname);
     
        $set_data['status'] = 'TRUE';



        $set_data['url_menu'] = '../login';
        
    }
    else{

         $set_data['status'] = 'FALSE';
         
    }

    echo json_encode($set_data);

}













?>