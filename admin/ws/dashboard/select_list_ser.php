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

    $sql = "SELECT COALESCE(CONCAT(c.ser_code,' ',c.ser_name),'ไม่ระบุชื่อ') AS 'iName',SUM(a.book_receipt) AS 'Total'
            FROM `booking` a  
            LEFT OUTER JOIN period b
            on a.per_id = b.per_id
            LEFT OUTER JOIN series c
            on b.ser_id = c.ser_id
            WHERE a.book_date BETWEEN CONCAT(DATE_FORMAT(NOW(),'%Y-%m-'),'01 00:00:00') AND CONCAT(LAST_DAY(NOW()),' 23:59:59')
            And a.status <> 40
            GROUP by c.ser_id
            ORDER BY SUM(a.book_receipt) DESC
            LIMIT 10
                ";

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
