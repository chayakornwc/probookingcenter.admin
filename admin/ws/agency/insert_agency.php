<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $agen_user_name        = $_REQUEST['agen_user_name'];
 $agen_password         = $_REQUEST['agen_password'];
 $agen_fname            = $_REQUEST['agen_fname'];
 $agen_lname            = $_REQUEST['agen_lname'];
 $agen_nickname         = $_REQUEST['agen_nickname'];
 $agen_position         = $_REQUEST['agen_position'];
 $agen_email            = $_REQUEST['agen_email'];
 $agen_tel              = $_REQUEST['agen_tel'];
 $agen_line_id          = $_REQUEST['agen_line_id'];
 $agen_skype            = $_REQUEST['agen_skype'];
 $agency_company_id          = $_REQUEST['agency_company_id'];
 $create_user_id        = $_REQUEST['create_user_id'];
 $remark                = $_REQUEST['remark'];
 $status                = $_REQUEST['status'];
 $agen_show                = $_REQUEST['agen_show'];
                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "INSERT INTO `agency`
                                (
                                 `agen_user_name`,
                                 `agen_password`,
                                 `agen_fname`,
                                 `agen_lname`,
                                 `agen_nickname`,
                                 `agen_position`,
                                 `agen_email`,
                                 `agen_tel`,
                                 `agen_line_id`,
                                 `agen_skype`,
                                 `agency_company_id`,
                                 `create_user_id`,
                                 `create_date`,
                                 `remark`,
                                 `agen_show`,
                                 `status`
                                 ) VALUES (
                                 '$agen_user_name',
                                 '$agen_password',
                                 '$agen_fname',
                                 '$agen_lname',
                                 '$agen_nickname',
                                 '$agen_position',
                                 '$agen_email',
                                 '$agen_tel',
                                 '$agen_line_id',
                                 '$agen_skype',
                                 '$agency_company_id',
                                 '$create_user_id',
                                 NOW() ,
                                 '$remark',
                                 '$agen_show',
                                 '$status'
                                 )";
    
    $result = mysqli_query($CON,$sql);
   

    $set_data['sql'] = $sql;

    if($result){
        $sql = "SELECT LAST_INSERT_ID() FROM `agency`";
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
