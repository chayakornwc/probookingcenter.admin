<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $book_id        = $_REQUEST['book_id'];
 $detail         = $_REQUEST['detail'];
 $source            = $_REQUEST['source'];
 $pay_id            = $_REQUEST['pay_id'];
 $user_id            = $_REQUEST['user_id'];
 $remark_cancel            = $_REQUEST['remark'];

                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "INSERT INTO `alert_msg`
                                (
                                 `book_id`,
                                 `detail`,
                                 `source`,
                                 `log_date`,
                                 `user_id`,
                                 `pay_id`
                                 ) VALUES (
                                 $book_id,
                                 '$detail',
                                 '$source',
                                 NOW(),
                                 $user_id,
                                 $pay_id
                                 )";
    
    $result = mysqli_query($CON,$sql);
   
    if ($source == '101payment' || $source == '102app_payment' || $source == '103cxl_payment' ){ // ชำระเงิน อนุมัติ , ไม่อนุมัติ
        $sql2 = " SELECT `user_id` FROM `user` WHERE `group_id` = 2"; // select แผนกบัญชี
        $result2 = mysqli_query($CON,$sql2);
         if (mysqli_num_rows($result2) > 0) {
             while ($data = mysqli_fetch_assoc($result2)) {
                $user_id_account = $data['user_id'];
                    $sql3 = "INSERT INTO `alert_msg`
                                (
                                 `book_id`,
                                 `detail`,
                                 `source`,
                                 `log_date`,
                                 `user_id`,
                                 `pay_id`
                                 ) VALUES (
                                 $book_id,
                                 '$detail',
                                 '$source',
                                 NOW(),
                                 $user_id_account,
                                 $pay_id
                                 )";
    
                    $result3 = mysqli_query($CON,$sql3);

             }


        }
    }
    if ($source == '104file_cost'){ // แนบไฟล์ cost แล้ว
        $sql4 = " SELECT `user_id` FROM `user` WHERE `group_id` IN ('1','2')"; // select แผนกบัญชี , แผนก ผู้บริหาร
        $result4 = mysqli_query($CON,$sql4);
         if (mysqli_num_rows($result4) > 0) {
             while ($data2 = mysqli_fetch_assoc($result4)) {
                $user_id = $data2['user_id'];
                    $sql5 = "INSERT INTO `alert_msg`
                                (
                                 `book_id`,
                                 `detail`,
                                 `source`,
                                 `log_date`,
                                 `user_id`,
                                 `pay_id`
                                 ) VALUES (
                                 $book_id,
                                 '$detail',
                                 '$source',
                                 NOW(),
                                 $user_id,
                                 $pay_id
                                 )";
    
                    $result5 = mysqli_query($CON,$sql5);

             }
        }

    } 

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
