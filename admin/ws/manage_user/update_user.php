<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $user_id               = $_REQUEST['user_id'];
 $status                = $_REQUEST['status'];
 $group_id              = $_REQUEST['group_id'];
 $user_fname            = $_REQUEST['user_fname'];
 $user_lname            = $_REQUEST['user_lname'];
  $user_nickname        = $_REQUEST['user_nickname'];
 $user_email            = $_REQUEST['user_email'];
 $user_address          = $_REQUEST['user_address'];
 $user_tel              = $_REQUEST['user_tel'];
 $user_line_id          = $_REQUEST['user_line_id'];
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
    

    $sql = "UPDATE `user` SET
                                `status`            = '$status',
                                `group_id`          = '$group_id',
                                `user_email`        = '$user_email',
                                 `user_fname`       = '$user_fname',
                                 `user_lname`       = '$user_lname',
                                 `user_nickname`    = '$user_nickname',
                                 `user_address`     = '$user_address',
                                 `user_tel`         = '$user_tel',
                                 `user_line_id`     = '$user_line_id',
                                 `update_user_id`   = '$update_user_id',
                                 `update_date`      = NOW(),
                                 `remark`           = '$remark'
                                 WHERE `user_id` = '$user_id' 
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
