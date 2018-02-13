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
    

    $sql = "UPDATE `prefixnumber` SET
                                 `pre_year`  = SUBSTR(DATE_FORMAT(NOW(),'%Y'),3),
                                 `pre_month`  = DATE_FORMAT(NOW(),'%m'),
                                 `pre_booking`  = 1,
                                 `pre_invoice`  = 1,
                                 `pre_receipt`  = 1
                                 WHERE `pre_month` <> DATE_FORMAT(NOW(),'%m')
                               
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
