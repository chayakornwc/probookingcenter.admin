<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $user_email   = $_REQUEST['user_email'];
                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

   $sql  = "SELECT * FROM `user` WHERE  `user_email` = '$user_email' AND `status` = '1'";

    $sql_all_row = $sql;
    
    $result = mysqli_query($CON,$sql);



    if(mysqli_num_rows($result)>=1){

        while ($data = mysqli_fetch_assoc($result)) {
            $set_data['result'][] = $data;
        }

        $set_data = return_object(200,$set_data['result']);
     
    }
    else{
        $set_data = return_object(204,'FALSE');
    }

}

/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
