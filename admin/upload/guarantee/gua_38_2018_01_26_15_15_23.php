<?php
#include
include_once('../unity/main_core.php');
include_once('../unity/post_to_ws/post_to_ws.php');
include_once('../unity/php_script.php');


$servername = "localhost";
$username = "jitwilaitour_dbo";
$password = "ObservationCampaignDoctor8Shift";
$dbname = "jitwilaitour_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("SET character_set_results = utf8"); 
$conn->query("SET character_set_client = utf8"); 
$conn->query("SET character_set_connection = utf8");


#REQUEST

#SET DATA RETURN

$set_data = array();


#METHOD

if($_REQUEST['method'] == 1){//select list

    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/manage_period'; //กำหนด Folder
    if ($_REQUEST['status_3'] == '3'){
        $wsfile		= '/select_list_period_close_period.php'; //กำหนด File
    }else{
        $wsfile		= '/select_list_period.php'; //กำหนด File
    }
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
        'word_search'   => $_REQUEST['word_search'],
        'country_id'    => $_REQUEST['country_id'],
        'date_start'    => Y_m_d($_REQUEST['date_start']),
        'date_end'      => Y_m_d($_REQUEST['date_end']),

        'offset'        => $_REQUEST['offset'],
        'limit'         => '',//LIMIT,
        'method'        => 'GET'
    );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );
    
    $set_data['all_row'] = intval($data_return['all_row']);
    $set_data['paginate_list'] = paginate_list( intval($data_return['all_row']) , $_REQUEST['offset'] , LIMIT ); // set page
    // <th width="5%">BUS</th>

    $thead = '<thead>
        <tr>
            <th width="1%">#</th>
            <th width="2%">Status</th>
            <th width="10%">เดินทาง</th>
            
            <th width="2%" class="bg-info-light">ราคา</th>
            
            <th width="2%" style="white-space: nowrap;">ที่นั่ง</th>
            <th width="2%">จอง</th>
            <th width="2%" style="white-space: nowrap;" class="bg-success-light">รับได้</th>
            <th width="2%" style="white-space: nowrap;">FP</th>
           
            <th width="40%">Booking</th>
            <th width="20%">W/L</th>

            <th width="8%" style="min-width:170px"></th>
        </tr>
    </thead>';

    if($data_return['status'] != 200){
        if($data_return['status']==503){
            $non_txt = 'เกิดข้อผิดพลาดระหว่างการเข้าถึง เซิร์ฟเวอร์';
        }
        else if($data_return['status'] == 204){
            $non_txt = 'ไม่พบข้อมูลที่ค้นหา';
        }
        else{
            $non_txt = 'การเชื่อมต่อเซิฟเวอร์ผิดพลาด';
        }
        $div_ser = ' <div class="col-lg-12">
                        <div class="alert alert-warning text-center" role="alert">
                            <font style="font-size:25px">'.$non_txt.'</font>
                        </div>
                    </div>';
    }
    else{

        $count		= ($_REQUEST['offset']*LIMIT)+1;
        $result     = $data_return['results'];
        $tr         = '';
        $div_ser    = '';
        $group_result_by_ser_id = array();
        foreach($result as $key => $value){

            if(!isset($group_result_by_ser_id[$value['ser_id']])) {
                $group_result_by_ser_id[$value['ser_id']] = array();
            }

            $group_result_by_ser_id[$value['ser_id']][] = $value;
        }
        
        $set_data['data_set'] = $group_result_by_ser_id;

        foreach($group_result_by_ser_id as $key  => $value){
        
            $tr     = '';
            $count  = 1;
            foreach($value as $key_i => $value_i){

                // -- check full payment -- 
                $bookingList = '<div class="text-center">-</div>';
                $sql = "SELECT 
                      agency.agen_fname as name
                    , agency_company.agen_com_name as company_name
                    , user.user_fname as sale_name
                    , booking.status
                    , booking.book_id
                    , booking.book_code
                    , COALESCE(SUM(booking_list.book_list_qty),0) as qty

                    FROM booking_list
                        LEFT JOIN booking ON booking_list.book_code=booking.book_code
         
                        LEFT JOIN (
                            agency LEFT JOIN agency_company ON agency_company.agen_com_id=agency.agency_company_id
                        ) ON booking.agen_id=agency.agen_id 
                        LEFT JOIN user ON booking.user_id=user.user_id
                    WHERE 
                            booking.per_id={$value_i['per_id']}

                        AND booking.bus_no={$value_i['bus_no']}
                        
                        AND booking.status!=40
                            
                    GROUP BY booking.book_id
                    
                    ORDER BY booking.status DESC, booking.create_date ASC
                    
                ";
                // HAVING qty>0
                // AND booking_list.book_list_code IN ('1', '2', '3')

                $result = $conn->query($sql);


                $bookingList = ''; $waitList = '';
                $fullPaymentTotal = 0;
                if ($result->num_rows > 0) {
                    // output data of each row
                    
                    while($row = $result->fetch_assoc()) {

                        $cls = " s-{$row['status']}";

                        if( $row['status']=='35' ){
                            $fullPaymentTotal+=$row['qty'];
                        }

                        //href="http://admin.probookingcenter.com/admin/booking/manage_booking.php?book_id='.$row['book_id'].'&book_code='.$row['book_code'].'" target="_blank"
                        $_txt = '<a class="ui-status'.$cls.'">'.$row['sale_name'].' '.$row['qty'].' ('.  ucwords($row['company_name']). ')</a>';


                        if( $row['status']=='05' ){
                            $waitList.= !empty($waitList) ? " | ":'';
                            $waitList.= $_txt;
                        }
                        else{
                            $bookingList.= !empty($bookingList) ? " | ":'';
                            $bookingList.= $_txt;
                        }

                    }
                }


                $btnaction = '';
                if( $fullPaymentTotal==intval($value_i['per_qty_seats']) ){
                    $txt = '<div class="label bg-danger">เต็ม</div> ';
                }
                else{
                    $txt = '<a class="mb-sm btn btn-xs btn-warning" href="http://admin.probookingcenter.com/admin/booking/manage_booking.php?per_id='.$value_i['per_id'].'&bus_no='.$value_i['bus_no'].'" target="_blank"><em class="icon-list"></em> W/L</a> ';
                }

                /* -- qty Receipt -- */
                if ( intval($value_i['qty_receipt'])<=0 ) {

                    $btnaction = ' <td class="text-center">'.
                        $txt.
                        '<button type="button" class="mb-sm btn btn-primary btn-xs" onclick="location.href=\'manage_period.php?per_id='.$value_i['per_id'].'&bus_no='.$value_i['bus_no'].'\'">
                            <em class="icon-note"></em>ดูรายละเอียด</button>'.
                    '</td>';
                }else{

                    $btnaction = ' <td class="text-center">
                        <button type="button" class="mb-sm btn btn-green btn-xs" onclick="location.href=\'../booking/manage_booking.php?per_id='.$value_i['per_id'].'&bus_no='.$value_i['bus_no'].'\'">
                            <em class="icon-plus"></em>
                            จอง
                        </button>
                        <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="location.href=\'manage_period.php?per_id='.$value_i['per_id'].'&bus_no='.$value_i['bus_no'].'\'">
                            <em class="icon-note"></em>
                            ดูรายละเอียด
                        </button>
                        
                    </td>';
                }

                if ($value_i['status']== '3'){

                    $btnaction = ' <td class="text-center">
                        <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="location.href=\'manage_period.php?per_id='.$value_i['per_id'].'&bus_no='.$value_i['bus_no'].'\'">
                            <em class="icon-note"></em>
                            ดูรายละเอียด
                        </button>
                    </td>';
                }


                $td = '';

                $td .= '<td class="text-center">'.number_format($count++).'</td>';
                $td .= '<td class="text-center">'.html_status_period($value_i['status']).'</td>';
                $td .= '<td class="text-center" style="white-space: nowrap;">'.thai_date_short(strtotime($value_i['per_date_start'])).' - '.thai_date_short(strtotime($value_i['per_date_end'])).'</td>';
                $td .= '<td class="text-center bg-info-light">'.number_format(intval($value_i['per_price_1'])).'</td>';
                $td .= '<td class="text-center">'.number_format(intval($value_i['per_qty_seats'])).'</td>';
                $td .= '<td class="text-center">'. number_format(intval($value_i['qty_book']) ).'</td>';
                $td .= '<td class="text-center bg-success-light">'. ( $value_i['qty_receipt'] <= 0 ? '0': number_format(intval($value_i['qty_receipt'])) ) .'</td>';
                $td .= '<td class="text-center">'.( $fullPaymentTotal==0 ? '-':number_format($fullPaymentTotal) ).'</td>';
                $td .= '<td class="">'.$bookingList.'</td>';
                $td .= '<td class="text-center">'.$waitList.'</td>';
                $td .= $btnaction;
        

                $tr .= '<tr>'.$td.'</tr>';      
            }
            
            $tbody = '<tbody>'.$tr.'</tbody>';

            $div_ser .= '
                        <div class="col-lg-12">
                            <div id="panelDemoRefresh2" class="panel panel-default">
                                <div class="panel-heading">
                                    <font class="pink"><strong>'.$value_i['ser_code'].' - '.$value_i['ser_name'].'</strong></font>
                                    <em class="icon-plane"></em> สายการบิน : '.$value_i['air_name'].'
                                </div>  
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="" class="table table-bordered table-hover table-devgun">
                                        '.$thead.'
                                        '.$tbody.'
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>';
        }



    }


  

    $set_data['interface_table'] = ' <div class="row">'.$div_ser.'</div>';
    echo json_encode($set_data);


}
else if($_REQUEST['method'] == 2){ // GET VALUE PERIOD 

    #WS 
    $wsserver   = URL_WS;
    $wsfolder	= '/manage_period'; //กำหนด Folder
    $wsfile		= '/select_list_by_id_period.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'per_id'            => $_REQUEST['per_id'],
                                'bus_no'            => $_REQUEST['bus_no'],
                                'method'            => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );

    if($data_return['status'] == 200){

        $result = $data_return['results'][0];

        $set_data['per_id']             = $result['per_id'];
        $set_data['ser_code']           = $result['ser_code'];
        $set_data['ser_name']           = $result['ser_name'];
        $set_data['country_name']       = $result['country_name'];
        $set_data['air_name']           = $result['air_name'];

        $set_data['per_date_start']     = thai_date_short(strtotime($result['per_date_start']));
        $set_data['per_date_end']       = thai_date_short(strtotime($result['per_date_end']));
        $set_data['QTY']                = number_format(intval($result['qty_book'])).'/'.number_format(intval($result['bus_qty']));
        $set_data['com']                = number_format(intval($result['per_com_company_agency'])).'+'.number_format(intval($result['per_com_agency']));
        $set_data['price']                = number_format(intval($result['per_price_1']));
        $set_data['sum_total']                = number_format(intval($result['sum_total']));
        $set_data['sum_receipt']                = number_format(intval($result['sum_receipt']));
        $set_data['balance']                = number_format(intval($result['sum_total']) - intval($result['sum_receipt']));

        $set_data['per_hotel']           = $result['per_hotel'];
        $set_data['per_hotel_tel']           = $result['per_hotel_tel'];
        if ($result['arrival_date'] == ''){
            $set_data['arrival_date']           = '';
        }else{
            $set_data['arrival_date']           = d_m_y($result['arrival_date']);
        }
        
        if ($result['status'] == 1){
            if(intval($result['qty_book']) == intval($result['bus_qty']) ){ //เต็ม
            $set_data['status']             = html_status_period(2);
            }else{ // ไม่เต็ม เปิดจอง
            $set_data['status']             = html_status_period($result['status']);
            }
        }else { // สถานะ ปกติ
             $set_data['status']             = html_status_period($result['status']);
        }

    }


    if($result['country_id'] == 1  ){ // japan
        $btn_tm_declare = '<a style="width:180px" type="button" href="../print/immigration_form_japan.php?per_id='.$set_data['per_id'].'" class="mb-sm btn btn-warning" target="_blank">
                                <em class="icon-printer"></em>
                                พิมพ์ ใบ ต.ม.
                            </a>
                            <a style="width:180px" type="button" href="../print/declare_japan.php?per_id='.$set_data['per_id'].'" class="mb-sm btn btn-danger" target="_blank">
                                <em class="icon-printer"></em>
                                พิมพ์ ใบ ดีแคร์
                            </a>';   
    }
    else if($result['country_id'] == 2){ // myanmar
        $btn_tm_declare = '<a style="width:180px" type="button" href="../print/immigration_form_myanmar.php?per_id='.$set_data['per_id'].'" class="mb-sm btn btn-warning" target="_blank">
                                <em class="icon-printer"></em>
                                พิมพ์ ใบ ต.ม.
                            </a>
                            <a style="width:180px" type="button" href="../print/declare_maynmar.php?per_id='.$set_data['per_id'].'" class="mb-sm btn btn-danger" target="_blank">
                                <em class="icon-printer"></em>
                                พิมพ์ ใบ ดีแคร์
                            </a>';   
    }
    else if($result['country_id'] == 3){ // vienam
        $btn_tm_declare = '';
    }
    else if($result['country_id'] == 4){ // maldves
        $btn_tm_declare = '';
    }
    else{
        $btn_tm_declare = '';
    }
    
    $set_data['btn_tm_declare'] = $btn_tm_declare;


    echo json_encode($set_data);


}
else if($_REQUEST['method'] == 3){ // get Table Detail Booking


    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/manage_period'; //กำหนด Folder
    $wsfile		= '/select_list_booking_by_perid.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

        'per_id'   => $_REQUEST['per_id'],
        'bus_no'    => $_REQUEST['bus_no'],

        'offset'        => $_REQUEST['offset'],
        'limit'         => LIMIT,
        'method'        => 'GET'
    );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );
    
    $set_data['all_row'] = intval($data_return['all_row']);
    $set_data['paginate_list'] = paginate_list( intval($data_return['all_row']) , $_REQUEST['offset'] , LIMIT ); // set page


    $thead = '<thead>
				<tr>
                    <th  width="5%">
                        #
                    </th>
                    <th>สถานะ</th>
                    <th>Booking No.</th>
                    <th>จำนวนคน</th>
                    <th>ยอดรวม</th>
                    <th class="bg-info-light">ส่วนลด</th>
                    <th class="bg-success-light">ยอดสุทธิ</th>
                    <th>วันที่จอง</th>
                    <th class="bg-danger-light">วันหมดอายุ</th>
                    <th>ชื่อบริษัท</th>
                    <th>Booking By</th>
                    <th>Sales contact</th>
                    <th class="bg-green-dark">Amount Receive</th>
                    <th></th>
                </tr>
			</thead>';

    if($data_return['status'] != 200){
        if($data_return['status']==503){
            $non_txt = 'เกิดข้อผิดพลาดระหว่างการเข้าถึง เซิร์ฟเวอร์';
        }
        else if($data_return['status'] == 204){
            $non_txt = 'ไม่พบข้อมูลที่ค้นหา';
        }
        else{
            $non_txt = 'การเชื่อมต่อเซิฟเวอร์ผิดพลาด';
        }
        $tbody = '<tbody><tr>
                        <td colspan="20" style="text-align:center">
                            <div class="alert alert-warning" role="alert">
                            <font style="font-size:25px">'.$non_txt.'</font>
                            </div>
                        </td>
                    </tr>
                </tbody>';
    }
    else{

        $count		= ($_REQUEST['offset']*LIMIT)+1;
        $result     = $data_return['results'];
        $tr         = '';
        $btndetail = '';
        foreach($result as $key => $value){

            if ($_SESSION['login']['menu_12'] == '0'){
                if ($_SESSION['login']['user_id'] == $value['user_id']){
                    $btndetail = '<td class="text-center">
                                    <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="location.href=\'../booking/manage_booking.php?book_id='.$value['book_id'].'&book_code='.$value['book_code'].'\'">
                                        <em class="icon-note"></em>
                                        จัดการ
                                    </button>
                                </td>
                    ';

                }else {
                     $btndetail = '<td class="text-center">
                                    <button type="button" class="mb-sm btn btn-primary btn-xs" disabled>
                                        <em class="icon-note"></em>
                                        จัดการ
                                    </button>
                                </td>
                    ';
                }
            }else {
                $btndetail = '<td class="text-center">
                                    <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="location.href=\'../booking/manage_booking.php?book_id='.$value['book_id'].'&book_code='.$value['book_code'].'\'">
                                        <em class="icon-note"></em>
                                        จัดการ
                                    </button>
                                </td>
                    ';

            }
             $td_duedate = '';
            if (intval($value['status']) >= 25 && intval($value['status']) != 40 ){
                $td_duedate = ' <td class="text-center bg-danger-light">'.thai_date_short(strtotime($value['book_due_date_full_payment'])).'</td>';
            }else {
                if (intval($value['book_master_deposit']) == 0){
                $td_duedate = ' <td class="text-center bg-danger-light">'.thai_date_short(strtotime($value['book_due_date_full_payment'])).'</td>';
                }else{
                $td_duedate = ' <td class="text-center bg-danger-light">'.thai_date_short(strtotime($value['book_due_date_deposit'])).'</td>';
                }
                
            }
            $td = '<td class="text-center">'.number_format($count++).'</td>';
            $td .= '<td class="text-center">'.html_status_book($value['status']).'</td>';
            $td .= '<td class="text-center">'.$value['book_code'].'</td>';
            $td .= '<td class="text-right">'.number_format(intval($value['QTY'])).'</td>';
            $td .= '<td class="text-right">'.number_format(intval($value['book_total'])).'</td>';
            $td .= '<td class="text-right bg-info-light">'.number_format(intval($value['book_discount'])).'</td>';
            $td .= '<td class="text-right bg-success-light">'.number_format(intval($value['book_amountgrandtotal'])).'</td>';
            $td .= '<td class="text-center">'.thai_date_short(strtotime($value['book_date'])).'</td>';
            $td .=  $td_duedate;
            $td .= '<td class="text-center">'.$value['agen_com_name'].'</td>';
            $td .= '<td class="text-center">'.$value['agen_name'].'</td>';
            $td .= '<td class="text-center">'.$value['user_name'].'</td>';
            $td .= '<td class="text-right bg-green-dark">'.number_format(intval($value['book_receipt'])).'</td>';
            $td .= $btndetail;
            

            $tr .= '<tr>'.$td.'</tr>';
        }
        $tbody = '<tbody>'.$tr.'</tbody>';

    }

   

    $set_data['interface_table'] = '<table id="" class="table table-bordered table-hover table-devgun">
                '.$thead.'
                '.$tbody.'
                </table>
            ';


    echo json_encode($set_data);


}
else if($_REQUEST['method'] == 20){// get model upload file

    $room_id = $_REQUEST['room_id'];

    $set_data['modal'] = '<div id="modal-manage-room" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 id="myModalLabel" class="modal-title">Upload</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="form-horizontal">

                                                <div class="form-group">
                                                    <label class="col-lg-3  control-label">File :</label>
                                                    <div class="col-lg-7 ">
                                                        <input type="file" class="form-control" id="file_room" name="file_room" value="">
                                                    </div>
                                                </div>
                                                
                                            </div>


                                        </div>

                                       

                                        <div class="modal-footer">
                                            <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
                                            <button type="button" class="btn btn-primary" onclick="upload_file_room('.$room_id.')">
                                                <em class="icon-cursor"></em>
                                                บันทีก
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>';

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 21){ // upload file return url

    if($_FILES['file_room']['size'] > 0){

        $path = $_FILES['file_room']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        $file_name = '../upload/room/room_file_'.date('Y_m_d_H_I_s').'.'.$ext;

        if(move_uploaded_file($_FILES['file_room']['tmp_name'],$file_name)){
            $set_data['file_room']    = $file_name;
            $set_data['status']        = 'TRUE';


            #WS
            $wsserver   = URL_WS;
            $wsfolder	= '/booking'; //กำหนด Folder
            $wsfile		= '/update_room_file.php'; //กำหนด File
            $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
            $data 		= array(    

                                        'room_id'       => $_REQUEST['room_id'],
                                        'room_file'     => $set_data['file_room'],
                                        'method'        => 'PUT'
                                    );

        
            $data_return =	json_decode( post_to_ws($url,$data),true );

            print_r($data_return);
        }
        else{
            
        }


    }

    print_r($set_data);
   

}
else if($_REQUEST['method'] == 22){// get model upload file T/L

    $per_leader_id = $_REQUEST['per_leader_id'];

    $set_data['modal'] = '<div id="modal-manage-room-tl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 id="myModalLabel" class="modal-title">Upload</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="form-horizontal">

                                                <div class="form-group">
                                                    <label class="col-lg-3  control-label">File :</label>
                                                    <div class="col-lg-7 ">
                                                        <input type="file" class="form-control" id="file_room_tl" name="file_room_tl" value="">
                                                    </div>
                                                </div>
                                                
                                            </div>


                                        </div>

                                       

                                        <div class="modal-footer">
                                            <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
                                            <button type="button" class="btn btn-primary" onclick="upload_file_room_tl('.$per_leader_id.')">
                                                <em class="icon-cursor"></em>
                                                บันทีก
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>';

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 23){ // upload file return url T/L

    if($_FILES['file_room_tl']['size'] > 0){

        $path = $_FILES['file_room_tl']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        $file_name = '../upload/room/room_file_'.date('Y_m_d_H_I_s').'.'.$ext;

        if(move_uploaded_file($_FILES['file_room_tl']['tmp_name'],$file_name)){
            $set_data['file_room_tl']    = $file_name;
            $set_data['status']        = 'TRUE';


            #WS
            $wsserver   = URL_WS;
            $wsfolder	= '/booking'; //กำหนด Folder
            $wsfile		= '/update_room_file_tl.php'; //กำหนด File
            $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
            $data 		= array(    

                                        'per_leader_id'       => $_REQUEST['per_leader_id'],
                                        'room_file'     => $set_data['file_room_tl'],
                                        'method'        => 'PUT'
                                    );

        
            $data_return =	json_decode( post_to_ws($url,$data),true );

            print_r($data_return);
        }
        else{
            
        }


    }

    print_r($set_data);
   

}else if($_REQUEST['method'] == 24){ // submit hotel
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/manage_period';      # กำหนด Folder
    $wsfile		=	'/update_period_hotel.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                                'per_id'                     => $_REQUEST['per_id'],
                                'per_hotel'                     => $_REQUEST['per_hotel'],
                                'per_hotel_tel'                     => $_REQUEST['per_hotel_tel'],
                                'arrival_date'                     => Y_m_d($_REQUEST['arrival_date']),
                                'method'                        => 'PUT'
                            );

   $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 1;

    echo json_encode($set_data);
    
   

}


