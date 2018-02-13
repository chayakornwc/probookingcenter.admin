<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST

$method         = strtoupper($_REQUEST['method']);


#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
  
 

    $sql = "SELECT 
                    `group_id`,
                    `group_name`,
                    `create_user_id`,
                    `create_date`,
                    `update_user_id`,
                    `update_date`,
                    `menu_1`,
                    `menu_2`,
                    `menu_3`,
                    `menu_4`,
                    `menu_5`,
                    `menu_6`,
                    `menu_7`,
                    `menu_8`,
                    `menu_9`,
                    `menu_10`,
                    `menu_11`,
                    `menu_12`,
                    `menu_13`,
                    `menu_14`,
                    `menu_15`,
                    `menu_16`,
                    `menu_17`,
                    `menu_18`,
                    `remark`
                FROM `group`
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
