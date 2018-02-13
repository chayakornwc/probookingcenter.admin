<?php
#include
include_once('../unity/main_core.php');
include_once('../unity/post_to_ws/post_to_ws.php');
include_once('../unity/php_script.php');

include_once('../unity/php_send_email.php');
#REQUEST

#SET DATA RETURN

$set_data = array();


#METHOD
if($_REQUEST['method'] == 1){ // select_country_grosssale
     #REQUEST
    if ($_REQUEST['country_id'] != ""){

     #WS
     $wsserver   = URL_WS;
     $wsfolder	= '/report'; //กำหนด Folder
     $wsfile		= '/select_ser_by_country_id.php'; //กำหนด File
     $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
     $data 		= array(    
                                 'country_id' => $_REQUEST['country_id'],
                                 'method'        => 'GET'
                             );
 
  
     $data_return =	json_decode( post_to_ws($url,$data),true );
 
 
      $set_data['select_country_grosssale'] = ' <div class="input-group col-lg-12 col-md-12 col-xs-12" >
                                                <select name="ser_id" id="ser_id" class="form-control select_search">';
                                                 
   if($data_return['status'] == 200){

                 $set_data['select_country_grosssale'] .= ' <option value="" selected>ทั้งหมด</option> ';
                 $result = $data_return['results'];
    
                 foreach($result as $key => $value){
                     $selected = '';
            
                     $set_data['select_country_grosssale'] .= '<option value="'.$value['ser_id'].'" '.$selected.'>'.$value['ser_name'].'</option>';
                 }
 
             }
             else{
                 
                  $set_data['select_country_grosssale'] .= '<option value="0">ไม่พบข้อมูล</option>';
             }

    }else {
        $set_data['select_country_grosssale'] = ' <div class="input-group col-lg-12 col-md-12 col-xs-12" >
        <select name="ser_id" id="ser_id" class="form-control select_search">
        <option value="" selected>ทั้งหมด</option> ';

    }
    $set_data['select_country_grosssale'] .= ' </select>';
    
    echo json_encode($set_data);
    
}else if($_REQUEST['method'] == 2){
    #REQUEST
    if ($_REQUEST['com_agency_id'] != ""){
        
     #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/booking'; //กำหนด Folder
    $wsfile		= '/select_agency_by_com_id.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'com_agency_id' => $_REQUEST['com_agency_id'],
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );


    $set_data['select_com_agency_grosssale'] = ' <div class="input-group col-lg-12 col-md-12 col-xs-12" >
            <select name="agency_grosssale" id="agency_grosssale" class="form-control select_search">';
    if($data_return['status'] == 200){
        $set_data['select_com_agency_grosssale'] .= ' <option value="" selected>ทั้งหมด</option> ';
        $result = $data_return['results'];
                foreach($result as $key => $value){
                    $selected = '';
                
                   
                    $set_data['select_com_agency_grosssale'] .= '<option value="'.$value['agen_id'].'" '.$selected.'>'.$value['name'].'</option>';
                }

            }
            else{
                
                 $set_data['select_com_agency_grosssale'] .= '<option value="0">ไม่พบข้อมูล</option>';
            }
        }else {
            $set_data['select_com_agency_grosssale'] = ' <div class="input-group col-lg-12 col-md-12 col-xs-12" >
            <select name="agency_grosssale" id="agency_grosssale" class="form-control select_search">
            <option value="" selected>ทั้งหมด</option> ';
    
        }

    $set_data['select_com_agency_grosssale'] .= ' </select>';

    echo json_encode($set_data);


}

else{



}




?>