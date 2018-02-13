<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$ser_id      = $_REQUEST['ser_id'];


$offset         = $_REQUEST['offset'];
$limit         = $_REQUEST['limit'];


$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
  
    

    $sql = "SELECT 
                    a.`per_id`,
                    a.`ser_id`,
                    a.`per_date_start`,
                    a.`per_date_end`,
                    a.`per_qty_seats`,
                    a.`per_price_1`,
                    a.`per_price_2`,
                    a.`per_price_3`,
                    a.`per_price_4`,
                    a.`per_price_5`,
                    a.`single_charge`,
                    a.`per_cost`,
                    a.`per_qty_seats`,
                    a.`per_expenses`,
                    a.`status`,
                    a.`per_on_fire`
                FROM `period` a
                WHERE (a.`ser_id` = '$ser_id'  )
                ";

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
