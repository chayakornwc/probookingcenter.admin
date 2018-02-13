<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    
   // check due deposit date
    $sql = "UPDATE `booking` SET
                                 `status` = '40',
                                 `status_cancel`  = '3'
                                 WHERE CONCAT(DATE_FORMAT(`book_due_date_deposit`,'%Y-%m-%d'),' 23:59:59')  < NOW()
                                 AND `book_master_deposit` <> 0
                                 AND `book_receipt` = 0 AND `status` IN ('0','5','10')
                                ";
    
    $result = mysqli_query($CON,$sql);
   
   // check due full paymeny date
  /*  $sql = " UPDATE `booking` SET
                                 `status` = '40',
                                 `status_cancel`  = '3'
                                 WHERE CONCAT(DATE_FORMAT(`book_due_date_full_payment`,'%Y-%m-%d'),' 23:59:59')  < NOW()
                                 AND `status` <> '35'
                                "; */
    
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
