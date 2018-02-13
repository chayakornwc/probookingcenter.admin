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

if($_REQUEST['method'] == 1){//update password

  
    #WS
    $wsserver   = URL_WS;        # ../unity/post_to_ws/config.php
    $wsfolder	= '/manage_user';      # กำหนด Folder
    $wsfile		= '/update_user_password.php';     # กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(
                            'user_id'           => $_REQUEST['user_id'],
                            'user_password'     => hash_password(trim($_REQUEST['password'])),
                            'update_user_id'    => $_REQUEST['user_id'],
                            'method'            => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );


    if($data_return['status'] == 200){

        $set_data['status'] = 'TRUE';
        
    }
    else{

         $set_data['status'] = 'FALSE';
         
    }

    echo json_encode($set_data);

}


