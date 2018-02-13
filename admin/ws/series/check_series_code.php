<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $ser_code   = $_REQUEST['ser_code'];
                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

   $sql  = "SELECT * FROM `series` WHERE  ser_code = '$ser_code' AND `status` = '1'";

    $sql_all_row = $sql;
    $sql         = $sql.set_limit($offset , $limit);
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;

    if(mysqli_num_rows($result)>=1){
     
        $set_data = return_object(200,'FALSE');
     
    }
    else{
        $set_data = return_object(204,'TRUE');
    }

}

/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
