<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$word_search      = $_REQUEST['word_search'];

$country          = $_REQUEST['country_id'];

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
    if($country!=''){
        $sql_country = 'a.`country_id` = '.$country;
    }
    else{
        $sql_country = '1=1';
    }

    $sql = "SELECT 
                    a.`ser_id`,
                    a.`ser_name`,
                    a.`ser_code`,
                    a.`country_id`,
                    a.`ser_url_img_1`,
                    a.`ser_url_img_2`,
                    a.`ser_url_word`,
                    a.`ser_url_pdf`,
                    b.`country_name`,
                    a.`air_id`,
                    c.`air_name`
                FROM `series` a
                LEFT OUTER JOIN `country` b
                ON a.`country_id` = b.`country_id`
                LEFT OUTER JOIN `airline` c
                ON a.`air_id` = c.`air_id`
                WHERE (a.`status` != '9'  ) 
                AND $sql_country
                AND (  a.`ser_name`  LIKE  '%$word_search%'
                    OR a.`ser_code` LIKE  '%$word_search%'
                    OR b.`country_name` LIKE  '%$word_search%'
                    OR c.`air_name` LIKE  '%$word_search%'
                )
                ORDER BY  a.`ser_id` DESC
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
        $set_data = return_object(204,$sql);
    }

}

/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
