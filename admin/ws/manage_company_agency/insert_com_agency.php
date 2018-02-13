<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $agen_com_name          = $_REQUEST['agen_com_name'];
 $agen_com_tel  = $_REQUEST['agen_com_tel'];
 $agen_com_fax      = $_REQUEST['agen_com_fax'];
 $agen_com_ttt_on      = $_REQUEST['agen_com_ttt_on'];
 $agen_com_ttt_url_img   = $_REQUEST['agen_com_ttt_url_img'];
 $agen_com_logo_img      = $_REQUEST['agen_com_logo_img'];
 $agen_com_address1         = $_REQUEST['agen_com_address1'];
 $agen_com_address2         = $_REQUEST['agen_com_address2'];
 $create_user_id        = $_REQUEST['create_user_id'];
 $remark                = $_REQUEST['remark'];
 $status                = $_REQUEST['status'];
                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "INSERT INTO `agency_company`
                                (
                                 `agen_com_name`,
                                 `agen_com_tel`,
                                 `agen_com_fax`,
                                 `agen_com_ttt_on`,
                                 `agen_com_ttt_url_img`,
                                 `agen_com_logo_img`,
                                 `agen_com_address1`,
                                 `agen_com_address2`,
                                 `create_user_id`,
                                 `create_date`,
                                 `remark`,
                                 `status`
                                 ) VALUES (
                                 '$agen_com_name',
                                 '$agen_com_tel',
                                 '$agen_com_fax',
                                 '$agen_com_ttt_on',
                                 '$agen_com_ttt_url_img',
                                 '$agen_com_logo_img',
                                 '$agen_com_address1',
                                 '$agen_com_address2',
                                 '$create_user_id',
                                 NOW() ,
                                 '$remark',
                                 '$status'
                                 )";
    
    $result = mysqli_query($CON,$sql);
   

    $set_data['sql'] = $sql;

    if($result){
        $sql = "SELECT LAST_INSERT_ID() FROM `agency_company`";
        $result = mysqli_query($CON,$sql);
        $set_data = return_object(200,$result);
     
    }
    else{
        $set_data = return_object(204);
    }

}

/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
