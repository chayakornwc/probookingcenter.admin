<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


$method         = strtoupper($_REQUEST['method']);
$year_now = date("Y");
#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 

    $sql = "SELECT a.*
FROM (( 
SELECT 
    '01' as 'name',
    COALESCE(sum(book_receipt),0) as 'total'

  FROM booking WHERE book_date BETWEEN CONCAT('$year_now-01-01 00:00:00') AND CONCAT('$year_now-01-',DATE_FORMAT(LAST_DAY('$year_now-01-01'),'%d'),' 23:59:59')
  AND status <> 40
) UNION ( 
SELECT 
    '02' as 'name',
COALESCE(sum(book_receipt),0) as 'total'
  FROM booking WHERE book_date BETWEEN CONCAT('$year_now-02-01 00:00:00') AND CONCAT('$year_now-02-',DATE_FORMAT(LAST_DAY('$year_now-02-01'),'%d'),' 23:59:59')
  AND status <> 40
)
UNION ( 
SELECT 
    '03' as 'name',
COALESCE(sum(book_receipt),0) as 'total'
  FROM booking WHERE book_date BETWEEN CONCAT('$year_now-03-01 00:00:00') AND CONCAT('$year_now-03-',DATE_FORMAT(LAST_DAY('$year_now-03-01'),'%d'),' 23:59:59')
  AND status <> 40
)
UNION ( 
SELECT 
    '04' as 'name',
COALESCE(sum(book_receipt),0) as 'total'
  FROM booking WHERE book_date BETWEEN CONCAT('$year_now-04-01 00:00:00') AND CONCAT('$year_now-04-',DATE_FORMAT(LAST_DAY('$year_now-04-01'),'%d'),' 23:59:59')
  AND status <> 40
)
UNION ( 
SELECT 
    '05' as 'name',
COALESCE(sum(book_receipt),0) as 'total'
  FROM booking WHERE book_date BETWEEN CONCAT('$year_now-05-01 00:00:00') AND CONCAT('$year_now-05-',DATE_FORMAT(LAST_DAY('$year_now-05-01'),'%d'),' 23:59:59')
  AND status <> 40
)
UNION ( 
SELECT 
    '06' as 'name',
COALESCE(sum(book_receipt),0) as 'total'
  FROM booking WHERE book_date BETWEEN CONCAT('$year_now-06-01 00:00:00') AND CONCAT('$year_now-06-',DATE_FORMAT(LAST_DAY('$year_now-06-01'),'%d'),' 23:59:59')
  AND status <> 40
)
UNION ( 
SELECT 
    '07' as 'name',
COALESCE(sum(book_receipt),0) as 'total'
  FROM booking WHERE book_date BETWEEN CONCAT('$year_now-07-01 00:00:00') AND CONCAT('$year_now-07-',DATE_FORMAT(LAST_DAY('$year_now-07-01'),'%d'),' 23:59:59')
  AND status <> 40
)
UNION ( 
SELECT 
    '08' as 'name',
COALESCE(sum(book_receipt),0) as 'total'
  FROM booking WHERE book_date BETWEEN CONCAT('$year_now-08-01 00:00:00') AND CONCAT('$year_now-08-',DATE_FORMAT(LAST_DAY('$year_now-08-01'),'%d'),' 23:59:59')
  AND status <> 40
)
UNION ( 
SELECT 
    '09' as 'name',
COALESCE(sum(book_receipt),0) as 'total'
  FROM booking WHERE book_date BETWEEN CONCAT('$year_now-09-01 00:00:00') AND CONCAT('$year_now-09-',DATE_FORMAT(LAST_DAY('$year_now-09-01'),'%d'),' 23:59:59')
  AND status <> 40
)
UNION ( 
SELECT 
    '10' as 'name',
COALESCE(sum(book_receipt),0) as 'total'
  FROM booking WHERE book_date BETWEEN CONCAT('$year_now-10-01 00:00:00') AND CONCAT('$year_now-10-',DATE_FORMAT(LAST_DAY('$year_now-10-01'),'%d'),' 23:59:59')
  AND status <> 40
)
UNION ( 
SELECT 
    '11' as 'name',
COALESCE(sum(book_receipt),0) as 'total'
  FROM booking WHERE book_date BETWEEN CONCAT('$year_now-11-01 00:00:00') AND CONCAT('$year_now-11-',DATE_FORMAT(LAST_DAY('$year_now-11-01'),'%d'),' 23:59:59')
  AND status <> 40
)
UNION ( 
SELECT 
    '12' as 'name',
COALESCE(sum(book_receipt),0) as 'total'
  FROM booking WHERE book_date BETWEEN CONCAT('$year_now-12-01 00:00:00') AND CONCAT('$year_now-12-',DATE_FORMAT(LAST_DAY('$year_now-12-01'),'%d'),' 23:59:59')
  AND status <> 40
)

) as a
ORDER by a.name
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
