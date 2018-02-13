<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$datefrom      = $_REQUEST['datefrom'];
$dateto         = $_REQUEST['dateto'];
$countryfrom         = $_REQUEST['countryfrom'];
$countryto         = $_REQUEST['countryto'];
$serfrom         = $_REQUEST['serfrom'];
$serto         = $_REQUEST['serto'];
$userfrom         = $_REQUEST['userfrom'];
$userto         = $_REQUEST['userto'];
$agenfrom         = $_REQUEST['agenfrom'];
$agento         = $_REQUEST['agento'];




$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
   
   
    

    $sql = "SELECT 
            a.book_code
            ,a.book_date
            ,CONCAT(d.agen_fname,' ',d.agen_lname) AS 'agenname'
            ,CONCAT(e.user_fname,' ',e.user_lname) AS 'username'
            ,c.ser_code
            ,b.per_date_start
            ,b.per_date_end
            ,(SELECT SUM(bl.book_list_qty) FROM booking_list bl WHERE bl.book_code = a.book_code AND bl.`book_list_code` IN ('1','2','3','4','5') ) AS 'QTY'
            ,a.book_amountgrandtotal
            ,f.country_id
            ,f.country_name
            FROM `booking` a 
            LEFT OUTER JOIN period b
            ON a.per_id = b.per_id
            LEFT OUTER JOIN series c
            on b.ser_id = c.ser_id
            LEFT OUTER JOIN agency d
            on a.agen_id = d.agen_id
            LEFT OUTER JOIN user e
            on a.user_id = e.user_id
            LEFT OUTER JOIN country f
            on c.country_id = f.country_id
            WHERE a.status NOT IN ('40')
            ORDER BY f.country_id ASC , a.book_date ASC";
               

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
        $set_data = return_object(204,$sql);
    }

}

/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
