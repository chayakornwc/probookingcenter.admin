<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$date_from      = $_REQUEST['date_from'];
$date_to         = $_REQUEST['date_to'];
$country_id         = $_REQUEST['country_id'];
$ser_id         = $_REQUEST['ser_id'];
$sort         = $_REQUEST['sort'];




$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{
    $strWhere = '';

    if ($sort == '0'){
        $strWhereCountry = '';
        $strWhereSer = '';

        if ($country_id != ''){
            $strWhereCountry = ' AND d.country_id = '. $country_id;
        }
        if ($ser_id != ''){
            $strWhereSer = ' AND c.ser_id = '. $ser_id;
        }
        $strWhere = $strWhereCountry.' '. $strWhereSer . '   
                        GROUP BY a.per_id
                        ORDER BY d.country_id  , a.per_date_start , c.ser_id';
    }
  
        
$sql = "SELECT 
c.ser_code
,c.ser_name
,a.per_date_start
,a.per_date_end
,a.per_qty_seats
,(SELECT  COALESCE(SUM(b.`book_list_qty`),0) FROM `booking_list` b LEFT OUTER JOIN `booking` e on b.`book_code` = e.`book_code` WHERE e.`per_id` =  a.`per_id` 
AND b.`book_list_code` IN ('1','2','3','4','5')
AND e.`status` != 40 )  AS 'qty_book'
,COALESCE(SUM(b.book_receipt),0) as 'amounttotal'
,COALESCE(SUM(b.book_com_agency_company),0) + COALESCE(SUM(b.book_com_agency),0) as 'amountcom'
,COALESCE(SUM(b.book_receipt),0) + (COALESCE(SUM(b.book_com_agency_company),0) + COALESCE(SUM(b.book_com_agency),0)) as 'amountgrandtotal'
,a.per_cost + a.per_expenses as 'amountcost'
,(COALESCE(SUM(b.book_receipt),0) + (COALESCE(SUM(b.book_com_agency_company),0) + COALESCE(SUM(b.book_com_agency),0))) - (a.per_cost + a.per_expenses) as 'grosstotal'
,COALESCE((((COALESCE(SUM(b.book_receipt),0) + (COALESCE(SUM(b.book_com_agency_company),0) + COALESCE(SUM(b.book_com_agency),0))) - (a.per_cost + a.per_expenses)) /  (a.per_cost + a.per_expenses)) * 100,0) AS 'percent'
,a.status
,d.country_id
,d.country_name
,g.agen_com_name
,CONCAT(f.agen_fname,' ',f.agen_lname) as 'agentname'
FROM period a
LEFT OUTER JOIN booking b
on a.per_id = b.per_id
LEFT OUTER JOIN series c
on a.ser_id = c.ser_id
LEFT OUTER JOIN country d
on c.country_id = d.country_id
LEFT OUTER JOIN user e
on b.user_id = e.user_id
LEFT OUTER JOIN agency f
on b.agen_id = f.agen_id
LEFT OUTER JOIN agency_company g
on f.agency_company_id = g.agen_com_id
WHERE a.status <> 9
AND (a.per_date_start  BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59')
AND b.status <> 40
$strWhere
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
