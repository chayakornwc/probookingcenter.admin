<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$word_search     = $_REQUEST['word_search'];
$country_name      = $_REQUEST['country_name'];

$date_start       = $_REQUEST['date_start'];
$date_end         = $_REQUEST['date_end'];



$offset         = $_REQUEST['offset'];
$limit          = $_REQUEST['limit'];


$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
      if($country_name!=''){
        $sql_country = "c.`country_name` = '$country_name'";
    }
    else{
        $sql_country = '1=1';
    }


  if($date_start!=''){
        $sql_date_start = "(a.`per_date_start` >= '$date_start')";
    }
    else{
        $sql_date_start = '1=1';
    }
    
    if($date_end!=''){
        $sql_date_end = "(a.`per_date_end` <= '$date_end')";
    }
    else{
        $sql_date_end = "1=1";
    }

    $sql = "SELECT 
                    a.`ser_id`,
                    a.`ser_name`,
                    a.`ser_code`,
                    a.`country_id`,
                    a.`ser_city`,
                    a.`air_id`,
                    a.`ser_route`,
                    COALESCE((SELECT b.`per_price_1` FROM `period` b WHERE b.`ser_id` = a.`ser_id` ORDER BY b.`per_id` DESC LIMIT 1),0) AS 'price', 
                    a.`ser_url_img_1`,
                    a.`ser_url_img_2`,
                    a.`ser_url_img_3`,
                    a.`ser_url_img_4`,
                    a.`ser_url_img_5`,
                    a.`ser_show`,
                    a.`remark`,
                    c.`country_name`,
                    d.`air_name`,
                    d.`air_url_img`
                FROM `series` a
                LEFT OUTER JOIN `country` c
                ON a.`country_id` = c.`country_id`
                LEFT OUTER JOIN `airline` d
                ON a.`air_id` = d.`air_id`
                WHERE (a.`status` = '1' )
                AND $sql_country
                AND (  a.`ser_name`  LIKE  '%$word_search%'
                    OR a.`ser_code` LIKE  '%$word_search%'
                    )
                ORDER BY a.`ser_id` DESC
                ";

   
  
    $result = mysqli_query($CON,$sql);

    
    



   if(mysqli_num_rows($result) == 0 ){
        $set_data = return_object(204);
   }
   else {
        if (mysqli_num_rows($result) > 0) {
              while ($data = mysqli_fetch_assoc($result)) {
                        $ser_id = $data['ser_id'];
                        $sql3 = "SELECT 
                                        a.`per_date_start`,
                                        a.`per_date_end`,
                                        a.`per_price_1`,
                                        a.`per_price_2`,
                                        a.`per_price_3`,
                                        a.`per_price_4`,
                                        a.`per_price_5`,
                                        a.`per_qty_seats`
                                    FROM `period` a
                                    WHERE a.`ser_id` = $ser_id
                                    AND a.`status` NOT IN ('3','9')
                                    AND $sql_date_start AND $sql_date_end
                                    ORDER BY a.`per_id` DESC LIMIT 3
                                 ";
                        $result3 = mysqli_query($CON,$sql3);

                        while ($data2 = mysqli_fetch_assoc($result3)) {
                            $data['date_period'][] = $data2;
                        }
                            
                        $set_data['result']['ser'][] = $data;
                }
        }

        $set_data = return_object(200,$set_data['result']);
   }
}
/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
