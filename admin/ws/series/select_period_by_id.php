<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$per_id      = $_REQUEST['per_id'];

$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
  
    

    $sql = "SELECT  `per_id`, 
                    `per_date_start`,
                    `per_date_end`,
                    `per_price_1`,
                    `per_price_2`,
                    `per_price_3`, 
                    `per_price_4`, 
                    `per_price_5`, 
                    `per_qty_seats`, 
                    `per_cost`, 
                    `per_expenses`,
                    `status`, 
                    `ser_id`, 
                    `create_user_id`, 
                    `create_date`, 
                    `update_user_id`, 
                    `update_date`, 
                    `remark`, 
                    `per_com_agency`, 
                    `per_com_company_agency`,
                    `single_charge`, 
                    `per_url_word`, 
                    `per_url_pdf` ,
                    `per_cost_file`,
                    `per_on_fire`
                    FROM `period` 
                    WHERE `per_id` = $per_id";

    
    $sql         = $sql;
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;

    if(mysqli_num_rows($result)>0){

      while ($data = mysqli_fetch_assoc($result)) {
                        
                        $sql3 = "SELECT 
                                        a.`bus_id`,
                                        a.`per_id`,
                                        a.`bus_no`,
                                        a.`bus_qty`
                                    FROM `bus_list` a
                                    WHERE a.`per_id` = $per_id
                                    ORDER BY  a.`bus_no`
                                 ";
                        $result3 = mysqli_query($CON,$sql3);

                        while ($data2 = mysqli_fetch_assoc($result3)) {
                            $data['bus'][] = $data2;
                        }
                            
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
