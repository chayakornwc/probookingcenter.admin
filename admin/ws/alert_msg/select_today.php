<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


$user_id           = $_REQUEST['user_id'];
$group_id           = $_REQUEST['group_id'];
$Limit           = $_REQUEST['Limit'];
$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    $strwhere = " AND user_id = $user_id"; // แสดง ครบกำหนดชำระ เฉพาะ พนักงานคนนั้น
    $strwhere2 = " AND per_id = 0"; // ไม่แสดง การแจ้ง ส่งใบ cost , cost2
    $strwhere3 = " AND a.per_id IN (SELECT b.per_id FROM booking b WHERE b.per_id = a.per_id AND b.user_id = $user_id AND b.status <> 40)"; // แสดง กรอกข้อมูลผู้เดินทาง เฉพาะ พนักงานคนนั้น
    if ($group_id == 1) { // select เฉพาะ ผู้บริหาร
        $strwhere2 = '';
    }
    if ($group_id == 2) { // select เฉพาะ แผนกบัญชี
        $strwhere2 = '';
    }
    if ($group_id == 3) {  // select เฉพาะ OP
        $strwhere2 = '';
    }
    if ($group_id == 7) {  // select เฉพาะ OP SUPPORT
        $strwhere3 = '';
    }
  /*  $sql2 = " SELECT `user_id` FROM `user` WHERE `group_id` = 2"; // select เฉพาะ แผนกบัญชี
    $result2 = mysqli_query($CON,$sql2);
    if (mysqli_num_rows($result2) > 0) {
         while ($data2 = mysqli_fetch_assoc($result2)) {
            $user_id_account = $data2['user_id'];
                if ($user_id == $user_id_account){
                    $strwhere = '';
                }
         }
    }
    $sql3 = " SELECT `user_id` FROM `user` WHERE `group_id` = 3"; // select เฉพาะ OP
    $result3 = mysqli_query($CON,$sql3);
    if (mysqli_num_rows($result3) > 0) {
         while ($data3 = mysqli_fetch_assoc($result3)) {
            $user_id_op = $data3['user_id'];
                if ($user_id == $user_id_op){
                    $strwhere = '';
                }
         }
    }*/
    

$sql = "SELECT a.book_id,a.book_code,a.book_date
,CONCAT(b.user_fname,' ',b.user_lname) AS 'username'
,CONCAT(c.agen_fname,' ',c.agen_lname) AS 'agenname'
,a.s_date,a.detail,a.total,a.source
,se.ser_name,se.ser_code,a.agen_id
FROM (( 
SELECT  book_id,book_code,book_date,user_id,agen_id,book_due_date_deposit AS 's_date','แจ้งครบกำหนดชำระเงิน(DEP)' AS 'detail'
,book_master_deposit  AS 'total','(DEP)' AS 'source'
FROM `booking`
WHERE ADDDATE(DATE_FORMAT(`book_due_date_deposit`,'%Y-%m-%d'),-1) =  DATE_FORMAT(NOW(),'%Y-%m-%d')
AND 	book_receipt = 0
AND status <> 40
$strwhere
) UNION ( 
SELECT book_id,book_code,book_date,user_id,agen_id,book_due_date_full_payment AS 's_date','แจ้งครบกำหนดชำระเงิน(FP)' AS 'detail'
,book_amountgrandtotal - book_receipt AS 'total','(FP)' AS 'source'
FROM `booking`
WHERE ADDDATE(DATE_FORMAT(`book_due_date_full_payment`,'%Y-%m-%d'),-1) =  DATE_FORMAT(NOW(),'%Y-%m-%d')
AND 	status NOT IN ('35','40')
$strwhere
) UNION ( 
SELECT per_id as 'book_id',ser_id as 'book_code',per_date_start as 'book_date','' as 'user_id',0 as 'agen_id',per_date_end  AS 's_date','แจ้งส่งไฟล์ใบต้นทุน(Cost)' AS 'detail'
,0 AS 'total','Cost' AS 'source'
FROM `period`
WHERE DATE_FORMAT(`per_date_start`,'%Y-%m-%d') =  DATE_FORMAT(NOW(),'%Y-%m-%d')
AND 	status  IN ('3','10')
AND per_cost_file = ''
$strwhere2
)UNION ( 
SELECT per_id as 'book_id',ser_id as 'book_code',per_date_start as 'book_date','' as 'user_id',0 as 'agen_id',per_date_end  AS 's_date','แจ้งส่งไฟล์ใบต้นทุน(Cost) ครั้งที่ 2' AS 'detail'
,0 AS 'total','Cost2' AS 'source'
FROM `period`
WHERE DATE_FORMAT(`per_date_end`,'%Y-%m-%d') =  DATE_FORMAT(NOW(),'%Y-%m-%d')
AND 	status  IN ('3','10')
AND per_cost_file = ''
$strwhere2
)UNION(
    SELECT a.per_id as 'book_id',a.ser_id as 'book_code',a.per_date_start as 'book_date','' as 'user_id',bl.bus_no as 'agen_id',a.per_date_end  AS 's_date','แจ้ง กรอกข้อมูลผู้เดินทาง' AS 'detail'
    ,0 AS 'total','room_list' AS 'source' 
    FROM `period` a
    LEFT OUTER JOIN bus_list bl
    ON a.per_id = bl.per_id
    WHERE ADDDATE(DATE_FORMAT(a.`per_date_start`,'%Y-%m-%d'),-5) =  DATE_FORMAT(NOW(),'%Y-%m-%d')
    AND 	a.status  IN ('1','2','10')
$strwhere3
)) as a
LEFT OUTER JOIN user b
ON a.user_id = b.user_id
LEFT OUTER JOIN agency c
ON a.agen_id = c.agen_id
LEFT OUTER JOIN series se
ON a.book_code = se.ser_id
ORDER BY  a.book_id DESC LIMIT $Limit ";
               

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
