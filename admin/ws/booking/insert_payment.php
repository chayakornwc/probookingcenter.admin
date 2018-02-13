<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $pay_date          = $_REQUEST['pay_date'];
 $pay_time          = $_REQUEST['pay_time'];
 $pay_url_file      = $_REQUEST['pay_url_file'];
 $pay_received      = $_REQUEST['pay_received'];
 $book_id           = $_REQUEST['book_id'];
 $book_status       = $_REQUEST['book_status'];
 $bankbook_id       = $_REQUEST['bankbook_id'];
 $create_user_id    = $_REQUEST['create_user_id'];
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
    

    $sql = "INSERT INTO `payment`
                                (
                                 `status`,
                                 `pay_date`,
                                 `pay_time`,
                                 `pay_url_file`,
                                 `pay_received`,
                                 `book_id`,
                                 `book_status`,
                                 `create_user_id`,
                                 `create_date`,
                                 `bankbook_id`,
                                 `remark`,
                                 `user_action`
                                 ) VALUES (
                                 '$status',
                                 '$pay_date',
                                 '$pay_time',
                                 '$pay_url_file',
                                 '$pay_received',
                                 $book_id,
                                 '$book_status',
                                 '$create_user_id',
                                 NOW() ,
                                 '$bankbook_id',
                                 '$remark',
                                 '$user_action'
                                 )";
    
    $result = mysqli_query($CON,$sql);
   

    $set_data['sql'] = $sql;

    if($result){
         $sql = "SELECT LAST_INSERT_ID() FROM `payment`";
        $result = mysqli_insert_id($CON); 
     
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
