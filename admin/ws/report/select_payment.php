<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$date_from      = $_REQUEST['date_from'];
$date_to         = $_REQUEST['date_to'];
$bankbook_id         = $_REQUEST['bankbook_id'];





$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{
    $strwhere_bankbook_id ='';
    #SEARCH 
   if ($bankbook_id != ''){
       $strwhere_bankbook_id = " AND e.bankbook_id = $bankbook_id   ";
   }
   
    

    $sql = "SELECT 
a.pay_id
,a.pay_date
,a.pay_time
,b.book_code
,d.ser_code
,DAY(c.per_date_start) as 'per_date_start'
,c.per_date_end
,(SELECT SUM(bl.book_list_qty) FROM booking_list bl WHERE b.book_code = bl.book_code AND bl.book_list_code IN ('1','2','3','4','5')) AS 'qty'
,ac.agen_com_name
,a.pay_received
,e.bankbook_id
,e.bank_name
,e.bankbook_code
,e.bankbook_name
,e.bankbook_branch
,a.book_status
FROM payment a
LEFT OUTER JOIN booking b
on a.book_id = b.book_id
LEFT OUTER JOIN period c
on b.per_id = c.per_id
LEFT OUTER JOIN series d
on c.ser_id = d.ser_id
LEFT OUTER JOIN bankbook e
on a.bankbook_id = e.bankbook_id
LEFT OUTER JOIN agency ag
on b.agen_id = ag.agen_id
LEFT OUTER JOIN agency_company ac
on ag.agency_company_id = ac.agen_com_id
WHERE a.status = 1
AND (a.pay_date  BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59')
$strwhere_bankbook_id
ORDER BY  a.pay_date ASC,a.pay_time ASC, b.book_code ASC";
               

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
