<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$per_id         = $_REQUEST['per_id'];
$bus_no         = $_REQUEST['bus_no'];


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


    $sql = "SELECT  
a.per_id
,b.bus_no
,a.per_date_start
,a.per_date_end
,b.user_id
,e.user_fname
,e.user_lname
,b.book_code
,b.invoice_code
,b.agen_id
,ac.agen_com_name
,ag.agen_fname
,ag.agen_lname
,b.book_room_twin
,b.book_room_double
,b.book_room_triple
,b.book_room_single
,d.per_leader_id
,d.room_name_thai AS 'leader_room_name_thai'
,d.room_prename AS 'leader_room_prename'
,d.room_fname AS 'leader_room_fname'
,d.room_lname AS 'leader_room_lname'
,d.room_sex AS 'leader_room_sex'
,d.room_country AS 'leader_room_country'
,d.room_nationality AS 'leader_room_nationality'
,d.room_address AS 'leader_room_address'
,d.room_birthday AS 'leader_room_birthday'
,d.room_passportno AS 'leader_room_passportno'
,d.room_expire AS 'leader_room_expire'
,d.room_file AS 'leader_room_file'
,d.room_remark AS 'leader_room_remark'
,d.room_career AS 'leader_career'
,d.room_placeofbirth AS 'leader_placeofbirth'
,d.room_place_pp AS 'leader_place_pp'
,d.room_date_pp AS 'leader_date_pp'
,s.ser_id
,s.ser_code
,s.ser_name
,s.ser_go_flight_code
,s.ser_go_route
,s.ser_go_time
,s.ser_return_flight_code
,s.ser_return_route
,s.ser_return_time
,ct.country_id
,ct.country_name
FROM period a
LEFT OUTER JOIN booking b
on a.per_id = b.per_id
LEFT OUTER JOIN user e
on b.user_id = e.user_id
LEFT OUTER JOIN period_leader d
on a.per_id = d.per_id
AND d.bus_no = $bus_no
LEFT OUTER JOIN agency ag
on b.agen_id = ag.agen_id
LEFT OUTER JOIN agency_company ac
on ag.agency_company_id = ac.agen_com_id
LEFT OUTER JOIN series s
on a.ser_id = s.ser_id
LEFT OUTER JOIN country ct
on s.country_id = ct.country_id
WHERE a.per_id = $per_id
AND b.bus_no = $bus_no
AND b.status <> 40

ORDER BY b.book_code 
                ";

    $sql_all_row = $sql;
    $sql         = $sql.set_limit($offset , $limit);
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;


   if(mysqli_num_rows($result) == 0){
        $set_data = return_object(204);
   } else {
        if (mysqli_num_rows($result) > 0) {
              while ($data = mysqli_fetch_assoc($result)) {
                        $book_code = $data['book_code'];
                        $sql3 = "SELECT c.*
                                    FROM room_detail c
                                    WHERE c.`book_code` = '$book_code'
                                    ORDER BY c.room_no
                                    
                                 ";
                        $result3 = mysqli_query($CON,$sql3);

                        while ($data2 = mysqli_fetch_assoc($result3)) {
                            $data['room_detail'][] = $data2;
                        }
                            
                        $set_data['result']['period'][] = $data;
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