else if($_REQUEST['method'] == 97){ // submit room T/L
    $birthday_arr =  array();
     for($i = 0 ; $i< count($_REQUEST['birthday_arr']);$i++){
        $birthday_arr[$i] = Y_m_d($_REQUEST['birthday_arr'][$i]);
     }
       $expire_arr =  array();
     for($i = 0 ; $i< count($_REQUEST['expire_arr']);$i++){
        $expire_arr[$i] = Y_m_d($_REQUEST['expire_arr'][$i]);
     }
        $date_pp_arr =  array();
     for($i = 0 ; $i< count($_REQUEST['date_pp_arr']);$i++){
        $date_pp_arr[$i] = Y_m_d($_REQUEST['date_pp_arr'][$i]);
     }
    #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/booking';      # กำหนด Folder
    $wsfile		=	'/insert_room_detail_tl.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                                'per_id'                     => $_REQUEST['per_id'],
                                'bus_no'                     => $_REQUEST['bus_no'],
                                'room_prename'                    => $_REQUEST['prename_arr'],
                                'room_fname'                    => $_REQUEST['firstname_arr'],
                                'room_lname'                    => $_REQUEST['lastname_arr'],
                                'room_name_thai'                => $_REQUEST['fullname_arr'],
                                'room_sex'                      => $_REQUEST['sex_arr'],
                                'room_country'                  => $_REQUEST['country_arr'],
                                'room_nationality'              => $_REQUEST['national_arr'],
                                'room_address'                  => $_REQUEST['address_arr'],
                                'room_birthday'                 => $birthday_arr,
                                'room_passportno'               => $_REQUEST['passportno_arr'],
                                'room_expire'                   => $expire_arr,
                                'room_remark'                   => $_REQUEST['remark_arr'],
                                'room_file'                   => $_REQUEST['room_file_arr'],
                                'room_career'                   => $_REQUEST['career_arr'],
                                'room_placeofbirth'                   => $_REQUEST['placeofbirth_arr'],
                                'room_place_pp'                   => $_REQUEST['place_pp_arr'],
                                'room_date_pp'                   => $date_pp_arr,
                                'method'                        => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 1;

    echo json_encode($set_data);

}


