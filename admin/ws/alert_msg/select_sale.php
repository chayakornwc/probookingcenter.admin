<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


$user_id           = $_REQUEST['user_id'];
$Limit           = $_REQUEST['Limit'];
$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 

   
    

$sql = "   SELECT 
a.book_id
,a.log_date
,a.detail
,b.book_code
,a.source
,(SELECT sum(bl.book_list_qty) FROM booking_list bl WHERE bl.book_code = b.book_code AND bl.book_list_code IN('1','2','3','4','5')) AS 'qty'
,CONCAT(c.user_fname,' ',c.user_lname) AS 'username'
,CONCAT(d.agen_fname,' ',d.agen_lname) AS 'agenname'
,e.agen_com_name
,p.book_status
,p.pay_received
,(SELECT COUNT(*) FROM `alert_msg` am WHERE am.`read_date` IS NULL AND am.user_id = '$user_id') AS 'count'
,(SELECT COUNT(*) FROM `alert_msg` am2 WHERE  am2.user_id = '$user_id') AS 'count_all'
,a.read_date
,COALESCE(CONCAT(us.`user_fname`,' ',us.`user_lname`),CONCAT(ag.`agen_fname`,' ',ag.`agen_lname`)) AS 'action_name'
,CONCAT(usc.`user_fname`,' ',usc.`user_lname`) AS 'action_name_cost'
,pd.per_id
,pd.per_date_start
,pd.per_date_end
,se.ser_id
,se.ser_name
,se.ser_code
FROM `alert_msg` a
LEFT OUTER JOIN booking b
on a.book_id = b.book_id
LEFT OUTER JOIN user c
on b.user_id = c.user_id
LEFT OUTER JOIN agency d
on b.agen_id = d.agen_id
LEFT OUTER JOIN agency_company e
on d.agency_company_id = e.agen_com_id
LEFT OUTER JOIN payment p
on a.pay_id = p.pay_id
LEFT OUTER JOIN `user` us
on p.`user_action` = us.`user_name`
LEFT OUTER JOIN `agency` ag
on p.`user_action` = ag.`agen_user_name`
LEFT OUTER JOIN `period` pd
on a.`book_id` = pd.`per_id`
LEFT OUTER JOIN `series` se
on pd.`ser_id` = se.`ser_id`
LEFT OUTER JOIN `user` usc
on a.`pay_id` = usc.`user_id`
WHERE a.user_id = '$user_id'
ORDER BY  a.log_date DESC LIMIT $Limit ";
               

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
