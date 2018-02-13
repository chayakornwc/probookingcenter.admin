<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$ser_id      = $_REQUEST['ser_id'];

$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
  
    

    $sql = "SELECT 
                    a.`ser_id`,
                    a.`ser_name`,
                    a.`ser_code`,
                    a.`country_id`,
                    b.`country_name`,
                    a.`air_id`,
                    c.`air_name`,
                    a.`ser_deposit`,
                    a.`ser_route`,
                    a.`ser_show`,
                    a.`ser_url_img_1`,
                    a.`ser_url_img_2`,
                    a.`ser_url_img_3`,
                    a.`ser_url_img_4`,
                    a.`ser_url_img_5`,
                    a.`ser_url_word`,
                    a.`ser_url_pdf`,
                    a.`status`,
                    a.`ser_go_flight_code`,
                    a.`ser_go_route`,
                    a.`ser_go_time`,
                    a.`ser_return_flight_code`,
                    a.`ser_return_route`,
                    a.`ser_return_time`,
                    a.`ser_is_promote`,
                    a.`ser_is_recommend`,
                    a.`remark`
                FROM `series` a
                LEFT OUTER JOIN `country` b
                ON a.`country_id` = b.`country_id`
                LEFT OUTER JOIN `airline` c
                ON a.`air_id` = c.`air_id`
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
