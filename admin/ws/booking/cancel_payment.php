<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $pay_id          = $_REQUEST['pay_id'];
 
                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "DELETE FROM `payment` WHERE `pay_id` = $pay_id  ";
                                 
                               
    
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
