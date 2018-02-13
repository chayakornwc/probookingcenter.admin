<?php include_once('../includes/main_core.php');?>
<?php include_once('../admin/unity/post_to_ws/post_to_ws.php');?>
<?php include_once('../admin/unity/php_script.php');?>
<?php
$div_data = '';
if ($_REQUEST["sid"] != ''){ 
     $ser_id =  $_REQUEST['sid'];
       # ------------------- get data
       	$status_agen = '';					
       if(isset($_SESSION['login']["agen_user_name"])){
	        $status_agen = '1';					

       }else{
           	$status_agen = '0';					
       }
       
       if ($status_agen == '1'){
            $agen_label_qty = ' <font class=" pull-right" style="font-size:12px">ที่นั่ง</font>';
        } else {
            $agen_label_qty = '';
                            
        }
        #WS
            $wsserver   = URL_WS;
            $wsfolder	= '/f_searchtour'; //กำหนด Folder
            $wsfile		= '/select_by_ser_id.php'; //กำหนด File
            $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
            $data 		= array(    
                                        'ser_id'        => $ser_id,
                                        'method'        => 'GET'
                                    );
            $data_return =	json_decode( post_to_ws($url,$data),true );

               if($data_return['status'] == 200){
                 
                if(isset($data_return['results']['ser'])){
                    foreach($data_return['results']['ser'] as $key => $value){


                        $date_period = '';
						foreach($value['date_period'] as $key_2 => $value_2){
                        $button_status = '';
                            if ($value_2['status'] == '1'){
                                // <button type="button" class="btn btn-info" onclick="booking()">จอง</button>
                               if ($status_agen == '1' && $value_2['qty_book'] != $value_2['per_qty_seats']){
                               $button_status = '<button type="button" class="btn btn-success" onclick="window.open(\'../booking/booking_detail.php?per_id='.$value_2['per_id'].'\',\'_parent\');">จอง</button>';

                               }else if($value_2['qty_book'] == $value_2['per_qty_seats']) {
                                  $button_status = ' <h4><span class="label label-danger">เต็ม</span></h4>';    
                               }
                               else{
                                $button_status = ' <h4><span class="label label-success">เปิดจอง</span></h4>';

                               }
                            }else if ($value_2['status'] == '2' ){
                                
                                $button_status = ' <h4><span class="label label-danger">เต็ม</span></h4>';
                                
                              //  $button_status = '<button type="button" class="btn btn-danger" disabled>เต็ม</button>';
                            }
                            $per_url_word = str_replace('../','../admin/',$value_2['per_url_word']); // รูปสายการบิน
                            $per_url_pdf = str_replace('../','../admin/',$value_2['per_url_pdf']); // รูปสายการบิน
                            if(is_file($per_url_pdf)){
                                $per_url_pdf = 'onclick="window.open(\''.$per_url_pdf.'\',\'_blank\');"';
                            }else{
                                $per_url_pdf = 'disabled';
                                
                            }
                             if(is_file($per_url_word)){
                                $per_url_word = 'onclick="window.open(\''.$per_url_word.'\',\'_blank\');"';
                            }else{
                                $per_url_word = 'disabled';
                                
                            }
                             if ($status_agen == '1'){
                                $agen_qty = '<font class=" pull-right" style="font-size:12px">'.$value_2['qty_book'].'/'.$value_2['per_qty_seats'].'</font>';
                            } else {
                                 $agen_qty = '';
                            
                            }
                            $date_period .= '<tr>
                                                            <td><i class="fa fa-clock-o"></i><font  style="font-size:14px">'.thai_date_short(strtotime($value_2['per_date_start'])).' - '.thai_date_short(strtotime($value_2['per_date_end'])).'</font></td>
                                                            <td>
                                                                <font class="color-blue"  style="font-size:14px"><b>'.number_format(intval($value_2['per_price_1'])).'</b></font>
                                                            </td>
                                                            <td>
                                                                 <div class="accordion  " style="font-size:14px;width: 100%;">
                                                                    <div class="acc-panel">
                                                                        <div class="acc-title  no-padd">
                                                                            <font class=" color-blue" style="font-size:12px"><u>ราคาอื่น ๆ</u></font>
                                                                            '.$agen_qty.'
                                                                        </div>
                                                                        <div class="acc-body no-padd" style="display: none;">
                                                                            <ul class="text-left color-dark-2">
                                                                                <li>
                                                                                    ผู้ใหญ่(พักเดี่ยว)  <font class="color-blue"><b>'.number_format(intval($value_2['price_alone'])).'</b></font>
                                                                                </li>
                                                                                <li>
                                                                                    ผู้ใหญ่(พักสาม)  <font class="color-blue"><b>'.number_format(intval($value_2['per_price_1'])).'</b></font>
                                                                                </li>
                                                                                <li>
                                                                                    เด็ก(มีเตียง) <font class="color-blue"><b>'.number_format(intval($value_2['per_price_2'])).'</b></font>
                                                                                </li>
                                                                                <li>
                                                                                    เด็ก(ไม่มีเตียง) <font class="color-blue"><b>'.number_format(intval($value_2['per_price_3'])).'</b></font>
                                                                                </li>
                                                                               
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                               '.$button_status.'
                                                            </td>
                                                             <td>
                                                        <button type="button" class="btn btn-warning " '.$per_url_pdf.'>PDF</button>
                                                        <button type="button" class="btn btn-primary " '.$per_url_word.'>WORD</button>
                                                    		
                                                             
                                                            </td>
                                                        </tr>
                            ';
						
						}
                        $url = str_replace('../','../admin/',$value['ser_url_img_1']);// รูป
                        $url_air = str_replace('../','../admin/',$value['air_url_img']); // รูปสายการบิน
                        $ser_url_pdf = str_replace('../','../admin/',$value['ser_url_pdf']); // รูปสายการบิน
                        $ser_url_word = str_replace('../','../admin/',$value['ser_url_word']); // รูปสายการบิน
                          if(is_file($ser_url_pdf)){
                                $ser_frame_pdf = str_replace('../','../admin/',$value['ser_url_pdf']); // รูปสายการบิน
                                $ser_url_pdf = 'onclick="window.open(\''.$ser_url_pdf.'\',\'_blank\');"';
                            }else{
                                $ser_url_pdf = 'disabled';
                                
                            }
                             if(is_file($ser_url_word)){
                                $ser_url_word = 'onclick="window.open(\''.$ser_url_word.'\',\'_blank\');"';
                            }else{
                                $ser_url_word = 'disabled';
                                
                            }
                          

                        
                        $div_data .= ' <h2 class="detail-title color-dark-2">'.$value['ser_name'].'</h2>
                            <hr>
                            
                            <div class="detail-top">
                                <img class="img-responsive" src="'.$url.'" alt="" style="">
                               
                            </div>

                            <div class="detail-content-block">
                               <font size="3"><b> ทัวร์โค้ด </b></font><font size="5" color="blue"><b> '.$value['ser_code'].'</b></font>
                                
                                <br>                                
                                '.$value['remark'].'
                            </div>

                            <div class="detail-content-block">
                                <div class="simple-tab color-1 tab-wrapper">
                                    <div class="tab-nav-wrapper">
                                        <div  class="nav-tab  clearfix">
                                            <div class="nav-tab-item active">
                                                <u>เลือกวันที่เดินทาง</u>
                                            </div>
                                            <div class="nav-tab-item">
                                                <u>รายละเอียดโปรแกรมทัวร์</u>
                                            </div>                        
                                        </div>
                                    </div>
                                    <div class="tabs-content clearfix">
                                        <div class="tab-info active">
                                            <div class="table-responsive">
                                                <table  class="table  type-4 f-12">
                                                    <thead>
                                                        <tr>
                                                            <th width="60%"></th>
                                                            <th width="10%" style="min-width:100px">ผู้ใหญ่ <br> (พักคู่)</th>
                                                            <th width="15%" style="min-width:150px">ราคาอื่น ๆ '.$agen_label_qty.'</th>
                                                            <th width="10%" style="min-width:50px"></th>
                                                            <th width="10%" style="min-width:150px">ไฟล์เตรียมตัวเดินทาง</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                    '.$date_period.'
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12" align="right">
                                                    <button type="button" class="btn btn-info " onclick="window.open(\'../agency/\');">ติดต่อ Agency</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-info"> 
                                            <h3>รายละเอียดโปรแกรมทัวร์</h3>
                                            <div class="row">
                                            
                                                <div class="col-lg-12" align="right">
                                                    <div class="btn-group">
                                                         <button type="button" class="btn btn-warning " '.$ser_url_pdf.'>ดาวน์โหลด PDF</button>
                                                        <button type="button" class="btn btn-primary " '.$ser_url_word.'>ดาวน์โหลด WORD</button>
                                                    	
                                                    </div>
                                                </div>
                                               
                                            </div>
                                            <hr>
                                            <iframe src="'.$ser_frame_pdf.'" style="width:100%;height:500px;"></iframe>                              	
                                          
                                            <div class="row">
                                                <div class="col-sm-12" align="right">
                                                    <button type="button" class="btn btn-info " onclick="window.open(\'../agency/\');">ติดต่อ Agency</button>
                                                </div>
                                            </div>
                                        </div>    
                                    </div>
                                    
                                    

                                </div>
                               						
                            </div>';
                    }

                }

            }else{
             $div_data .= '<div class="text-center">
                            <h50>ไม่พบข้อมูล</h50>
                            </div>
            ';
            }

}


