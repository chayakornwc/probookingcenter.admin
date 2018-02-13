<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $status                = $_REQUEST['status'];
 $group_id              = $_REQUEST['group_id'];
 $user_name             = $_REQUEST['user_name'];
 $user_password         = $_REQUEST['user_password'];
 $user_fname            = $_REQUEST['user_fname'];
 $user_lname            = $_REQUEST['user_lname'];
 $user_nickname         = $_REQUEST['user_nickname'];
 $user_email            = $_REQUEST['user_email'];
 $user_address          = $_REQUEST['user_address'];
 $user_tel              = $_REQUEST['user_tel'];
 $user_line_id          = $_REQUEST['user_line_id'];
 $create_user_id        = $_REQUEST['create_user_id'];
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
    

    $sql = "INSERT INTO `user`
                                (
                                `status`,
                                `group_id`,
                                 `user_name`,
                                 `user_password`,
                                 `user_fname`,
                                 `user_lname`,
                                 `user_nickname`,
                                 `user_email`,
                                 `user_address`,
                                 `user_tel`,
                                 `user_line_id`,
                                 `create_user_id`,
                                 `create_date`,
                                 `remark`
                                 ) VALUES (
                                     '$status',
                                     '$group_id',
                                     '$user_name',
                                     '$user_password',
                                     '$user_fname',
                                     '$user_lname',
                                     '$user_nickname',
                                     '$user_email',
                                     '$user_address',
                                     '$user_tel',
                                     '$user_line_id',
                                     '$create_user_id',
                                     NOW(),
                                     '$remark'
                                 )";
    $sql         = $sql.set_limit($offset , $limit);
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;

    if($result){
        $sql = "SELECT LAST_INSERT_ID() FROM `user`";
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
