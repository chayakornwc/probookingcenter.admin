<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $pay_id          = $_REQUEST['pay_id'];
 $book_id           = $_REQUEST['book_id'];
 $book_status       = $_REQUEST['book_status'];
 $update_user_id    = $_REQUEST['update_user_id'];
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
                                 `update_user_id`= '$update_user_id',
                                 `update_date`= NOW()
                                 WHERE `pay_id` = $pay_id
                                ";
    
    $result = mysqli_query($CON,$sql);
   

    $set_data['sql'] = $sql;

    if($result){
        $set_data = return_object(200,$result);
        $sql = " SELECT COALESCE(SUM(`pay_received`),0) AS 'pay_received' FROM `payment`  WHERE `book_id` = $book_id AND `status` = '1'";
        $result = mysqli_query($CON,$sql);
            if(mysqli_num_rows($result) == 1){
                 while ($data = mysqli_fetch_assoc($result)) {
                     $set_data['result'][] = $data;
                    }
               $pay_received = $set_data['result']['0']['pay_received'];
               $sql = " UPDATE `booking` SET
                                        `book_receipt`  = '$pay_received',
                                        `status`        = '$book_status',
                                        `update_user_id`= '$update_user_id',
                                        `update_date`   = NOW()
                                        WHERE `book_id` = $book_id
                        ";
               $result = mysqli_query($CON,$sql);
              
            }
     
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
