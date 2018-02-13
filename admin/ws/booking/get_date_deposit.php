<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST

$per_id           = $_REQUEST['per_id'];
$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 

   
    

    //$sql = "   SELECT DATE_ADD(CURDATE(), INTERVAL +2 DAY) AS 'date_deposit' , FROM period WHERE per_id = '$per_id'  ";
    $sql = "   SELECT CASE WHEN CURDATE() > DATE_ADD(per_date_start, INTERVAL -23 DAY) THEN null ELSE DATE_ADD(CURDATE(), INTERVAL +2 DAY)  END as 'date_deposit'
                 FROM period WHERE per_id = '$per_id'  ";
               

    $sql_all_row = $sql;
    $sql         = $sql.set_limit($offset , $limit);
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
