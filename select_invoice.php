<?php
header('Content-Type: text/html; charset=utf-8');
require_once('../function.php');

#GET
$date_from = date("Y-m-d 00:00:00", strtotime($_REQUEST['date_from']));
$date_to   = date("Y-m-d 23:59:59", strtotime($_REQUEST['date_to']));

$method = strtoupper($_REQUEST['method']);

#set var
$set_data = array();

if ($method != 'GET') {
    $set_data = return_object(400);
} else {
    $sql   = "SELECT b.book_code
               , b.invoice_code
               , b.book_total
               , u.user_name
               , u.user_fname
               , u.user_lname
               , u.user_nickname
               , p.per_date_start
               , p.per_date_end
               , s.ser_code
               , s.ser_name
               , a.agen_fname
               , a.agen_lname
               , ac.agen_com_name
        FROM booking b 
            LEFT JOIN user u ON b.user_id=u.user_id
            LEFT JOIN period p ON b.per_id=p.per_id
            LEFT JOIN series s ON p.ser_id=s.ser_id
            LEFT JOIN agency a ON b.agen_id=a.agen_id
            LEFT JOIN agency_company ac ON a.agency_company_id=agen_com_id
        WHERE b.invoice_date BETWEEN '{$date_from}' AND '{$date_to}'
        ORDER BY b.invoice_code ASC";
    $query = mysqli_query($CON, $sql);
    
    $set_data['sql'] = $sql;
    
    if (mysqli_num_rows($query) > 0) {
        while ($result = mysqli_fetch_assoc($query)) {
            $sqlSum        = "SELECT COALESCE(SUM(booking_list.book_list_qty),0) AS qty 
            FROM booking_list 
            WHERE book_code='{$result['book_code']}'";
            $querySum      = mysqli_query($CON,$sqlSum);
            $sum           = mysqli_fetch_assoc($querySum);
            $result['qty'] = $sum['qty']; 
            $set_data['result'][] = $result;
        }

        $set_data = return_object(200,$set_data['result']);

        #ALL ROW
        $set_data['all_row'] = mysqli_num_rows($query);
    }
    else{
        $set_date = return_object(204,$sql);
    }
}

echo json_encode($set_data);
disconect_db();