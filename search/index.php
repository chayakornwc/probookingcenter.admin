<?php include_once('../includes/main_core.php');?>
<?php include_once('../admin/unity/post_to_ws/post_to_ws.php');?>
<?php include_once('../admin/unity/php_script.php');?>

<?php
  	$div_data 	= '';
  	$text_h 	= '';
  if ($_REQUEST["s"] == 'menu'){ 
    $country_id =  $_REQUEST['cid'];
      # ------------------- get data
        #WS
            $wsserver   = URL_WS;
            $wsfolder	= '/f_searchtour'; //กำหนด Folder
            $wsfile		= '/select_list_by_country_id.php'; //กำหนด File
            $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
            $data 		= array(    
                                        'country_id'        => $country_id,
                                        'method'        => 'GET'
                                    );
            $data_return =	json_decode( post_to_ws($url,$data),true );
  }else if ($_REQUEST["s"] == 'search'){ // กรณี search
    $country_name =  $_REQUEST['cid'];
    $dateto =  $_REQUEST['dateto'];
    $datefrom =  $_REQUEST['datefrom'];
    $search =  $_REQUEST['search'];

     if ($dateto == '') {
                $dateto = '1/1/9999';
    }
    if ($datefrom == '') {
                $datefrom = '1/1/1900';
    }
    if ($country_name == 'ทุกประเทศ'){
        $country_name = '';
    }
      # ------------------- get data
        #WS
            $wsserver   = URL_WS;
            $wsfolder	= '/f_searchtour'; //กำหนด Folder
            $wsfile		= '/select_list_searchtour.php'; //กำหนด File
            $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
            $data 		= array(    
                                        'country_name'        => $country_name,
                                        'date_end'    => Y_m_d($dateto),
                                        'date_start'    => Y_m_d($datefrom),
                                        'word_search'    => $search,

                                        'method'        => 'GET'
                                    );
            $data_return =	json_decode( post_to_ws($url,$data),true );

  }

            if($data_return['status'] == 200){
                    if ($_REQUEST["s"] != 'search') {
                    $text_h = ' <font size="6"><b>'.$data_return['results']['ser'][0]['country_name'].'</b></font>';
                    }
                if(isset($data_return['results']['ser'])){
                    foreach($data_return['results']['ser'] as $key => $value){
                        $date_period = '';
                        $count_period =  count($value['date_period']);
                        if ($count_period > 0){ // เช็คว่ามี period ไหม
						foreach($value['date_period'] as $key_2 => $value_2){
							$date_period .= '<h5 class="color-dark-2"><i class="fa fa-clock-o"></i>'.thai_date_short(strtotime($value_2['per_date_start'])).' - '.thai_date_short(strtotime($value_2['per_date_end'])).'</h5>';
						}
                        $url = str_replace('../','../admin/',$value['ser_url_img_1']);// รูป
                        $url_air = str_replace('../','../admin/',$value['air_url_img']); // รูปสายการบิน

                        $div_data .= '<div class="list-item-entry">
                                                <div class="hotel-item  style-3 bg-white">
                                                    <div class="table-view">
                                                        <div class="radius-top cell-view" style="vertical-align:top">
                                                            <div class="" style="padding:10px" >
                                                                <img src="'.$url.'" alt="" >
                                                            </div>
                                                        </div>
                                                        <div class="title hotel-middle clearfix cell-view">
                                                            <h4><b>'.$value['ser_name'].'('.$value['ser_code'].')</b></h4>
                                                            <h5 class="f-14 color-dark-2">เส้นทางไปกลับ : <strong> '.$value['ser_route'].'</strong></h5>
                                                            
                                                            <p class="f-18 color-dark-2" style="color:#333333">
                                                                '.$value['remark'].'
                                                            </p>
                                                            <hr>
                                                            <h5 style="color-dark-2"><u>ช่วงเวลาการเดินทาง</u></h5>
                                                           
                                                            <div class="date_period  ">
                                                              '.$date_period.'
                                                            </div>
                                                        </div>
                                                        <div class="title hotel-right clearfix cell-view" >
                                                            <div class="hotel-person color-dark-2">
                                                                <span class="color-blue"></span>
                                                                
                                                                <img src="'.$url_air.'" width="80px">
                                                            </div>
                                                            <div class="hotel-person ">
                                                                <hr>
                                                                <span class="color-red">'.number_format(intval($value['price'])).'</span>
                                                            </div>
                                                            <a href="../search/tour.php?sid='.$value['ser_id'].'" class="c-button b-40 bg-blue-t hv-blue b-1 ">ดูรายละเอียด</a>
                                                        </div>
                                                    </div>
                                                </div>
                                             </div>';
                    }
                    }

                }

            }else{
             $div_data .= '<div class="text-center">
                            <h50>ไม่พบข้อมูล</h50>
                            </div>
            ';
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
        <div class="list-wrapper padd-70-70">
            <img class="center-image" src="../includes/img/tmp/pexels-photo-242236.jpeg" alt="" style="display: none;">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-3">
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
                    </div>
                    
                    <div class="col-xs-12 col-sm-8 col-md-9">
                    <div class = "text-left">
                   <?php echo $text_h; ?>
                        </div>
                        
                        <!--<div class="list-header clearfix">
                           
                            <div class="list-view-change">
                                <div class="change-grid color-1 fr"><i class="fa fa-th"></i></div>
                                <div class="change-list color-1 fr active"><i class="fa fa-bars"></i></div>
                                <div class="change-to-label fr color-grey-8">View:</div>
                            </div>
                        </div>-->
                        <div class="list-content clearfix">
                                <?php echo $div_data; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <!-- contant -->
       
    <?php include_once('../includes/footer.php');?>
    <?php include_once('../includes/tag_script.php');?>                                                                                                                    
	<script>
  		$(document).ready(function(){

          //  CREATE_INPUT_DATE_SEARCH();
            

        });
		function search_period(){

		           b_select = document.getElementById("b_select").innerHTML;
				 dateto =  $('#date_to').val();
				 datefrom = $('#date_from').val();
				 search =  $('#txtsearch').val();
				 url = '../search/index.php?s=search&cid='+ b_select + '&dateto=' + dateto +'&datefrom=' + datefrom + '&search=' + search ;
				 window.open(url, '_parent' );

		}

		</script>





</body>
</html>				   