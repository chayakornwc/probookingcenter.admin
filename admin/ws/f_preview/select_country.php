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
      if($country_id!=''){
        $sql_country = 'a.`country_id` = '.$country_id;
    }
    else{
        $sql_country = '1=1';
    }
    $sql = "SELECT 
                    `country_id`,
                    `country_name`,
                    `country_deposit`,
                    `create_user_id`,
                    `create_date`,
                    `update_user_id`,
                    `update_date`,
                    `remark`,
                    `status`
                FROM `country`
                WHERE (`status` = '1' )
                ";
                

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
