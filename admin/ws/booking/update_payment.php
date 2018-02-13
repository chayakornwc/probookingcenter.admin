<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $pay_id          = $_REQUEST['pay_id'];
 $pay_date          = $_REQUEST['pay_date'];
 $pay_time          = $_REQUEST['pay_time'];
 $pay_url_file      = $_REQUEST['pay_url_file'];
 $pay_received      = $_REQUEST['pay_received'];
 $book_id           = $_REQUEST['book_id'];
 $book_status       = $_REQUEST['book_status'];
 $bankbook_id       = $_REQUEST['bankbook_id'];
 $update_user_id    = $_REQUEST['update_user_id'];
 $user_action       = $_REQUEST['user_action'];
 $remark       = $_REQUEST['remark'];
 $status            = $_REQUEST['status'];
                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "UPDATE `payment` SET
                                 `status` = '$status',
                                 `pay_date` = '$pay_date',
                                 `pay_time`= '$pay_time',
                                 `pay_url_file`= '$pay_url_file',
                                 `pay_received`= '$pay_received',
                                 `book_id`= $book_id,
                                 `book_status`= '$book_status',
                                 `update_user_id`= '$update_user_id',
                                 `update_date`= NOW(),
                                 `bankbook_id`= '$bankbook_id',
                                 `remark`= '$remark',
                                 `user_action`= '$user_action'
                                 WHERE `pay_id` = $pay_id
                                ";
    
    $result = mysqli_query($CON,$sql);
   

    $set_data['sql'] = $sql;

    if($result){
      
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
