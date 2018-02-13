<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$date_from      = $_REQUEST['date_from'];
$date_to         = $_REQUEST['date_to'];
$user_id         = $_REQUEST['user_id'];





$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{
    

$sql = "SELECT 
s.ser_code
,pd.per_date_start
,pd.per_date_end
,ct.country_id
,ct.country_name
,ac.agen_com_name
,b.book_receipt
,b.invoice_code
,(SELECT  SUM(bl.`book_list_qty`) FROM `booking_list` bl  WHERE bl.`book_code` =  b.`book_code` AND bl.`book_list_code` IN ('1','2','3','4','5'))  AS 'QTY'
,CASE WHEN  (SELECT sum(bk.book_receipt) from booking bk where bk.per_id = pd.per_id and bk.status != 40) > (pd.per_cost + pd.per_expenses) THEN 'Com' ELSE 'No Com' END AS 'Situation'
,a.user_id
,a.user_fname
,a.user_lname
From user a
LEFT OUTER JOIN booking b
ON a.user_id = b.user_id
LEFT OUTER JOIN period pd
ON b.per_id = pd.per_id
LEFT OUTER JOIN series s
ON pd.ser_id = s.ser_id
LEFT OUTER JOIN country ct
on s.country_id = ct.country_id
LEFT OUTER JOIN agency ag
on b.agen_id = ag.agen_id
LEFT OUTER JOIN agency_company ac
on ag.agency_company_id = ac.agen_com_id
WHERE pd.per_cost <> 0
AND b.status <> 40
AND pd.status = 3
AND a.user_id = $user_id
AND (pd.per_date_start  BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59')
AND (SELECT  SUM(bl.`book_list_qty`) FROM `booking_list` bl  WHERE bl.`book_code` =  b.`book_code` AND bl.`book_list_code` IN ('1','2','3','4','5')) > 0
ORDER BY ct.country_id , s.ser_id , pd.per_id ,b.book_id
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
        $set_data = return_object(204,$sql);
    }

}

/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
