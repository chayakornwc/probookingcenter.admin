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

   
    

    $sql = "   SELECT '' AS 'bankbook_id' , '--ระบุธนาคาร--' AS 'bank_name'
                union
                SELECT 
                `bankbook_id`
                ,CONCAT(`bank_name`,'(',`bankbook_name`,')') AS 'bank_name'
                FROM `bankbook` WHERE `status` NOT IN ('2','9') ORDER BY `bank_name`  ";
               

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
        $set_data = return_object(204);
    }

}

/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
