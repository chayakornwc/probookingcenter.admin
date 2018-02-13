<?php
#include
if(session_id()==''){
    session_start();
}

include_once('../admin/unity/post_to_ws/post_to_ws.php');
include_once('../admin/unity/php_script.php');
include_once('../admin/unity/php_send_email_agen.php');

#REQUEST

#SET DATA RETURN

$set_data = array();



#METHOD

if($_REQUEST['method'] == 1){//login

    #WS
    $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	= '/f_login_agency';      # กำหนด Folder
    $wsfile		= '/check_recover_agency_email.php';     # กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(
                            'agen_email'         => $_REQUEST['user_email'],
                            'method'            => 'GET'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );


    if($data_return['status'] == 200){

        $result = $data_return['results'][0];

        $agen_id        = $result['agen_id'];
        $agen_user_name          = $result['agen_user_name'];
        $agen_email      = $result['agen_email'];
        $agen_fullname  = $result['agen_fname'].' '.$result['agen_lname'];
        
        send_email_reset_password_agen($agen_email,$agen_id,$agen_user_name,$agen_fullname);
     
        $set_data['status'] = 'TRUE';



        $set_data['url_menu'] = '../login';
        
    }
    else{

         $set_data['status'] = 'FALSE';
         
    }

    echo json_encode($set_data);

}













?>