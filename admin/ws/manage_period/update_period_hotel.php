<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $per_id          = $_REQUEST['per_id'];
 $per_hotel          = $_REQUEST['per_hotel'];
 $per_hotel_tel          = $_REQUEST['per_hotel_tel'];
 $arrival_date          = $_REQUEST['arrival_date'];

                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "UPDATE `period` SET
                                 `per_hotel` = '$per_hotel',
                                 `per_hotel_tel` = '$per_hotel_tel',
                                 `arrival_date` = '$arrival_date'
                                 WHERE `per_id` = $per_id
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
