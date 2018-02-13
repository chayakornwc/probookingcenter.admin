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
sum(a.sumtotal) as 'sumtotal'
,sum(a.sumqty) as 'sumqty'
,sum(a.sumperiod) as 'sumperiod'

FROM (( 
 SELECT
sum(a.book_receipt) as 'sumtotal',0 as 'sumqty',0 as 'sumperiod'
FROM `booking` a
WHERE a.book_date BETWEEN CONCAT(DATE_FORMAT(NOW(),'%Y-%m-'),'01 00:00:00') AND CONCAT(LAST_DAY(NOW()),' 23:59:59')
AND a.status <> 40
AND book_receipt > 0
) UNION ( 
     SELECT
0 as 'sumtotal',sum(a.book_list_qty) as 'sumqty',0 as 'sumperiod'
FROM `booking_list` a
LEFT OUTER JOIN booking b
on a.book_code = b.book_code
WHERE b.book_date BETWEEN CONCAT(DATE_FORMAT(NOW(),'%Y-%m-'),'01 00:00:00') AND CONCAT(LAST_DAY(NOW()),' 23:59:59')
AND b.status <> 40
AND a.book_list_code IN('1','2','3','4','5')
)UNION ( 
  SELECT
  0 as 'sumtotal',0 as 'sumqty',  COUNT(per_id) as 'sumperiod'
    FROM period a
    WHERE a.status = 1
 )) as a
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