else if($_REQUEST['method'] == 98){ // submit room
    $birthday_arr =  array();
     for($i = 0 ; $i< count($_REQUEST['birthday_arr']);$i++){
        $birthday_arr[$i] = Y_m_d($_REQUEST['birthday_arr'][$i]);
     }
       $expire_arr =  array();
     for($i = 0 ; $i< count($_REQUEST['expire_arr']);$i++){
        $expire_arr[$i] = Y_m_d($_REQUEST['expire_arr'][$i]);
     }
        $date_pp_arr =  array();
     for($i = 0 ; $i< count($_REQUEST['date_pp_arr']);$i++){
        $date_pp_arr[$i] = Y_m_d($_REQUEST['date_pp_arr'][$i]);
     }
    #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/booking';      # กำหนด Folder
    $wsfile		=	'/insert_room_detail.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                                'book_code'                     => $_REQUEST['book_code'],
                                'room_prename'                    => $_REQUEST['prename_arr'],
                                'room_fname'                    => $_REQUEST['firstname_arr'],
                                'room_lname'                    => $_REQUEST['lastname_arr'],
                                'room_name_thai'                => $_REQUEST['fullname_arr'],
                                'room_sex'                      => $_REQUEST['sex_arr'],
                                'room_country'                  => $_REQUEST['country_arr'],
                                'room_nationality'              => $_REQUEST['national_arr'],
                                'room_address'                  => $_REQUEST['address_arr'],
                                'room_birthday'                 => $birthday_arr,
                                'room_passportno'               => $_REQUEST['passportno_arr'],
                                'room_expire'                   => $expire_arr,
                                'room_remark'                   => $_REQUEST['remark_arr'],
                                'room_file'                   => $_REQUEST['room_file_arr'],
                                'room_career'                   => $_REQUEST['career_arr'],
                                'room_placeofbirth'                   => $_REQUEST['placeofbirth_arr'],
                                'room_place_pp'                   => $_REQUEST['place_pp_arr'],
                                'room_date_pp'                   => $date_pp_arr,
                                'method'                        => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 1;

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 99){ // select room type by id book 

    #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/manage_period';      # กำหนด Folder
    $wsfile		=	'/select_list_roomlist.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                            'per_id'          => $_REQUEST['per_id'],
                            'bus_no'          => $_REQUEST['bus_no'],
                            'method'            => 'GET'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );
    $book_room_twin  = 0;
    $book_room_double = 0;
    $book_room_triple = 0;
    $book_room_single = 0;
    
    
        if($data_return['status'] == 200){

 

        $result     = $data_return['results']['period'];
        $tr         = '';
        $div_ser    = '';
        $group_result_by_user_id = array();
        $group_result_by_book_code = array();
        foreach($result as $key => $value){

            if(!isset($group_result_by_user_id[$value['user_id']])){
                $group_result_by_user_id[$value['user_id']] = array();
            }

            $group_result_by_user_id[$value['user_id']][] = $value;

            if(!isset($group_result_by_book_code[$value['invoice_code']])){
                $group_result_by_book_code[$value['invoice_code']] = array();
              
            }

                $group_result_by_book_code[$value['invoice_code']][] = $value;
             
    
                
        }
  
        $set_data['data_set'] = $group_result_by_user_id;





        foreach($group_result_by_user_id as $key  => $value){




                foreach($value as $key_i => $value_i){


                               $div_agen .= '   <div class="panel-heading">
                                    <font class="pink"><strong>INVOICE NO  : '.$value_i['invoice_code'].'</strong></font>
                                    <br>
                                    <font class=""><strong>Agent booking  : '.$value_i['agen_com_name'].'</strong></font>
                                     <br>
                                    <font class=""><strong>Sale Agent booking   : '.$value_i['agen_fname'].' '.$value_i['agen_lname'].' </strong></font>

                                    <font class="pink"><strong>Twin  : '.$value_i['book_room_twin'].'</strong></font>
                                    <font class="pink"><strong>Double  : '.$value_i['book_room_double'].'</strong></font>
                                    <font class="pink"><strong>Triple  : '.$value_i['book_room_triple'].'</strong></font>
                                    <font class="pink"><strong>Single  : '.$value_i['book_room_single'].'</strong></font>
                                </div>  
                                
                     '; 
                        $book_room_twin  = $value_i['book_room_twin'];
                        $book_room_double = $value_i['book_room_double'];
                        $book_room_triple = $value_i['book_room_triple'];
                        $book_room_single = $value_i['book_room_single'];

                           if($value_i['leader_room_sex'] == 1){
                    $leader_room_sex_male     = 'selected';
                    $leader_room_sex_female    = '';
                }else{
                    $leader_room_sex_male      = '';
                    $leader_room_sex_female    = 'selected';
                }
                 
                    if(is_file($value_i['leader_room_file'])){
                        $url_leader_room_file = $value_i['leader_room_file'];
                    }
                    else{
                        $url_leader_room_file = 'javascript:;';
                    }
                        $div_TL = '        <div class="panel panel panel-warning">
                                        <div class="panel-heading">-T/L  </div>
                                        <div class="panel-body">
                                            <div class="table-responsive" style="padding-bottom:20px">
                                                <table id="" class="table table-bordered table-hover table-devgun" style="width: 2000px;">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th>Prename</th>
                                                            <th>Firstname</th>
                                                            <th>Lastname</th>
                                                            <th>Fullname THAI</th>
                                                            <th>Sex</th>
                                                            <th>Country</th>
                                                            <th>National</th>
                                                            <th>Address in Thailand</th>
                                                            <th>Birthday</th>
                                                            <th>Passport No.</th>
                                                            <th>Expire</th>
                                                            <th>File</th>
                                                            <th>Remark</th>
                                                            <th>Upload</th>
                                                            <th>อาชีพ</th>
                                                        <th>จัดหวัดที่เกิด</th>
                                                        <th>สถานที่ออก pp</th>
                                                        <th>วันที่ออก pp</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center">1</td>
                                                            <td class="text-center">
                                                            <input type="text" class="form-control " name ="prename_tl[]" value = "'. $value_i['leader_room_prename'].'" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name ="firtname_tl[]" value = "'. $value_i['leader_room_fname'].'" required> 
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name ="lastname_tl[]" value = "'. $value_i['leader_room_lname'].'" required>
                                                            </td>
                                                              <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai_tl[]" value = "'. $value_i['leader_room_name_thai'].'" required>
                                                        </td>
                                                            <td class="text-center">
                                                                <select  name="sex_tl[]">
                                                                    <option value="1" '.$leader_room_sex_male.'>M</option>
                                                                    <option value="2" '.$leader_room_sex_female.'>F</option>
                                                                </select>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control"  name="country_tl[]" value = "'. $value_i['leader_room_country'].'" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name="national_tl[]" value = "'. $value_i['leader_room_nationality'].'" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name="address_tl[]" value = "'. $value_i['leader_room_address'].'" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control date period_requried" name="birthday_tl[]" value = "'.d_m_y($value_i['leader_room_birthday']).'">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name="passportno_tl[]" value = "'.$value_i['leader_room_passportno'].'" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control date period_requried" name="expire_tl[]" value = "'. d_m_y($value_i['leader_room_expire']).'">
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="'.$url_leader_room_file.'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank" >
                                                                    <em class="icon-cloud-download"></em>
                                                                </a>
                                                            </td>
                                                              <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file_tl[]" value = "'.$url_leader_room_file.'">
                                                        </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name="remark_tl[]" value = "'.$value_i['leader_room_remark'].'">
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="javascript:manage_upload_tl('.$value_i['per_leader_id'].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                    <em class="icon-cloud-upload"></em>
                                                                </a>
                                                            </td>
                                                            <td class="text-center">
                                                             <input type="text" class="form-control" name="career_tl[]" value = "'.$value_i['leader_career'].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth_tl[]" value = "'.$value_i['leader_placeofbirth'].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp_tl[]" value = "'.$value_i['leader_place_pp'].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date" name="date_pp_tl[]" value = "'.d_m_y($value_i['leader_date_pp']).'" >
                                                        </td>
                                                            
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                            </div>
                            
                            <div class="panel">
                            <div class="panel-body" align="center">
                               <button type="button" class="btn btn-primary" onclick="check_submit_room_tl()">
                                <em class="icon-cursor"></em>
                                    บันทีก
                                </button>
                            </div>
                            </div>';


        $room_id                = array();
        $room_fname             = array();
        $room_lname             = array();
        $room_name_thai             = array();
        $room_sex_male          = array();
        $room_sex_female        = array();
        $room_country           = array();
        $room_nationality       = array();
        $room_address           = array();
        $room_birthday          = array();
        $room_passportno        = array();
        $room_expire            = array();
        $room_file              = array();
        $room_remark            = array();
        $readonly           = 'readonly';
        $room_career            = array();
        $room_placeofbirth        = array();
        $room_place_pp            = array();
        $room_date_pp            = array();
        $room_prename            = array();
        
                        $i = 0 ;
                     	foreach($value_i['room_detail'] as $key_2 => $value_2){

                             $room_prename[$i]         = $value_2['room_prename'];
                             $room_fname[$i]         = $value_2['room_fname'];
                    $room_lname[$i]         = $value_2['room_lname'];
                    $room_name_thai[$i]         = $value_2['room_name_thai'];
                if($value_2['room_sex'] == 1){
                    $room_sex_male[$i]      = 'selected';
                    $room_sex_female[$i]    = '';
                }else{
                    $room_sex_male[$i]      = '';
                    $room_sex_female[$i]    = 'selected';
                }
                    $room_id[$i]            = $value_2['room_id'];
                    $room_country[$i]       = $value_2['room_country'];
                    $room_nationality[$i]   = $value_2['room_nationality'];
                    $room_address[$i]       = $value_2['room_address'];
                    $room_birthday[$i]      = d_m_y($value_2['room_birthday']);
                    $room_passportno[$i]    = $value_2['room_passportno'];
                    $room_expire[$i]        = d_m_y($value_2['room_expire']);
                    $room_career[$i]        = $value_2['room_career'];
                    $room_placeofbirth[$i]        = $value_2['room_placeofbirth'];
                    $room_place_pp[$i]        = $value_2['room_place_pp'];
                    $room_date_pp[$i]        = d_m_y($value_2['room_date_pp']);
                    if(is_file($value_2['room_file'])){
                        $url_room_file = $value_2['room_file'];
                    }
                    else{
                        $url_room_file = 'javascript:;';
                    }

                    $room_file[$i]          = $url_room_file;
                    $room_remark[$i]        = $value_2['room_remark'];
                    $i = $i + 1;
                         }

                         if (count($value_i['room_detail']) <= 0 ){
                                for($i = 0 ; $i< 100;$i++){
             $room_file[$i] = 'javascript:;';
            }
                         }

                    
    $x = 0;

    for($i = 0; $i< $book_room_twin; $i++){
        $div_room .= '<div class="panel panel-primary" style="" >
                                    <div class="panel-heading">-Twin </div>
                                    <div class="panel-body">
                                        <div class="table-responsive" style="padding-bottom:20px;">
                                            <table id="" class="table table-bordered table-hover table-devgun" style="width: 2000px;">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th>Prename</th>
                                                        <th>Firstname</th>
                                                        <th>Lastname</th>
                                                        <th>Fullname THAI</th>
                                                        <th>Sex</th>
                                                        <th>Country</th>
                                                        <th >National</th>
                                                        <th>Address in Thailand</th>
                                                        <th>Birthday</th>
                                                        <th>Passport No.</th>
                                                        <th>Expire</th>
                                                        <th>File</th>
                                                        <th>Remark</th>
                                                        <th>Upload</th>
                                                        <th>อาชีพ</th>
                                                        <th>จัดหวัดที่เกิด</th>
                                                        <th>สถานที่ออก pp</th>
                                                        <th>วันที่ออก pp</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">1</td>
                                                        <td class="text-center">
                                                        <input type="text" class="form-control " name ="prename\''.$value_i['book_code'].'\'[]" value = "'.$room_prename[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name ="firtname\''.$value_i['book_code'].'\'[]" value = "'.$room_fname[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="lastname\''.$value_i['book_code'].'\'[]" value = "'.$room_lname[$x].'" required>
                                                        </td>
                                                         <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai\''.$value_i['book_code'].'\'[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                      <td class="text-center">
                                                            <select name="sex\''.$value_i['book_code'].'\'[]">
                                                                <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                <option value="2" '.$room_sex_female[$x].'>F</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="country\''.$value_i['book_code'].'\'[]" value = "'.$room_country[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="national\''.$value_i['book_code'].'\'[]" value = "'.$room_nationality[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="address\''.$value_i['book_code'].'\'[]" value = "'.$room_address[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="birthday\''.$value_i['book_code'].'\'[]" value = "'.$room_birthday[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="passportno\''.$value_i['book_code'].'\'[]" value = "'.$room_passportno[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="expire\''.$value_i['book_code'].'\'[]" value = "'.$room_expire[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank">
                                                                <em class="icon-cloud-download"></em>
                                                            </a>
                                                        </td>
                                                        <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file\''.$value_i['book_code'].'\'[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name="remark\''.$value_i['book_code'].'\'[]" value = "'.$room_remark[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                <em class="icon-cloud-upload"></em>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="career\''.$value_i['book_code'].'\'[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth\''.$value_i['book_code'].'\'[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date" name="date_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">2</td>
                                                        <td class="text-center">
                                                        <input type="text" class="form-control " name ="prename\''.$value_i['book_code'].'\'[]" value = "'.$room_prename[($x = $x+1)].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control"  name ="firtname\''.$value_i['book_code'].'\'[]" value = "'.$room_fname[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="lastname\''.$value_i['book_code'].'\'[]" value = "'.$room_lname[$x].'" required>
                                                        </td>
                                                          <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai\''.$value_i['book_code'].'\'[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                      <td class="text-center">
                                                            <select  name="sex\''.$value_i['book_code'].'\'[]"> 
                                                                <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                <option value="2" '.$room_sex_female[$x].'>F</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="country\''.$value_i['book_code'].'\'[]" value = "'.$room_country[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="national\''.$value_i['book_code'].'\'[]" value = "'.$room_nationality[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="address\''.$value_i['book_code'].'\'[]" value = "'.$room_address[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="birthday\''.$value_i['book_code'].'\'[]" value = "'.$room_birthday[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="passportno\''.$value_i['book_code'].'\'[]" value = "'.$room_passportno[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="expire\''.$value_i['book_code'].'\'[]" value = "'.$room_expire[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank">
                                                                <em class="icon-cloud-download"></em>
                                                            </a>
                                                        </td>
                                                        <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file\''.$value_i['book_code'].'\'[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name="remark\''.$value_i['book_code'].'\'[]" value = "'.$room_remark[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                <em class="icon-cloud-upload"></em>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="career\''.$value_i['book_code'].'\'[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth\''.$value_i['book_code'].'\'[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date" name="date_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>
                                                        
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>';
    $x = $x+1;
    }

    for($i = 0; $i< $book_room_double; $i++){
          $div_room .= '<div class="panel panel-success">
                                    <div class="panel-heading">-Double </div>
                                    <div class="panel-body">
                                        <div class="table-responsive" style="padding-bottom:20px">
                                            <table id="" class="table table-bordered table-hover table-devgun" style="width: 2000px;">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th>Prename</th>
                                                        <th>Firstname</th>
                                                        <th>Lastname</th>
                                                        <th>Fullname THAI</th>
                                                        <th>Sex</th>
                                                        <th>Country</th>
                                                        <th>National</th>
                                                        <th>Address in Thailand</th>
                                                        <th>Birthday</th>
                                                        <th>Passport No.</th>
                                                        <th>Expire</th>
                                                        <th>File</th>
                                                        <th>Remark</th>
                                                        <th>Upload</th>
                                                        <th>อาชีพ</th>
                                                        <th>จัดหวัดที่เกิด</th>
                                                        <th>สถานที่ออก pp</th>
                                                        <th>วันที่ออก pp</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">1</td>
                                                        <td class="text-center">
                                                        <input type="text" class="form-control " name ="prename\''.$value_i['book_code'].'\'[]" value = "'.$room_prename[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="firtname\''.$value_i['book_code'].'\'[]" value = "'.$room_fname[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="lastname\''.$value_i['book_code'].'\'[]" value = "'.$room_lname[$x].'" required>
                                                        </td>
                                                         <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai\''.$value_i['book_code'].'\'[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <select  name="sex\''.$value_i['book_code'].'\'[]">
                                                                <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                <option value="2" '.$room_sex_female[$x].'>F</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                         <input type="text" class="form-control" name="country\''.$value_i['book_code'].'\'[]" value = "'.$room_country[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                         <input type="text" class="form-control" name="national\''.$value_i['book_code'].'\'[]" value = "'.$room_nationality[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="address\''.$value_i['book_code'].'\'[]" value = "'.$room_address[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="birthday\''.$value_i['book_code'].'\'[]" value = "'.$room_birthday[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="passportno\''.$value_i['book_code'].'\'[]" value = "'.$room_passportno[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="expire\''.$value_i['book_code'].'\'[]" value = "'.$room_expire[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank" >
                                                                <em class="icon-cloud-download"></em>
                                                            </a>
                                                        </td>
                                                          <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file\''.$value_i['book_code'].'\'[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name="remark\''.$value_i['book_code'].'\'[]" value = "'.$room_remark[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                <em class="icon-cloud-upload"></em>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="career\''.$value_i['book_code'].'\'[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth\''.$value_i['book_code'].'\'[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date" name="date_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>
                                                        
                                                    </tr>
                                                   <tr>
                                                        <td class="text-center">2</td>
                                                        <td class="text-center">
                                                        <input type="text" class="form-control " name ="prename\''.$value_i['book_code'].'\'[]" value = "'.$room_prename[($x = $x+1)].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="firtname\''.$value_i['book_code'].'\'[]" value = "'.$room_fname[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="lastname\''.$value_i['book_code'].'\'[]" value = "'.$room_lname[$x].'" required>
                                                        </td>
                                                           <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai\''.$value_i['book_code'].'\'[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <select  name="sex\''.$value_i['book_code'].'\'[]">
                                                                <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                <option value="2" '.$room_sex_female[$x].'>F</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                         <input type="text" class="form-control" name="country\''.$value_i['book_code'].'\'[]" value = "'.$room_country[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                         <input type="text" class="form-control" name="national\''.$value_i['book_code'].'\'[]" value = "'.$room_nationality[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="address\''.$value_i['book_code'].'\'[]" value = "'.$room_address[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="birthday\''.$value_i['book_code'].'\'[]" value = "'.$room_birthday[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="passportno\''.$value_i['book_code'].'\'[]" value = "'.$room_passportno[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="expire\''.$value_i['book_code'].'\'[]" value = "'.$room_expire[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank" >
                                                                <em class="icon-cloud-download"></em>
                                                            </a>
                                                        </td>
                                                          <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file\''.$value_i['book_code'].'\'[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name="remark\''.$value_i['book_code'].'\'[]" value = "'.$room_remark[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                <em class="icon-cloud-upload"></em>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="career\''.$value_i['book_code'].'\'[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth\''.$value_i['book_code'].'\'[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date" name="date_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>
                                                        
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>';
      $x = $x+1;
    }
    for($i = 0; $i< $book_room_triple; $i++){
          $div_room .= ' <div class="panel panel panel-info">
                                    <div class="panel-heading">-Triple  </div>
                                    <div class="panel-body">
                                        <div class="table-responsive" style="padding-bottom:20px">
                                            <table id="" class="table table-bordered table-hover table-devgun" style="width: 2000px;">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th>Prename</th>
                                                        <th>Firstname</th>
                                                        <th>Lastname</th>
                                                        <th>Fullname THAI</th>
                                                        <th>Sex</th>
                                                        <th>Country</th>
                                                        <th>National</th>
                                                        <th>Address in Thailand</th>
                                                        <th>Birthday</th>
                                                        <th>Passport No.</th>
                                                        <th>Expire</th>
                                                        <th>File</th>
                                                        <th>Remark</th>
                                                        <th>Upload</th>
                                                        <th>อาชีพ</th>
                                                        <th>จัดหวัดที่เกิด</th>
                                                        <th>สถานที่ออก pp</th>
                                                        <th>วันที่ออก pp</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">1</td>
                                                        <td class="text-center">
                                                        <input type="text" class="form-control " name ="prename\''.$value_i['book_code'].'\'[]" value = "'.$room_prename[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="firtname\''.$value_i['book_code'].'\'[]" value = "'.$room_fname[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="lastname\''.$value_i['book_code'].'\'[]" value = "'.$room_lname[$x].'" required>
                                                        </td>
                                                         <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai\''.$value_i['book_code'].'\'[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <select  name="sex\''.$value_i['book_code'].'\'[]">
                                                                <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                <option value="2" '.$room_sex_female[$x].'>F</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="country\''.$value_i['book_code'].'\'[]" value = "'.$room_country[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="national\''.$value_i['book_code'].'\'[]" value = "'.$room_nationality[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="address\''.$value_i['book_code'].'\'[]" value = "'.$room_address[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="birthday\''.$value_i['book_code'].'\'[]" value = "'.$room_birthday[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="passportno\''.$value_i['book_code'].'\'[]" value = "'.$room_passportno[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="expire\''.$value_i['book_code'].'\'[]" value = "'.$room_expire[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank">
                                                                <em class="icon-cloud-download"></em>
                                                            </a>
                                                        </td>
                                                          <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file\''.$value_i['book_code'].'\'[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name="remark\''.$value_i['book_code'].'\'[]" value = "'.$room_remark[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                <em class="icon-cloud-upload"></em>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="career\''.$value_i['book_code'].'\'[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth\''.$value_i['book_code'].'\'[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date" name="date_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">2</td>
                                                        <td class="text-center">
                                                        <input type="text" class="form-control " name ="prename\''.$value_i['book_code'].'\'[]" value = "'.$room_prename[($x = $x+1)].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="firtname\''.$value_i['book_code'].'\'[]" value = "'.$room_fname[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="lastname\''.$value_i['book_code'].'\'[]" value = "'.$room_lname[$x].'" required>
                                                        </td>
                                                         <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai\''.$value_i['book_code'].'\'[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <select  name="sex\''.$value_i['book_code'].'\'[]">
                                                                <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                <option value="2" '.$room_sex_female[$x].'>F</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="country\''.$value_i['book_code'].'\'[]" value = "'.$room_country[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                              <input type="text" class="form-control" name="national\''.$value_i['book_code'].'\'[]" value = "'.$room_nationality[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="address\''.$value_i['book_code'].'\'[]" value = "'.$room_address[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="birthday\''.$value_i['book_code'].'\'[]" value = "'.$room_birthday[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="passportno\''.$value_i['book_code'].'\'[]" value = "'.$room_passportno[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="expire\''.$value_i['book_code'].'\'[]" value = "'.$room_expire[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank" >
                                                                <em class="icon-cloud-download"></em>
                                                            </a>
                                                        </td>
                                                          <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file\''.$value_i['book_code'].'\'[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name="remark\''.$value_i['book_code'].'\'[]" value = "'.$room_remark[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                <em class="icon-cloud-upload"></em>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="career\''.$value_i['book_code'].'\'[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth\''.$value_i['book_code'].'\'[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date" name="date_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>

                                                        
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">3</td>
                                                        <td class="text-center">
                                                        <input type="text" class="form-control " name ="prename\''.$value_i['book_code'].'\'[]" value = "'.$room_prename[($x = $x+1)].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="firtname\''.$value_i['book_code'].'\'[]" value = "'.$room_fname[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="lastname\''.$value_i['book_code'].'\'[]" value = "'.$room_lname[$x].'" required>
                                                        </td>
                                                         <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai\''.$value_i['book_code'].'\'[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <select  name="sex\''.$value_i['book_code'].'\'[]">
                                                                <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                <option value="2" '.$room_sex_female[$x].'>F</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="country\''.$value_i['book_code'].'\'[]" value = "'.$room_country[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                              <input type="text" class="form-control" name="national\''.$value_i['book_code'].'\'[]" value = "'.$room_nationality[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="address\''.$value_i['book_code'].'\'[]" value = "'.$room_address[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="birthday\''.$value_i['book_code'].'\'[]" value = "'.$room_birthday[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="passportno\''.$value_i['book_code'].'\'[]" value = "'.$room_passportno[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="expire\''.$value_i['book_code'].'\'[]" value = "'.$room_expire[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank" >
                                                                <em class="icon-cloud-download"></em>
                                                            </a>
                                                        </td>
                                                          <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file\''.$value_i['book_code'].'\'[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name="remark\''.$value_i['book_code'].'\'[]" value = "'.$room_remark[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                <em class="icon-cloud-upload"></em>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="career\''.$value_i['book_code'].'\'[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth\''.$value_i['book_code'].'\'[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date" name="date_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>

                                                        
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>
                                    ';
        $x = $x+1;
    }
    for($i = 0; $i< $book_room_single; $i++){
             $div_room .= ' <div class="panel panel panel-warning">
                                        <div class="panel-heading">-Single  </div>
                                        <div class="panel-body">
                                            <div class="table-responsive" style="padding-bottom:20px">
                                                <table id="" class="table table-bordered table-hover table-devgun" style="width: 2000px;">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th>Prename</th>
                                                            <th>Firstname</th>
                                                            <th>Lastname</th>
                                                            <th>Fullname THAI</th>
                                                            <th>Sex</th>
                                                            <th>Country</th>
                                                            <th>National</th>
                                                            <th>Address in Thailand</th>
                                                            <th>Birthday</th>
                                                            <th>Passport No.</th>
                                                            <th>Expire</th>
                                                            <th>File</th>
                                                            <th>Remark</th>
                                                            <th>Upload</th>
                                                            <th>อาชีพ</th>
                                                        <th>จัดหวัดที่เกิด</th>
                                                        <th>สถานที่ออก pp</th>
                                                        <th>วันที่ออก pp</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center">1</td>
                                                            <td class="text-center">
                                                            <input type="text" class="form-control " name ="prename\''.$value_i['book_code'].'\'[]" value = "'.$room_prename[$x].'" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name ="firtname\''.$value_i['book_code'].'\'[]" value = "'.$room_fname[$x].'" required> 
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name ="lastname\''.$value_i['book_code'].'\'[]" value = "'.$room_lname[$x].'" required>
                                                            </td>
                                                              <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai\''.$value_i['book_code'].'\'[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                            <td class="text-center">
                                                                <select  name="sex\''.$value_i['book_code'].'\'[]">
                                                                    <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                    <option value="2" '.$room_sex_female[$x].'>F</option>
                                                                </select>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control"  name="country\''.$value_i['book_code'].'\'[]" value = "'.$room_country[$x].'" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name="national\''.$value_i['book_code'].'\'[]" value = "'.$room_nationality[$x].'" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name="address\''.$value_i['book_code'].'\'[]" value = "'.$room_address[$x].'" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control date period_requried" name="birthday\''.$value_i['book_code'].'\'[]" value = "'.$room_birthday[$x].'">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name="passportno\''.$value_i['book_code'].'\'[]" value = "'.$room_passportno[$x].'" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control date period_requried" name="expire\''.$value_i['book_code'].'\'[]" value = "'.$room_expire[$x].'">
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank" >
                                                                    <em class="icon-cloud-download"></em>
                                                                </a>
                                                            </td>
                                                              <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file\''.$value_i['book_code'].'\'[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name="remark\''.$value_i['book_code'].'\'[]" value = "'.$room_remark[$x].'">
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                    <em class="icon-cloud-upload"></em>
                                                                </a>
                                                            </td>
                                                            <td class="text-center">
                                                             <input type="text" class="form-control" name="career\''.$value_i['book_code'].'\'[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth\''.$value_i['book_code'].'\'[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date" name="date_pp\''.$value_i['book_code'].'\'[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>
                                                            
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                    </div>';
        $x= $x + 1;
    }
          $div_save ='      <div class="panel">
                            <div class="panel-body" align="center">
                               <button type="button" class="btn btn-primary" onclick="check_submit_room(\''.$value_i['book_code'].'\')">
                                <em class="icon-cursor"></em>
                                    บันทีก
                                </button>
                            </div>
                        </div>    '; 

                   

    $div_detail .= $div_agen.' '.$div_room. ' '.$div_save;

    
     $div_agen = '';
     $div_room = '';
     



        }           
     

            $div_ser .= '
                        <div class="col-lg-12">
                            <div id="panelDemoRefresh2" class="panel panel-default">
                                <div class="panel-heading">
                                    <font class="" size = "5"><strong> Sale contact : '.$value_i['user_fname'].'  '.$value_i['user_lname'].'</strong></font>
                                    '.$div_detail.'
                                    
                                </div>  
                           
                            </div>
                        </div>';
                         $div_detail = '';
       
            
        }
        
                           

            $div_ser .= ' 
                        <div class="col-lg-12">
                            <div id="panelDemoRefresh2" class="panel panel-default">
                                <div class="panel-heading">
                                    <font class="" size = "5"><strong>T/L</strong></font>
                                    '.$div_TL.'
                                    
                                </div>  
                           
                            </div>
                        </div>';
                       

        }
   

    $set_data['div_room'] = ' <div class="row">'.$div_ser.'</div>';
    echo json_encode($set_data);







}


else{

}
















?>