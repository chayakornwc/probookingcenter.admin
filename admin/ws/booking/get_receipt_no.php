<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST

$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 

   
    

    $sql = "   SELECT COUNT(`receipt_code`) AS 'receipt_no' ,SUBSTR(DATE_FORMAT(NOW(),'%Y/%m'),3) AS 'format_running' FROM `booking` WHERE COALESCE(`receipt_code`,'') <> '' ";
               

    $sql_all_row = $sql;
    
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;

    if(mysqli_num_rows($result)>0){
        
        
        while ($data = mysqli_fetch_assoc($result)) {
            $set_data['result'][] = $data;
        }

        
        $set_data = return_object(200,$set_data['result']);
        
        
        #ALL ROW
        
        $set_data['all_row'] = mysqli_num_rows(mysqli_query($CON,$sql_all_row));
    }
    else{
        $sql = "   SELECT SUBSTR(DATE_FORMAT(NOW(),'%Y/%m'),3) AS 'format_running' ";
        $result = mysqli_query($CON,$sql);    
        while ($data = mysqli_fetch_assoc($result)) {
            $set_data['result'][] = $data;
        }

        $set_data = return_object(204,$set_data['result']);
    }

}

/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
