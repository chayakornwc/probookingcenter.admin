<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $group_id   = $_REQUEST['group_id'];
 $group_name   = $_REQUEST['group_name'];
 $update_user_id   = $_REQUEST['update_user_id'];
 $remark   = $_REQUEST['remark'];
                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "UPDATE `group` SET
                                 `group_name` = '$group_name',
                                 `update_user_id` = '$update_user_id',
                                 `update_date` = NOW(),
                                 `remark` = '$remark'
                                 WHERE `group_id` = '$group_id' 
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
