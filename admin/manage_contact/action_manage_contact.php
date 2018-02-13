<?php
#include
include_once('../unity/main_core.php');
include_once('../unity/post_to_ws/post_to_ws.php');
include_once('../unity/php_script.php');

#REQUEST
 $contact_id   = $_REQUEST['contact_id'];
 $contact_detail   = $_REQUEST['contact_detail'];
#SET DATA RETURN

$set_data = array();



#METHOD

if($_REQUEST['method'] == 1){//select by id


    #WS
    $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	= '/contact';      # กำหนด Folder
    $wsfile		=	'/select_contact_id.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(
                            'method'          => 'GET'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );
    
   
   if($data_return['status'] == 200){

        $result                         = $data_return['results'][0];
        $set_data['contact_id']         = $result['contact_id'];
        $set_data['contact_detail']     = $result['contact_detail'];

   }
   else{
       
        $set_data['contact_id']         = '';
        $set_data['contact_detail']     = '';
   }


    echo json_encode($set_data);
    


}
    else if ($_REQUEST['method'] == 2){ # update contact
         #WS
    $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	= '/contact';      # กำหนด Folder
    $wsfile		=	'/update_contact.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(
                            'method'                 => 'PUT' ,
                            'contact_id'             => $contact_id ,
                            'contact_detail'         => $contact_detail ,
                            );
    $data_return =	json_decode( post_to_ws($url,$data),true );
    $set_data['status'] = $data_return['status'];
    echo json_encode($set_data);

}
else{

}
















?>