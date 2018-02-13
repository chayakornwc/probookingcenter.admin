<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $bankbook_id           = $_REQUEST['bankbook_id'];
 $bank_name             = $_REQUEST['bank_name'];
 $bankbook_code         = $_REQUEST['bankbook_code'];
 $bankbook_name         = $_REQUEST['bankbook_name'];
 $bankbook_branch       = $_REQUEST['bankbook_branch'];
 $status                = $_REQUEST['status'];
 $update_user_id        = $_REQUEST['update_user_id'];
 $remark                = $_REQUEST['remark'];
                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "UPDATE `bankbook` SET
                                `bank_name` = '$bank_name',
                                 `bankbook_code` = '$bankbook_code',
                                 `bankbook_name` = '$bankbook_name',
                                 `bankbook_branch` = '$bankbook_branch',
                                 `status` = '$status',
                                 `update_user_id` = '$update_user_id',
                                 `update_date` = NOW(),
                                 `remark` = '$remark'
                                 WHERE `bankbook_id` = '$bankbook_id' 
                               ";
    $sql         = $sql.set_limit($offset , $limit);
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;

    if($result){
       // $sql = "SELECT LAST_INSERT_ID() FROM `user`";
        //$result = mysqli_query($CON,$sql);
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