// select contry
	
    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/f_preview'; //กำหนด Folder
    $wsfile		= '/select_country.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );


    $list_country = '';
    if($data_return['status'] == 200){

        $result = $data_return['results'];
        
        foreach($result as $key => $value){
			$list_country .= '<a href="#" name = '.$value['country_id'].'>'.$value['country_name'].'</a>';

        }
        
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
  
    
    <!-- contant -->
       <div class="detail-wrapper">
            <img class="center-image" src="../includes/img/tmp/pexels-photo-242236.jpeg" alt="" style="display: none;">
            <div class="container">
                <div class="detail-header">
                    <div class="row">
                        <div class="col-lg-3">
                            
                            <div class="sidebar bg-white clearfix">
                                <div class="sidebar-block">
                                    <h4 class="sidebar-title color-dark-2">search</h4>
                                    <div class="search-inputs">
                                        <div class="form-block clearfix">
                                            <div class="input-style-1 b-50 color-3">
                                            <div class="input-style-1 b-50 color-3">
                                                    <img src="../includes/img/favicon.png" alt="">
                                                     <input type="text" placeholder="รหัสทัวน์ ,ชื่อทัวน์" id = "txtsearch">
                                                </div>
                                            </div>
                                        </div>	
                                       <div class="form-block clearfix">
                                        <div class="drop-wrap drop-wrap-s-4 color-5" style="margin-bottom:20px">
											<div class="drop">
												<b id = "b_select">
													ทุกประเทศ
												</b>
												<a href="#" class="drop-list"><i class="fa fa-angle-down"></i></a>
												<span>
													<?php echo $list_country; ?>
												</span>
											</div>
										</div>
                                    </div>

                                        <div class="form-block clearfix">
                                            <div class="input-style-1 b-50 color-3">
                                                <img src="../includes/img/calendar_icon_grey.png" alt="">
                                               	<input type="text" placeholder="วันที่เดินทางไป" class="form-control date_search" id="date_from">
                                            </div>					
                                        </div>
                                        <div class="form-block clearfix">
                                            <div class="input-style-1 b-50 color-3">
                                                <img src="../includes/img/calendar_icon_grey.png" alt="">
                                             <input type="text" placeholder="วันที่กลับ" class="form-control date_search" id="date_to">
                                            </div>					
                                        </div>
                                    </div>
                                    <a href="#" class="c-button b-50 bg-white bg-6 hv-orange" style="margin:0px" onclick= "search_period()">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <font class="color-white"><i class="fa fa-search"></i> ค้นหา</font>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </a>		
                                </div>				
                            </div>
                            <br>
                        </div>
                        <div class="col-lg-8">
                            
                           <?php echo $div_data; ?>

                            <div class="detail-content-block">
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- contant -->
       
    <?php include_once('../includes/footer.php');?>
    <?php include_once('../includes/tag_script.php');?>                                                                                                                    


    <div id="modal-booking" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">จองทัวร์</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal f-14">
                    <div class="form-group">
                                                
                        <label class="col-lg-3 control-label">Adult  :</label>
                        <label class="col-lg-2 control-label">
                            10,000
                        </label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="price_1">
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
                                <input type="text" name="price_1" id="price_1" class="form-control input-number period_requried" value="3" min="1" max="1000">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="price_1">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <label class="col-lg-2 control-label" align="right">
                            30,000
                        </label>
                    </div>
                    <hr>
                    <div class="form-group">
                                                
                        <label class="col-lg-3 control-label">Child  :</label>
                        <label class="col-lg-2 control-label">
                            10,000
                        </label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="price_2">
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
                                <input type="text" name="price_2" id="price_2" class="form-control input-number period_requried" value="3" min="1" max="1000">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="price_2">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <label class="col-lg-2 control-label" align="right">
                            30,000
                        </label>
                    </div>
                    <hr>
                    <div class="form-group">
                                                
                        <label class="col-lg-3 control-label">Child No bed :</label>
                        <label class="col-lg-2 control-label">
                            10,000
                        </label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="price_3">
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
                                <input type="text" name="price_3" id="price_3" class="form-control input-number period_requried" value="3" min="1" max="1000">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="price_3">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <label class="col-lg-2 control-label" align="right">
                            30,000
                        </label>
                    </div>
                    <hr>
                    <div class="form-group">
                                                
                        <label class="col-lg-3 control-label">Infant  :</label>
                        <label class="col-lg-2 control-label">
                            10,000
                        </label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="price_4">
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
                                <input type="text" name="price_4" id="price_4" class="form-control input-number period_requried" value="3" min="1" max="1000">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="price_4">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <label class="col-lg-2 control-label" align="right">
                            30,000
                        </label>
                    </div>
                    <hr>
                    <div class="form-group">
                                                
                        <label class="col-lg-3 control-label">Joinland  :</label>
                        <label class="col-lg-2  control-label">
                            10,000
                        </label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="price_5">
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
                                <input type="text" name="price_5" id="price_5" class="form-control input-number period_requried" value="3" min="1" max="1000">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="price_5">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <label class="col-lg-2 control-label" >
                            30,000
                        </label>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="col-lg-9 control-label">Total :</label>
                        <label class="col-lg-2 control-label">150,000</label>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">รายการพิเศษ :</label>
                        <div class="col-lg-9">
                            <textarea rows="4" cols="" class="form-control"></textarea>
                        </div>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success">จอง</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
            </div>
            </div>

        </div>
    </div>
    <script>
        function search_period(){

		           b_select = document.getElementById("b_select").innerHTML;
				 dateto =  $('#date_to').val();
				 datefrom = $('#date_from').val();
				 search =  $('#txtsearch').val();
				 url = '../search/index.php?s=search&cid='+ b_select + '&dateto=' + dateto +'&datefrom=' + datefrom + '&search=' + search ;
				 window.open(url, '_parent' );

		}
        function booking(){
            $('#modal-booking').modal('show');
        }
    </script>

</body>
</html>				   