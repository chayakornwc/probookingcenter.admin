<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$per_id         = $_REQUEST['per_id'];


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


    $sql = "SELECT a.*
,b.ser_code
,b.ser_go_flight_code
,b.ser_go_route
,b.ser_go_time
,b.ser_return_flight_code
,b.ser_return_route
,b.ser_return_time
,c.air_id
,c.air_name
FROM (( 
SELECT 
a.per_id
,a.ser_id
,a.per_date_start
,a.per_date_end
,a.per_hotel
,a.per_hotel_tel
,coalesce(a.arrival_date,a.per_date_start) as 'arrival_date'
,c.room_name_thai
,c.room_prename
,c.room_fname
,c.room_lname
,c.room_sex
,c.room_country
,c.room_nationality
,c.room_address
,c.room_birthday
,c.room_passportno
,c.room_expire
,c.room_remark
,c.room_career
,c.room_placeofbirth
,c.room_place_pp
,c.room_date_pp
FROM period	a
LEFT OUTER JOIN booking b
on a.per_id = b.per_id
LEFT OUTER JOIN room_detail c
on b.book_code =c.book_code
 WHERE a.per_id = $per_id  
) UNION ( 
SELECT 
 a.per_id
,a.ser_id
,a.per_date_start
,a.per_date_end
,a.per_hotel
,a.per_hotel_tel
,coalesce(a.arrival_date,a.per_date_start) as 'arrival_date'
,c.room_name_thai
,c.room_prename
,c.room_fname
,c.room_lname
,c.room_sex
,c.room_country
,c.room_nationality
,c.room_address
,c.room_birthday
,c.room_passportno
,c.room_expire
,c.room_remark
,c.room_career
,c.room_placeofbirth
,c.room_place_pp
,c.room_date_pp
FROM period	a
LEFT OUTER JOIN period_leader c
on a.per_id =c.per_id
 WHERE a.per_id = $per_id 
)) as a
LEFT OUTER JOIN series b
on a.ser_id = b.ser_id
LEFT OUTER JOIN airline c
on b.air_id = c.air_id
WHERE a.room_fname <> ''
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
