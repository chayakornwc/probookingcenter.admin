<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST

$per_id        = $_REQUEST['per_id'];
$per_cost_file      = $_REQUEST['per_cost_file'];


$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "UPDATE `period`    SET
                                    `per_cost_file` = '$per_cost_file'
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
