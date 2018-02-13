<?php include_once('../includes/main_core.php');?>
<?php include_once('../admin/unity/post_to_ws/post_to_ws.php');?>
<?php include_once('../admin/unity/php_script.php');?>

<?php 

	#WS
    $wsserver   = URL_WS;
    $wsfolder	= '/f_home'; //กำหนด Folder
    $wsfile		= '/select_home.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );

	$div_promote 	= '';
	$div_promotion 	= '';
	$div_hot 		= '';

	$div_review 	= '';
	$count_div_rev = 0;

	if($data_return['status'] == 200){
		
		if(isset($data_return['results']['ser'])){

			$count_div_hot = 0;
			# div ser
			foreach($data_return['results']['ser'] as $key => $value){
			$count_period =  count($value['date_period']);
            if ($count_period > 0){
				$url = str_replace('../','../admin/',$value['ser_url_img_1']);// รูปใหญ่
				
				if(is_file($url)){


					if($value['ser_show'] == 1){ // โปรโมด
						$div_promote .= '<div class="swiper-slide active" data-val="'.$key.'"> 
						<a href="../search/tour.php?sid='.$value['ser_id'].'">
							<div class="clip">
								<div class="bg bg-bg-chrome act">
									<img src="'.$url.'" alt="" width="500px">
								</div>
							</div>
							<div class="vertical-align">
								<div class="container">
								
								</div>
							</div>
							</a>
						</div>';
					}
					else if($value['ser_show'] == 2){ //โปรโมชั่น
							
						$date_period = '';
						
						foreach($value['date_period'] as $key_2 => $value_2){
							$date_period .= '<span><i class="fa fa-clock-o"></i>'.thai_date_short(strtotime($value_2['per_date_start'])).' - '.thai_date_short(strtotime($value_2['per_date_end'])).'</span><br>';
						}

						$url_air = str_replace('../','../admin/',$value['air_url_img']); // รูปสายการบิน

						$div_promotion .= '<div class="swiper-slide">
											<div class="tour-item  style-3">
												<div class="radius-top">
													<img src="'.$url.'" alt="" style="height:160px;">
												</div>
												<div class="tour-desc bg-white" style="padding-top: 20px;">
													<h4><b>'.$value['ser_name'].'</b></h4>
													<div class="">
														<img src="'.$url_air.'" width="50px">
													</div>
													<span class="f-14 people">เส้นทางไปกลับ : <strong>'.$value['ser_route'].'</strong></span>
													<div class="tour-text color-grey-3">
														
													</div>
													<div class="date_period">
														'.$date_period.'
													</div>
													<div class="row">
														<div class="col-sm-12" align="right">
															<a href="../search/tour.php?sid='.$value['ser_id'].'" class="c-button b-40 bg-blue-t hv-blue b-1 ">ดูช่วงเวลาเดินทางทั้งหมด</a>
														</div>
													</div>
												</div>
											</div>
											</div>';
					}
					else if($value['ser_show'] == 3){ //HOT

						if($count_div_hot < 6){

						$count_div_hot++;

						$date_period = '';
						foreach($value['date_period'] as $key_2 => $value_2){
							$date_period .= '<span><i class="fa fa-clock-o"></i>'.thai_date_short(strtotime($value_2['per_date_start'])).' - '.thai_date_short(strtotime($value_2['per_date_end'])).'</span><br>';
						}

						
						$url_air = str_replace('../','../admin/',$value['air_url_img']); // รูปสายการบิน

						$div_hot .= '<div class="col-mob-12 col-xs-6 col-sm-6 col-md-4 clear-xs-2 clear-md-3">
										<div class="tour-item style-5">
											<div class="radius-top">
												<img src="'.$url.'" alt="">
											</div>
											<div class="tour-desc bg-white">
												<h4><b>'.$value['ser_name'].'</b></h4>
												<div class="">
													<img src="'.$url_air.'" width="50px">
												</div>
												<span class="f-14 people">เส้นทางไปกลับ : <strong>'.$value['ser_route'].'</strong></span>
												<div class="date_period">
													'.$date_period.'
												</div>
												<div class="tour-text color-grey-3"></div>
												<div class="row">
													<div class="col-sm-12" align="right">
														<a href="../search/tour.php?sid='.$value['ser_id'].'" class="c-button b-40 bg-blue-t hv-blue b-1 ">ดูช่วงเวลาเดินทางทั้งหมด</a>
													</div>
												</div>
											</div>
										</div>					
									</div>';
						}
				
					}
					

				}
			}

			}

		}
		
		// Review
		if(isset($data_return['results']['rev'])){
			
			foreach($data_return['results']['rev'] as $key => $value){

				if($count_div_rev < 4){

					

				$url = str_replace('../','../admin/',$value['review_url_img_1']);// รูปใหญ่

				if(is_file($url)){
					
				$review_detail = mb_substr($value['review_detail'],0,300,'UTF-8');
				$div_review 	.= '<div class="col-xs-12 col-sm-6 col-md-3">
										<div class="world-city black-hover">
											<div class="tour-layer delay-1"></div>
											<img class="center-image" src="'.$url.'" alt="">
											
										</div>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-3">
										<div class="world-country">
											<img class="center-image" src="../includes/img/home_9/country_3.png" alt="">
											<div class="vertical-align">
												<h4>'.$value['country_name'].'</h4>
												<p>
													'.$review_detail.'
												</p>
												<a href="../review/review_detail.php?r='.$value['review_id'].'" class="c-button b-30 bg-blue-t hv-blue b-1 ">อ่านต่อที่นี่</a>				
											</div>
										</div>
									</div>';

				}
					$count_div_rev++;

				}
			}


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
	
	<!-- PROMOTE-->

		<div class="top-baner swiper-animate arrows ">
			<div class="swiper-container main-slider" data-autoplay="5000" data-loop="1" data-speed="900" data-center="0" data-slides-per-view="1">
				<div class="swiper-wrapper">
					
					<?php echo $div_promote;?>
					
				</div>    
				<div class="pagination pagination-hidden poin-style-1"></div>
			</div>
			<div class="arrow-wrapp m-200">
				<div class="cont-1170">
					<div class="swiper-arrow-left sw-arrow"><span class="fa fa-angle-left"></span></div>
					<div class="swiper-arrow-right sw-arrow"><span class="fa fa-angle-right"></span></div>
				</div>
			</div>
				
			<div class="baner-tabs">
				
				<div class="tab-content tpl-tabs-cont section-text t-con-style-1" style="min-height:60px;">
					<div class="tab-pane active in" id="one">
						<div class="container">
							<div class="row">

								<div class="col-lg-3 ">
									<div class="form-block ">
										<div class="input-style-1 b-50 color-3">
											<img src="../includes/img/favicon.png" alt="">
											<input type="text" placeholder="รหัสทัวน์ ,ชื่อทัวน์" id = "txtsearch">
										</div>
									</div>   
								</div>
								
								
								<div class="col-lg-3">
									
										<div class="drop-wrap drop-wrap-s-4 color-5" style="margin-bottom:20px" id = "select_contry">
											<div class="drop" id = "select_contry">
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


								<div class="col-lg-2 ">
									<div class="form-block  ">
										<div class="input-style-1 b-50 color-3">
											<img src="../includes/img/calendar_icon_grey.png" alt="">
											<input type="text" placeholder="วันที่เดินทางไป" class="form-control date_search" id = "date_from">
										</div>
									</div> 
								</div>

								<div class="col-lg-2 ">
									<div class="form-block  ">
										<div class="input-style-1 b-50 color-3">
											<img src="../includes/img/calendar_icon_grey.png" alt="">
											<input type="text" placeholder="วันที่กลับ" class="form-control date_search" id = "date_to">
										</div>
									</div>	
								</div>
								
								
								<div class="col-lg-2">
									
									<a  class="c-button b-50 bg-white hv-orange" style="margin:0px" onclick= "search_period()">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<i class="fa fa-search"></i>
										ค้นหา
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									</a>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>				  	
		</div> 
	<!-- PROMOTE-->



	<!-- PROMOTION -->
		<div class="main-wraper bg-black  padd-100">
			
			<div class="wide-container clearfix">
				<div class="left-title">
					<div class="second-title" style="width:200px">
						<!--<h4 class="subtitle color-white-light">our tours</h4>-->
						<h2 class="color-white underline">PROMOTION</h2>
						<p class="color-white-light">TOP QUALITY TOURS </p>
					</div>				
				</div>
				<div class="left-content">
					<div class="row">
						<div class="swiper-container pad-15 " data-autoplay="5000" data-loop="0" data-speed="1000" data-center="0" data-slides-per-view="responsive" data-mob-slides="1" data-xs-slides="2" data-sm-slides="2" data-md-slides="3" data-lg-slides="4" data-add-slides="4">
							<div class="swiper-wrapper">
								
								<?php echo $div_promotion;?>

							</div>
							<div class="pagination"></div>
						</div>
					</div>				
				</div>

				

			</div>
		</div>
	<!-- PROMOTION -->

		
	<!-- HOT SALE -->
	
		<div class="main-wraper bg-grey-2 padd-40" >
			<img class="center-image" src="../includes/img/tmp/pexels-photo-242236.jpeg" alt="" style="display: none;">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-8 col-sm-offset-2">
						<div class="second-title">
							<!--<h4 class="color-dark-2-light">our directions</h4>-->
							
							<h2 class="color-dark-2">HOT Sale</h2>
						</div>
					</div>
				</div>
				<div class="tour-item-grid row">
					<?php echo $div_hot;?>
																												
				</div>			
			</div>
		</div>
		
	<!-- HOT SALE -->
		
	<!-- REVIEW -->
		<div class="main-wraper padd-40">
			<div class="container">
				<div class="row">
					<div class="col-xs-12" align="center">
						<div class="second-title">
							
							<h2 class="color-dark-2">THANK YOU FOR SHARING YOUR PLEASURE</h2>
						</div>
					</div>
				</div>
				<div class="row">

					<?php echo $div_review;?>

				</div>
			</div>
		</div>
	<!-- REVIEW -->
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