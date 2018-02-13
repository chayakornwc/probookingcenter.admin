<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $bankbook_code   = $_REQUEST['bankbook_code'];
                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

   $sql  = "SELECT `bankbook_code` FROM `bankbook` WHERE  `bankbook_code` = $bankbook_code";

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
