<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$date_from      = $_REQUEST['date_from'];
$date_to         = $_REQUEST['date_to'];
$com_agency_id         = $_REQUEST['com_agency_id'];
$agency_id         = $_REQUEST['agency_id'];
$sort         = $_REQUEST['sort'];




$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{
    $strWhere = '';
  
    if ($sort == '1'){
        $strWhereagen_com_id = '';
        $strWhereagency_id = '';

        if ($com_agency_id != ''){
            $strWhereagen_com_id  = ' AND  e.agen_com_id = '. $com_agency_id;
        }
        if ($agency_id != ''){
            $strWhereagency_id = ' AND  d.agen_id = '. $agency_id;
        }
        $strWhere = $strWhereagen_com_id.' '. $strWhereagency_id;
      
    }
        
$sql = "SELECT
c.ser_code
,b.per_date_start
,b.per_date_end
,e.agen_com_name
,CONCAT(d.agen_fname,' ',d.agen_lname) as 'agentname'

,b.per_qty_seats
,(SELECT  COALESCE(SUM(bl.`book_list_qty`),0) FROM `booking_list` bl  
  LEFT OUTER JOIN booking bi on bl.book_code = bi.book_code
  WHERE bl.`book_list_code` IN ('1','2','3','4','5')
  and bi.status <> 40
  and bi.per_id = a.per_id
AND bi.agen_id = a.agen_id)   AS 'qty_book'
,b.status
,COALESCE(SUM(a.book_receipt),0) as 'amounttotal'
,COALESCE(SUM(a.book_com_agency_company),0) + COALESCE(SUM(a.book_com_agency),0) as 'amountcom'
,COALESCE(SUM(a.book_receipt),0) + (COALESCE(SUM(a.book_com_agency_company),0) + COALESCE(SUM(a.book_com_agency),0)) as 'amountgrandtotal'

FROM booking a
LEFT OUTER JOIN period b
on a.per_id = b.per_id
LEFT OUTER JOIN series c
on b.ser_id = c.ser_id
LEFT OUTER JOIN agency d
on a.agen_id = d.agen_id
LEFT OUTER JOIN agency_company e
on d.agency_company_id = e.agen_com_id
LEFT OUTER JOIN country ct
on c.country_id = ct.country_id
WHERE b.status <> 9
AND (b.per_date_start  BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59')
AND a.status <> 40
$strWhere
GROUP BY e.agen_com_name,d.agen_id
ORDER BY e.agen_com_name,d.agen_fname,ct.country_id  , b.per_date_start , c.ser_id
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
