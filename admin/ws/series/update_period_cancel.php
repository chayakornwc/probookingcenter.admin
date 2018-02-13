<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST

 $per_id   = $_REQUEST['per_id'];



//$offset         = $_REQUEST['offset'];
//$limit         = $_REQUEST['limit'];


$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
  
    

    $sql = "UPDATE `period` SET 
                    `status` = '9'
                WHERE `per_id` = '$per_id'
                ";

   // $sql         = $sql.set_limit($offset , $limit);
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;

    if($result){
       // $sql = "SELECT LAST_INSERT_ID() FROM `user`";
        //$result = mysqli_query($CON,$sql);
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
